<?php 

    require "./back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    // Récupérer les 20 derniers élèves inscrits
    $sql = "SELECT nom_responsable,
                    prenom_responsable,
                    username,
                    compte,
                    image
            FROM responsable
            WHERE responsable_id = ?
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['responsable_id']]);
    $comptes = $stmt->fetchAll();

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
                    <h2>Mon à jour mon profile</h2>
                    <h4>Modifier tous mes données</h4>
                </div>
                
            </div>

            <div class="profile-body">
                <form action="./back/responsable/update.php" method="POST" enctype="multipart/form-data" class="edit">
                    <?php foreach ( $comptes as $compte ): ?>
                        <div class="form-group">
                            <div class="label">
                            <h4>Photo</h4>
                            </div>
                            <div class="input">
                                <?php if (!empty($compte['image'])): ?>
                                    <img src="./assets/img/<?= $compte['image'] ?>" alt="Image" width="100">
                                <?php else: ?>
                                    <p>Aucune image</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="label">
                                <h4>Modifier Photo</h4>
                            </div>
                            <div class="input">
                                <input type="file" name="image" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="label">
                                <h4>Nom</h4>
                            </div>
                            <div class="input">
                                <input type="text" placeholder="Nom" value="<?= $_SESSION['nom_responsable'] ?>" name="nom">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="label">
                                <h4>Prénom</h4>
                            </div>
                            <div class="input">
                                <input type="text" placeholder="Prénom" value="<?= $_SESSION['prenom_responsable'] ?>" name="prenom">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="label">
                                <h4>Username</h4>
                            </div>
                            <div class="input">
                                <input type="text" placeholder="Username" value="<?= $_SESSION['username'] ?>" name="username">
                            </div>
                        </div>

                        <div class="form-group">
                        <div class="label">
                            <h4>Compte</h4>
                        </div>
                        <div class="select">
                            <div class="select">
                                <select name="compte">
                                   <option value="<?= $_SESSION['compte'] ?>"><?= $_SESSION['compte'] ?></option>
                                   <option value="Surveillant">Surveillant</option>
                                   <option value="Professeur">Professeur</option>
                                </select>

                            </div>
                            
                        </div>
                        </div>

                        <div class="form-submit" style="display: flex; gap: 10px">
                            <button type="submit">Modifier</button>
                            <a href="./home.php">Retour</a>
                        </div>
                    <?php endforeach; ?>

                </form>
            </div>

        </div>
    </div>
</body>
</html>