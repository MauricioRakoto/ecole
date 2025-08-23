<?php 

    // Inclusion du fichier de connexion à la base de données
    require "../back/database.php";

    // Démarrage de la session
    session_start();

    // Vérifie si un responsable est connecté, sinon redirige vers la page de connexion
    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    // Récupération de l'ID de la classe depuis l'URL (GET)
    $id_classe = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    // Requête pour récupérer les informations de la classe
    $sql_classes = "SELECT * FROM classes WHERE classe_id = ? ";
    $stmt_classes = $pdo->prepare($sql_classes);
    $stmt_classes->execute([$id_classe]);
    $classes = $stmt_classes->fetchAll();

    // Requête pour récupérer les élèves de la classe
    $sql_cours = "SELECT a.*,
                        b.*
                FROM cours a
                LEFT JOIN matieres b
                ON a.matiere_id = b.matiere_id
                WHERE a.classe_id = ?               
    ";
    $stmt_cours = $pdo->prepare($sql_cours);
    $stmt_cours->execute([$id_classe]);
    $cours = $stmt_cours->fetchAll();

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

        <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">

            <!-- Barre latérale avec les liens du menu -->
            <div class="sidebar" style="width: 200px; padding: 0 20px;">
                <nav class="navbar bg-light">
                <div class="navbar-nav w-100" style="margin-top: 25px">
                    <div class="nav-top">
                        <div class="nav-l">
                            <h3>Classe</h3>
                        </div>
                        <div class="nav-r">
                            <a class="df-jc-ac" href="./">X</a>
                        </div>
                    </div>
                    <ul>
                        <!-- Liens vers les différentes pages liées à la classe -->
                        <li>
                            <a href="./eleves.php?id=<?php echo $id_classe ?>" class="nav-link">Eleves</a>
                        </li>
                        <?php if ($_SESSION['compte'] == "Surveillant"):  ?>
                            <li>
                                <a href="./numbers.php?id=<?php echo $id_classe ?>" class="nav-link">Numéros</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="./absences.php?id=<?php echo $id_classe ?>" class="nav-link">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link active">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Notes</a>
                        </li>
                        <li><a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link">Renvoyer</a></li>
                        <li>
                            <a href="./bulletins.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Bulletins</a>
                        </li>
                        <li>
                            <a href="./supprimer.php?id=<?php echo $id_classe ?>" class="nav-link">Supprimer</a>
                        </li>
                    </ul>
                </div>
                </nav>
            </div>

            <div class="col-right">
            <?php if (count($classes) > 0):  ?>
                <?php if ($_SESSION['compte'] == "Surveillant"):  ?>
                    <div class="tp">
                        <div class="add notes">
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="btn-add active">Listes</a>
                            <a href="./addcours.php?id=<?php echo $id_classe ?>" class="btn-add">Nouveaux</a>
                        </div>
                    
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['compte'] == "Surveillant"):  ?>

                    <div class="col-classe-l" style="margin-top: 20px;">
                    <?php foreach ( $classes as $classe ): ?>
                        <!-- Informations générales de la classe -->
                        <h1>Cours dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                        <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                        <h3>Salle: <?php echo $classe['salle'] ?></h3>
                    <?php endforeach; ?>
                   
                    </div>
                <?php else: ?>
                    <div class="col-classe-l">
                    <?php foreach ( $classes as $classe ): ?>
                        <!-- Informations générales de la classe -->
                        <h1>Cours dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                        <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                        <h3>Salle: <?php echo $classe['salle'] ?></h3>
                    <?php endforeach; ?>
                   
                    </div>
                <?php endif; ?>

                <div class="bd">
                    <?php if (count($cours) > 0):  ?>
                        <!-- Affichage de la liste des élèves -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col">#</th>
                                    <th class="col">Matière</th>
                                    <th class="col">Coefficent</th>
                                    <th class="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $cours as $cour ): ?>
                                    <tr>
                                        <!-- Numéro attribué -->
                                        <th scope="row">
                                            <?= $cour['cours_id'] ?>
                                        </th>
                                        <!-- ID élève -->
                                        <td>
                                            <?= $cour['nom_matiere'] ?>
                                        </td>
                                        <!-- Nom complet -->
                                        <td>
                                            <?= $cour['coefficient'] ?>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION['compte'] == "Surveillant"):  ?>
                                                <div class="links">
                                                    <a href="./editcours.php?id=<?= $id_classe ?>&&idc=<?= $cour['cours_id'] ?>" class="btn btn-data">Modifier</a>
                                                    <a href="../back/cours/deleteCours.php?id=<?= $cour['cours_id'] ?>&idc=<?= $id_classe ?>" class="btn btn-data">Supprimer</a>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Message si aucun élève -->
                        <h5>Aucun matière trouvé</h5>
                    <?php endif; ?>
                </div>
                           
            <?php else: ?>
                <!-- Message si aucune classe trouvée -->
                <h5>Aucun classe trouvé</h5>
            <?php endif; ?>
            </div>
        </div>


        <!-- Inclusion du JavaScript principal -->
        <script src="../assets/js/main.js"></script>
    </body>
</html>

