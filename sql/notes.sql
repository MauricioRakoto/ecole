CREATE TABLE notes (
    note_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    session INT(5) NOT NULL,
    type VARCHAR(50) NOT NULL,
    note FLOAT NOT NULL,
    classe_id INT(10) NOT NULL,
    matiere_id INT(10) NOT NULL,
    eleve_id INT(10) NOT NULL,

    -- Clés étrangères
    CONSTRAINT fk_classe_note FOREIGN KEY (classe_id) REFERENCES classes(classe_id) ON DELETE CASCADE,
    CONSTRAINT fk_matiere_note FOREIGN KEY (matiere_id) REFERENCES matieres(matiere_id) ON DELETE CASCADE,
    CONSTRAINT fk_eleve_note FOREIGN KEY (eleve_id) REFERENCES eleves(eleve_id) ON DELETE CASCADE
);