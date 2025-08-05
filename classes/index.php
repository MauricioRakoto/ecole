<?php 
    // Inclusion du fichier de connexion à la base de données
    require "../back/database.php";

    // Démarrage de la session
    session_start();

    // Vérifie si le responsable est connecté, sinon redirige vers la page de connexion
    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }
    
    // Récupération des classes depuis la base de données
    $sql = "SELECT classe_id, nom_classe FROM classes";
    $stmt_classes = $pdo->query($sql);
    $classes = $stmt_classes->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Déclaration de l'encodage des caractères -->
    <meta charset="UTF-8">
    <!-- Compatibilité avec Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Paramétrage pour un affichage responsive sur mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>
    
    <!-- Inclusion des fichiers CSS nécessaires -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Barre de navigation principale -->
    <nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
        <!-- Logo et lien vers la page d'accueil -->
        <a  href="./home.php" class="text-primary" style="display: flex; gap: 10px; align-items: center">
            <img src="../assets/img/logo-ecole.png" alt="logo" style="width: 35px">
            <h3 style="font-size: 20px">Ecole</h3>
        </a>

        <!-- Menu utilisateur avec nom et options -->
        <div class="menu">
            <button class="btn-menu" id="menu"> M </button>
            <div class="menu-name">
                <h4><?= $_SESSION['username'] ?></h4>
            </div>

            <!-- Fenêtre modale du menu utilisateur -->
            <div class="menu-modal">
                <div class="modal-top">
                    <div class="tp-image">
                        <div class="image">Image</div>
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
                        <li><a href="../back/responsable/logout.php">Se déconnecter</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteneur principal avec barre latérale et contenu principal -->
    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
        
        <!-- Barre latérale de navigation -->
        <div class="sidebar" style="width: 200px; padding: 0 20px; ">
            <nav class="navbar bg-light">
                <div class="navbar-nav w-100" style="margin-top: 25px">
                    <ul>
                        <li><a href="../home.php" class="nav-link">Accueil</a></li>
                        <li><a href="./" class="nav-link active">Classes</a></li>
                        <li><a href="../inscription.php" class="nav-link">Inscription</a></li>
                        <li><a href="../matieres/" class="nav-link">Matières</a></li>
                        <li><a href="#" class="nav-link">Bulletins</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Colonne de droite contenant le contenu dynamique -->
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
    </div>

    <!-- Inclusion du fichier JavaScript principal -->
    <script src="../assets/js/main.js"></script>
    
</body>
</html>
