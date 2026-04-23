<?php
// 1. Détection de l'environnement
$is_local = ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1' || str_contains($_SERVER['HTTP_HOST'], '192.168.'));

if ($is_local) {
    // --- CONFIGURATION LOCALE (Ton PC) ---
    $host     = 'localhost';
    $port     = '5436'; // Ton port spécifique trouvé via PSQLTool
    $dbname   = 'sebastien.darre';
    $username = 'postgres';
    $password = 'TON_MOT_DE_PASSE_LOCAL';
} else {
    // --- CONFIGURATION PROD (Serveur Free.fr) ---
    // Note : Chez Free, le host est souvent omis ou spécifique
    $host     = '';
    $port     = '5432';
    $dbname   = 'sebastien.darre';
    $username = 'sebastien.darre';
    $password = 'Sebda2812/1';
}

// 2. Construction de la chaîne de connexion
$conn_string = "dbname=$dbname user=$username password=$password";

// On ajoute le host et le port seulement s'ils sont définis (important pour la compatibilité Free)
if (!empty($host)) {
    $conn_string .= " host=$host";
}
if (!empty($port)) {
    $conn_string .= " port=$port";
}

// 3. Tentative de connexion
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    // Petit bonus : message d'erreur plus précis selon l'environnement
    if ($is_local) {
        die("Erreur de connexion locale : Vérifiez que PostgreSQL est lancé sur le port $port");
    } else {
        die("Erreur de connexion production : Maintenance en cours.");
    }
}
?>