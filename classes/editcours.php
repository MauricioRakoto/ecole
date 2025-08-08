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
    $sql_matieres = "SELECT matiere_id, nom_matiere, coefficient FROM matieres ORDER BY matiere_id ASC";
    $stmt_matieres = $pdo->query($sql_matieres);
    $matieres = $stmt_matieres->fetchAll();

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

    $sql_cour = "SELECT a.*, b.* 
                    FROM cours a
                    LEFT JOIN matieres b
                    ON a.matiere_id = b.matiere_id  
                    WHERE a.cours_id = ?";
    $stmt_cour = $pdo->prepare($sql_cour);
    $stmt_cour->execute([$_GET['idc']]);
    $cours_edits = $stmt_cour->fetchAll();

    $sql_cour_matiere = "SELECT matiere_id, nom_matiere FROM matieres WHERE matiere_id = ?";
    $stmt_cour_matiere = $pdo->prepare($sql_cour_matiere);
    $stmt_cour_matiere->execute([$_GET['idc']]);
    $cour_matieres = $stmt_cour_matiere->fetchAll();

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
            width: 5%;
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
</head>
    <body>

        <!-- Formulaire modal pour l'ajout de nouvelle classe -->
        <div class="modal-add">
        <form action="../back/cours/updateCours.php?id=<?= $_GET['idc']?>" class="add-content" method="POST" style="height: 250px;">
            <div class="tp-add">
                <div class="ttl">
                    <h1>Modifier un cours</h1>
                </div>
                <div class="close">
                    <a href="./cours.php?id=<?php echo $id_classe ?>">X</a>
                </div>
            </div>
            <div class="bd-add">
                <?php if (count($cours_edits) > 0):  ?>

                <?php foreach ( $cours_edits as $cours_edit ): ?>
                    <!-- Champ: nom du matiere -->
                    <div class="form-group">
                    <div class="label">
                        <h4>Classe</h4>
                    </div>
                    <div class="select">
                        <select name="classe_id">
                            <?php foreach ( $classes as $classe ): ?>
                                <option value="<?= $classe['classe_id'] ?>"><?= $classe['nom_classe'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                    </div>
                    </div>

                    <div class="form-group">
                    <div class="label">
                        <h4>Matière</h4>
                    </div>
                    <div class="select">
                        <select name="matiere_id">
                            
                            <option value="<?= $cours_edit['matiere_id'] ?>">
                                <?= $cours_edit['nom_matiere'] ?>
                            </option>
                            <?php foreach ( $cour_matieres as $cour_matiere ): ?>
                                <option value="<?= $cour_matiere['matiere_id'] ?>">
                                    <?= $cour_matiere['nom_matiere'] ?>
                                </option>
                                <?php endforeach; ?>
                                <?php foreach ( $matieres as $matiere ): ?>
                                    <option value="<?= $matiere['matiere_id'] ?>">
                                    <?= $matiere['nom_matiere'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                    </div>
                    </div>
                <?php endforeach; ?>

                <?php else: ?>
                        <!-- Message si aucun élève -->
                        <h5>Aucun cour trouvé</h5>
                <?php endif; ?>
                
                <!-- Bouton de soumission du formulaire -->
                <div class="form-submit">
                    <button type="submit" name="modifier">Terminer</button>
                </div>
            </div>
        </form>
        </div>

        <nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
            <!-- Logo et nom -->
            <a  href="../home.php" class="text-primary" style="display: flex; gap: 10px; align-items: center">
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
                        <li><a href="./eleves.php?id=<?php echo $id_classe ?>" class="nav-link">Eleves</a></li>
                        <li><a href="./numbers.php?id=<?php echo $id_classe ?>" class="nav-link">Numéros</a></li>
                        <li><a href="./absences.html" class="nav-link">Absences</a></li>
                        <li><a href="./matieres.php?id=<?php echo $id_classe ?>" class="nav-link active">Matières</a></li>
                        <li><a href="./notes.html" class="nav-link">Notes</a></li>
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
                        <a href="./cours.php?id=<?php echo $id_classe ?>" class="btn-add">Listes</a>
                        <a href="./addcours.php?id=<?php echo $id_classe ?>" class="btn-add active">Nouveaux</a>
                    </div>
                
                </div>

                <div class="col-classe-l" style="margin-top: 20px;">
                    <?php foreach ( $classes as $classe ): ?>
                        <!-- Informations générales de la classe -->
                        <h1>Cours dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                        <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                        <h3>Salle: <?php echo $classe['salle'] ?></h3>
                    <?php endforeach; ?>
                </div>

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
                                            <div class="links">
                                                <a href="#" class="btn btn-print">Modifier</a>
                                                <a href="#" class="btn btn-print">Supprimer</a>
                                            </div>
                                            
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
                            <!-- Bouton pour imprimer la liste de la classe -->
                            <div class="bt">
                    <a href="./printclasse.php?id=<?php echo $id_classe ?>" class="btn btn-print">Imprimer</a>
                </div>
            <?php else: ?>
                <!-- Message si aucune classe trouvée -->
                <h5>Aucun classe trouvé</h5>
            <?php endif; ?>
            </div>
        </div>


        <!-- Inclusion du JavaScript principal -->
        <script src="../assets/js/main.js"></script>

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
    </body>
</html>

