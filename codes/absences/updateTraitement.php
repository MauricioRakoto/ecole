<?php
require_once "../back/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_absence = $_POST['id_absence'];
    $id_matiere = $_POST['id_matiere'];
    $minutes = $_POST['minutes'];
    $date = $_POST['date'];

    $sql = "UPDATE absences SET id_matiere = ?, minutes = ?, date = ? WHERE id_absence = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_matiere, $minutes, $date, $id_absence]);

    echo "<p style='color: green'>Absence modifiée avec succès</p>";
    echo "<a href='liste_absences.php'>Retour</a>";
}
