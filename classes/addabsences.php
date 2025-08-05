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

    // Requête pour récupérer les cours de la classe
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

    // Requête pour récupérer les élèves de la classe
    $sql_eleves = "SELECT eleve_id,
                        numero,
                        nom_eleve,
                        prenom_eleve,
                        sexe_eleve
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
            <button class="btn-menu" id="menu">
                M
            </button>
            <div class="menu-name">
                <h4><?= $_SESSION['username'] ?></h4>
            </div>
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

                <!-- Liens vers les options du menu utilisateur -->
                <div class="modal-body">
                    <ul class="modal-links">
                        <li>
                            <a href="#">Mon profile</a>
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
                        <li>
                            <a href="./numbers.php?id=<?php echo $id_classe ?>" class="nav-link">Numéros</a>
                        </li>
                        <li>
                            <a href="./absences.html" class="nav-link active">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.html" class="nav-link">Notes</a>
                        </li>
                        <li><a href="./renvoyer.html" class="nav-link">Renvoyer</a></li>
                        <li><a href="index.php" class="nav-link">Bulletins</a></li>
                    </ul>
                </div>
                </nav>
            </div>

            <div class="col-right">
            <?php if (count($classes) > 0):  ?>
                <div class="tp">
                    <div class="add notes">
                        <a href="./absences.php?id=<?php echo $id_classe ?>" class="btn-add">Listes</a>
                        <a href="./addabsences.php?id=<?php echo $id_classe ?>" class="btn-add active">Nouveaux</a>
                    </div>
                
                </div>


                <div class="col-classe-l" style="margin-top: 20px;">
                    <?php foreach ( $classes as $classe ): ?>
                        <!-- Informations générales de la classe -->
                        <h1>Ajouter des Absences dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                        <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                        <h3>Matiere: Exemple</h3>
                        <h3>Salle: <?php echo $classe['salle'] ?></h3>
                    <?php endforeach; ?>
                </div>

                <div id="carouselExampleIndicators" class="carousel" >
                    <h3 style="font-size: 16px; font-weight: 500">Mode de saisir</h3>
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1">Automatique</button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" class="" aria-label="Slide 2">Manuel</button>
                    </div>

                    <div class="bd">
                    
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                            <form action="../back/absences/addAbsences.php" method="POST" class="d-block w-100 auto">
                                <div class="tp-form" style="margin-top: 20px;">

                                    <!-- Sélection matière -->
                                    <div class="form-group">
                                        <div class="label">
                                            <h3>Matière</h3>
                                                                </div>
                                                                <div class="select">  
                                            <?php if (count($cours) > 0): ?>
                                                <select name="matiere_id" required>
                                                    <?php foreach ($cours as $cour): ?>
                                                        <option value="<?= $cour['matiere_id'] ?>"><?= $cour['nom_matiere'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                <h5>Aucune matière trouvée</h5>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Date d'absence global -->
                                    <div class="form-group">
                                        <div class="label">
                                            <h4>Date d'absence</h4>
                                        </div>
                                        <div class="input">
                                            <input type="date" name="date" required>
                                        </div>
                                    </div>

                                    <!-- Tableau des élèves -->
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Matricule</th>
                                                <th>Nom & Prénom</th>
                                                <th>Sexe</th>
                                                <th>Minutes d'absence</th>
                                            </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($eleves as $eleve): ?> 
                                            <tr>
                                                <td><?= $eleve['numero'] ?></td>
                                                <td><?= $eleve['eleve_id'] ?></td>
                                                <td><?= $eleve['nom_eleve'] ?> <?= $eleve['prenom_eleve'] ?></td>
                                                <td><?= $eleve['sexe_eleve'] ?></td>
                                                <td>
                                                    <input type="hidden" name="eleve_id[]" value="<?= $eleve['eleve_id'] ?>">
                                                    <input type="number" name="minutes[]" class="form-control" min="0" placeholder="minutes">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <!-- Bouton -->
                                    <div class="submit">
                                        <button type="submit" class="btn btn-primary">Terminer</button>
                                    </div>
                                </div>
                            </form>

                            </div>
                            <div class="carousel-item">
                                <form action="#" method="POST" class="d-block w-100 manuel">
                                    <div class="form-group">
                                        <div class="label">
                                            <h4>Matière</h4>
                                        </div>
                                        <div class="select">
                                            <select name="" id="">
                                                <option value="1">Anglais</option>
                                                <option value="1">Français</option>
                                                <option value="1">Malagasy</option>
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="label">
                                            <h4>Eleves</h4>
                                        </div>
                                        <div class="input">
                                            <input type="text" placeholder="N° Eleve">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="label">
                                            <h4>Heure</h4>
                                        </div>
                                        <div class="input">
                                            <input type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="label">
                                            <h4>Date d'absence</h4>
                                        </div>
                                        <div class="input">
                                            <input type="date">
                                        </div>
                                    </div>
                                    <div class="submit">
                                        <button type="submit" href="./print-classe.html" class="btn btn-primary">Terminer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                         
                    </div>
                <div class="bt">
                    
                </div>
            </div>

            <?php else: ?>
                <!-- Message si aucune classe trouvée -->
                <h5>Aucun classe trouvé</h5>
            <?php endif; ?>
            </div>
        </div>


        <!-- Inclusion du JavaScript principal -->
        <script src="../assets/js/main.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

