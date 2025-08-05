<form action="update_absence.php" method="POST">
    <input type="hidden" name="id_absence" value="<?= $absence['id_absence'] ?>">

    <label>Matière :</label>
    <select name="id_matiere">
        <?php foreach ($cours as $cour): ?>
            <option value="<?= $cour['matiere_id'] ?>" <?= $cour['matiere_id'] == $absence['id_matiere'] ? 'selected' : '' ?>>
                <?= $cour['nom_matiere'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Minutes :</label>
    <input type="number" name="minutes" value="<?= $absence['minutes'] ?>">

    <label>Date :</label>
    <input type="date" name="date" value="<?= $absence['date'] ?>">

    <button type="submit">Modifier</button>
</form>
