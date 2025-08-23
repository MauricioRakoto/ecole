<?php
require_once "../database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_matiere = $_POST['nom_matiere'];
    $coef = $_POST['coefficient'];

    // Vérifie si la combinaison existe déjà
    $check_sql = "SELECT * FROM matieres WHERE matiere_id != ?";
    $stmt_check = $pdo->prepare($check_sql);
    $stmt_check->execute([$_GET['id']]);

    if ( $stmt_check->rowCount() == 0 ) {
        $msg = "Matière n'existe pas";
        header('Location: ../../classes/index.php' . '?' . 'msg=error');
        
    } else {
        $sql = "UPDATE matieres SET nom_matiere = ?, 
                                    coefficient = ?
                WHERE matiere_id = ?
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom_matiere, 
            $coef, 
            $_GET['id']
        ]);

        $msg = "Matière modifier avec success";

        header('Location: ../../matieres/index.php' . '?' . 'msg=success');

    }


}