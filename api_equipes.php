<?php
ini_set('display_errors', 0); 
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($dbconn) || !$dbconn) { exit; }

$method = $_SERVER['REQUEST_METHOD'];
$pi_code = isset($_GET['pi_code']) ? trim(pg_escape_string($dbconn, $_GET['pi_code'])) : '2026.2';

// --- 1. RÉSOLVER LE PI CODE (Gestion des doublons de format dans le dump) ---
// On cherche quel format est utilisé pour les KPI (2026.2 ou PI 2026.2)
$final_pi = $pi_code;
$check_exists = pg_query($dbconn, "SELECT pi_code FROM kpi_ratios_equipe WHERE pi_code = '$pi_code' AND (charge_engagee > 0 OR realise > 0) LIMIT 1");
if (pg_num_rows($check_exists) === 0) {
    $alt_pi = "PI " . $pi_code;
    $check_alt = pg_query($dbconn, "SELECT pi_code FROM kpi_ratios_equipe WHERE pi_code = '$alt_pi' LIMIT 1");
    if (pg_num_rows($check_alt) > 0) {
        $final_pi = $alt_pi;
    }
}

// --- 2. CALCUL MOYENNE TRAIN ---
$train_history_sum = 0; $count_pis = 0;
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
$q = pg_query($dbconn, "SELECT id_equipe, charge_engagee, realise, point_dev_jour FROM kpi_ratios_equipe WHERE pi_code = '$final_pi'");
while($r = pg_fetch_assoc($q)) { $kpis[$r['id_equipe']] = $r; }

// --- 4. CHARGEMENT DES ABSENCES, MCO ET TRA (Recherche large sur les 2 formats) ---
$absences = array();
$q = pg_query($dbconn, "SELECT id_membre, numero_iteration, jours_conges FROM absences_sprints WHERE pi_code IN ('$pi_code', 'PI $pi_code')");
if($q) while($r = pg_fetch_assoc($q)) { $absences[$r['id_membre']][$r['numero_iteration']] = (float)$r['jours_conges']; }

$mco = array();
$q = pg_query($dbconn, "SELECT am.id_membre, s.numero_iteration FROM affectations_mco am JOIN sprints s ON am.id_sprint = s.id_sprint WHERE s.pi_code IN ('$pi_code', 'PI $pi_code')");
if($q) while($r = pg_fetch_assoc($q)) { $mco[$r['id_membre']][$r['numero_iteration']] = 1; }

$tra = array();
$q = pg_query($dbconn, "SELECT at.id_membre, s.numero_iteration, at.nb_semaines FROM affectations_tra at JOIN sprints s ON at.id_sprint = s.id_sprint WHERE s.pi_code IN ('$pi_code', 'PI $pi_code')");
if($q) while($r = pg_fetch_assoc($q)) { $tra[$r['id_membre']][$r['numero_iteration']] = (float)$r['nb_semaines']; }

// --- 5. STRUCTURE DES ÉQUIPES ---
$result = pg_query($dbconn, "SELECT e.id_equipe, e.nom_equipe, m.id_membre, m.nom, m.role, p.nom_pays 
    FROM equipes e 
    LEFT JOIN membres m ON e.id_equipe::text = ANY(string_to_array(m.id_equipe, ',')) 
    LEFT JOIN pays p ON m.id_pays = p.id_pays 
    ORDER BY e.id_equipe, m.id_membre");

$teams = array(); $cur_tid = null; $t_idx = -1;
while ($row = pg_fetch_assoc($result)) {
    if ($row['id_equipe'] !== $cur_tid) {
        $t_idx++; $cur_tid = $row['id_equipe'];
        $kpi = isset($kpis[$cur_tid]) ? $kpis[$cur_tid] : null;
        $team_name = $row['nom_equipe'];
        $team_id_js = strtolower($team_name);
        if ($team_id_js === 'discovery') $team_id_js = 'disco';
        
        $teams[$t_idx] = array(
            "db_id" => (int)$cur_tid, "id" => $team_id_js, "name" => utf8_encode($team_name),
            "velocityFactor" => $kpi ? (float)$kpi['point_dev_jour'] : 0.46,
            "committedLoad" => $kpi ? (float)$kpi['charge_engagee'] : 0,
            "realise" => $kpi ? (float)$kpi['realise'] : 0,
            "globalTrainAvg" => $global_train_avg,
            "members" => array()
        );
    }
    if ($row['id_membre']) {
        $mid = $row['id_membre'];
        $m_abs = array(); $m_mco = array(); $m_tra = array();
        for($i=1;$i<=6;$i++) {
            $m_abs[$i] = isset($absences[$mid][$i]) ? $absences[$mid][$i] : 0;
            $m_mco[$i] = isset($mco[$mid][$i]) ? 1 : 0;
            $m_tra[$i] = isset($tra[$mid][$i]) ? $tra[$mid][$i] : 0;
        }
        $teams[$t_idx]["members"][] = array(
            "db_id" => (int)$mid, "name" => utf8_encode($row['nom']), "role" => utf8_encode($row['role']), "pays" => utf8_encode($row['nom_pays']),
            "absences" => $m_abs, "mco" => $m_mco, "tra" => $m_tra
        );
    }
}
echo json_encode($teams);
?>