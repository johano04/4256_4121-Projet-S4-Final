# Taches.md — Suivi des tâches (Version 1)

Projet : Simulateur d'opérateur Mobile Money
Binôme : Étudiant 1 (_à compléter_) / Étudiant 2 (_à compléter_)

Légende : `[ ]` à faire · `[x]` terminé · `[~]` en cours

---

## Répartition proposée

- **Étudiant 1 — Côté opérateur (admin)**
- **Étudiant 2 — Côté client**

(À adapter selon vos préférences, mais évitez de travailler à deux sur le même fichier en même temps pour limiter les conflits Git.)

---

## Socle commun (à faire ensemble avant de se répartir)

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

## Étudiant 1 — Côté opérateur (admin)

- [x] Connexion admin (`Admin\AuthController`)
- [x] Gestion des préfixes autorisés (`Admin\PrefixeController` + vue `admin/prefixes.php`)
- [x] Gestion des types d'opération (`Admin\TypeOperationController` + vue `admin/types_operation.php`)
- [x] Gestion des frais par tranche (`Admin\FraisController` + vue `admin/frais.php`)
- [x] Tableau de bord admin : gains par frais + situation globale des comptes (`Admin\DashboardController`)
- [ ] Ajouter une recherche/filtre sur la liste des comptes clients
- [ ] Ajouter une pagination sur l'historique global des opérations (si besoin)
- [ ] Relecture et tests manuels de tout le module admin

## Étudiant 2 — Côté client

- [x] Connexion automatique par numéro de téléphone (`AuthController`)
- [x] Tableau de bord client : solde + dernières opérations (`Client\CompteController`)
- [x] Dépôt (`Client\DepotController` + vue `client/depot.php`)
- [x] Retrait avec vérification du solde (`Client\RetraitController` + vue `client/retrait.php`)
- [x] Transfert entre clients (`Client\TransfertController` + vue `client/transfert.php`)
- [x] Historique complet des opérations (`Client\HistoriqueController` + vue `client/historique.php`)
- [ ] Améliorer les messages d'erreur (montant invalide, numéro inconnu, etc.)
- [ ] Relecture et tests manuels de tout le module client

---

## Tests à faire avant de taguer v1

- [ ] Connexion avec un numéro à préfixe autorisé → compte créé automatiquement
- [ ] Connexion avec un numéro à préfixe non autorisé → message d'erreur
- [ ] Dépôt → solde mis à jour, opération dans l'historique
- [ ] Retrait avec solde suffisant → frais appliqués correctement selon la tranche
- [ ] Retrait avec solde insuffisant → opération refusée
- [ ] Transfert vers un numéro existant → débit envoyeur (montant + frais), crédit destinataire (montant seul)
- [ ] Transfert vers un numéro inconnu → message d'erreur
- [ ] Admin : ajout/désactivation d'un préfixe
- [ ] Admin : ajout/désactivation d'un type d'opération
- [ ] Admin : ajout/suppression d'une tranche de frais
- [ ] Admin : le tableau de bord reflète bien les gains et la situation des comptes

---

## Livraison V1

- [ ] `base.sql` à jour à la racine
- [ ] `Taches.md` à jour
- [ ] Commit final + tag `v1`
