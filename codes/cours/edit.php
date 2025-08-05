<?php
require_once "../back/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupère les cours actuels
    $sql = "SELECT * FROM cours WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $cours = $stmt->fetch();

    // Récupère les matières et classes pour le select
    $matieres = $pdo->query("SELECT * FROM matieres")->fetchAll();
    $classes = $pdo->query("SELECT * FROM classes")->fetchAll();
}
?>

<form action="update-cours.php" method="POST">
    <input type="hidden" name="id" value="<?= $cours['id'] ?>">

    <label>Matière :</label>
    <select name="matiere_id">
        <?php foreach ($matieres as $matiere): ?>
            <option value="<?= $matiere['matiere_id'] ?>" 
                <?= $cours['matiere_id'] == $matiere['matiere_id'] ? 'selected' : '' ?>>
                <?= $matiere['nom_matiere'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Classe :</label>
    <select name="classe_id">
        <?php foreach ($classes as $classe): ?>
            <option value="<?= $classe['classe_id'] ?>" 
                <?= $cours['classe_id'] == $classe['classe_id'] ? 'selected' : '' ?>>
                <?= $classe['nom_classe'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" name="modifier">Modifier</button>
</form>
