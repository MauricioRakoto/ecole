<?php 

    session_start();

    if (isset($_SESSION['responsable_id'])) {
        header("Location: home.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/auth.css">
</head>
<body>
    <div class="container">
        <div class="row" style="justify-content: center;">
           
            <form method="POST" action="./back/responsable/signin.php" class="auth" style="height:  340px;">
                <div class="auth-content">
                    <div class="f-tp">
                        <div class="ttl">
                            <h3>Connecter à un compte</h3>
                        </div>
                        <div class="links">
                            <a href="./signin.php" class="link active">Se connecter</a>
                            <a href="./signup.php" class="link ">S'inscrire</a>
                        </div>
                    </div>
                    <div class="f-bd">
                        
                        <div class="form-group">
                            <div class="label">
                                <h4>Username</h4>
                            </div>
                            <div class="input">
                                <input type="text" placeholder="Username" name="username" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="label">
                                <h4>Password</h4>
                            </div>
                            <div class="input">
                                <input type="password" placeholder="Password" name="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="f-bt">
                        <button type="submit" name="login">S'inscrire</button>
                    </div>
                </div>
                
            </form>
           
        </div>
    </div>

    <!-- <form method="POST" action="./back/responsable/signin.php" style="margin-top: 40px">
        <h2>Créer un compte Responsable</h2>

        <label>Username :</label><br>
        <input type="text" name="username" required><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login">Se connecter</button>
    </form> -->
</body>
</html>