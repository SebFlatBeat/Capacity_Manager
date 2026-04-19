<?php
ini_set('display_errors', 0); 
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($dbconn) || !$dbconn) { 
    echo json_encode(array("status" => "error", "message" => "Connexion BDD perdue.")); 
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $login = pg_escape_string($dbconn, $data['login']);
    $password = $data['mot_de_passe'];

    // On sélectionne exactement ce qu'il y a dans ta capture + id_membre qu'on vient d'ajouter
    $query = "SELECT id, login, role, equipes_ids, id_membre, mot_de_passe FROM utilisateurs WHERE login = '$login'";
    $result = @pg_query($dbconn, $query);

    if (!$result) {
        $err = pg_last_error($dbconn);
        echo json_encode(array("status" => "error", "message" => "Erreur SQL : " . $err));
        exit;
    }

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        if ($password === $user['mot_de_passe']) {
            unset($user['mot_de_passe']);
            // On transforme la chaîne "1,2" en vrai tableau pour React
            $user['equipes_ids'] = !empty($user['equipes_ids']) ? array_map('intval', explode(',', $user['equipes_ids'])) : [];
            echo json_encode(array("status" => "success", "user" => $user));
        } else {
            echo json_encode(array("status" => "error", "message" => "Mot de passe incorrect."));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Utilisateur introuvable."));
    }
}
?>