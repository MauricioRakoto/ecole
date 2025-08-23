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
            padding: 20px 40px;
            background: #f8f9fa;
            font-family: arial;
        }

        .title img {
            width: 35px;
            height: 35px;
        }

        .title h1 {
            font-size: 25px;
            font-weight: 700;
            color: #009cff;
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

        .text {
            width: 90%;
        }

        .text p {
            font-size: 18px;
            font-weight: 400;
            color: #000;
            text-align: justify;
            transition: 5s ease-in-out;
            animation: text 5s ease-in-out;
        }

        @keyframes text {
            to {
                opacity: 1;
            }

            from {
                opacity: 0;
            }
        }

        @keyframes image {
            to {
                transform: scale(1);
            }

            from {
                transform: scale(0.8);
            }
        }

        .images {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            background: #009cff;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 2s ease-in-out;
            animation: image 2s ease-in-out;
        }

        .images img {
            width: 99%;
            height: 99%;
            border-radius: 10px;
        }

        .features-container .title {
            display: flex;
            justify-content: center;
        }

        .features-container .title h3 {
            position: relative;
            font-size: 30px;
            
        }

        .features-container .title h3::after {
            position: absolute;
            content: '';
            left: 0;
            bottom: 0;
            margin-bottom: -3px;
            width: 50px;
            height: 3px;
            background: #009cff;
        }

        .feature {
            width: 350px;
            border-radius: 8px;
            padding: 15px;
            transition: transform 0.3s ease;
            border: 2px solid #009cff;
        }

        .feature h3 {
            font-size: 18px;
            color: #000;
            margin-bottom: 15px;
        }

        .feature p {
            color: #000;
            font-size: 1rem;
            line-height: 1.4;
        }

        footer {
            text-align: center;
            padding: 30px 0;
            color: #555;
            font-size: 0.9rem;
        }

        .carousel-container {
    position: relative;
    overflow: hidden;
    max-width: 800px;
    margin: 30px auto;
    min-height: 300px; /* pour tester */
}

.carousel-images {
    display: flex;
    transition: transform 1.5s ease-in-out;
}

.image {
    width: 100%;
    min-width: 100%;
    transform: scale(.8);
    transition: 2s ease-in-out;
}

.image.active {
    transform: scale(1);
}

.image img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #009cff;
}

.carousel-buttons {
    text-align: center;
    margin-top: 15px;
}

.carousel-buttons .btn {
    width: 12px;
    height: 12px;
    
    border: none;
    background-color: #ccc;
    margin: 0 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.carousel-buttons .btn.active {
    background-color: #009cff;
}
    </style>
</head>
<body>
<div class="row g-0 top-pres">
        <div class="col-md-8">
          <div class="title" style="display: flex;gap: 10px;">
                <img src="./assets/img/logo-ecole.png" alt="">
                <h1>Ecole</h1>
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
                    Grâce à une interface simple et intuitive, les responsables peuvent gérer les élèves, les notes et les absences en quelques clics.
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
          <div class="images">
            <img src="./assets/img/home.png" alt="">
          </div>
        </div>
    </div>

    <div class="row g-0 intro" style="margin-top: 60px">
        
        <div class="col-md-6">
          <div class="images">
            <img src="./assets/img/eleves.png" alt="">
          </div>
        </div>

        <div class="col-md-6" style="position: relative">
            <div class="text" style="position: absolute; right: 0">
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

    <div class="row g-0 carousel-container" style="margin-top: 60px; margin-left:auto; margin-right:auto;">
        <div class="carousel-images">
        <div class="image" data-index="0">
            <img src="./assets/img/bulletins.png" alt="">
        </div>
        <div class="image " data-index="1">
            <img src="./assets/img/bulletin.png" alt="">
        </div>
        <div class="image" data-index="2">
            <img src="./assets/img/absences.png" alt="">
        </div>
        <div class="image" data-index="3">
            <img src="./assets/img/renvoyer.png" alt="">
        </div>
        </div>

        <div class="carousel-buttons">
            <button class="btn" data-index="0"></button>
            <button class="btn" data-index="1"></button>
            <button class="btn" data-index="2"></button>
            <button class="btn" data-index="3"></button>
        </div>
    </div>

    <main class="features-container">
        <div class="title">
            <h3>Fonctionnalités</h3>
        </div>
        <div class="body" style="margin-top: 20px; display: flex; justify-content: center; flex-wrap: wrap; gap: 30px; ">
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
            <h3>Recherches</h3>
            <p>Recherchez facilement les absences par date, filtrez les notes par matière et session pour un accès rapide à l’information désirée.</p>
            </div>
        </div>
        
    </main>
    <footer>
        &copy; 2025 Ecole - Tous droits réservés
    </footer>

    <script>
        const images = document.querySelectorAll('.carousel-images .image');
        const buttons = document.querySelectorAll('.carousel-buttons .btn');
        let currentIndex = 0;

        function showImage(index) {
            const offset = -index * 100;
            document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;
            buttons.forEach(btn => btn.classList.remove('active'));
            buttons[index].classList.add('active');
            currentIndex = index;

            images.forEach(image => {
                image.classList.remove('active')
            })
            images[index].classList.add('active')
            
        }

        // Initial
        showImage(0);

        // Click buttons
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const index = parseInt(btn.getAttribute('data-index'));
                showImage(index);
            });


        });


</script>

</body>
</html>
