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

        .items {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .items .card {
            width: 200px;
            height: 80px;
            background: #d3cad9;
            padding: 10px;
            border-radius: 5px;
            transition: .5s ease-in-out;
            margin-bottom: 10px;
            z-index: 5;
            border: 0;
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
                            <a href="./absences.php?id=<?php echo $id_classe ?>" class="nav-link ">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.html" class="nav-link">Notes</a>
                        </li>
                        <li><a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link">Renvoyer</a></li>
                        <li>
                            <a href="./bulletins.php?id=<?php echo $id_classe ?>" class="nav-link active">Bulletins</a>
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
                    
                        if (id && s) {
                          fetch(`get_notes.php?id=${id}&s=${s}`)
                            .then(response => response.json())
                            .then(data => {
                              if (data.error) {
                                document.getElementById('notesContainer').innerHTML = `<p>Erreur: ${data.error}</p>`;
                                return;
                              }
                              afficherNotes(data);
                            })
                            .catch(error => {
                              document.getElementById('notesContainer').innerHTML = `<p>Erreur réseau: ${error}</p>`;
                            });
                        } else {
                          document.getElementById('notesContainer').innerHTML = `<p>Paramètres URL manquants.</p>`;
                        }
                    
                        // Fonction pour afficher les notes
                        function afficherNotes(data) {
                        const container = document.getElementById('notesContainer');
                        let html = `
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Matricule</th>
                                <th>Nom et Prénom</th>
                                <th>Sexe</th>
                                <th>Note</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                        `;

                        data.forEach(eleve => {
                          // Pour afficher les notes, on va concaténer toutes les notes dans une cellule
                          let notesHTML = eleve.notes.map(note => `${note.note}`).join('<br>');
                        
                          html += `
                            <tr>
                              <td>${eleve.numero}</td>
                              <td>${eleve.eleve_id}</td>
                              <td>${eleve.nom_eleve} ${eleve.prenom_eleve}</td>
                              <td>${eleve.sexe_eleve}</td>
                              <td>${notesHTML}</td>
                              <td>
                                <a class='btn btn-primary' href='./bulletin.php?id=${id}&ide=${eleve.eleve_id}&s=${s}'>Voir</a>
                              </td>
                            </tr>
                          `;
                        });
                    
                        html += `
                            </tbody>
                          </table>
                        `;
                    
                        container.innerHTML = html;
                        }

                    </script>

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
                            <h5>Aucun élèves trouvé</h5>
                                <?php endif; ?>
                            </div>
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

    

    

    <script>
        document.querySelectorAll('.periodes-links a').forEach(link => {
            
        link.addEventListener('click', e => {
            e.preventDefault();

            // Retirer la classe active de tous les liens et la remettre sur celui cliqué
            document.querySelectorAll('.periodes-links a').forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Extraire la session du href
            const urlParams = new URLSearchParams(link.search);
            const sValue = urlParams.get('s') || 1;

            // Charger les notes pour la session choisie
            loadNotes(idClasse, sValue);

            // Optionnel : modifier l'URL sans recharger (history API)
            history.replaceState(null, '', `?id=${idClasse}&s=${sValue}`);
        });
    });

    </script>

    </body>
</html>

