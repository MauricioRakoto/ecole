<?php 

    session_start();

    if (isset($_SESSION['responsable_id'])) {
        header("Location: home.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Présentation de l'application - Ecole</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <style>
        body {
            padding: 40px;
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }
        .header-logo img {
            width: 60px;
            height: 60px;
        }
        .header-logo h1 {
            font-weight: 700;
            color: #009cff;
            font-size: 2rem;
        }
        .feature {
            width: 45%;
            background: white;
            border-radius: 8px;
            padding: 25px 30px;
            box-shadow: 0 3px 10px rgb(0 0 0 / 0.1);
            margin-bottom: 25px;
            transition: transform 0.3s ease;
        }
        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgb(0 0 0 / 0.15);
        }
        .feature h3 {
            color: #0056b3;
            margin-bottom: 15px;
        }
        .feature p {
            color: #333;
            font-size: 1rem;
            line-height: 1.4;
        }
        .features-container {
            max-width: 900px;
            margin: auto;
        }
        footer {
            text-align: center;
            padding: 30px 0;
            color: #555;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="row g-0 top-pres">
        <div class="col-md-8">
          <div class="title" style="display: flex;gap: 10px;">
                <img src="./assets/img/logo-ecole.png" alt="" style="width: 50px;height: 50px;">
                <h1 style="font-size: 25px">Ecole</h1>
          </div>
        </div>
        <div class="col-md-4" style="position: relative;">
          <div class="links" style="position: absolute;right: 0; width: 300px; display: flex; gap: 10px;">
            <a href="./signin.php" class="btn btn-link" style="width: 150px; height: 35px;border: 2px solid #009cff;color: #000; transition: 1s ease-in-out;">Se Connecter</a>
            <a href="./signup.php" class="btn btn-link" style="width: 150px; height: 35px; border: 2px solid #009cff; color: #000; transition: 1s ease-in-out;">S'inscrire</a>
          </div>
        </div>
    </div>
      
    <div class="row g-0 intro" style="margin-top: 30px">
        <div class="col-md-6">
          <div class="text">
                <p>
                    Notre application de gestion scolaire facilite le suivi complet des élèves, des enseignants et des cours. 
                    Grâce à une interface simple et intuitive, les responsables peuvent gérer les emplois du temps, les notes et les absences en quelques clics.
                </p>
                <p>
                    Les enseignants disposent d’un espace dédié pour saisir les évaluations (DS1, DS2, examens) et suivre la progression de leurs classes.
                    Les parents peuvent consulter les résultats de leurs enfants en temps réel, depuis n’importe quel appareil.
                </p>
                <p>
                    Sécurisée et rapide, notre solution permet de centraliser toutes les informations pédagogiques dans un seul outil moderne, réduisant les tâches administratives et améliorant la communication au sein de l’établissement.
                </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="image" style="width: 100%; height: 400px; border-radius: 10px;background: #d3cad9;">
            <img style="width: 100%; height: 100%" src="./assets/img/bulletins.png" alt="">
          </div>
        </div>
    </div>

    <div class="row g-0 intro" style="margin-top: 30px">
        
        <div class="col-md-6">
          <div class="image" style="width: 100%; height: 400px; border-radius: 10px;background: #d3cad9;">
            <img style="width: 100%; height: 100%" src="./assets/img/bulletin.png" alt="">
          </div>
        </div>

        <div class="col-md-6">
            <div class="text" style="padding-right: 0; padding-left: 60px;">
              <p>
                    L’application intègre également un tableau de bord complet, affichant en temps réel les statistiques clés : 
                    taux de réussite, absences par classe, et moyennes générales.
                </p>
                <p>
                    Conçue pour s’adapter à tous les types d’établissements, notre solution est accessible depuis un ordinateur, une tablette ou un smartphone.
                </p>
                <p>
                    En choisissant notre application, vous optez pour un gain de temps considérable, une meilleure organisation et une communication plus fluide entre tous les acteurs de l’éducation.
                </p>
            </div>
          </div>
    </div>

    <main class="features-container" style="display: flex; flex-wrap: wrap; gap: 50px; margin-top: 40px">
        <div class="feature">
            <h3>Gestion des Élèves</h3>
            <p>Ajoutez, modifiez et visualisez facilement les informations détaillées de chaque élève inscrit dans l’établissement.</p>
        </div>

        <div class="feature">
            <h3>Gestion des Classes</h3>
            <p>Créez et organisez les classes avec leurs caractéristiques : noms, salles, matières attribuées et enseignants responsables.</p>
        </div>

        <div class="feature">
            <h3>Suivi des Absences</h3>
            <p>Enregistrez les absences des élèves par date, matière et classe pour un suivi précis et un meilleur contrôle administratif.</p>
        </div>

        <div class="feature">
            <h3>Gestion des Notes</h3>
            <p>Consultez et enregistrez les notes des élèves par session d’examen et par matière, avec un affichage clair et des filtres personnalisés.</p>
        </div>

        <div class="feature">
            <h3>Interface Responsable</h3>
            <p>Un espace sécurisé dédié aux responsables avec gestion des profils, accès aux données sensibles, et des fonctions adaptées au rôle.</p>
        </div>

        <div class="feature">
            <h3>Recherche et Filtres Dynamiques</h3>
            <p>Recherchez facilement les absences par date, filtrez les notes par matière et session pour un accès rapide à l’information désirée.</p>
    </div>
    </main>

    <footer>
        &copy; 2025 Ecole - Tous droits réservés
    </footer>

</body>
</html>
