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
                            <a href="./absences.php?id=<?php echo $id_classe ?>" class="nav-link ">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Notes</a>
                        </li>
                        <li><a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link">Renvoyer</a></li>
                        <li>
                            <a href="./bulletins.php?id=<?php echo $id_classe ?>" class="nav-link active">Bulletins</a>
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

                    <div class="tp">
                        <div class="periodes" style="margin-top: 0">
                            <div class="periode-name">
                                <h3>Session d'examen</h3>
                            </div>
                            <div class="periodes-links">
                                <a class="active" href="./bulletins.php?id=<?php echo $id_classe ?>&s=1">1 Trimèstre</a>
                                <a href="./bulletins.php?id=<?php echo $id_classe ?>&s=2">2 Trimèstre</a>
                                <a href="./bulletins.php?id=<?php echo $id_classe ?>&s=3">3 Trimèstre</a>
                            </div>
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
                          

                            <div class="col-classe-l" style="margin-top: 20px;">
                                <?php foreach ( $classes as $classe ): ?>
                                    <!-- Informations générales de la classe -->
                                    <h1>Bulletins dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                                    <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                                    <h3>Salle: <?php echo $classe['salle'] ?></h3>
                                <?php endforeach; ?>
                            </div>

                            <div class="bd">
                            <table class="table table-hover" id="table-eleves">
                                <thead>
                                    <tr>
                                        <th>Rang</th>
                                        <th>Matricule</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Moyenne</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody >

                                </tbody>
                                
                            </table>

                            <div id="notesContainer"></div>

                    <script>
                        // Fonction pour récupérer un paramètre dans l'URL
                        function getParameterByName(name) {
                          const url = window.location.href;
                          name = name.replace(/[\[\]]/g, '\\$&'); // échappe les crochets
                          const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
                          const results = regex.exec(url);
                          if (!results) return null;
                          if (!results[2]) return '';
                          return decodeURIComponent(results[2].replace(/\+/g, ' '));
                        }
                    
                        // Récupérer id et s dans l'URL
                        const id = getParameterByName('id');
                        const s = getParameterByName('s');

                        // Si 's' est défini, gérer la classe active
                        if (s) {
                          // Sélectionner tous les liens de session d'examen
                          const links = document.querySelectorAll('.periodes-links a');
                        
                          links.forEach(link => {
                            // Extraire la valeur du paramètre s dans le href du lien
                            const urlParams = new URLSearchParams(link.search);
                            const sValue = urlParams.get('s');
                        
                            // Ajouter ou retirer la classe "active"
                            if (sValue === s) {
                              link.classList.add('active');
                            } else {
                              link.classList.remove('active');
                            }
                          });
                        }
                    
                        function afficherNote(data) {
                            // Pour chaque élève
                            data.forEach(eleve => {
                                let sommeTotaux = 0;
                                let totalCoef = 0;
                                let nombreMatieres = eleve.matieres.length; // nombre de matières de cet élève
                            
                                eleve.matieres.forEach(note => {
                                    let coef = parseFloat(note.coefficient) || 0;
                                    totalCoef += coef;
                                
                                    let ds1 = parseFloat(note.ds1) || 0;
                                    let ds2 = parseFloat(note.ds2) || 0;
                                    let exam = parseFloat(note.exam) || 0;
                                
                                    // Calcul DS total (moyenne des DS)
                                    let dsTotal = (ds1 + ds2) / 2;
                                
                                    // Pondération par coefficient
                                    let dsTotalCof = dsTotal * coef;
                                    let examCof = exam * coef;
                                
                                    // Total matière (comme ton modèle)
                                    let totalNoteMat = (dsTotalCof + examCof) / 3;
                                    totalNoteMat = Math.round(totalNoteMat);
                                
                                    sommeTotaux += totalNoteMat;
                                });
                            
                                // Moyenne générale
                                eleve.moyenneGenerale = nombreMatieres > 0 ? (sommeTotaux / nombreMatieres).toFixed(2) : 0;
                            });
                            
                            // Tri décroissant par moyenne générale
                            data.sort((a, b) => b.moyenneGenerale - a.moyenneGenerale);
                        
                            // Affichage dans le tableau
                            let tbody = document.querySelector("#table-eleves tbody");
                            tbody.innerHTML = "";
                        
                            data.forEach((e, index) => {
                                let row = document.createElement("tr");
                                row.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${e.numero}</td>
                                    <td>${e.nom_eleve} ${e.prenom_eleve}</td>
                                    <td>${e.sexe_eleve}</td>
                                    <td>${e.moyenneGenerale}</td>
                                    <td>
                                        <a class="btn btn-primary" href='./bulletin.php?id=${e.classe_id}&ide=${e.eleve_id}&s=${s}&r=${index + 1}'>Voir</a>
                                    </td>
                                `;
                                tbody.appendChild(row);
                            });
                        }

                        if (id && s) {
                            fetch(`get_notes.php?id=${id}&s=${s}`)
                                .then(res => res.json())
                                .then(data => afficherNote(data))
                                .catch(err => console.error("Erreur AJAX:", err));
                        }
                    </script>

                           
                            <!-- Bouton pour imprimer la liste de la classe -->
                        <?php endif; ?>
                    
                    <?php else: ?>
                        <!-- Message si aucune classe trouvée -->
                        <h5>Aucun classe trouvé</h5>
                    <?php endif; ?>
                </div>
            </div>


        <!-- Inclusion du JavaScript principal -->
        <script src="../assets/js/main.js"></script>

        <!-- <script>
            // Fonction pour récupérer la valeur d'un paramètre GET dans l'URL
            function getParameterByName(name) {
              const url = window.location.href;
              name = name.replace(/[\[\]]/g, '\\$&'); // échappe les crochets
              const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
              const results = regex.exec(url);
              if (!results) return null;
              if (!results[2]) return '';
              return decodeURIComponent(results[2].replace(/\+/g, ' '));
            }
        
            // Récupérer la valeur du paramètre "s"
            const s = getParameterByName('s');
        
            // Si 's' est défini, gérer la classe active
            if (s) {
              // Sélectionner tous les liens de session d'examen
              const links = document.querySelectorAll('.periodes-links a');
            
              links.forEach(link => {
                // Extraire la valeur du paramètre s dans le href du lien
                const urlParams = new URLSearchParams(link.search);
                const sValue = urlParams.get('s');

                // Ajouter ou retirer la classe "active"
                if (sValue === s) {
                  link.classList.add('active');
                } else {
                  link.classList.remove('active');
                }
              });
            }
        </script> -->

    

    

    

    </body>
</html>

