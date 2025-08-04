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

     // Récupération des classes depuis la base de données
    $sql = "SELECT classe_id, nom_classe FROM classes";
    $stmt_classes = $pdo->query($sql);
    $classes = $stmt_classes->fetchAll();
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
        <form action="../back/matieres/addMatiere.php" class="add-content" method="POST" style="height: 450px;">
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
                <img src="../assets/img/logo.png" alt="logo" style="width: 35px">
                <h3 style="font-size: 20px">Ecole</h3>
            </a>

            <!-- Menu utilisateur -->
            <div class="menu">
            <button class="btn-menu" id="menu">
                M
            </button>
            <div class="menu-name">
                <h4><?= $_SESSION['username'] ?></h4>
            </div>
            <!-- Menu déroulant utilisateur -->
            <div class="menu-modal">
                <div class="modal-top">
                    <div class="tp-image">
                        <div class="image">
                            Image
                        </div>
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
                        <li><a href="#">Mon profile</a></li>
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
                    <li><a href="../classes" class="nav-link active">Classes</a></li>
                    <li><a href="./inscription.php" class="nav-link">Inscription</a></li>
                    <li><a href="./" class="nav-link">Matières</a></li>
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
                            <?php if (count($classes) > 0):  ?>
                                <!-- Boucle sur les classes et les affiche sous forme de cartes -->
                                <?php  foreach ( $classes as $classe ): 
                                    $sql_eleves = "SELECT COUNT(eleve_id), eleve_id AS nombre_eleves FROM eleves WHERE classe_id = ?";

                                    $stmt_eleves = $pdo->prepare($sql_eleves);
                                    $stmt_eleves->execute([$classe['classe_id']]);
                                    $eleves = $stmt_eleves->fetchAll();


                                ?>
                                    <a href="./eleves.php?id=<?php echo $classe['classe_id'] ?>">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title">
                                                    <h3>Classe: <?= $classe['nom_classe'] ?></h3>

                                                    
                                                    <?php  foreach ( $eleves as $eleve ): ?>
                                                        <?php if ( $eleve['nombre_eleves'] > 0):  ?>
                                                            <h4>Effectifs: <?= $eleve['nombre_eleves'] ?></h4> <!-- Valeur statique ici, à adapter dynamiquement si nécessaire -->
                                                            <?php else: ?>
                                                        <!-- Message si aucune classe trouvée -->
                                                            <h4>Effectifs: 0</h4>
                                                        <?php endif; ?>
                                                    <?php  endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php  endforeach; ?>
                            <?php else: ?>
                                <!-- Message si aucune classe trouvée -->
                                <h5>Aucun classe trouvé</h5>
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
