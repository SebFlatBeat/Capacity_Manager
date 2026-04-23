<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';

if (!isset($dbconn) || !$dbconn) {
    echo json_encode(array("status" => "error", "message" => "Connexion BDD impossible."));
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $pi_raw = isset($_GET['pi_code']) ? $_GET['pi_code'] : '2025.2';
    $pi_code = trim(pg_escape_string($dbconn, $pi_raw));

    // On cherche les sprints avec ou sans le préfixe "PI " pour être sûr de les trouver
    $query = "SELECT * FROM sprints WHERE pi_code IN ($1, $2) ORDER BY numero_iteration";
    $result = pg_query_params($dbconn, $query, array($pi_code, 'PI ' . $pi_code));

    $sprints = array();
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $sprints[] = array(
                "id_sprint" => (int)$row['id_sprint'],
                "pi_code" => utf8_encode($row['pi_code']),
                "numero_iteration" => (int)$row['numero_iteration'],
                "date_debut" => $row['date_debut'],
                "date_fin" => $row['date_fin']
            );
        }
    }
    echo json_encode($sprints);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pi_code = pg_escape_string($dbconn, $data['pi_code']);
    $iteration = (int)$data['numero_iteration'];
    $debut = pg_escape_string($dbconn, $data['date_debut']);
    $fin = pg_escape_string($dbconn, $data['date_fin']);

    $query = "INSERT INTO sprints (pi_code, numero_iteration, date_debut, date_fin)
              VALUES ($1, $2, $3, $4)";
    $params = array($pi_code, $iteration, $debut, $fin);

    if (pg_query_params($dbconn, $query, $params)) {
        echo json_encode(array("status" => "success", "message" => "Sprint ajouté !"));
    } else {
        echo json_encode(array("status" => "error", "message" => pg_last_error($dbconn)));
    }
}
