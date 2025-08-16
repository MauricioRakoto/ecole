<?php
require_once "../database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_classe = $_POST['nom_classe'];
    $niveau = $_POST['niveau'];
    $annee_debut = $_POST['annee_debut'];
    $annee_fin = $_POST['annee_fin'];
    $salle = $_POST['salle'];

    // Vérifie si la combinaison existe déjà
    $check_sql = "SELECT * FROM classes WHERE classe_id != ?";
    $stmt_check = $pdo->prepare($check_sql);
    $stmt_check->execute([$_GET['id']]);

    if ( $stmt_check->rowCount() == 0 ) {
        header('Location: ../../classes/index.php' . '?' . 'msg=error');
        
    } else {
        $sql = "UPDATE classes SET nom_classe = ?, 
                                    niveau = ?,
                                    annee_debut = ?,
                                    annee_fin = ?,
                                    salle = ? 
                WHERE classe_id = ?
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom_classe, 
            $niveau, 
            $annee_debut,
            $annee_fin,
            $salle,
            $_GET['id']
        ]);

        header('Location: ../../classes/index.php' . '?' . 'msg=success');

    }


}