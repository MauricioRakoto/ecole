<?php 

    require "./back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    // Récupérer les 20 derniers élèves inscrits
    $sql = "SELECT a.*,
            b.classe_id,
            b.nom_classe
            FROM eleves a
            LEFT JOIN classes b 
            ON a.classe_id = b.classe_id
            ORDER BY a.eleve_id DESC 
            LIMIT 20";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $eleves = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="profile">
            <div class="profile-top">
                <div class="ttl">
                    <h2>Informations de profile</h2>
                    <h4>Mon identité</h4>
                </div>
                
            </div>

            <div class="profile-body">
                <div class="profile-image">
                    <img src="./assets/img/Koala.jpg" alt="">
                </div>

                <div class="pro-name">
                    <h4>
                        <span>Nom:</span>
                        <?= $_SESSION['nom_responsable'] ?>
                    </h4>
                </div>

                <div class="pro-name">
                    <h4>
                        <span>Prénom:</span>
                        <?= $_SESSION['prenom_responsable'] ?>
                    </h4>
                </div>

                <div class="pro-name">
                    
                    <h4>
                        <span>Username:</span>
                        <?= $_SESSION['username'] ?>
                    </h4>
                </div>

                <div class="pro-name">
                    
                    <h4>
                        <span>Compte:</span>
                        <?= $_SESSION['compte'] ?>
                    </h4>
                </div>
            </div>

            <div class="profile-bottom">
                <div class="profile-links">
                    <a href="./editprofile.php" class="link">Mettre à jour</a>
                    <a href="#" class="link">Effacer</a>
                    <a href="#" class="link">Retour</a>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>