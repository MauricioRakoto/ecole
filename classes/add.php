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
        header("Location: ../classes/");
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

<!-- Début du document HTML -->
<!DOCTYPE html>
<html lang="en">
<?php require '../includes/head.php' ?>
<body>

    <!-- Formulaire modal pour l'ajout de nouvelle classe -->
    <div class="modal-add">
        <form action="../back/classes/addClasse.php" class="add-content" method="POST" style="height: 450px;">
            <div class="tp-add">
                <div class="ttl">
                    <h1>Ajouter un nouveau classe</h1>
                </div>
                <div class="close">
                    <a href="./index.php">X</a>
                </div>
            </div>
            <div class="bd-add">
                <!-- Champ: nom du classe -->
                <div class="form-group">
                    <div class="label">
                        <h4>Nom du classe</h4>
                    </div>
                    <div class="input">
                        <input type="text" name="nom_classe" placeholder="Nom du classe" required>
                    </div>
                </div>
                <!-- Champ: niveau -->
                <div class="form-group">
                    <div class="label">
                        <h4>Niveau</h4>
                    </div>
                    <div class="input">
                        <input type="text" name="niveau" placeholder="Niveau" required>
                    </div>
                </div>
                <!-- Champ: année de début -->
                <div class="form-group">
                    <div class="label">
                        <h4>Début d'année</h4>
                    </div>
                    <div class="input">
                        <input type="number" name="annee_debut" placeholder="Début d'année" required>
                    </div>
                </div>
                <!-- Champ: année de fin -->
                <div class="form-group">
                    <div class="label">
                        <h4>Fin d'année</h4>
                    </div>
                    <div class="input">
                        <input type="number"  name="annee_fin" placeholder="Fin d'année" required>
                    </div>
                </div>
                <!-- Champ: salle -->
                <div class="form-group">
                    <div class="label">
                        <h4>Salle</h4>
                    </div>
                    <div class="input">
                        <input type="number" name="salle" placeholder="Salle" required>
                    </div>
                </div>
                <!-- Bouton de soumission du formulaire -->
                <div class="form-submit">
                    <button type="submit" name="enregistrer">Terminer</button>
                </div>
            </div>
        </form>
    </div>

    <?php require '../includes/navbar.php' ?>

        <!-- Conteneur principal -->
    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
    
    <!-- Barre latérale -->
    <div class="sidebar" style="width: 200px; padding: 0 20px;">
        <nav class="navbar bg-light">
            <div class="navbar-nav w-100" style="margin-top: 25px">
                <ul>
                    <li><a href="../../home.html" class="nav-link">Accueil</a></li>
                    <li><a href="./" class="nav-link active">Classes</a></li>
                    <li><a href="./pages/classes/index.html" class="nav-link">Inscription</a></li>
                    <li><a href="index.php" class="nav-link">Matières</a></li>
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
