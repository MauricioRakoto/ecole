<?php
  require_once "../database.php";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matiere_id = $_POST['matiere_id'];
    $date = $_POST['date'];
    $eleve_ids = $_POST['eleve_id'];
    $minutes = $_POST['minutes'];

    foreach ($eleve_ids as $index => $eleve_id) {
        $minute = intval($minutes[$index]);

        if ($minute > 0) {
            $stmt = $pdo->prepare("INSERT INTO absences (eleve_id, matiere_id, minutes, date_absence) VALUES (?, ?, ?, ?)");
            $stmt->execute([$eleve_id, $matiere_id, $minute, $date]);
        }
    }

    echo "<p style='color: green'>Absences enregistrées avec succès !</p>";
    echo "<a href='../../classes/'>Retour</a>";
} else {
    echo "<p style='color: red'>Méthode non autorisée.</p>";
}