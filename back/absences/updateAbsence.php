<?php
    require "../database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matiere_id = $_POST['matiere_id'];
        $eleve_id = $_POST['eleve_id'];
        $minutes = $_POST['minutes'];
        $date = $_POST['date'];

        $sql_absence = "UPDATE absences SET matiere_id = ?,
                    eleve_id = ?,
                    minutes = ?, 
                    date_absence = ?
                WHERE absences_id = ?
        ";

        $stmt_absence = $pdo->prepare($sql_absence);
        $stmt_absence->execute(
            [
                $matiere_id,
                $eleve_id,
                $minutes,
                $date,
                $_GET['idc']
            ]
        );

        header("Location: ../../classes/absences" . ".php?" . "id" . "=" . $_GET['id']); // Recharge la page
        exit();
    }

