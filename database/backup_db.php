<?php
// backup_db.php (Version Sécurisée UTF-8)
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require 'db.php';

if (!$dbconn) { 
    die("Erreur Critique : Connexion BDD perdue."); 
}

// 1. On force PostgreSQL à nous parler en UTF-8 !
pg_set_client_encoding($dbconn, "UTF8");

$tables_to_backup = ['utilisateurs', 'membres', 'equipes', 'sprints', 'absences_sprints', 'historique_pi', 'kpi_ratios_equipe', 'predictions', 'jours_feries'];
$backup_data = [];

foreach ($tables_to_backup as $table) {
    $query = "SELECT * FROM $table";
    $result = @pg_query($dbconn, $query);
    
    if ($result) {
        $backup_data[$table] = [];
        while ($row = pg_fetch_assoc($result)) {
            // Sécurité supplémentaire : on force l'encodage UTF-8 sur chaque cellule
            array_walk_recursive($row, function(&$item) {
                if (is_string($item) && !mb_check_encoding($item, 'UTF-8')) {
                    $item = utf8_encode($item);
                }
            });
            $backup_data[$table][] = $row;
        }
    } else {
        $backup_data[$table] = "Table vide ou inexistante.";
    }
}

// 2. Génération du JSON
$json_output = json_encode($backup_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// 3. Vérification de la conversion
if ($json_output === false) {
    die("Échec de la génération du JSON. Erreur : " . json_last_error_msg());
}

// 4. Téléchargement forcé
$filename = 'backup_prac_db_' . date('Y-m-d_H-i-s') . '.json';
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo $json_output;
exit;
?>