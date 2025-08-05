<?php
require_once "../back/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM absences WHERE id_absence = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo "<p style='color: green'>Absence supprimée avec succès</p>";
    echo "<a href='liste_absences.php'>Retour</a>";
}
