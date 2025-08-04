CREATE TABLE IF NOT EXISTS eleves (
    eleve_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_eleve VARCHAR(100),
    prenom_eleve VARCHAR(100),
    sexe_eleve VARCHAR(50),
    date_naissance DATE,
    lieu_naissance VARCHAR(50),
    status VARCHAR(50),
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
