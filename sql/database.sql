-- Base de données
CREATE DATABASE IF NOT EXISTS ecole;
USE ecole;

-- Table des classes
CREATE TABLE IF NOT EXISTS classes (
    classe_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_classe VARCHAR(100) NOT NULL,
    niveau VARCHAR(50),
    annee_debut YEAR,
    annee_fin YEAR,
    salle VARCHAR(50),
    PRIMARY KEY (classe_id)
);

-- Table des élèves
CREATE TABLE IF NOT EXISTS eleves (
    eleve_id INT(10) NOT NULL AUTO_INCREMENT,
    numero INT(10),
    nom_eleve VARCHAR(100),
    prenom_eleve VARCHAR(100),
    sexe_eleve VARCHAR(50),
    date_naissance DATE,
    lieu_naissance VARCHAR(50),
    STATUS VARCHAR(50),
    adresse VARCHAR(50),
    tel_eleve VARCHAR(50),
    etablissement_orign VARCHAR(50),
    annee_scolaire VARCHAR(50),
    date_inscrit DATE,

    nom_pere VARCHAR(100),
    profession_pere VARCHAR(50),
    tel_pere VARCHAR(50),

    nom_mere VARCHAR(100),
    profession_mere VARCHAR(50),
    tel_mere VARCHAR(50),

    nom_tuteur VARCHAR(100),
    profession_tuteur VARCHAR(50),
    tel_tuteur VARCHAR(50),

    classe_id INT(10),
    
    PRIMARY KEY (eleve_id),
    FOREIGN KEY (classe_id) REFERENCES classe(classe_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);


-- Table des matières
CREATE TABLE IF NOT EXISTS matieres (
    matiere_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_matiere VARCHAR(100) NOT NULL,
    coefficient INT(5),
    PRIMARY KEY (matiere_id)
);


-- Table des responsables
CREATE TABLE IF NOT EXISTS responsable (
    responsable_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_responsable VARCHAR(100) NOT NULL,
    prenom_responsable VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL ,
    compte VARCHAR(100) NOT NULL,
    PRIMARY KEY (responsable_id)
);