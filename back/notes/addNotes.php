<?php
    require "../database.php";
    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: ../../signin.php");
        exit();
    }

    // Récupérer les données du formulaire
    $classe_id = $_GET['id'];
    $matiere_id = $_POST['matiere_id'];
    $session = $_POST['session'];
    $type = $_POST['type'];
    $eleves = $_POST['eleve_id'];
    $notes = $_POST['notes'];

    // Boucler sur chaque élève et ajouter les notes
    foreach ($eleves as $index => $eleve_id) {
        $note = $notes[$index];

        // Ne pas enregistrer si la note est vide
        if ($note === '' || is_null($note)) {
            continue;
        }

        // Vérifier si une note existe déjà pour cet élève, matière, session et type
        $sql_check = "SELECT COUNT(*) FROM notes WHERE eleve_id = ? AND matiere_id = ? AND session = ? AND type = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$eleve_id, $matiere_id, $session, $type]);
        $exists = $stmt_check->fetchColumn();

        // S’il n’existe pas encore, on l’insère
        if ($exists == 0) {
            $sql_insert = "INSERT INTO notes (session, type, note, classe_id, matiere_id, eleve_id)
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$session, $type, $note, $classe_id, $matiere_id, $eleve_id]);

        }
    }

    // Redirection avec message de succès
    header("Location: ../../classes/addnotes" . ".php?" . "id" . "=" . $classe_id);
    exit();

