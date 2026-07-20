PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS prefixes (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe     VARCHAR(10) NOT NULL UNIQUE,
    description VARCHAR(100),
    actif       TINYINT DEFAULT 1,
    created_at  DATETIME,
    updated_at  DATETIME
);

CREATE TABLE IF NOT EXISTS types_operation (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    code        VARCHAR(30) NOT NULL UNIQUE,
    libelle     VARCHAR(100) NOT NULL,
    frais_actif TINYINT DEFAULT 1,
    created_at  DATETIME,
    updated_at  DATETIME
);

CREATE TABLE IF NOT EXISTS baremes (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER UNSIGNED NOT NULL,
    montant_min         INTEGER UNSIGNED NOT NULL,
    montant_max         INTEGER UNSIGNED,
    frais               INTEGER UNSIGNED NOT NULL,
    created_at          DATETIME,
    updated_at          DATETIME,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS clients (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    nom         VARCHAR(100) NOT NULL,
    telephone   VARCHAR(20) NOT NULL UNIQUE,
    solde       DECIMAL(15,2) DEFAULT 0,
    actif       TINYINT DEFAULT 1,
    created_at  DATETIME,
    updated_at  DATETIME
);

CREATE TABLE IF NOT EXISTS transactions (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    reference           VARCHAR(50) NOT NULL UNIQUE,
    type_operation_id   INTEGER UNSIGNED NOT NULL,
    client_id           INTEGER UNSIGNED NOT NULL,
    client_dest_id      INTEGER UNSIGNED,
    montant             DECIMAL(15,2) NOT NULL,
    frais               DECIMAL(15,2) DEFAULT 0,
    gain_operateur      DECIMAL(15,2) DEFAULT 0,
    statut              VARCHAR(20) DEFAULT 'succes',
    created_at          DATETIME,
    updated_at          DATETIME,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (client_id)        REFERENCES clients(id)         ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (client_dest_id)   REFERENCES clients(id)         ON DELETE SET NULL ON UPDATE CASCADE
);

INSERT INTO prefixes (prefixe, description, actif, created_at, updated_at) VALUES
    ('033', 'Préfixe operateur 033', 1, datetime('now'), datetime('now')),
    ('037', 'Préfixe operateur 037', 1, datetime('now'), datetime('now'));

INSERT INTO types_operation (code, libelle, frais_actif, created_at, updated_at) VALUES
    ('depot',     'Dépôt',     0, datetime('now'), datetime('now')),
    ('retrait',   'Retrait',   1, datetime('now'), datetime('now')),
    ('transfert', 'Transfert', 1, datetime('now'), datetime('now'));

INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais, created_at, updated_at)
SELECT t.id, b.montant_min, b.montant_max, b.frais, datetime('now'), datetime('now')
FROM types_operation t
CROSS JOIN (
    SELECT 100     AS montant_min, 1000     AS montant_max, 50    AS frais UNION ALL
    SELECT 1001    AS montant_min, 5000     AS montant_max, 50    AS frais UNION ALL
    SELECT 5001    AS montant_min, 10000    AS montant_max, 100   AS frais UNION ALL
    SELECT 10001   AS montant_min, 25000    AS montant_max, 200   AS frais UNION ALL
    SELECT 25001   AS montant_min, 50000    AS montant_max, 400   AS frais UNION ALL
    SELECT 50001   AS montant_min, 100000   AS montant_max, 800   AS frais UNION ALL
    SELECT 100001  AS montant_min, 250000   AS montant_max, 1500  AS frais UNION ALL
    SELECT 250001  AS montant_min, 500000   AS montant_max, 1500  AS frais UNION ALL
    SELECT 500001  AS montant_min, 1000000  AS montant_max, 2500  AS frais UNION ALL
    SELECT 1000001 AS montant_min, 2000000  AS montant_max, 3000  AS frais
) b
WHERE t.code IN ('retrait', 'transfert');
