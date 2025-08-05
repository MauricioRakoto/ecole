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

    // Récupérer un compte
    $sql_compte = "SELECT image FROM responsable WHERE responsable_id = ?";
    $stmt_compte = $pdo->prepare($sql_compte);
    $stmt_compte->execute([$_SESSION['responsable_id']]);
    $comptes = $stmt_compte->fetchAll();

    // Requête pour récupérer les informations de la classe
    $sql_classes = "SELECT * FROM classes WHERE classe_id = ? ";
    $stmt_classes = $pdo->prepare($sql_classes);
    $stmt_classes->execute([$id_classe]);
    $classes = $stmt_classes->fetchAll();

    // Requête pour récupérer les élèves de la classe
    $sql_eleves = "SELECT 
                    eleve_id, numero, 
                    nom_eleve, 
                    prenom_eleve, 
                    sexe_eleve,
                    status 
                    FROM eleves 
                    WHERE classe_id = ? 
                    ORDER BY eleve_id ASC
    ";
    $stmt_eleves = $pdo->prepare($sql_eleves);
    $stmt_eleves->execute([$id_classe]);
    $eleves = $stmt_eleves->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Métadonnées et liens vers les fichiers CSS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <!-- Style CSS personnalisé -->
    <style>
        table th:nth-child(1),
        table td:nth-child(1) {
            width: 50px;
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 1%;
        }

        table th:nth-child(3),
        table td:nth-child(3) {
            width: 35%;
        }

        a.btn-renvoyer {
            width: 100px;
            height: 35px;
            background: #d3cad9;
            transition: 1s ease-in-out;
            color: black;

        }

        a.btn-effacer {
            width: 100px;
            height: 35px;
            background: #f34040;
            transition: 1s ease-in-out;
            color: black;
        }

        a.btn-renvoyer:hover {
            background: #009cff;
        }
    </style>
</head>
    <body>

        <nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
            <!-- Logo et nom -->
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

                <!-- Liens vers les options du menu utilisateur -->
                <div class="modal-body">
                    <ul class="modal-links">
                        <li>
                            <a href="../profile.php">Mon profile</a>
                        </li>
                        <li>
                            <a href="#">Paramètre</a>
                        </li>
                        <li>
                            <a href="../back/responsable/logout.php">Se déconnecter</a>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
        </nav>

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
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.html" class="nav-link">Notes</a>
                        </li>
                        <li>
                            <a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link active">Renvoyer</a>
                        </li>
                        <li><a href="index.php" class="nav-link">Bulletins</a></li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="col-right">
            <?php if (count($classes) > 0):  ?>

                <div class="tp">
                    <div class="add notes">
                        <a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="btn-add">Listes</a>
                        <a href="./addrenvoyer.php?id=<?php echo $id_classe ?>" class="btn-add active">Nouveaux</a>
                    </div>
                    <div class="search">
                        <form action="">
                            <input type="text" placeholder="Rechercher">
                            <button type="submit">Rechercher</button>
                        </form>
                    
                    </div>
                </div>

                <div class="col-classe-l" style="margin-top: 20px;">
                    <?php foreach ( $classes as $classe ): ?>
                        <!-- Informations générales de la classe -->
                        <h1>Renvoyer des élèves dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                        <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                        <h3>Salle: <?php echo $classe['salle'] ?></h3>
                    <?php endforeach; ?>
                </div>

                <div class="bd">
                    <?php if (count($eleves) > 0):  ?>
                        <!-- Affichage de la liste des élèves -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col">#</th>
                                    <th class="col">Matricule</th>
                                    <th class="col">Nom & Prénom</th>
                                    <th class="col">Sexe</th>
                                    <th class="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $eleves as $eleve ): ?>
                                    <tr>
                                        <!-- Numéro attribué -->
                                        <th scope="row">
                                            <?php echo $eleve['numero'] ?>
                                        </th>
                                        <!-- ID élève -->
                                        <td>
                                            <?php echo $eleve['eleve_id'] ?>
                                        </td>
                                        <!-- Nom complet -->
                                        <td>
                                            <?php echo $eleve['nom_eleve'] ?>
                                            <?php echo $eleve['prenom_eleve'] ?>
                                        </td>
                                        <!-- Sexe -->
                                        <td>
                                            <?php echo $eleve['sexe_eleve'] ?>
                                        </td>
                                        <td>
                                        <?php if ($eleve['status'] == "renvoyer"):  ?>
                                            <a href="../back/eleves/deleteRenvoyer.php?id=<?= $eleve['eleve_id'] ?>&idc=<?= $id_classe ?>" class="btn btn-effacer">Effacer</a>
                                            <?php else: ?>
                                                <a href="../back/eleves/addRenvoyer.php?id=<?= $eleve['eleve_id'] ?>&idc=<?= $id_classe ?>" class="btn btn-renvoyer">Ajouter</a>
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Message si aucun élève -->
                        <h5>Aucun élève trouvé</h5>
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

