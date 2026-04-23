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
$data = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $id_membre = (int)$data['id_membre'];
    $id_sprint = (int)$data['id_sprint'];
    $nb_semaines = (float)$data['nb_semaines'];

    $check_query = 'SELECT id_tra FROM affectations_tra WHERE id_membre = $1 AND id_sprint = $2';
    $check = pg_query_params($dbconn, $check_query, array($id_membre, $id_sprint));

    if ($check && pg_num_rows($check) > 0) {
        if ($nb_semaines > 0) {
            $query = 'UPDATE affectations_tra SET nb_semaines = $1 WHERE id_membre = $2 AND id_sprint = $3';
            $params = array($nb_semaines, $id_membre, $id_sprint);
        } else {
            $query = 'DELETE FROM affectations_tra WHERE id_membre = $1 AND id_sprint = $2';
            $params = array($id_membre, $id_sprint);
        }
    } else {
        if ($nb_semaines > 0) {
            $query = 'INSERT INTO affectations_tra (id_membre, id_sprint, nb_semaines) VALUES ($1, $2, $3)';
            $params = array($id_membre, $id_sprint, $nb_semaines);
        } else {
            echo json_encode(array('status' => 'success'));
            exit;
        }
    }

    if (pg_query_params($dbconn, $query, $params)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => pg_last_error($dbconn)));
    }
    exit;
}
