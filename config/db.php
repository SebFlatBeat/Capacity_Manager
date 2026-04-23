<?php
// 1. Détection de l'environnement (avec prise en compte du port 8000)
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], '192.168.') !== false);

if ($is_local) {
    // --- CONFIGURATION LOCALE (Ton PC) ---
    $host     = 'localhost';
    $port     = '5436'; // Correction du port à 5436
    $dbname   = 'sebastien.darre';
    $username = 'postgres';
    $pass_db  = 'Sebda2812/1';
} else {
    // --- CONFIGURATION PROD (Serveur Free.fr) ---
    $host     = '';
    $port     = '5432';
    $dbname   = 'sebastien.darre';
    $username = 'sebastien.darre';
    $pass_db  = 'Sebda2812/1';
}

// 2. Construction de la chaîne de connexion
$conn_string = "dbname=$dbname user=$username password=$pass_db";

if (!empty($host)) {
    $conn_string .= " host=$host";
}
if (!empty($port)) {
    $conn_string .= " port=$port";
}

// 3. Tentative de connexion (silencieuse pour attraper l'erreur proprement)
$dbconn = @pg_connect($conn_string);

if (!$dbconn) {
    // On renvoie l'erreur au format JSON pour que React puisse l'afficher sans planter
    $error_msg = error_get_last() ? error_get_last()['message'] : 'Erreur inconnue';
    die(json_encode(array("status" => "error", "message" => "Erreur BDD (Port $port) : " . $error_msg)));
}
