<?php
/**
 * API PROXY JIRA - CAPACITY PLANNER
 * -------------------------------------------------------------------------
 * Ce fichier sert de pont sécurisé entre le Capacity Planner et l'API Jira.
 * Il permet d'éviter d'exposer le Personal Access Token (PAT) côté client.
 * -------------------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

// CONFIGURATION (À REMPLIR LORS DE L'OBTENTION DU TOKEN)
$JIRA_CONFIG = [
    'enabled' => false, // Feature Flag serveur
    'url'     => 'https://votre-instance.atlassian.net',
    'token'   => '', // Personal Access Token (PAT)
    'project' => 'PROJET_CLE'
];

// SI DÉSACTIVÉ OU TOKEN ABSENT
if (!$JIRA_CONFIG['enabled'] || empty($JIRA_CONFIG['token'])) {
    echo json_encode([
        'status'  => 'inactive',
        'message' => 'L\'intégration Jira n\'est pas encore configurée ou activée.',
        'data'    => null
    ]);
    exit;
}

// LOGIQUE DE RÉCUPÉRATION JQL (À IMPLÉMENTER PLUS TARD)
// ...
