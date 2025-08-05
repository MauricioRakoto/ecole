<?php 
    // Connexion à la base de données
    require "../back/database.php";

    // Démarrage de la session
    session_start();

    // Vérifie si l'utilisateur (responsable) est connecté
    if (!isset($_SESSION['responsable_id'])) {
        // Redirige vers la page de connexion si non connecté
        header("Location: signin.php");
        exit();
    }

    if ($_SESSION['compte'] !== "Surveillant") {
        header("Location: ../home" . ".php");
    }

     // Récupération des classes depuis la base de données
    $sql = "SELECT classe_id, nom_classe FROM classes";
    $stmt_classes = $pdo->query($sql);
    $classes = $stmt_classes->fetchAll();

     // Récupération des matières 
     $sql = "SELECT matiere_id, nom_matiere, coefficient FROM matieres";
     $stmt_matieres = $pdo->query($sql);
     $matieres = $stmt_matieres->fetchAll();


      // Récupérer un compte
    $sql_compte = "SELECT image FROM responsable WHERE responsable_id = ?";
    $stmt_compte = $pdo->prepare($sql_compte);
    $stmt_compte->execute([$_SESSION['responsable_id']]);
    $comptes = $stmt_compte->fetchAll();
?>

<!-- Début du document HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Encodage des caractères -->
    <meta charset="UTF-8">
    <!-- Compatibilité avec Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Configuration du viewport pour mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page -->
    <title>Students</title>
    <!-- Inclusion du CSS Bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclusion du fichier CSS personnalisé -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Formulaire modal pour l'ajout de nouvelle classe -->
    <div class="modal-add">
        <form action="../back/matieres/addMatiere.php" class="add-content" method="POST" style="height: 250px;">
            <div class="tp-add">
                <div class="ttl">
                    <h1>Ajouter un nouveau matière</h1>
                </div>
                <div class="close">
                    <a href="./index.php">X</a>
                </div>
            </div>
            <div class="bd-add">
                <!-- Champ: nom du matiere -->
                <div class="form-group">
                    <div class="label">
                        <h4>Nom du classe</h4>
                    </div>
                    <div class="input">
                        <input type="text" name="nom_matiere" placeholder="Nom du classe" required>
                    </div>
                </div>
                
                <!-- Champ: coefficient -->
                <div class="form-group">
                    <div class="label">
                        <h4>Coefficient</h4>
                    </div>
                    <div class="input">
                        <input type="number" name="coefficient" placeholder="coefficient" required>
                    </div>
                </div>
                <!-- Bouton de soumission du formulaire -->
                <div class="form-submit">
                    <button type="submit" name="enregistrer">Terminer</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Barre de navigation supérieure -->
    <nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
            <!-- Logo et titre -->
            <a  href="./home.php" class="text-primary" style="display: flex; gap: 10px; align-items: center">
                <img src="../assets/img/logo-ecole.png" alt="logo" style="width: 35px">
                <h3 style="font-size: 20px">Ecole</h3>
            </a>

            <!-- Menu utilisateur -->
            <div class="menu">
                <?php foreach ( $comptes as $compte ): ?>
                    <button class="btn-menu" id="menu">
                        <?php if (!empty($compte['image'])): ?>
                            <img src="../assets/img/<?= $compte['image'] ?>" alt="Image" width="25">
                        <?php else: ?>
                            M
                        <?php endif; ?>
                    </button>

                <?php endforeach; ?>  
                <div class="menu-name">
                <h4><?= $_SESSION['username'] ?></h4>
                </div>
                <!-- Menu déroulant utilisateur -->
                <div class="menu-modal">
                    <div class="modal-top">
                    <div class="tp-image">
                        <?php foreach ( $comptes as $compte ): ?>
                            <?php if (!empty($compte['image'])): ?>
                                <div class="image">
                                    <img src="../assets/img/<?= $compte['image'] ?>" alt="Image" width="35">
                                </div>
                                <?php else: ?>
                                    <div class="image">
                                        Image
                                    </div>
                            <?php endif; ?>
                       
                        <?php endforeach; ?>
                    </div>
                    <div class="tp-name">
                        <h4><?= $_SESSION['username'] ?></h4>
                    </div>
                    <div class="tp-compte">
                        <h4>Compte: <?= $_SESSION['compte'] ?></h4>
                    </div>
                    </div>
                    <div class="modal-body">
                    <ul class="modal-links">
                        <li><a href="../profile.php">Mon profile</a></li>
                        <li><a href="#">Paramètre</a></li>
                        <li><a href="./back/responsable/logout.php">Se déconnecter</a></li>
                    </ul>
                    </div>
                </div>
            </div>
    </nav>

        <!-- Conteneur principal -->
    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
    
    <!-- Barre latérale -->
    <div class="sidebar" style="width: 200px; padding: 0 20px;">
        <nav class="navbar bg-light">
            <div class="navbar-nav w-100" style="margin-top: 25px">
                <ul>
                    <li><a href="../home.php" class="nav-link">Accueil</a></li>
                    <li><a href="../classes" class="nav-link ">Classes</a></li>
                    <li><a href="./inscription.php" class="nav-link">Inscription</a></li>
                    <li><a href="./" class="nav-link active">Matières</a></li>
                    <li><a href="index.php" class="nav-link">Bulletins</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="col-right">
            <!-- Barre supérieure avec bouton "Nouveau" et formulaire de recherche -->
            <div class="tp">
                <div class="add">
                    <a href="./add.php" class="btn-add">Nouveau</a>
                </div>
                <div class="search">
                    <form action="">
                        <input type="text" placeholder="Rechercher">
                        <button type="submit">Rechercher</button>
                    </form>
                </div>
            </div>

            <!-- Corps principal affichant la liste des classes -->
            <div class="bd">
                <div class="carousel-item" style="display: block;">
                    <div class="d-block w-100">
                        <div class="items">
                            <!-- Vérifie si des classes existent -->
                            <?php if (count($matieres) > 0):  ?>
                                <!-- Boucle sur les classes et les affiche sous forme de cartes -->
                                <?php  foreach ( $matieres as $matiere ): ?>
                                    <a href="#">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title">
                                                    <h3><?= $matiere['nom_matiere'] ?></h3>
                    
                                                    <h4>Coefficients: <?= $matiere['coefficient'] ?></h4> <!-- Valeur statique ici, à adapter dynamiquement si nécessaire -->
                            
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php  endforeach; ?>
                            <?php else: ?>
                                <!-- Message si aucune classe trouvée -->
                                <h5>Aucun matière trouvé</h5>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div>
        </div>

        <!-- Script pour afficher le modal d'ajout avec animation -->
        <script>
        const modalAdd = document.querySelector('.modal-add')
        const content = document.querySelector('.add-content')

        function showModal () {
            setTimeout(() => {
                content.style.opacity = "1"
            }, 1000)
        }

        window.addEventListener('load', showModal)
    </script>

    <!-- Script JS principal -->
    <script src="../assets/js/main.js"></script>
</body>
</html>
