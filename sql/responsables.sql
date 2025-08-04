CREATE TABLE IF NOT EXISTS responsable (
    responsable_id INT(10) NOT NULL AUTO_INCREMENT,
    nom_responsable VARCHAR(100) NOT NULL,
    prenom_responsable VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL ,
    compte VARCHAR(100) NOT NULL,
    PRIMARY KEY (responsable_id)
);
