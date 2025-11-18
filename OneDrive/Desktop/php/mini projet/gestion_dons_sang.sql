REATE DATABASE IF NOT EXISTS gestion_dons_sang;
USE gestion_dons_sang;


CREATE TABLE donneurs (
    id_donneur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    cin VARCHAR(20) NOT NULL UNIQUE,
    date_naissance DATE,
    groupe_sanguin ENUM('A', 'B', 'AB', 'O') NOT NULL,
    rhesus ENUM('+', '-') NOT NULL,
    adresse VARCHAR(255),
    telephone VARCHAR(20)
);


CREATE TABLE centres_collecte (
    id_centre INT AUTO_INCREMENT PRIMARY KEY,
    nom_centre VARCHAR(150) NOT NULL,
    adresse VARCHAR(255),
    telephone VARCHAR(20)
);


CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,  -- haché avec password_hash()
    role ENUM('ADMIN', 'MEDECIN', 'SECRETAIRE') NOT NULL,
    id_centre INT,
    FOREIGN KEY (id_centre) REFERENCES centres_collecte(id_centre)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);


CREATE TABLE dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_donneur INT NOT NULL,
    id_centre INT NOT NULL,
    date_don DATE NOT NULL,
    statut ENUM('EN_STOCK', 'UTILISE', 'REJETE') DEFAULT 'EN_STOCK',
    FOREIGN KEY (id_donneur) REFERENCES donneurs(id_donneur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_centre) REFERENCES centres_collecte(id_centre)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE tests_don (
    id_test INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT UNIQUE NOT NULL,
    resultat ENUM('POSITIF', 'NEGATIF') NOT NULL,
    est_conforme BOOLEAN NOT NULL DEFAULT 0,
    date_test DATE NOT NULL,
    FOREIGN KEY (id_don) REFERENCES dons(id_don)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);



CREATE TABLE transfusions (
    id_transfusion INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT UNIQUE NOT NULL,
    hopital_recepteur VARCHAR(150) NOT NULL,
    date_transfusion DATE NOT NULL,
    FOREIGN KEY (id_don) REFERENCES dons(id_don)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);




CREATE TABLE besoins (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    groupe_sanguin ENUM('A', 'B', 'AB', 'O') NOT NULL,
    rhesus ENUM('+', '-') NOT NULL,
    niveau_alerte ENUM('URGENT', 'CRITIQUE', 'NORMAL') DEFAULT 'NORMAL',
    quantite_minimale INT DEFAULT 0,
    date_maj DATE DEFAULT CURRENT_DATE
);