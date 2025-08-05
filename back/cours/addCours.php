<?php
require_once "../database.php";

if (isset($_POST['enregistrer'])) {
    $matiere_id = $_POST['matiere_id'];
    $classe_id = $_POST['classe_id'];

    // Vérifie si cette matière est déjà associée à cette classe
    $check_sql = "SELECT * FROM cours WHERE matiere_id = ? AND classe_id = ?";
    $stmt_check = $pdo->prepare($check_sql);
    $stmt_check->execute([$matiere_id, $classe_id]);

    if ($stmt_check->rowCount() > 0) {
        // La matière existe déjà pour cette classe
        echo "
            <div style='color: red; margin-top: 20px;'>
                Cette matière est déjà ajoutée pour cette classe.
                <br><a href='add-cours.php'>Retour</a>
            </div>
        ";
    } else {
        // Insertion si pas encore ajoutée
        $insert_sql = "INSERT INTO cours (matiere_id, classe_id) VALUES (?, ?)";
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute([$matiere_id, $classe_id]);

        echo "
            <div style='color: green; margin-top: 20px;'>
                Cours ajouté avec succès.
                <br><a href='../../classes/addcours.php?id=". $classe_id ."'>Retour</a>
            </div>
        ";
    }
}
?>
