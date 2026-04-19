<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

require 'db.php';
if (!isset($dbconn) || !$dbconn) { echo json_encode(array("status" => "error", "message" => "Connexion BDD perdue.")); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $id_membre = (int)$data['id_membre'];
    $id_sprint = (int)$data['id_sprint'];
    $nb_semaines = (float)$data['nb_semaines'];

    $check = pg_query($dbconn, "SELECT id_tra FROM affectations_tra WHERE id_membre = $id_membre AND id_sprint = $id_sprint");

    if (pg_num_rows($check) > 0) {
        if ($nb_semaines > 0) {
            $query = "UPDATE affectations_tra SET nb_semaines = $nb_semaines WHERE id_membre = $id_membre AND id_sprint = $id_sprint";
        } else {
            $query = "DELETE FROM affectations_tra WHERE id_membre = $id_membre AND id_sprint = $id_sprint";
        }
    } else {
        if ($nb_semaines > 0) {
            $query = "INSERT INTO affectations_tra (id_membre, id_sprint, nb_semaines) VALUES ($id_membre, $id_sprint, $nb_semaines)";
        } else {
            echo json_encode(array('status' => 'success')); 
            exit;
        }
    }

    if (pg_query($dbconn, $query)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => pg_last_error($dbconn)));
    }
    exit;
}
?>