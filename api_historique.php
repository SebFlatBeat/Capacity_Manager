<?php
ini_set('display_errors', 0); 
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($dbconn) || !$dbconn) { exit; }

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : 'save';
    $pi = pg_escape_string($dbconn, utf8_decode($data['pi']));
    
    if ($action === 'close_pi') {
        $query = "UPDATE historique_pi SET statut = 'ARCHIVE' WHERE pi_code = '$pi'";
        if(@pg_query($dbconn, $query)) echo json_encode(array("status" => "success"));
        else echo json_encode(array("status" => "error"));
        exit;
    }

    $total = (float)$data['total'];
    $build = (float)$data['build'];
    $ordre = (int)$data['ordre'];
    $statut = isset($data['statut']) ? pg_escape_string($dbconn, $data['statut']) : 'PLANIFICATION';
    
    // Nouvelles colonnes
    $d_debut = isset($data['date_debut']) && $data['date_debut'] ? "'" . pg_escape_string($dbconn, $data['date_debut']) . "'" : "NULL";
    $d_fin = isset($data['date_fin']) && $data['date_fin'] ? "'" . pg_escape_string($dbconn, $data['date_fin']) . "'" : "NULL";
    $iters = isset($data['iterations']) ? (int)$data['iterations'] : 4;
    $days_iter = isset($data['jours_par_iteration']) ? (int)$data['jours_par_iteration'] : 15;

    $mco = isset($data['mco']) && $data['mco'] !== '' && $data['mco'] !== null ? (float)$data['mco'] : 'NULL';
    $tra = isset($data['tra']) && $data['tra'] !== '' && $data['tra'] !== null ? (float)$data['tra'] : 'NULL';
	$anomalies = isset($data['anomalies']) && $data['anomalies'] !== '' && $data['anomalies'] !== null ? (float)$data['anomalies'] : 'NULL';
    
    $apollo = isset($data['teams']['apollo']) && $data['teams']['apollo'] !== '' && $data['teams']['apollo'] !== null ? (float)$data['teams']['apollo'] : 'NULL';
    $disco = isset($data['teams']['disco']) && $data['teams']['disco'] !== '' && $data['teams']['disco'] !== null ? (float)$data['teams']['disco'] : 'NULL';
    $allstars = isset($data['teams']['allstars']) && $data['teams']['allstars'] !== '' && $data['teams']['allstars'] !== null ? (float)$data['teams']['allstars'] : 'NULL';

    $check_query = "SELECT 1 FROM historique_pi WHERE pi_code = '$pi'";
    $check_result = pg_query($dbconn, $check_query);

    if ($check_result && pg_num_rows($check_result) > 0) {
        $query = "UPDATE historique_pi SET 
                  total_pts = $total, build_pts = $build, mco_pts = $mco, tra_pts = $tra, anomalies_build_pts = $anomalies, 
                  apollo_pts = $apollo, disco_pts = $disco, allstars_pts = $allstars, ordre = $ordre, statut = '$statut',
                  date_debut = $d_debut, date_fin = $d_fin, iterations = $iters, jours_par_iteration = $days_iter
                  WHERE pi_code = '$pi'";
    } else {
        $query = "INSERT INTO historique_pi (pi_code, total_pts, build_pts, mco_pts, tra_pts, anomalies_build_pts, apollo_pts, disco_pts, allstars_pts, ordre, statut, date_debut, date_fin, iterations, jours_par_iteration) 
                  VALUES ('$pi', $total, $build, $mco, $tra, $anomalies, $apollo, $disco, $allstars, $ordre, '$statut', $d_debut, $d_fin, $iters, $days_iter)";
    }
    if(@pg_query($dbconn, $query)) echo json_encode(array("status" => "success"));
    else echo json_encode(array("status" => "error", "message" => pg_last_error($dbconn)));
    exit;
}

$query = "SELECT * FROM historique_pi ORDER BY ordre ASC";
$result = pg_query($dbconn, $query);
$historique = array();
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $historique[] = array(
            "pi" => utf8_encode($row['pi_code']),
            "total" => (float)$row['total_pts'],
            "build" => (float)$row['build_pts'],
            "mco" => isset($row['mco_pts']) ? (float)$row['mco_pts'] : null,
            "tra" => isset($row['tra_pts']) ? (float)$row['tra_pts'] : null,
			"anomalies" => isset($row['anomalies_build_pts']) ? (float)$row['anomalies_build_pts'] : null,
            "ordre" => (int)$row['ordre'],
            "statut" => isset($row['statut']) ? $row['statut'] : 'EN COURS',
            "date_debut" => $row['date_debut'],
            "date_fin" => $row['date_fin'],
            "iterations" => isset($row['iterations']) ? (int)$row['iterations'] : 4,
            "jours_par_iteration" => isset($row['jours_par_iteration']) ? (int)$row['jours_par_iteration'] : 15,
            "teams" => array(
                "apollo" => isset($row['apollo_pts']) ? (float)$row['apollo_pts'] : null,
                "disco" => isset($row['disco_pts']) ? (float)$row['disco_pts'] : null,
                "allstars" => isset($row['allstars_pts']) ? (float)$row['allstars_pts'] : null
            )
        );
    }
}
echo json_encode($historique);
?>