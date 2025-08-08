<?php
    require_once "../database.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM absences WHERE absences_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        header("Location: ../../classes/absences" . ".php?" . "id" . "=" . $_GET['idc']);
        exit();
    } else {
        echo "Identifiant non fourni.";
    }