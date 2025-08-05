<?php
    if (isset($_POST['login'])) {
        require_once "../database.php";

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Recherche du responsable
        $stmt = $pdo->prepare("SELECT * FROM responsable WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            session_start();
            $_SESSION['responsable_id'] = $user['responsable_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['responsable_id'] = $user['responsable_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nom_responsable'] = $user['nom_responsable'];
            $_SESSION['prenom_responsable'] = $user['prenom_responsable'];
            $_SESSION['compte'] = $user['compte'];

            header('Location:../../home'. '.php'); // redirection vers un tableau de bord
            exit();
        } else {
            echo "<p style='color:red;'>Identifiants incorrects.</p>";
        }
    }