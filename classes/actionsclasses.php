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
    $sql_classes = "SELECT * FROM classes";
    $stmt_classes = $pdo->prepare($sql_classes);
    $stmt_classes->execute([]);
    $classes = $stmt_classes->fetchAll();

    // Requête pour récupérer les élèves de la classe
    $sql_eleves = "SELECT eleve_id, numero, nom_eleve, prenom_eleve, sexe_eleve FROM eleves WHERE classe_id = ? ORDER BY eleve_id ASC";
    $stmt_eleves = $pdo->prepare($sql_eleves);
    $stmt_eleves->execute([$id_classe]);
    $eleves = $stmt_eleves->fetchAll();


   // Récupérer les heures d'absences par élève (somme des minutes / 60)
    $sql_absences = "SELECT eleve_id, SUM(minutes) AS total_minutes 
    FROM absences 
    WHERE classe_id = ?
    GROUP BY eleve_id";
    $stmt_absences = $pdo->prepare($sql_absences);
    $stmt_absences->execute([$id_classe]);
    $absences_data = $stmt_absences->fetchAll(PDO::FETCH_ASSOC);

    // Transformer les minutes en heures arrondies à 1 chiffre après la virgule
    $absences_par_heure = [];
    foreach ($absences_data as $row) {
    $absences_par_heure[$row['eleve_id']] = round($row['total_minutes'] / 60, 1);
    }

    // Encodage pour JS
    $absences_json = json_encode($absences_par_heure);

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
        table td:nth-child(2),
        table th:nth-child(3),
        table td:nth-child(3),
        table th:nth-child(4),
        table td:nth-child(4),
        table th:nth-child(5),
        table td:nth-child(5) {
            width: 1%;
        }

        table th:nth-child(6),
        table td:nth-child(6) {
            width: 13%;
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

        button.select {
        width: 20px;
        height: 20px;
        border: 1px solid #000;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        }

        button.select.active {
            background-color: #007bff;
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
            <button class="icone" id="menu" >
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
                            <a href="./back/responsable/logout.php">Se déconnecter</a>
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
                            <a href="./notes.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Notes</a>
                        </li>
                        <li>
                            <a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link">Renvoyer</a>
                        </li>
                        <li>
                            <a href="./bulletins.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Bulletins</a>
                        </li>
                        <li>
                            <a href="./supprimer.php?id=<?php echo $id_classe ?>" class="nav-link active">Supprimer</a>
                        </li>
                    </ul>
                </div>
            </nav>
            </div>

            <div class="col-right">
            
                <div class="tp">
                <div class="add" style="display: flex; gap: 20px">
                        <a href="./add.php" class="btn-add">Nouveau</a>
                        <a class="btn-add" href="./actionsclasses.php">Actions</a>
                    </div>
                </div>

                <div class="bd">
                   
                    <form action="../back/classes/deleteClasses.php" method="POST" class="d-block w-100 auto">
                            
                        <div class="form-top">
                                <button class="btn btn-primary" type="submit">
                                    <span style="color: blue; margin-right: 10px">0</span>
                                    Supprimer
                                </button>
                        </div>
                    
                        <table class="table table-hover" style="margin-top: 20px">
                                <thead>
                                    <tr>
                                        <th class="col"></th>
                                        <th class="col">N°</th>
                                        <th class="col">Classe</th>
                                        <th class="col">Effectifs</th>
                                        <th class="col">Niveau</th>
                                        <th class="col">Année Scolaire</th>
                                        <th class="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                            
                                <?php foreach ( $classes as $classe ): ?>
                                    <tr>
                                        <th>
                                            <button type="button" class="select"></button>
                                        </th>
                                        <td><?= $classe['classe_id'] ?></td>
                                        <td><?= $classe['nom_classe'] ?></td>
                                        <td>0</td>
                                        <td><?= $classe['niveau'] ?></td>
                                        <td><?= $classe['annee_debut'] ?> - <?= $classe['annee_fin'] ?></td>
                                        <td>
                                            <a href="./editclasse.php?id=<?= $classe['classe_id'] ?>" class="btn btn-primary">Modifier</a>
                                        </td>
                                    </tr>
                                
                                <?php endforeach; ?>
                                        
                                </tbody>
                        </table>
                            
                    </form> 

                  
                </div>
                           
                            
            
            </div>
        </div>


        <!-- Inclusion du JavaScript principal -->
        <script src="../assets/js/main.js"></script>

        <script>
            // Objet contenant les heures d'absence par élève
            const absencesParHeure = <?= $absences_json ?>;

            document.addEventListener("DOMContentLoaded", function () {
                const absencesCells = document.querySelectorAll('.absences-cell');

                absencesCells.forEach(cell => {
                    const eleveId = cell.getAttribute('data-eleve-id');
                    const heures = absencesParHeure[eleveId] || 0;
                    cell.textContent = heures + ' h';
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const selectButtons = document.querySelectorAll('button.select');
                const compteurSpan = document.querySelector('.form-top span');
                const form = document.querySelector('form');
                let selectedEleves = new Set(); // pour stocker les eleve_id sélectionnés

                selectButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const row = button.closest('tr');
                        const eleveId = row.querySelector('td:nth-child(2)').textContent;
                    
                        if (button.classList.contains('active')) {
                            button.classList.remove('active');
                            selectedEleves.delete(eleveId);
                        } else {
                            button.classList.add('active');
                            selectedEleves.add(eleveId);
                        }
                    
                        // Mettre à jour le compteur
                        compteurSpan.textContent = selectedEleves.size;
                    });
                });

                // Avant la soumission du formulaire, ajouter les eleve_id sélectionnés en input hidden
                form.addEventListener('submit', function(e) {
                    // Supprimer les anciens inputs si existants
                    const oldInputs = form.querySelectorAll('input[name="classes[]"]');
                    oldInputs.forEach(input => input.remove());
                
                    // Ajouter un input hidden pour chaque eleve_id sélectionné
                    selectedEleves.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'classes[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                
                    // Si aucun élève sélectionné, empêcher la soumission
                    if (selectedEleves.size === 0) {
                        e.preventDefault();
                        alert("Veuillez sélectionner au moins un élève à supprimer.");
                    }
                });
            });
        </script>



    </body>
</html>

