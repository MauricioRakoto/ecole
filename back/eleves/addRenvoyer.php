<?php

    require_once "../database.php";

    if (isset($_GET['id'])) {
        $eleve_id = $_GET['id'];
        $status = "renvoyer";

        $sql = "UPDATE eleves SET status = ? WHERE eleve_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $status,
            $eleve_id
        ]);

        header("Location: ../../classes/renvoyer" . ".php?" . "id" . "=" . $_GET['idc']);
    } else {
        echo "Eleve n'existe pas";
    }