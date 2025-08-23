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
                            <a href="./eleves.php?id=<?php echo $id_classe ?>" class="nav-link active">Eleves</a>
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
                            <a href="./supprimer.php?id=<?php echo $id_classe ?>" class="nav-link">Supprimer</a>
                        </li>
                    </ul>
                </div>
            </nav>
            </div>

            <div class="col-right">
            <?php if (count($classes) > 0):  ?>
                <div class="tp">
                    <div class="col-classe-l">
                        <?php foreach ( $classes as $classe ): ?>
                            <!-- Informations générales de la classe -->
                            <h1>Liste des Eleves dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                            <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                            <h3>Salle: <?php echo $classe['salle'] ?></h3>
                        <?php endforeach; ?>
                    </div>
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
                                    <th class="col">Absences</th>
                                    <th class="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $eleves as $eleve ): ?>
                                    <tr>
                                        <th scope="row">
                                            <?php echo !empty($eleve['numero']) ? $eleve['numero'] : 0; ?>
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
                                        <td class="absences-cell" data-eleve-id="<?= $eleve['eleve_id'] ?>"></td>
                                        <!-- Lien vers détail -->
                                        <td>
                                            <a href="#" class="btn btn-primary">Voir</a>
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


    </body>
</html>

