<?php 
    // Connexion à la base de données
    require "../back/database.php";

    // Récupération de l'identifiant de la classe passé en paramètre GET
    $id_classe = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    // Requête pour obtenir les informations de la classe
    $sql_classes = "SELECT * FROM classes WHERE classe_id = ? ";
    $stmt_classes = $pdo->prepare($sql_classes);
    $stmt_classes->execute([$id_classe]);
    $classes = $stmt_classes->fetchAll();

    // Requête pour obtenir les élèves appartenant à la classe spécifiée
    $sql_eleves = "SELECT eleve_id,
                        numero,
                        nom_eleve, 
                        prenom_eleve, 
                        sexe_eleve,
                        date_naissance,
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
    <!-- Métadonnées de la page -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>

    <!-- Style personnalisé pour les composants -->
    <style>
        /* Conteneur des paramètres d'affichage */
        .parms {
            display: flex;
            margin-bottom: 10px;
        }

        .parms .col .p-ttl h3 {
            font-size: 18px;
        }

        .parms .col .b-opt {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding-left: 15px;
        }

        .parms .col .b-opt .option {
            display: flex;
            gap: 10px;
        }

        .parms .col .b-opt .option button {
            width: 20px;
            height: 20px;
            border-radius: 5px;
            background: none;
            cursor: pointer;
            transition: 1s ease-in-out;
        }

        /* Styles actifs au survol */
        .parms .col .b-opt .option button:hover,
        .parms .col .b-opt .option button.active {
            background: #009CFF;
        }

        .parms .col .b-opt .option h4 {
            font-size: 14px;
        }

        /* Tableau de données imprimable */
        .row-print {
            position: relative;
            width: 99%;
            top: -90px;
            left: 10px;
        }

        /* Largeur des colonnes du tableau */
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

        /* Masquer certaines colonnes (notes, date de naissance, statut) */
        table th:nth-child(5), 
        table td:nth-child(5),
        table th:nth-child(6), 
        table td:nth-child(6),
        table th:nth-child(7), 
        table td:nth-child(7),
        table th:nth-child(8), 
        table td:nth-child(8),
        table th:nth-child(9), 
        table td:nth-child(9) {
            display: none;
            transition: 1s ease-in-out;
        }

        /* Afficher les colonnes masquées lorsque la classe "show" est activée */
        table th:nth-child(5).show, 
        table td:nth-child(5).show,
        table th:nth-child(6).show, 
        table td:nth-child(6).show,
        table th:nth-child(7).show, 
        table td:nth-child(7).show,
        table th:nth-child(8).show, 
        table td:nth-child(8).show,
        table th:nth-child(9).show, 
        table td:nth-child(9).show {
            display: table-cell;
        }

        /* Style du bouton retour */
        a.return {
            background: #009CFF;
            color: #000;
        }

        /* Cacher les éléments lors de l'impression */
        @media print {
            .parms {
                display: none;
            }

            a.return,
            button.btn-print {
                display: none;
            }
        }
    </style>

    <!-- Liens vers les fichiers CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    
    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
        <div class="row-print">
            <?php if (count($classes) > 0):  ?>
                <!-- Section d'affichage des options -->
                <div class="parms">
                    <div class="col">
                        <div class="p-ttl"><h3>Affichages</h3></div>
                        <div class="b-opt">
                            <div class="option">
                                <button id="btnstatus"></button>
                                <h4>Status</h4>
                            </div>
                            <div class="option">
                                <button id="btndatebirth"></button>
                                <h4>Date de naissance</h4>
                            </div>
                            <div class="option">
                                <button id="btnnotes"></button>
                                <h4>Notes</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-ttl"><h3>Classements</h3></div>
                        <div class="b-opt">
                            <div class="option">
                                <button class="active" id="numberasc"></button>
                                <h4>N° Ascendant</h4>
                            </div>
                            <div class="option">
                                <button id="numberdsc"></button>
                                <h4>N° Dscendant</h4>
                            </div>
                            <div class="option">
                                <button id="nomasc"></button>
                                <h4>Nom Ascendant</h4>
                            </div>
                            <div class="option">
                                <button id="nomdsc"></button>
                                <h4>Nom Dscendant</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations sur la classe -->
                <div class="tp">
                    <div class="col-classe-l">
                        <?php foreach ( $classes as $classe ): ?>
                            <h1>Liste des Eleves dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                            <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                            <h3>Salle: <?php echo $classe['salle'] ?></h3>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tableau des élèves -->
                <div class="bd">
                    <?php if (count($eleves) > 0):  ?>
                        <table class="table">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Matricule</th>
                                <th class="col">Nom & Prénom</th>
                                <th class="col">Sexe</th>
                                <th class="col">Date naissance</th>
                                <th class="col">Status</th>
                                <th class="col">DS 1</th>
                                <th class="col">DS 2</th>
                                <th class="col">Examen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $eleves as $eleve ): ?>
                                <tr>
                                    <th scope="row">
                                        <?php 
                                            // Affichage du numéro d'ordre (numéro dans la classe)
                                            $numero = isset($eleve['numero']) ? (int) $eleve['numero'] : null;
                                            echo $numero;
                                        ?>
                                    </th>
                                    <td><?php echo $eleve['eleve_id'] ?></td>
                                    <td><?php echo $eleve['nom_eleve'] ?> <?php echo $eleve['prenom_eleve'] ?></td>
                                    <td><?php echo $eleve['sexe_eleve'] ?></td>
                                    <td>
                                        <?php 
                                            $date_birth_d = new DateTime($eleve['date_naissance']);
                                            $date_birth = $date_birth_d->format('d-m-Y');
                                            echo $date_birth;
                                        ?>
                                    </td>
                                    <td><?php echo $eleve['status']?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Message si aucun élève trouvé -->
                        <h5>Aucun élève trouvé</h5>
                    <?php endif; ?>
                </div>

                <!-- Boutons d'action -->
                <div class="bt" style="display: flex; gap: 10px;">
                    <button class="btn btn-print" onclick="print()" style="background: #009CFF; width: 100px; height: 35px; color: #000;">Imprimer</button>
                    <a style="height: 35px;" href="./eleves.php?id=<?php echo $id_classe ?>" class="btn return">Retour</a>
                </div>
            <?php else: ?>
                <!-- Message si aucun classe trouvée -->
                <h5>Aucun classe trouvé</h5>
            <?php endif; ?>
        </div>
    </div>

    <!-- Script pour gérer l'affichage dynamique des colonnes -->
<script>
    const btnstatus = document.getElementById('btnstatus')
    const btnDateBirth = document.getElementById("btndatebirth")
    const btnNotes = document.getElementById('btnnotes')

    const table = document.querySelector('table')
    const dateBirth = table.querySelectorAll('th:nth-child(5), td:nth-child(5)')
    const status = table.querySelectorAll('th:nth-child(6), td:nth-child(6)')
    const ds1 = table.querySelectorAll('table th:nth-child(7), table td:nth-child(7)')
    const ds2 = table.querySelectorAll('table th:nth-child(8), table td:nth-child(8)')
    const exam = table.querySelectorAll('table th:nth-child(9), table td:nth-child(9)')

    // Fonction pour afficher/masquer la date de naissance
    function toggleDateBirth () {
        dateBirth.forEach(el => el.classList.toggle("show"))
        btnDateBirth.classList.toggle('active')
    }

    // Fonction pour afficher/masquer le statut
    function toggleStatus () {
        status.forEach(el => el.classList.toggle("show"))
        btnstatus.classList.toggle('active')
    }

    // Fonction pour afficher/masquer les colonnes de notes
    function toggleNotes () {
        ds1.forEach(el => el.classList.toggle("show"))
        ds2.forEach(el => el.classList.toggle("show"))
        exam.forEach(el => el.classList.toggle("show"))
        btnNotes.classList.toggle('active')
    }

    // Événements de clic
    btnDateBirth.addEventListener('click', toggleDateBirth)
    btnstatus.addEventListener('click', toggleStatus)
    btnNotes.addEventListener('click', toggleNotes)
</script>

<!-- Fichier JS principal -->
<script src="../../js/main.js"></script>
</body>
</html>
