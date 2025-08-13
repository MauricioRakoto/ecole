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

       /* Par défaut, on cache Date naissance (5), Status (6) et Notes (7) */
table th:nth-child(5), table td:nth-child(5),
table th:nth-child(6), table td:nth-child(6),
table th:nth-child(7), table td:nth-child(7) {
    display: none;
}

/* Quand on ajoute .show -> affichage */
table th:nth-child(5).show, table td:nth-child(5).show,
table th:nth-child(6).show, table td:nth-child(6).show,
table th:nth-child(7).show, table td:nth-child(7).show {
    display: table-cell;
}

/* Juste pour marquer le bouton actif */
button.active {
    background-color: #007bff;
    color: white;
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
                                <button class="active" id="numberasc"> </button>
                                <h4>N° Ascendant</h4>
                               
                            </div>
                            <div class="option">
                                <button id="numberdsc"></button>
                                <h4>N° Dscendant</h4>
                                
                            </div>
                            <div class="option">
                                <button id="nomasc"> </button>
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
                        <table class="table" id="elevesTable">
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
                           
                        </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Message si aucun élève trouvé -->
                        <h5>Aucun élève trouvé</h5>
                    <?php endif; ?>
                </div>

                <!-- Boutons d'action -->
                <div class="bt" style="margin-top: 30px;display: flex; gap: 10px;">
                    <button class="btn btn-print" onclick="print()" style="background: #009CFF; width: 100px; height: 35px; color: #000;">
                        Imprimer
                    </button>
                    <button class="btn btn-print" onclick="exportTableToExcel()" style="background: #009CFF; width: 200px; height: 35px; color: #000;">
                        Exporter en Excel
                    </button>

                    <button class="btn btn-print" onclick="exportTableToPDF()" style="background: #009CFF; width: 200px; height: 35px; color: #000;">
                        Exporter en PDF
                    </button>
                <a style="height: 35px;" href="./eleves.php?id=<?php echo $id_classe ?>" class="btn return">Retour</a>
                </div>
            <?php else: ?>
                <!-- Message si aucun classe trouvée -->
                <h5>Aucun classe trouvé</h5>
            <?php endif; ?>
        </div>
    </div>

  

    <!-- Fichier JS principal -->
    <script src="../../js/main.js"></script>
    <script src="../assets/js/xlsx.full.min.js"></script>
    <script src="../assets/js/jspdf.umd.min.js"></script>
    <script src="../assets/js/jspdf.plugin.autotable.min.js"></script>
    <script>
        // Transfert des données PHP vers JS
        const elevesData = <?php echo json_encode($eleves); ?>;
    </script>

    <script>
    const tbody = document.querySelector("tbody");

    function renderEleves(data) {
        tbody.innerHTML = ""; // Vider le tableau
        data.forEach((eleve, index) => {
            const dateNaissance = new Date(eleve.date_naissance).toLocaleDateString('fr-FR');

            tbody.innerHTML += `
                <tr>
                    <th scope="row">${eleve.numero ?? ""}</th>
                    <td>${eleve.eleve_id}</td>
                    <td>${eleve.nom_eleve} ${eleve.prenom_eleve}</td>
                    <td>${eleve.sexe_eleve}</td>
                    <td>${dateNaissance}</td>
                    <td>${eleve.status}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            `;
        });
    }



    // Affichage initial
    renderEleves(elevesData);

    // Boutons
    const btnNumberAsc = document.getElementById("numberasc");
    const btnNumberDsc = document.getElementById("numberdsc");
    const btnNomAsc = document.getElementById("nomasc");
    const btnNomDsc = document.getElementById("nomdsc");

    // Tri numéro ascendant
    btnNumberAsc.addEventListener("click", () => {
        const sorted = [...elevesData].sort((a, b) => (a.numero ?? 0) - (b.numero ?? 0));
        renderEleves(sorted);
    });

    // Tri numéro descendant
    btnNumberDsc.addEventListener("click", () => {
        const sorted = [...elevesData].sort((a, b) => (b.numero ?? 0) - (a.numero ?? 0));
        renderEleves(sorted);
    });

    // Tri nom ascendant
    btnNomAsc.addEventListener("click", () => {
        const sorted = [...elevesData].sort((a, b) => a.nom_eleve.localeCompare(b.nom_eleve));
        renderEleves(sorted);
    });

    // Tri nom descendant
    btnNomDsc.addEventListener("click", () => {
        const sorted = [...elevesData].sort((a, b) => b.nom_eleve.localeCompare(a.nom_eleve));
        renderEleves(sorted);
    });



    // Sélectionner tous les boutons de classement
    const sortButtons = [
        document.getElementById('numberasc'),
        document.getElementById('numberdsc'),
        document.getElementById('nomasc'),
        document.getElementById('nomdsc')
    ];

    // Ajouter un gestionnaire d'événement à chacun
    sortButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Supprimer la classe active de tous les boutons
            sortButtons.forEach(btn => btn.classList.remove('active'));

            // Ajouter la classe active au bouton cliqué
            button.classList.add('active');
        });
    });


    </script>


    <script>
       function exportTableToExcel() {
        const table = document.getElementById("elevesTable");
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Élèves" });
        XLSX.writeFile(workbook, "liste_eleves.xlsx");
    }
    </script>

    <script>
    async function exportTableToPDF() {
    const { jsPDF } = window.jspdf;

    const doc = new jsPDF();

    doc.setFontSize(14);
    doc.text("Liste des élèves", 14, 15);

    doc.autoTable({
        html: '#elevesTable',
        startY: 20,
        styles: {
            fontSize: 10,
            cellPadding: 3
        },
        headStyles: {
            fillColor: [0, 156, 255],
            textColor: 0,
            halign: 'center',
            valign: 'middle'
        },
        bodyStyles: {
            halign: 'left'
        }
    });

    doc.save("liste_eleves.pdf");
    }
    </script>

    <script>

const table = document.getElementById('elevesTable');

const btnDateBirth = document.getElementById("btndatebirth");
const btnstatus = document.getElementById('btnstatus');
const btnnotes = document.getElementById('btnnotes');

// Sélection colonnes
const dateBirth = table.querySelectorAll('th:nth-child(5), td:nth-child(5)');
const status = table.querySelectorAll('th:nth-child(6), td:nth-child(6)');
const notes = table.querySelectorAll('th:nth-child(7), td:nth-child(7)');

// Fonctions
function toggleColumn(columnElements, button) {
    columnElements.forEach(el => el.classList.toggle("show"));
    button.classList.toggle('active');
}

// Événements
btnDateBirth.addEventListener('click', () => toggleColumn(dateBirth, btnDateBirth));
btnstatus.addEventListener('click', () => toggleColumn(status, btnstatus));
btnnotes.addEventListener('click', () => toggleColumn(notes, btnnotes));


    </script>


</body>
</html>
