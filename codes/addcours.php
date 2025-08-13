<?php
require "../back/database.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classe_id = $_POST['classe_id'];
    $matiere_id = $_POST['matiere_id'];

    // Vérifie si le cours existe déjà
    $sql_check = "SELECT * FROM cours WHERE classe_id = ? AND matiere_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$classe_id, $matiere_id]);

    if ($stmt_check->rowCount() > 0) {
        // Le cours existe déjà : redirection ou message
        header("Location: ../front/cours.php?id=$classe_id&error=exist");
        exit();
    }

    // Sinon on insère
    $sql_insert = "INSERT INTO cours (classe_id, matiere_id) VALUES (?, ?)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([$classe_id, $matiere_id]);

    header("Location: ../front/cours.php?id=$classe_id&success=added");
    exit();
}
?>

<select name="matiere_id">
    <?php foreach ($matieres as $matiere): ?>
        <?php if (!in_array($matiere['matiere_id'], $existing_matiere_ids)): ?>
            <option value="<?= $matiere['matiere_id'] ?>">
                <?= $matiere['nom_matiere'] ?>
            </option>
        <?php endif; ?>
    <?php endforeach; ?>
</select>
