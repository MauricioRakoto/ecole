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

    // Requête pour récupérer les absences de la classe
    $sql_absences = "SELECT a.*,
                        b.*
                FROM cours a
                LEFT JOIN matieres b
                ON a.matiere_id = b.matiere_id
                WHERE a.classe_id = ?               
    ";
    
    $stmt_cours = $pdo->prepare($sql_cours);
    $stmt_cours->execute([$id_classe]);
    $cours = $stmt_cours->fetchAll();

    $selectedMatiereId = isset($_POST['matiere_id']) ? intval($_POST['matiere_id']) : ($cours[0] ['matiere_id'] ?? 0);

    if ( $selectedMatiereId ) {

         // Récupérer des absences aujourd'hui
        $date_today = date("Y-m-d");
        $sql_absences = "SELECT * FROM absences WHERE date_absence = ? AND classe_id = ? AND matiere_id = ?";
        $stmt_absences = $pdo->prepare($sql_absences);
        $stmt_absences->execute([
            $date_today,
            $id_classe,
            $selectedMatiereId
        ]);

        $absences = $stmt_absences->fetchAll();

         // Récupérer un matiere
        $sql_matiere = "SELECT nom_matiere FROM matieres WHERE matiere_id = ?";
        $stmt_matiere = $pdo->prepare($sql_matiere);
        $stmt_matiere->execute([$selectedMatiereId]);
        $matieres = $stmt_matiere->fetchAll();

    }
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

        .links {
            display: flex;
            gap: 10px;
        }

        a.btn-data {
            width: 90px;
            height: 30px;
            background: #d3cad9;
            transition: 1s ease-in-out;
            color: black;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        a.btn-data:hover {
            background: #009cff;
        }

        button.icone {
            border: 0;
         }

        button.icone img {
            width: 30px; 
            height: 30px; 
            border-radius: 50%;
        }

        .photo img {
            width: 60px; 
            height: 60px; 
            border-radius: 50%;
        }
    </style>
    <script>
        function submitForm() {
            document.getElementById('classForm').submit();
        }
    </script>
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
                <button class="icone" id="menu">
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
                            <div class="photo">
                                <img src="../assets/img/<?= $compte['image'] ?>" alt="Image" width="35">
                            </div>
                            <?php else: ?>
                                <div class="image">
                                    M
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
                        <li>
                            <a href="./profile.php">Mon profile</a>
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
                            <a href="./absences.php?id=<?php echo $id_classe ?>" class="nav-link active">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.html" class="nav-link">Notes</a>
                        </li>
                        <li><a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link">Renvoyer</a></li>
                        <li><a href="index.php" class="nav-link">Bulletins</a></li>
                    </ul>
                </div>
                </nav>
            </div>

            <div class="col-right">
            <?php if (count($classes) > 0):  
                $date_today = date("Y-m-d");
                ?>
                <div class="tp">
                    <div class="add notes">
                        <a href="./absences.php?id=<?php echo $id_classe ?>" class="btn-add active">Listes</a>
                        <a href="./addabsences.php?id=<?php echo $id_classe ?>" class="btn-add">Nouveaux</a>
                    </div>
                
                    <div class="search">
                        <form action="./resultabsence.php" method="POST">
                            <input type="date" name="date" value="<?= $date_today ?>">
                            <button type="submit" >Rechercher</button>
                        </form>

                    </div>
                </div>

                <div class="bd">
                    
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

