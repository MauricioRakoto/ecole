<?php

    if (isset($_POST['inscrire'])) {
        require_once "../database.php";

        $nom = trim($_POST['nom_responsable']);
        $prenom = trim($_POST['prenom_responsable']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $compte = $_POST['compte'];

        // Vérifier si le nom d'utilisateur existe déjà
        $check = $pdo->prepare("SELECT * FROM responsable WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            echo "<p style='color:red;'>Ce nom d'utilisateur existe déjà.</p>";
        } else {
            // Hacher le mot de passe
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base
            $insert = $pdo->prepare("INSERT INTO 
                    responsable (
                        nom_responsable, 
                        prenom_responsable, 
                        username, 
                        password,
                        compte
                    )

                    VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$nom, $prenom, $username, $hash, $compte]);

            // Recherche du responsable
            $stmt = $pdo->prepare("SELECT * FROM responsable WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(); 

            session_start();

            $_SESSION['responsable_id'] = $user['responsable_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nom_responsable'] = $user['nom_responsable'];
            $_SESSION['prenom_responsable'] = $user['prenom_responsable'];
            $_SESSION['compte'] = $user['compte'];

            header('Location:../../home'. '.php');
        }
    }
    ?>