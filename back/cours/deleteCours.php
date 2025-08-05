<?php
require_once "../database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM cours WHERE cours_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: ../../classes/cours" . ".php?" . "id" . "=" . $_GET['idc']);
    exit();
} else {
    echo "Identifiant non fourni.";
}