<?php
require_once "../back/database.php";

if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $matiere_id = $_POST['matiere_id'];
    $classe_id = $_POST['classe_id'];

    // Vérifie si la combinaison existe déjà
    $check_sql = "SELECT * FROM cours WHERE matiere_id = ? AND classe_id = ? AND id != ?";
    $stmt_check = $pdo->prepare($check_sql);
    $stmt_check->execute([$matiere_id, $classe_id, $id]);

    if ($stmt_check->rowCount() > 0) {
        echo "Ce cours existe déjà pour cette classe.";
    } else {
        $sql = "UPDATE cours SET matiere_id = ?, classe_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$matiere_id, $classe_id, $id]);

        header("Location: liste-cours.php?updated=1");
        exit();
    }
}
?>
