-- Désactivé pendant la phase DROP/CREATE : sur une base déjà peuplée (ex: réimport
-- de ce script), SQLite exécute un DELETE implicite avant chaque DROP TABLE quand
-- les clés étrangères sont actives, ce qui peut échouer si une table enfant (ex:
-- tranches_frais) contient encore des lignes référençant la table parente supprimée.
PRAGMA foreign_keys = OFF;

-- =========================================================
-- 1. TABLES
-- =========================================================

-- Opérateurs Mobile Money (Telma, Orange, Airtel, ...)
-- commission_inter_operateur = pourcentage SUPPLEMENTAIRE appliqué
-- en plus des frais normaux quand un transfert change d'opérateur.
DROP TABLE IF EXISTS operateurs;
CREATE TABLE operateurs (
    id                          INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_operateur               VARCHAR(50)   NOT NULL UNIQUE,
    commission_inter_operateur  DECIMAL(5,2)  NOT NULL DEFAULT 0, -- ex: 2.00 = 2%
    created_at                  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Préfixes de numéros autorisés, rattachés à un opérateur (ex: 034 -> Telma)
DROP TABLE IF EXISTS prefixes;
CREATE TABLE prefixes (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe       VARCHAR(5)  NOT NULL UNIQUE,   -- ex: '034'
    operateur_id  INTEGER     NOT NULL,          -- FK -> operateurs.id
    actif         INTEGER     NOT NULL DEFAULT 1, -- 1 = autorisé, 0 = désactivé
    created_at    DATETIME    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operateur_id) REFERENCES operateurs(id)
);

-- Types d'opérations possibles : dépôt, retrait, transfert
DROP TABLE IF EXISTS types_operation;
CREATE TABLE types_operation (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    code        VARCHAR(20) NOT NULL UNIQUE,
    libelle     VARCHAR(50) NOT NULL,
    actif       INTEGER     NOT NULL DEFAULT 1,
    created_at  DATETIME    DEFAULT CURRENT_TIMESTAMP
);

-- Frais par tranche de montant, propre à chaque type d'opération
DROP TABLE IF EXISTS tranches_frais;
CREATE TABLE tranches_frais (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         DECIMAL(12,2) NOT NULL,
    montant_max         DECIMAL(12,2) NULL,
    frais               DECIMAL(12,2) NOT NULL DEFAULT 0, -- frais fixe pour la tranche
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id)
);

-- Clients
DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone    VARCHAR(15) NOT NULL UNIQUE,   -- ex: 0331234567
    nom          VARCHAR(100) NULL,
    prefixe_id   INTEGER NOT NULL,
    solde        DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prefixe_id) REFERENCES prefixes(id)
);

-- Historique des opérations
DROP TABLE IF EXISTS operations;
CREATE TABLE operations (
    id                          INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id           INTEGER NOT NULL,
    client_id                   INTEGER NOT NULL,        -- client qui initie l'opération
    client_destinataire_id      INTEGER NULL,             -- rempli uniquement pour un transfert
    montant                     DECIMAL(12,2) NOT NULL,   -- montant de l'opération (hors frais)
    frais                       DECIMAL(12,2) NOT NULL DEFAULT 0, -- commission "normale" (tranche)
    commission_supplementaire   DECIMAL(12,2) NOT NULL DEFAULT 0, -- surcoût inter-opérateur (V2)
    est_inter_operateur         INTEGER       NOT NULL DEFAULT 0, -- 1 = transfert entre 2 opérateurs différents (V2)
    frais_retrait_inclus        DECIMAL(12,2) NOT NULL DEFAULT 0, -- frais de retrait prépayé par l'expéditeur (V2)
    reference_groupe            VARCHAR(40)   NULL,               -- regroupe les opérations d'un envoi multiple (V2)
    -- Un transfert crée 2 lignes (une par participant) pour que chacun voie
    -- l'opération dans son propre historique. `role` distingue la ligne réelle
    -- de l'expéditeur (PRINCIPAL, celle qui porte les frais) de la ligne miroir
    -- du destinataire (MIROIR), pour éviter de compter le montant deux fois
    -- dans les rapports par opérateur.
    role                        VARCHAR(10)   NOT NULL DEFAULT 'PRINCIPAL',
    solde_avant                 DECIMAL(12,2) NOT NULL,
    solde_apres                 DECIMAL(12,2) NOT NULL,
    statut                      VARCHAR(20) NOT NULL DEFAULT 'REUSSI', -- REUSSI | ECHEC
    created_at                  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (client_destinataire_id) REFERENCES clients(id)
);

CREATE INDEX idx_operations_client ON operations(client_id);
CREATE INDEX idx_operations_type ON operations(type_operation_id);
CREATE INDEX idx_operations_groupe ON operations(reference_groupe);
CREATE INDEX idx_clients_telephone ON clients(telephone);
CREATE INDEX idx_prefixes_operateur ON prefixes(operateur_id);

-- =========================================================
-- 2. VUES
-- =========================================================

-- Vue détaillée des opérations (pratique pour l'historique et l'admin)
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
    o.commission_supplementaire,
    o.est_inter_operateur,
    o.frais_retrait_inclus,
    o.reference_groupe,
    o.solde_avant,
    o.solde_apres,
    o.statut
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
JOIN clients c1 ON c1.id = o.client_id
LEFT JOIN clients c2 ON c2.id = o.client_destinataire_id;

-- Vue des gains générés par les frais (retrait + transfert), par type
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

-- Vue "SITUATION GAIN VIA LES DIFFERENTS FRAIS" (V2)
-- Sépare les gains intra-opérateur des gains inter-opérateur, et distingue
-- la commission normale (frais de tranche) de la commission supplémentaire.
-- Ne compte que la ligne PRINCIPAL (celle de l'expéditeur) pour ne pas
-- compter chaque transfert deux fois (la ligne MIROIR du destinataire n'a
-- jamais de frais).
DROP VIEW IF EXISTS vue_gains_operateurs;
CREATE VIEW vue_gains_operateurs AS
SELECT
    CASE WHEN o.est_inter_operateur = 1 THEN 'INTER' ELSE 'INTRA' END AS categorie,
    COUNT(o.id)                                   AS nb_operations,
    COALESCE(SUM(o.frais), 0)                     AS total_commission_normale,
    COALESCE(SUM(o.commission_supplementaire), 0) AS total_commission_supplementaire,
    COALESCE(SUM(o.frais + o.commission_supplementaire), 0) AS total_gains
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
WHERE o.statut = 'REUSSI' AND t.code = 'TRANSFERT' AND o.role = 'PRINCIPAL'
GROUP BY categorie;

-- Vue "SITUATION DES MONTANTS PAR OPERATEUR" (V2)
-- Total envoyé (transferts réussis) vers les clients de chaque opérateur.
-- Filtre role='PRINCIPAL' pour ne compter que la ligne réelle d'envoi
-- (et non la ligne miroir insérée côté destinataire pour son historique).
DROP VIEW IF EXISTS vue_montants_par_operateur;
CREATE VIEW vue_montants_par_operateur AS
SELECT
    op.id                        AS operateur_id,
    op.nom_operateur,
    COUNT(o.id)                  AS nb_operations,
    COALESCE(SUM(o.montant), 0)  AS total_envoye
FROM operateurs op
LEFT JOIN prefixes p ON p.operateur_id = op.id
LEFT JOIN clients cd ON cd.prefixe_id = p.id
LEFT JOIN operations o ON o.client_destinataire_id = cd.id
    AND o.statut = 'REUSSI'
    AND o.role = 'PRINCIPAL'
    AND o.type_operation_id = (SELECT id FROM types_operation WHERE code = 'TRANSFERT')
GROUP BY op.id;

-- Vue de la situation globale des comptes clients
DROP VIEW IF EXISTS vue_situation_comptes;
CREATE VIEW vue_situation_comptes AS
SELECT
    c.id,
    c.telephone,
    c.nom,
    p.prefixe,
    op.nom_operateur,
    c.solde,
    c.created_at
FROM clients c
JOIN prefixes p ON p.id = c.prefixe_id
JOIN operateurs op ON op.id = p.operateur_id;

-- Réactivé avant l'insertion des données : les contraintes doivent être
-- respectées pour le contenu réel de l'application.
PRAGMA foreign_keys = ON;

-- =========================================================
-- 3. DONNÉES INITIALES
-- =========================================================

-- Opérateurs (commission_inter_operateur = surcoût % pour un transfert
-- qui sort vers un autre opérateur, en plus des frais normaux)
INSERT INTO operateurs (nom_operateur, commission_inter_operateur) VALUES
    ('Telma',  2.00),
    ('Orange', 2.00),
    ('Airtel', 2.50);

-- Préfixes autorisés (exemple : Madagascar)
INSERT INTO prefixes (prefixe, operateur_id, actif) VALUES
    ('034', (SELECT id FROM operateurs WHERE nom_operateur = 'Telma'),  1),
    ('038', (SELECT id FROM operateurs WHERE nom_operateur = 'Telma'),  1),
    ('033', (SELECT id FROM operateurs WHERE nom_operateur = 'Airtel'), 1),
    ('032', (SELECT id FROM operateurs WHERE nom_operateur = 'Orange'), 1),
    ('037', (SELECT id FROM operateurs WHERE nom_operateur = 'Orange'), 1);

-- Types d'opération
INSERT INTO types_operation (code, libelle, actif) VALUES
    ('DEPOT', 'Dépôt', 1),
    ('RETRAIT', 'Retrait', 1),
    ('TRANSFERT', 'Transfert', 1);

-- Frais par tranche
-- Dépôt : gratuit (aucun frais)
INSERT INTO tranches_frais (type_operation_id, montant_min, montant_max, frais) VALUES
    (1, 0, NULL, 0);

-- Retrait : frais progressifs par tranche
INSERT INTO tranches_frais (type_operation_id, montant_min, montant_max, frais) VALUES
    (2, 0,      5000,   200),
    (2, 5001,   20000,  500),
    (2, 20001,  50000,  1000),
    (2, 50001,  NULL,   2000);

-- Transfert : frais progressifs par tranche (commission "normale", intra-opérateur)
INSERT INTO tranches_frais (type_operation_id, montant_min, montant_max, frais) VALUES
    (3, 0,      5000,   100),
    (3, 5001,   20000,  300),
    (3, 20001,  50000,  700),
    (3, 50001,  NULL,   1500);
