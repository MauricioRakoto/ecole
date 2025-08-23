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

    // Récupérer un compte
    $sql_compte = "SELECT image FROM responsable WHERE responsable_id = ?";
    $stmt_compte = $pdo->prepare($sql_compte);
    $stmt_compte->execute([$_SESSION['responsable_id']]);
    $comptes = $stmt_compte->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<?php require '../includes/head.php' ?>
<body>

    <?php require '../includes/navbar.php' ?>

    <!-- Conteneur principal avec barre latérale et contenu principal -->
    <div id="mainContent" class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
        
        <!-- Barre latérale de navigation -->
        <div class="sidebar" style="width: 200px; padding: 0 20px; ">
            <nav class="navbar bg-light">
                <div class="navbar-nav w-100" style="margin-top: 25px">
                    <ul>
                        <li>
                            <a href="../home.php" class="nav-link">Accueil</a>
                        </li>
                        <li>
                            <a href="./" class="nav-link active">Classes</a>
                        </li>
                        <?php if ($_SESSION['compte'] == "Surveillant"):  ?>
                            <li>
                                <a href="../inscription.php" class="nav-link">Inscription</a>
                            </li>
                            <li>
                                <a href="../matieres/" class="nav-link">Matières</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="#" class="nav-link">Bulletins</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Colonne de droite contenant le contenu dynamique -->
        <div class="col-right">
            <!-- Barre supérieure avec bouton "Nouveau" et formulaire de recherche -->
            <div class="tp">
                <?php if ($_SESSION['compte'] == "Surveillant"):  ?>
                    <div class="add" style="display: flex; gap: 20px">
                        <a href="./add.php" class="btn-add">Nouveau</a>
                        <a class="btn-add" href="./actionsclasses.php">Actions</a>
                    </div>
                <?php endif; ?>
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
                                    $sql_eleves = "SELECT COUNT(eleve_id) AS eleve_id FROM eleves WHERE classe_id = ?";

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
                                                        <?php if ( $eleve['eleve_id'] > 0):  ?>
                                                            <h4>Effectifs: <?= $eleve['eleve_id'] ?></h4> <!-- Valeur statique ici, à adapter dynamiquement si nécessaire -->
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

    <script>
    const menuBtn = document.getElementById("menu");
    const menuModal = document.querySelector(".menu-modal");
    const mainContent = document.getElementById("mainContent");

    let isMenuOpen = false;

    menuBtn.addEventListener("click", () => {
        isMenuOpen = !isMenuOpen;
        
        if (isMenuOpen) {
            menuModal.style.display = "block";
            mainContent.style.display = "none"; // Masque le contenu principal
        } else {
            menuModal.style.display = "none";
            mainContent.style.display = "flex"; // Réaffiche le contenu principal
        }
    });

    // Clique extérieur pour fermer le menu
    document.addEventListener("click", (e) => {
        if (!menuModal.contains(e.target) && !menuBtn.contains(e.target)) {
            menuModal.style.display = "none";
            mainContent.style.display = "flex";
            isMenuOpen = false;
        }
    });
</script>

    
</body>
</html>
