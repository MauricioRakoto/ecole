<?php
require_once "../back/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM cours WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: liste-cours.php?success=1");
    exit();
} else {
    echo "Identifiant non fourni.";
}
?>
