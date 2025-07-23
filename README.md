# StacGateLMS

Plateforme LMS (Learning Management System) modulaire et entièrement personnalisable développée en PHP 8.

## Description

StacGateLMS est une solution moderne de gestion d'apprentissage qui offre :

- **Personnalisation complète** : Interface WYSIWYG pour modifier tous les éléments
- **Formations flexibles** : Support synchrone (visioconférence) et asynchrone  
- **Multi-rôles** : Espaces dédiés pour administrateurs, formateurs, apprenants et managers
- **Extensibilité** : Compatible avec les plugins Moodle, SCORM et H5P
- **Archivage** : Sauvegarde automatique des sessions pour conformité et audit

## Technologies utilisées

- **Backend** : PHP 8.2+, Architecture Mini-MVC
- **Frontend** : HTML5, CSS3, JavaScript ES6 (mobile-first)
- **Base de données** : MySQL/MariaDB
- **Serveur** : Apache/Nginx
- **Gestion des dépendances** : Composer avec PSR-4

## Prérequis

- PHP 8.2 ou supérieur
- MySQL/MariaDB 8.0+
- Composer
- Serveur web (XAMPP recommandé pour le développement)

## Installation

1. Clonez le projet :


## Mode Debug Developpement/Production : 
- Dans : public/index.php
- -- En Mode Developpement : // Active les messages d’erreur en dev
define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
- -- En Mode Production : // Active les messages d’erreur en dev
define('DEBUG', False);
error_reporting(E_ALL);
ini_set('display_errors', '0');

## Clarification sur la notion de « templates » de configuration et la copie des fichiers
1. Qu’entend-on par « templates » ?
Dans le contexte d’un projet PHP :

Terme	Signification	Pourquoi c’est utile
Fichier template (exemple)	Un fichier _example.php contenant la structure attendue et des valeurs factices (hôte, utilisateur, mot de passe).	- Sert de référence commune à toute l’équipe.
- Peut être versionné sans risque dans Git : aucun secret n’y figure.
Fichier réel	La copie privée que chaque développeur conserve localement (database.php, app_settings.php, etc.) et dans laquelle il saisit ses vraies informations sensibles.	- N’est pas versionné (présent dans .gitignore).
- Contient vos identifiants MySQL, vos clés API, vos chemins de production, etc.
Ainsi, « template » ne désigne pas un moteur de vues ; c’est simplement un modèle de fichier de configuration.

2. Pourquoi la commande « copier le template » ?
Même si vous avez créé vos fichiers directement dans Config/, la procédure standard prévoit :
Créer le modèle public

text
Config/database.example.php

Le versionner dans Git pour guider les autres contributeurs.
Copier ce modèle – ou le dupliquer – en fichier privé :

text
Config/database.php

Ce fichier doit rester hors du dépôt (grâce à .gitignore).
Cette étape de copie a deux avantages :
Empêcher tout oubli : le développeur est sûr de partir d’une structure valide.
Préserver la séparation public/privé : si, par erreur, vous git add Config/*, seul database.example.php montera dans le commit ; database.php sera ignoré.
3. Cas où vous créez directement le fichier cible
Si vous avez déjà :

text
Config/database.php
Config/app_settings.php
et qu’ils sont exclus du dépôt :
La commande de copie n’est plus indispensable pour vous.
Créez néanmoins les versions example vides de secrets, puis :

bash
git add Config/database.example.php Config/app_settings.example.php
git commit -m "Ajout des templates de configuration"
Ainsi, le prochain développeur saura exactement quelles clés sont requises.

4. Résumé des bonnes pratiques
Toujours fournir un fichier _example.php (ou .env.example).
Ne jamais committer les fichiers réels contenant des secrets.
Ajouter les fichiers réels à .gitignore pour éviter les fuites.
Mettre à jour le README pour rappeler la commande :

bash
cp Config/database.example.php Config/database.php
Vous travaillez seul ? La séparation peut sembler superflue, mais elle évite les oublis le jour où le dépôt devient public ou que vous collaborez avec d’autres développeurs.
Ainsi, « template » = modèle public sans données sensibles, et la copie est le mécanisme simple qui oblige chacun à conserver ses informations confidentielles hors du contrôle de version.

# Dans Core/BaseModel.php :
Pour Tests Public :  la fonction doit etre : public static function db(): PDO .... reste du code
Pour rendre interne a l'application :  la fonction doit etre : Protected static function db(): PDO .... reste du code

# Execution SCript Requete SQL pour creation Tables :
Avant d'executer la requet des script de creations de tables (stacgatelms\migrations\001-*******) il faut supprimer les commentaires
Compte Administrateur Initial
Le script crée automatiquement un compte administrateur :

Email : admin@stacgate.local

Mot de passe : StacGate2025!

Statut : Actif avec tous les droits

## Ajouter les fichiers au dépôt
git add migrations/001_create_auth_tables.sql test-auth-tables.php

# Commit avec message descriptif
git commit -m "Étape 2.3.4 : Création complète des tables d'authentification

- Tables users, roles, permissions avec sécurité renforcée
- Système de permissions granulaires par module
- Utilisateur admin initial avec rôle système
- Contraintes et index pour performance et sécurité
- Tests de validation inclus
- ajouts script creation BD et Tables dans dossier migrations"

# Push vers le dépôt
git push origin main



git commit -m "Étape 2.4.3 : Implémentation helper base_url() - Portabilité totale

🚀 NOUVELLES FONCTIONNALITÉS :
• Helper global base_url() avec auto-détection protocole/domaine/chemin
• Support dynamique multi-environnements (localhost/production/staging)
• BasePath calculé automatiquement dans le Router
• Chargement global du helper dans le front-controller

🔧 AMÉLIORATIONS TECHNIQUES :
• Suppression de tous les chemins codés en dur (/stacgatelms/)
• Auto-détection HTTPS/HTTP selon configuration serveur
• Calcul intelligent du chemin racine depuis SCRIPT_NAME
• Gestion robuste des sous-dossiers et domaines

📝 FICHIERS MODIFIÉS :
• core/helpers.php (NOUVEAU) - Helper central de portabilité
• public/index.php - BasePath dynamique + chargement helper
• templates/layouts/main.php - Liens CSS avec base_url()
• templates/errors/*.php - Liens de retour dynamiques
• Src/Controllers/HomeController.php - Navigation portable
• Core/Router.php - Amélioration gestion erreurs debug

✅ BÉNÉFICES OBTENUS :
• Application 100% portable entre environnements
• Déploiement sans modification de code
• Compatibilité localhost/domaine/sous-domaine garantie
• Maintenance simplifiée pour les changements d'URL
• Architecture respectant les standards MVC

Tests : Fonctionnement validé en local et simulation changement dossier
Architecture : Mini-MVC modulaire maintenue
Sécurité : Échappement HTML conservé dans tous les templates"