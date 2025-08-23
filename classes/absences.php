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


    
    $search = isset($_GET['date']) ? trim($_GET['date']) : '';
    $classeId = $_GET['id'];

   

    if (!empty($search)) {
        $sql_search = "SELECT 
                        b.nom_matiere,
                        COUNT(DISTINCT a.eleve_id) AS eleve_id
                        FROM absences a
                        LEFT JOIN matieres b ON a.matiere_id = b.matiere_id
                        LEFT JOIN eleves e ON a.eleve_id = e.eleve_id
                        WHERE a.date_absence LIKE :search
                        AND a.classe_id = :classeId
                        GROUP BY b.nom_matiere
                    ";
        $stmt_search = $pdo->prepare($sql_search);
        $stmt_search->execute([
        ':search' => "%$search%",
        ':classeId' => $classeId
        ]);

        $search_absences = $stmt_search->fetchAll();
    }

   
    
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
                            <a href="./absences.php?id=<?php echo $id_classe ?>" class="nav-link active">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
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
                <?php if (count($classes) > 0):  
                    $date_today = date("Y-m-d");
                    ?>
                    <div class="tp" style="justify-content: normal; gap: 100px">
                    <div class="add notes">
                        <a href="./absences.php?id=<?php echo $id_classe ?>" class="btn-add active">Listes</a>
                        <a href="./addabsences.php?id=<?php echo $id_classe ?>" class="btn-add">Nouveaux</a>
                    </div>
                
                    <div class="search">
                        <form action="" method="GET">
                            <input type="hidden" name="id" value="<?php echo $id_classe ?>">
                            <input type="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">

                            <button type="submit">Rechercher</button>
                        </form>

                    </div>
                    </div>

                    <?php if ($search): ?>
                        <div class="bd">
                            <div class="carousel-item" style="display: block;">
                            <div class="d-block w-100">
                                <div class="items">

                                    <?php foreach ( $search_absences as $result ): 

                                        $sql_nombres = "SELECT COUNT(eleve_id), eleve_id AS nombres_eleves FROM eleves WHERE eleve_id = ?";
                                        $stmt_nombres = $pdo->prepare($sql_nombres);
                                        $stmt_nombres->execute([$result['eleve_id']]);
                                        $nombres = $stmt_nombres->fetchAll();

                                        ?>
                                        <a href="#">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-title">
                                                        <h3><?= $result['nom_matiere'] ?></h3>
                                                        <?php foreach ( $nombres as $nombre ): ?>
                                                            <h4>Eleves: <?= $nombre['nombres_eleves'] ?></h4>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            </div>
                        </div>

                        <?php else: ?>
                            <div class="tp-form" style="margin-top: 20px;">

                                <form action="" class="form-group" id="classForm" method="POST">
                                    <div class="label">
                                        <h4>Matière</h4>
                                    </div>
                                    <div class="select" >
                                        <select name="matiere_id" id="Classe" onchange="submitForm()">
                                            <?php foreach ($cours as $cour): ?>
                                                <option value="<?= $cour['matiere_id'] ?>"
                                                    <?= $selectedMatiereId == $cour['matiere_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cour['nom_matiere']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <div class="col-classe-l" style="margin-top: 20px;">
                                <?php foreach ( $classes as $classe ): ?>
                                    <!-- Informations générales de la classe -->
                                    <h1>Absences dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                                    <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                                    <h3>
                                        Matiere: 
                                        <?php foreach ($matieres as $matiere): ?>
                                            <?= htmlspecialchars($matiere['nom_matiere']) ?>
                                        <?php endforeach; ?>
                                    </h3>
                                    <h3>Salle: <?php echo $classe['salle'] ?></h3>
                                <?php endforeach; ?>
                            </div>

                            <div class="bd">
                            <?php if (count($absences) > 0):  ?>
                                <!-- Affichage de la liste des élèves -->
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col">#</th>
                                            <th class="col">Matricule</th>
                                            <th class="col">Nom & Prénom</th>
                                            <th class="col">Sexe</th>
                                            <th class="col">Minutes</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ( $absences as $absence ): 
                                    
                                    // Requête pour récupérer les absences de la classe
                                    $sql_eleves = "SELECT eleve_id,
                                                        numero,
                                                        nom_eleve,
                                                        prenom_eleve,
                                                        sexe_eleve
                                                    FROM eleves
                                                    WHERE eleve_id = ?               
                                    ";

                                    $stmt_eleves = $pdo->prepare($sql_eleves);
                                    $stmt_eleves->execute(
                                        [
                                            $absence['eleve_id']
                                            ]
                                    );
                                    $eleves = $stmt_eleves->fetchAll();
                                    
                                    ?>
                                    <tr>
                                        <?php foreach ( $eleves as $eleve ): ?>
                                            <!-- Numéro attribué -->
                                            <th scope="row">
                                                <?= $eleve['numero'] ?>
                                            </th>

                                             <!-- ID élève -->
                                            <td>
                                                <?= $eleve['eleve_id'] ?>
                                            </td>

                                            <!-- Nom et Prénom élève -->
                                            <td>
                                                <?= $eleve['nom_eleve'] ?>
                                                <?= $eleve['prenom_eleve'] ?>
                                            </td>

                                             <!-- Sexe -->
                                             <td>
                                                <?= $eleve['sexe_eleve'] ?>
                                            </td>
                                        <?php endforeach; ?>
                                       
                                        <!-- Nom complet -->
                                        <td>
                                            <?= $absence['minutes'] ?> min
                                        </td>
                                        <td>
                                            <div class="links">
                                                <a href="./editabsence.php?id=<?= $id_classe ?>&idc=<?= $absence['absences_id'] ?>" class="btn btn-data">Modifier</a>
                                                <a href="../back/absences/deleteAbsence.php?id=<?= $absence['absences_id'] ?>&idc=<?= $id_classe ?>" class="btn btn-data">Supprimer</a>
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
                        <?php endif; ?>
                    
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

