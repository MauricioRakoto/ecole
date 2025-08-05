CREATE TABLE cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matiere_id INT NOT NULL,
    classe_id INT NOT NULL,
    FOREIGN KEY (matiere_id) REFERENCES matieres(matiere_id),
    FOREIGN KEY (classe_id) REFERENCES classes(classe_id)
);
