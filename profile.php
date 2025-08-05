<?php 

    require "./back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

     // Récupérer un compte
     $sql_compte = "SELECT image FROM responsable WHERE responsable_id = ?";
     $stmt_compte = $pdo->prepare($sql_compte);
     $stmt_compte->execute([$_SESSION['responsable_id']]);
     $comptes = $stmt_compte->fetchAll();

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
                    <?php foreach ( $comptes as $compte ): ?>
                        <?php if (!empty($compte['image'])): ?>
                            <img src="./assets/img/<?= $compte['image'] ?>" alt="<?= $compte['image'] ?>">
                        <?php else: ?>
                            <p>Pas d'image</p>
                        <?php endif; ?>
                    <?php endforeach; ?>  
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
                    <a href="./home.php" class="link">Retour</a>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>