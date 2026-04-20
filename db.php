<?php
$dbname   = 'sebastien.darre';       
$username = 'sebastien.darre';       
$password = 'Sebda2812/1';    

// Création de la chaîne de connexion spécifique à pg_connect
$conn_string = "dbname=$dbname user=$username password=$password";

// Tentative de connexion native
$dbconn = pg_connect($conn_string);

// Vérification de la connexion
if (!$dbconn) {
    die("Erreur critique : Impossible de se connecter à PostgreSQL.");
}
?>