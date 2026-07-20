# Mobile Money Simulator — Version 1

Projet d'examen (binôme) — simulateur d'opérateur de mobile money.
Backend : PHP / CodeIgniter 4. Base de données : SQLite. Frontend : Bootstrap 5.

## Installation (première fois)

1. Installer les dépendances PHP :
   ```
   composer install
   ```

2. Créer le fichier d'environnement à partir du modèle :
   ```
   cp env .env
   ```
   Le fichier `.env` de ce dépôt (non versionné) doit contenir au minimum :
   ```
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'

   database.default.DBDriver = SQLite3
   database.default.database = database.db
   database.default.DBPrefix =
   database.default.foreignKeys = true

   admin.username = admin
   admin.password = admin123
   ```

3. Créer / réinitialiser la base SQLite à partir de `base.sql` :
   ```
   php -r "$db = new SQLite3('writable/database.db'); $db->exec(file_get_contents('base.sql'));"
   ```
   Cette commande (re)crée les tables, les vues et les données initiales
   (préfixes, types d'opération, tranches de frais, clients de démo) dans
   `writable/database.db`. Le fichier `.db` n'est **pas** versionné : c'est
   `base.sql` qui fait foi.

4. Lancer le serveur de développement :
   ```
   php spark serve
   ```
   Le site est accessible sur http://localhost:8080

## Comptes de démonstration

- Espace client : connectez-vous avec le numéro `0331234567` ou `0341234567`
  (comptes créés par `base.sql`). Tout numéro dont le préfixe est autorisé
  (033, 034, 037, 038) crée automatiquement un compte à la connexion.
- Espace admin : http://localhost:8080/admin/connexion
  — identifiants définis dans `.env` (`admin.username` / `admin.password`,
  par défaut `admin` / `admin123`).

## Structure du projet

Voir le schéma de base de données et l'organisation des dossiers dans
`base.sql` (tables/vues) et `app/` (Controllers, Models, Views, Filters,
Libraries). Le suivi des tâches par étudiant est dans `Taches.md`.
