<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

require_once '../config/db.php';

if (!isset($dbconn) || !$dbconn) {
    echo json_encode(array("status" => "error", "message" => "Connexion BDD perdue."));
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT j.id_ferie as id, j.date_ferie as date, j.description as name, p.nom_pays as country, j.id_pays
              FROM jours_feries j
              LEFT JOIN pays p ON j.id_pays = p.id_pays
              ORDER BY j.date_ferie ASC";

    $result = pg_query($dbconn, $query);
    $feries = array();
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            if (!$row['country'] || $row['id_pays'] == 0) {
                $row['country'] = 'Tous';
            }
            $feries[] = array(
                "id" => (int)$row['id'],
                "date" => $row['date'],
                "name" => $row['name'],
                "country" => $row['country'],
                "id_pays" => (int)$row['id_pays']
            );
        }
    }
    echo json_encode($feries);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $date = pg_escape_string($dbconn, $data['date']);
    $desc = pg_escape_string($dbconn, $data['name']);
    $id_pays = (int)$data['id_pays'];

    if (isset($data['id']) && $data['id'] > 0) {
        $id = (int)$data['id'];
        $query = "UPDATE jours_feries SET date_ferie = $1, description = $2, id_pays = $3 WHERE id_ferie = $4";
        $params = array($date, $desc, $id_pays, $id);
    } else {
        $query = "INSERT INTO jours_feries (date_ferie, description, id_pays) VALUES ($1, $2, $3)";
        $params = array($date, $desc, $id_pays);
    }

    if (pg_query_params($dbconn, $query, $params)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => pg_last_error($dbconn)));
    }
    exit;
}

if ($method === 'DELETE') {
    $id = (int)$data['id'];
    $query = "DELETE FROM jours_feries WHERE id_ferie = $1";
    if (pg_query_params($dbconn, $query, array($id))) {
        echo json_encode(array('status' => 'deleted'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => pg_last_error($dbconn)));
    }
    exit;
}
