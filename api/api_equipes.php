<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';

if (!isset($dbconn) || !$dbconn) {
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$pi_raw = isset($_GET['pi_code']) ? $_GET['pi_code'] : '2026.2';
$pi_code = trim(pg_escape_string($dbconn, utf8_decode($pi_raw)));

// --- 1. RÉSOLVER LE PI CODE (Gestion des doublons de format dans le dump) ---
// On cherche quel format est utilisé pour les KPI (2026.2 ou PI 2026.2)
$final_pi = $pi_code;
$check_exists = pg_query_params($dbconn, 'SELECT pi_code FROM kpi_ratios_equipe WHERE pi_code = $1 AND (charge_engagee > 0 OR realise > 0) LIMIT 1', array($pi_code));
if (pg_num_rows($check_exists) === 0) {
    $alt_pi = "PI " . $pi_code;
    $check_alt = pg_query_params($dbconn, 'SELECT pi_code FROM kpi_ratios_equipe WHERE pi_code = $1 LIMIT 1', array($alt_pi));
    if (pg_num_rows($check_alt) > 0) {
        $final_pi = $alt_pi;
    }
}

// --- 2. CALCUL MOYENNE TRAIN ---
$train_history_sum = 0;
$count_pis = 0;
$histResult = pg_query($dbconn, "SELECT total_pts, allstars_pts FROM historique_pi WHERE total_pts > 0 ORDER BY ordre DESC LIMIT 4");
if ($histResult) {
    while ($row = pg_fetch_assoc($histResult)) {
        $train_history_sum += ((float)$row['total_pts'] - (float)$row['allstars_pts']);
        $count_pis++;
    }
}
$global_train_avg = ($count_pis > 0) ? ($train_history_sum / $count_pis) : 0;

// --- 3. CHARGEMENT DES KPI (Charge et Réalisé) ---
$kpis = array();
$q = pg_query_params($dbconn, 'SELECT id_equipe, charge_engagee, realise, point_dev_jour FROM kpi_ratios_equipe WHERE pi_code = $1', array($final_pi));
if ($q) {
    while ($r = pg_fetch_assoc($q)) {
        $kpis[$r['id_equipe']] = $r;
    }
}

// --- 4. CHARGEMENT DES ABSENCES, MCO ET TRA (Recherche large sur les 2 formats) ---
$absences = array();
$q = pg_query_params($dbconn, 'SELECT id_membre, numero_iteration, jours_conges FROM absences_sprints WHERE pi_code IN ($1, $2)', array($pi_code, 'PI ' . $pi_code));
if ($q) {
    while ($r = pg_fetch_assoc($q)) {
        $absences[$r['id_membre']][$r['numero_iteration']] = (float)$r['jours_conges'];
    }
}

$mco = array();
$q = pg_query_params($dbconn, 'SELECT am.id_membre, s.numero_iteration FROM affectations_mco am JOIN sprints s ON am.id_sprint = s.id_sprint WHERE s.pi_code IN ($1, $2)', array($pi_code, 'PI ' . $pi_code));
if ($q) {
    while ($r = pg_fetch_assoc($q)) {
        $mco[$r['id_membre']][$r['numero_iteration']] = 1;
    }
}

$tra = array();
$q = pg_query_params($dbconn, 'SELECT at.id_membre, s.numero_iteration, at.nb_semaines FROM affectations_tra at JOIN sprints s ON at.id_sprint = s.id_sprint WHERE s.pi_code IN ($1, $2)', array($pi_code, 'PI ' . $pi_code));
if ($q) {
    while ($r = pg_fetch_assoc($q)) {
        $tra[$r['id_membre']][$r['numero_iteration']] = (float)$r['nb_semaines'];
    }
}

// --- 5. STRUCTURE DES ÉQUIPES ---
$res_equipes = pg_query($dbconn, "SELECT id_equipe, nom_equipe FROM equipes ORDER BY id_equipe");
$teams = array();

if ($res_equipes) {
    while ($e = pg_fetch_assoc($res_equipes)) {
        $tid = (int)$e['id_equipe'];
        $team_name = trim($e['nom_equipe']);

        $team_id_js = str_replace(' ', '', strtolower($team_name));
        if ($team_id_js === 'discovery') {
            $team_id_js = 'disco';
        }

        $kpi = isset($kpis[$tid]) ? $kpis[$tid] : null;

        $current_team = array(
            "db_id" => $tid,
            "id" => $team_id_js,
            "name" => utf8_encode($team_name),
            "velocityFactor" => $kpi ? (float)$kpi['point_dev_jour'] : 0.46,
            "committedLoad" => $kpi ? (float)$kpi['charge_engagee'] : 0,
            "realise" => $kpi ? (float)$kpi['realise'] : 0,
            "globalTrainAvg" => $global_train_avg,
            "members" => array()
        );

        $res_membres = pg_query_params($dbconn, "SELECT m.id_membre, m.nom, m.role, p.nom_pays
            FROM membres m
            LEFT JOIN pays p ON m.id_pays = p.id_pays
            WHERE m.id_equipe = $1
               OR m.id_equipe LIKE $2
               OR m.id_equipe LIKE $3
               OR m.id_equipe LIKE $4
            ORDER BY m.id_membre", array((string)$tid, $tid . ',%', '%,' . $tid, '%,' . $tid . ',%'));

        if ($res_membres) {
            while ($m = pg_fetch_assoc($res_membres)) {
                $mid = $m['id_membre'];
                $m_abs = array();
                $m_mco = array();
                $m_tra = array();
                for ($i = 1; $i <= 6; $i++) {
                    $m_abs[$i] = isset($absences[$mid][$i]) ? $absences[$mid][$i] : 0;
                    $m_mco[$i] = isset($mco[$mid][$i]) ? 1 : 0;
                    $m_tra[$i] = isset($tra[$mid][$i]) ? $tra[$mid][$i] : 0;
                }
                $current_team["members"][] = array(
                    "db_id" => (int)$mid,
                    "name" => utf8_encode($m['nom']),
                    "role" => utf8_encode($m['role']),
                    "pays" => utf8_encode($m['nom_pays']),
                    "absences" => $m_abs, "mco" => $m_mco, "tra" => $m_tra
                );
            }
        }
        $teams[] = $current_team;
    }
}

// --- 6. GESTION DES ACTIONS (POST / DELETE) ---
$data = json_decode(file_get_contents('php://input'), true);
if (!$data && $method === 'POST') {
    echo json_encode(array("status" => "error", "message" => "Données invalides."));
    exit;
}

if ($method === 'POST') {
    $action = $data['action'];

    if ($action === 'update_team_metrics') {
        $id_equipe = (int)$data['id_equipe'];
        $pi = pg_escape_string($dbconn, utf8_decode($data['pi_code']));
        $charge = (float)$data['charge_engagee'];
        $realise = (float)$data['realise'];
        $vel = (float)$data['velocity'];

        $check = pg_query_params($dbconn, 'SELECT id_ratio FROM kpi_ratios_equipe WHERE id_equipe = $1 AND pi_code = $2', array($id_equipe, $pi));
        if ($check && pg_num_rows($check) > 0) {
            $query = "UPDATE kpi_ratios_equipe SET charge_engagee = $1, realise = $2, point_dev_jour = $3 WHERE id_equipe = $4 AND pi_code = $5";
            $params = array($charge, $realise, $vel, $id_equipe, $pi);
        } else {
            $query = "INSERT INTO kpi_ratios_equipe (id_equipe, pi_code, charge_engagee, realise, point_dev_jour) VALUES ($1, $2, $3, $4, $5)";
            $params = array($id_equipe, $pi, $charge, $realise, $vel);
        }

        if (pg_query_params($dbconn, $query, $params)) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "error", "message" => pg_last_error($dbconn)));
        }
    } elseif ($action === 'update_sprint_data') {
        $id_m = (int)$data['id_membre'];
        $pi = pg_escape_string($dbconn, utf8_decode($data['pi_code']));
        $iter = (int)$data['numero_iteration'];
        $abs = (float)$data['jours_conges'];

        $check = pg_query_params($dbconn, 'SELECT id_membre FROM absences_sprints WHERE id_membre = $1 AND pi_code = $2 AND numero_iteration = $3', array($id_m, $pi, $iter));
        if ($check && pg_num_rows($check) > 0) {
            $query = "UPDATE absences_sprints SET jours_conges = $1 WHERE id_membre = $2 AND pi_code = $3 AND numero_iteration = $4";
            $params = array($abs, $id_m, $pi, $iter);
        } else {
            $query = "INSERT INTO absences_sprints (id_membre, pi_code, numero_iteration, jours_conges) VALUES ($1, $2, $3, $4)";
            $params = array($id_m, $pi, $iter, $abs);
        }

        if (pg_query_params($dbconn, $query, $params)) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "error", "message" => pg_last_error($dbconn)));
        }
    } elseif ($action === 'add_member') {
        $id_e = pg_escape_string($dbconn, $data['id_equipe']);
        $id_p = (int)$data['id_pays'];
        $nom = pg_escape_string($dbconn, utf8_decode($data['nom']));
        $role = pg_escape_string($dbconn, utf8_decode($data['role']));

        $query = "INSERT INTO membres (nom, role, id_equipe, id_pays) VALUES ($1, $2, $3, $4)";
        $params = array($nom, $role, $id_e, $id_p);
        if (pg_query_params($dbconn, $query, $params)) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "error", "message" => pg_last_error($dbconn)));
        }
    }
    exit;
}

if ($method === 'DELETE') {
    $id_m = (int)$data['id_membre'];
    $query = "DELETE FROM membres WHERE id_membre = $1";
    if (pg_query_params($dbconn, $query, array($id_m))) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "message" => pg_last_error($dbconn)));
    }
    exit;
}

echo json_encode($teams);
