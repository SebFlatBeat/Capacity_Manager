<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');
require 'db.php'; 

if (!isset($dbconn) || !$dbconn) {
    echo json_encode(array("status" => "error", "message" => "Connexion BDD perdue."));
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sprint = (int)$_GET['id_sprint'];
    $res = pg_query($dbconn, "SELECT id_membre FROM affectations_mco WHERE id_sprint = $sprint");
    $ids = array();
    if ($res) {
        while($row = pg_fetch_assoc($res)) {
            $ids[] = (int)$row['id_membre'];
        }
    }
    echo json_encode($ids);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $id_membre = (int)$data['id_membre'];
    $id_sprint = (int)$data['id_sprint'];
    pg_query($dbconn, "DELETE FROM affectations_mco WHERE id_membre = $id_membre AND id_sprint = $id_sprint");
    $query = "INSERT INTO affectations_mco (id_membre, id_sprint, commentaire) VALUES ($id_membre, $id_sprint, '')";
    $result = pg_query($dbconn, $query);
    if ($result) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => pg_last_error($dbconn)));
    }
    exit;
}

if ($method === 'DELETE') {
    $id_membre = (int)$data['id_membre'];
    $id_sprint = (int)$data['id_sprint'];
    $result = pg_query($dbconn, "DELETE FROM affectations_mco WHERE id_membre = $id_membre AND id_sprint = $id_sprint");
    if ($result) {
        echo json_encode(array('status' => 'deleted'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => pg_last_error($dbconn)));
    }
    exit;
}
?>