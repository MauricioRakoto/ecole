CREATE TABLE IF NOT EXISTS matiere (
    matiere_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_matiere VARCHAR(100) NOT NULL,
    coefficient INT(5),
    PRIMARY KEY (matiere_id)
);
