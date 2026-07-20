
Projet : S4 Final Mobile Money


Légende : `[ ]` à faire · `[x]` terminé · `[~]` en cours

---


- 4121 — Côté opérateur (admin)
- 4256 — Côté client

(À adapter selon vos préférences, mais évitez de travailler à deux sur le même fichier en même temps pour limiter les conflits Git.)

---
-On a fait ceci ensemble :

- [x] Installation du projet CodeIgniter 4
- [x] Configuration de la base SQLite (`.env`, `app/Config/Database.php`)
- [x] Conception du schéma de base de données
- [x] Écriture de `base.sql` (tables, vues, données initiales)
- [x] Import de `base.sql` dans `writable/database.db`
- [x] Modèles CI4 : `PrefixeModel`, `TypeOperationModel`, `TrancheFraisModel`, `ClientModel`, `OperationModel`
- [x] Service `FraisCalculator` (calcul des frais par tranche)
- [x] Filtres d'authentification (`clientAuth`, `adminAuth`)
- [x] Définition des routes (`app/Config/Routes.php`)
- [x] Layouts Bootstrap (`layouts/client.php`, `layouts/admin.php`)

---
4121 — Côté opérateur (admin)

- [x] Connexion admin (`Admin\AuthController`)
- [x] Gestion des préfixes autorisés (`Admin\PrefixeController` + vue `admin/prefixes.php`)
- [x] Gestion des types d'opération (`Admin\TypeOperationController` + vue `admin/types_operation.php`)
- [x] Gestion des frais par tranche (`Admin\FraisController` + vue `admin/frais.php`)
- [x] Édition des tranches de frais existantes (modal « Modifier » + route `frais/(:num)/editer`)
- [x] Tableau de bord admin : gains par frais + situation globale des comptes (`Admin\DashboardController`)
- [x] Page d'accueil (`/`) présentant les deux espaces (Client / Administrateur)
- [x] Lien « Espace administrateur » sur la connexion client et lien « Se connecter en tant que client » sur la connexion admin
- [ ] Ajouter une recherche/filtre sur la liste des comptes clients
- [ ] Ajouter une pagination sur l'historique global des opérations (si besoin)
- [ ] Relecture et tests manuels de tout le module admin
- [x] Base nettoyée : aucun client exemple (comptes à créer manuellement via `/connexion`)

4256— Côté client

- [x] Connexion automatique par numéro de téléphone (`AuthController`)
- [x] Tableau de bord client : solde + dernières opérations (`Client\CompteController`)
- [x] Dépôt (`Client\DepotController` + vue `client/depot.php`)
- [x] Retrait avec vérification du solde (`Client\RetraitController` + vue `client/retrait.php`)
- [x] Transfert entre clients (`Client\TransfertController` + vue `client/transfert.php`)
- [x] Historique complet des opérations (`Client\HistoriqueController` + vue `client/historique.php`)
- [ ] Améliorer les messages d'erreur (montant invalide, numéro inconnu, etc.)
- [ ] Relecture et tests manuels de tout le module client

---
Test 

- [x] Connexion avec un numéro à préfixe autorisé → compte créé automatiquement
- [x] Connexion avec un numéro à préfixe non autorisé → message d'erreur
- [x] Dépôt → solde mis à jour, opération dans l'historique
- [x] Retrait avec solde suffisant → frais appliqués correctement selon la tranche
- [x] Retrait avec solde insuffisant → opération refusée
- [x] Transfert vers un numéro existant → débit envoyeur (montant + frais), crédit destinataire (montant seul)
- [x] Transfert vers un numéro inconnu → message d'erreur
- [x] Admin : ajout/désactivation d'un préfixe
- [x] Admin : ajout/désactivation d'un type d'opération
- [x] Admin : ajout/suppression/édition d'une tranche de frais
- [x] Admin : le tableau de bord reflète bien les gains et la situation des comptes

---

Livraison V1

- [x] `base.sql` à jour à la racine
- [x] `Taches.md` à jour
- [x] Commit final + tag `v1`

---

4121 — Côté opérateur (admin) — V2

- [x] Table `operateurs` (nom + `commission_inter_operateur` %) — `Admin\OperateurController` + vue `admin/operateurs.php`
- [x] Préfixes rattachés à un opérateur via `operateur_id` (FK) au lieu d'un texte libre (`Admin\PrefixeController`, `admin/prefixes.php`)
- [x] Commission inter-opérateur appliquée automatiquement quand un transfert change d'opérateur (en plus des frais normaux)
- [x] Page « Situation gain via les différents frais » : intra vs inter-opérateur, commission normale vs supplémentaire — `Admin\RapportController::situationGains` + `admin/situation_gains.php`
- [x] Page « Situation des montants par opérateur » : total envoyé (transferts) vers chaque opérateur — `Admin\RapportController::situationMontants` + `admin/situation_montants.php`

4256 — Côté client — V2

- [x] Case « Inclure frais de retrait » sur le transfert : l'expéditeur prépaie le frais de retrait, le destinataire reçoit sans frais — `Client\TransfertController::effectuer`
- [x] Envoi multiple : plusieurs numéros, division automatique du montant total OU montant personnalisé par numéro — `Client\TransfertController::multiple` / `effectuerMultiple` + vue `client/transfert_multiple.php`
- [x] Historique : badge « lot » pour les envois multiples, colonne commission inter-opérateur

Base de données V2

- [x] `base.sql` corrigé (conflit Git non résolu) et étendu (table `operateurs`, `operations.commission_supplementaire`/`est_inter_operateur`/`frais_retrait_inclus`/`reference_groupe`/`role`, nouvelles vues)
- [x] Migration CI4 `app/Database/Migrations/2026-07-20-100000_AjoutOperateursEtCommissions.php`
- [x] Testé de bout en bout (transfert intra/inter, frais de retrait inclus, envoi multiple, rapports admin) sur le serveur de dev
