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
<?php require '../includes/head.php' ?>
<style>
    table th:nth-child(1),
    table td:nth-child(1) {
        width: 50px;
    }

    table th:nth-child(2),
    table td:nth-child(2),
    table th:nth-child(4),
    table td:nth-child(4),
    table th:nth-child(5),
    table td:nth-child(5) {
        width: 1%;
    }

    table th:nth-child(3),
    table td:nth-child(3) {
        width: 10%;
    }

    table th:nth-child(6),
    table td:nth-child(6) {
        width: 13%;
    }


</style>
    <body>

        <?php require '../includes/navbar.php' ?>

        <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">

            <!-- Barre latérale avec les liens du menu -->
            <div class="sidebar" style="width: 200px; padding: 0 20px;">
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

