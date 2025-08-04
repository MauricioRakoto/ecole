CREATE TABLE IF NOT EXISTS classe (
    classe_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_classe VARCHAR(100) NOT NULL,
    niveau VARCHAR(50),
    annee_debut YEAR,
    annee_fin YEAR,
    salle VARCHAR(50),
    PRIMARY KEY (classe_id)
);
