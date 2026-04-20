<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// On charge la connexion
require 'db.php';

echo "<h1>Test de connexion PostgreSQL :</h1>";

if ($dbconn) {
    echo "<p style='color:green;'><b>SUCCÈS :</b> La connexion native à PostgreSQL 9.1.2 est établie !</p>";
    
    // Requête native pour lister les tables
    $query = "SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'";
    $result = pg_query($dbconn, $query);
    
    if ($result) {
        echo "<h3>Vos tables :</h3><ul>";
        // Lecture native des résultats
        while ($row = pg_fetch_assoc($result)) {
            echo "<li>" . $row['tablename'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Connexion réussie, mais impossible de lister les tables.</p>";
    }
}
?>