PRAGMA foreign_keys = ON;

<<<<<<< HEAD
-- 1. TABLES

-- Préfixes de numéros autorisés par l'opérateur (ex: 034, 037)
DROP TABLE IF EXISTS prefixes;
CREATE TABLE prefixes (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe     VARCHAR(5)  NOT NULL UNIQUE,   -- ex: '034'
    operateur   VARCHAR(50) NOT NULL,          -- ex: 'Telma'
    actif       INTEGER     NOT NULL DEFAULT 1, -- 1 = autorisé, 0 = désactivé
    created_at  DATETIME    DEFAULT CURRENT_TIMESTAMP
);

-- Types d'opérations possibles : dépôt, retrait, transfert
DROP TABLE IF EXISTS types_operation;
CREATE TABLE types_operation (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    code        VARCHAR(20) NOT NULL UNIQUE,
    libelle     VARCHAR(50) NOT NULL,
=======
DROP TABLE IF EXISTS prefixes;
CREATE TABLE prefixes (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe     VARCHAR(5)  NOT NULL UNIQUE,
    operateur   VARCHAR(50) NOT NULL,
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
    actif       INTEGER     NOT NULL DEFAULT 1,
    created_at  DATETIME    DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS types_operation;
CREATE TABLE types_operation (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    code        VARCHAR(20) NOT NULL UNIQUE,
    libelle     VARCHAR(50) NOT NULL,
    actif       INTEGER     NOT NULL DEFAULT 1,
    created_at  DATETIME    DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS tranches_frais;
CREATE TABLE tranches_frais (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         DECIMAL(12,2) NOT NULL,
    montant_max         DECIMAL(12,2) NULL,
<<<<<<< HEAD
    frais               DECIMAL(12,2) NOT NULL DEFAULT 0, -- frais fixe pour la tranche
=======
    frais               DECIMAL(12,2) NOT NULL DEFAULT 0,
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id)
);

<<<<<<< HEAD
-- Clients :
DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone    VARCHAR(15) NOT NULL UNIQUE,   -- ex: 0331234567
=======
DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone    VARCHAR(15) NOT NULL UNIQUE,
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
    nom          VARCHAR(100) NULL,
    prefixe_id   INTEGER NOT NULL,
    solde        DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prefixe_id) REFERENCES prefixes(id)
);

<<<<<<< HEAD
-- Historique des opérations
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
DROP TABLE IF EXISTS operations;
CREATE TABLE operations (
    id                     INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id      INTEGER NOT NULL,
<<<<<<< HEAD
    client_id              INTEGER NOT NULL,        -- client qui initie l'opération
    client_destinataire_id INTEGER NULL,             -- rempli uniquement pour un transfert
    montant                DECIMAL(12,2) NOT NULL,   -- montant de l'opération (hors frais)
    frais                  DECIMAL(12,2) NOT NULL DEFAULT 0,
    solde_avant            DECIMAL(12,2) NOT NULL,
    solde_apres            DECIMAL(12,2) NOT NULL,
    statut                 VARCHAR(20) NOT NULL DEFAULT 'REUSSI', -- REUSSI | ECHEC
=======
    client_id              INTEGER NOT NULL,
    client_destinataire_id INTEGER NULL,
    montant                DECIMAL(12,2) NOT NULL,
    frais                  DECIMAL(12,2) NOT NULL DEFAULT 0,
    solde_avant            DECIMAL(12,2) NOT NULL,
    solde_apres            DECIMAL(12,2) NOT NULL,
    statut                 VARCHAR(20) NOT NULL DEFAULT 'REUSSI',
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
    created_at             DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (client_destinataire_id) REFERENCES clients(id)
);

CREATE INDEX idx_operations_client ON operations(client_id);
CREATE INDEX idx_operations_type ON operations(type_operation_id);
CREATE INDEX idx_clients_telephone ON clients(telephone);

<<<<<<< HEAD
-- 2. VUES

-- Vue détaillée des opérations (pratique pour l'historique et l'admin)
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
DROP VIEW IF EXISTS vue_operations_detail;
CREATE VIEW vue_operations_detail AS
SELECT
    o.id,
    o.created_at,
    t.code            AS type_code,
    t.libelle         AS type_libelle,
    c1.telephone      AS client_telephone,
    c2.telephone      AS destinataire_telephone,
    o.montant,
    o.frais,
    o.solde_avant,
    o.solde_apres,
    o.statut
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
JOIN clients c1 ON c1.id = o.client_id
LEFT JOIN clients c2 ON c2.id = o.client_destinataire_id;

<<<<<<< HEAD
-- Vue des gains générés par les frais (retrait + transfert), par type
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
DROP VIEW IF EXISTS vue_gains_par_type;
CREATE VIEW vue_gains_par_type AS
SELECT
    t.code                AS type_code,
    t.libelle             AS type_libelle,
    COUNT(o.id)            AS nb_operations,
    COALESCE(SUM(o.frais), 0) AS total_frais
FROM types_operation t
LEFT JOIN operations o ON o.type_operation_id = t.id AND o.statut = 'REUSSI'
GROUP BY t.id;

<<<<<<< HEAD
-- Vue de la situation globale des comptes clients
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
DROP VIEW IF EXISTS vue_situation_comptes;
CREATE VIEW vue_situation_comptes AS
SELECT
    c.id,
    c.telephone,
    c.nom,
    p.prefixe,
    c.solde,
    c.created_at
FROM clients c
JOIN prefixes p ON p.id = c.prefixe_id;

<<<<<<< HEAD
-- 3. DONNÉES INITIALES

-- Préfixes autorisés (exemple : Madagascar)
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
INSERT INTO prefixes (prefixe, operateur, actif) VALUES
    ('034', 'Telma', 1),
    ('038', 'Telma', 1),
    ('033', 'Airtel', 1),
    ('032', 'Orange', 1),
    ('037', 'Orange', 1);

<<<<<<< HEAD
-- Types d'opération
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
INSERT INTO types_operation (code, libelle, actif) VALUES
    ('DEPOT', 'Dépôt', 1),
    ('RETRAIT', 'Retrait', 1),
    ('TRANSFERT', 'Transfert', 1);

<<<<<<< HEAD
-- Frais par tranche
-- Dépôt : gratuit (aucun frais)
INSERT INTO tranches_frais (type_operation_id, montant_min, montant_max, frais) VALUES
    (1, 0, NULL, 0);

-- Retrait : frais progressifs par tranche
=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
INSERT INTO tranches_frais (type_operation_id, montant_min, montant_max, frais) VALUES
    (1, 0, NULL, 0),
    (2, 0,      5000,   200),
    (2, 5001,   20000,  500),
    (2, 20001,  50000,  1000),
<<<<<<< HEAD
    (2, 50001,  NULL,   2000);

-- Transfert : frais progressifs par tranche
INSERT INTO tranches_frais (type_operation_id, montant_min, montant_max, frais) VALUES
=======
    (2, 50001,  NULL,   2000),
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
    (3, 0,      5000,   100),
    (3, 5001,   20000,  300),
    (3, 20001,  50000,  700),
    (3, 50001,  NULL,   1500);
<<<<<<< HEAD

=======
>>>>>>> 59a97c7b961abbec2d87040bfc21e4fe4164f648
