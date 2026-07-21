# Structure du projet — Mobile Money (CodeIgniter 4 + SQLite)

```
4256_4121-Projet-S4-Final/
│
├── app/                                  ← Cœur de l'application (MVC)
│   │
│   ├── Config/                           ← Configuration du framework
│   │   ├── Database.php                  → Connexion SQLite3 ('database' => 'database.db')
│   │   ├── Routes.php                    → Définition des routes (client/, admin/)
│   │   ├── Filters.php                   → Enregistrement des filtres (clientAuth, adminAuth)
│   │   ├── App.php                       → Config générale (baseURL, locale, session…)
│   │   └── ... (Cache, Cors, Security, Session, etc.)
│   │
│   ├── Controllers/                      ← Logique métier / orchestration
│   │   ├── BaseController.php            → Contrôleur parent commun
│   │   ├── Home.php                      → Page d'accueil publique
│   │   ├── AuthController.php            → Connexion/déconnexion client
│   │   │
│   │   ├── Admin/                        ← Espace back-office
│   │   │   ├── AuthController.php        → Connexion admin (vue: connexion.php)
│   │   │   ├── DashboardController.php   → Tableau de bord admin
│   │   │   ├── OperateurController.php   → CRUD opérateurs (MVola, Airtel, Orange…) + commission
│   │   │   ├── PrefixeController.php     → Gestion des préfixes numériques (034, 033…)
│   │   │   ├── TypeOperationController.php → Types d'opérations (dépôt, retrait, transfert…)
│   │   │   ├── FraisController.php       → Grille de frais / commissions par tranche
│   │   │   └── RapportController.php     → Rapports : situationGains, situationMontants
│   │   │
│   │   └── Client/                       ← Espace utilisateur final
│   │       ├── ClientBaseController.php  → Contrôleur parent client (session, garde)
│   │       ├── CompteController.php      → Tableau de bord / solde du compte
│   │       ├── DepotController.php       → Dépôt d'argent
│   │       ├── RetraitController.php     → Retrait d'argent
│   │       ├── TransfertController.php   → Transfert (même opérateur / inter-opérateurs)
│   │       └── HistoriqueController.php  → Historique des transactions
│   │
│   ├── Models/                           ← Accès aux données (ORM CI4 / Query Builder)
│   │   ├── ClientModel.php               → Table clients (comptes, soldes)
│   │   ├── OperateurModel.php            → Table opérateurs (nom, taux de commission)
│   │   ├── PrefixeModel.php              → Table préfixes (liés à un opérateur)
│   │   ├── TypeOperationModel.php        → Table types d'opération
│   │   ├── OperationModel.php            → Table transactions (dépôt/retrait/transfert)
│   │   └── TrancheFraisModel.php         → Table tranches de frais (montant min/max → frais)
│   │
│   ├── Views/                            ← Présentation (HTML + Bootstrap)
│   │   ├── accueil.php                   → Page d'accueil publique
│   │   ├── layouts/                      → Templates communs
│   │   │   ├── accueil.php               → Layout page publique
│   │   │   ├── admin.php                 → Layout back-office (sidebar, navbar admin)
│   │   │   └── client.php                → Layout espace client
│   │   ├── admin/
│   │   │   ├── connexion.php             → Formulaire de connexion admin
│   │   │   ├── tableau_de_bord.php       → Dashboard admin
│   │   │   ├── operateurs.php            → Liste/gestion des opérateurs
│   │   │   ├── prefixes.php              → Liste/gestion des préfixes
│   │   │   ├── types_operation.php       → Liste/gestion des types d'opération
│   │   │   ├── frais.php                 → Grille de frais/commissions
│   │   │   ├── situation_gains.php       → Rapport des gains par type de frais
│   │   │   └── situation_montants.php    → Rapport des montants transigés
│   │   ├── client/
│   │   │   ├── connexion.php             → Connexion client
│   │   │   ├── tableau_de_bord.php       → Solde + résumé du compte
│   │   │   ├── depot.php                 → Formulaire de dépôt
│   │   │   ├── retrait.php               → Formulaire de retrait
│   │   │   ├── transfert.php             → Transfert simple
│   │   │   ├── transfert_multiple.php    → Transfert vers plusieurs bénéficiaires
│   │   │   └── historique.php            → Historique des transactions
│   │   └── errors/                       → Pages d'erreur (404, exceptions…)
│   │
│   ├── Filters/                          ← Middlewares CI4
│   │   ├── AdminAuthFilter.php           → Protège les routes /admin/*
│   │   └── ClientAuthFilter.php          → Protège les routes /client/*
│   │
│   ├── Libraries/                        ← Logique métier réutilisable, hors Model
│   │   └── FraisCalculator.php           → Calcule le frais/commission selon opérateur + tranche
│   │
│   ├── Database/
│   │   ├── Migrations/                   → Schéma versionné (ex: AjoutOperateursEtCommissions)
│   │   └── Seeds/                        → Données de test (opérateurs, préfixes…)
│   │
│   ├── Helpers/                          → Fonctions utilitaires globales (formatage montant, etc.)
│   └── Language/en/                      → Fichiers de traduction
│
├── public/                                ← Racine web (seul dossier exposé publiquement)
│   ├── index.php                         → Point d'entrée unique (front controller)
│   ├── .htaccess                         → Réécriture d'URL Apache
│   └── assets/
│       └── css/app.css                   → Styles custom (+ Bootstrap en CDN/vendor)
│
├── writable/                              ← Fichiers générés à l'exécution
│   ├── database.db                       → Fichier SQLite (la base de données elle-même)
│   ├── logs/                             → Logs applicatifs
│   ├── session/                          → Fichiers de session PHP
│   ├── cache/                            → Cache CI4
│   └── uploads/                          → Fichiers uploadés (justificatifs, etc.)
│
├── .env                                   → Variables d'environnement (CI_ENVIRONMENT, DB…)
├── composer.json                          → Dépendances PHP (codeigniter4/appstarter)
├── base.sql                               → Dump/schéma SQL de référence
├── spark                                  → CLI CodeIgniter (migrations, serve, make:…)
└── README.md

```
