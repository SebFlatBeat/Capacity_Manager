# 🚀 Capacity Planner Agile - Documentation Technique

## 📌 Présentation
Le **Capacity Planner** est un outil de pilotage capacitaire conçu pour les trains SAFe et les organisations Agile. Il permet de calculer l'offre humaine réelle (Net Capacity) en déduisant les absences, la MCO (Maintien en Condition Opérationnelle) et la TRA (Travaux de Recette) de la capacité brute des équipes.

---

## 🛠 Stack Technique
*   **Frontend :** React 18 (via Babel Standalone), TailwindCSS 3.
*   **Icons :** FontAwesome 6.0.
*   **Backend :** PHP 7.4+ (APIs REST).
*   **Database :** PostgreSQL 9.1.2 (Compatibilité SQL Standard).
*   **Design :** Glassmorphism "Next-Gen" avec Hiérarchie d'élévation Dark Mode.

---

## 🏗 Architecture & Gestion des PI (Program Increments)
L'application gère désormais le cycle de vie complet des PI :

### 1. Cycle de Vie & Statuts
*   **PLANIFICATION** : État initial. Les dates et équipes sont configurables. Le Dashboard affiche des prévisions.
*   **EN COURS** : Suivi réel activé. Les données de réalisation (Say/Do) sont suivies en temps réel.
*   **ARCHIVE** : Le PI est clos. L'interface passe en **Lecture Seule** pour protéger l'intégrité de l'historique.

### 2. Système de Navigation (Multi-PI)
*   **Sélecteur de Vue** : Un menu déroulant dans le Header permet de basculer instantanément entre les PI.
*   **Héritage Automatique** : Lors de la création d'un nouveau PI, le système clone automatiquement la structure des équipes et récupère la **dernière vélocité connue** (Velocity Factor) pour assurer la continuité des prévisions.
*   **Filtrage Hybride** : Le backend supporte les formats de noms flexibles (ex: "2026.2" et "PI 2026.2") pour une transition sans perte de données.

---

## 🧠 Moteur de Calcul (Engine)
### 1. Capacité brute vs Nette
`Disponibilité (jours) = Jours_Ouvrés_Sprint - Absences`
*   **Capacité Brute :** Potentiel total de production (Build + MCO + TRA).
*   **Capacité Nette (Build) :** Potentiel réel après déduction des activités transverses.

### 2. Calcul du Temps Écoulé
Le Dashboard calcule le `% de temps écoulé` en utilisant prioritairement les **Dates Globales du PI** (Début/Fin) définies dans l'onglet Config, avec un repli sur les dates des sprints si elles ne sont pas renseignées.

---

## 🌓 Dark Mode & Standards UX
Le design respecte une hiérarchie d'élévation spécifique pour le confort visuel en environnement sombre :
*   **Niveau 3 (Lightest) :** Navigation Header (Toujours visible).
*   **Niveau 2 :** Cartes Bento et Tableaux.
*   **Niveau 1 (Darkest) :** Fond de page avec gradients radiaux subtils.
*   **Accessibilité :** Ratio de contraste maintenu > 4.5:1 sur tous les textes.

---

## 🗄 Structure Base de Données (PostgreSQL 9.1.2)
Table principale modifiée : `historique_pi`
*   `statut` (VARCHAR) : Etat du PI.
*   `date_debut` / `date_fin` (DATE) : Bornes temporelles du train.
*   `iterations` (INT) : Nombre de sprints prévus.
*   `jours_par_iteration` (INT) : Cadence standard.

---

## 📡 Interactions API
*   `api_equipes.php` : Gestion intelligente des PI (Héritage et Filtrage).
*   `api_historique.php` : CRUD des PI et changement de statut.
*   `api_sprints.php` : Dates des itérations filtrées par PI.

---
*Dernière mise à jour : 18 Avril 2026 - Version 4.3.0 "Multi-PI Edition"*
