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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ecole</title>
    <!-- Bootstrap 5 CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <?php if (count($classes) > 0): ?> 
            <?php foreach ( $classes as $classe ): ?>
            <div class="col-classe-l">
            <div class="notes-top mb-4">
                <h1 class="mb-3" id="bulletinTitle">Bulletin des notes 1è Trimestre <?php echo $classe['nom_classe'] ?></h1>
                <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?></h3>
            </div>

            <div class="eleve-top mb-4 row">
                <div class="col-md-3 mb-2"><h5>Matricule: 1</h5></div>
                <div class="col-md-3 mb-2"><h5>Nom et Prénom: Exemple</h5></div>
                <div class="col-md-3 mb-2"><h5>Classe: Exemple</h5></div>
                <div class="col-md-3 mb-2"><h5>Nombre d'absences: 0</h5></div>
            </div>

            <div id="notesContainer">

            </div>
            </div>
        <?php endforeach; ?>

        <?php else: ?>
        <!-- Message si aucune classe trouvée -->
        <h5>Aucun classe trouvé</h5>
        <?php endif; ?>
    </div>

    <!-- <script>
        async function fetchNotes() {
            // Récupérer idc et s depuis l'URL
            const params = new URLSearchParams(window.location.search);
            const idc = params.get('idc');
            const s = params.get('s') || 1;
        
            const response = await fetch(`get_notes_bulletin.php?idc=${idc}&s=${s}`);
            const data = await response.json();
        
            afficherNotes(data);
        }

        function afficherNotes(data) {
            const container = document.getElementById('notesContainer');
            let html = '';
        
            data.forEach(eleve => {
                html += `<h3>${eleve.nom_eleve} ${eleve.prenom_eleve} (Matricule: ${eleve.numero})</h3>`;
                html += `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>DS 1</th>
                            <th>DS 2</th>
                            <th>Moyenne DS</th>
                            <th>Exam</th>
                            <th>Coef</th>
                            <th>Total</th>
                            <th>Appréciations</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
            
                eleve.notes.forEach(note => {
                    html += `
                        <tr>
                            <td>${note.nom_matiere}</td>
                            <td>${note.ds1 ?? ''}</td>
                            <td>${note.ds2 ?? ''}</td>
                            <td>${note.moyenne_ds ?? ''}</td>
                            <td>${note.exam ?? ''}</td>
                            <td>${note.coef ?? ''}</td>
                            <td>${note.total ?? ''}</td>
                            
                        </tr>
                    `;
                });
            
                html += `</tbody></table>`;
            });
        
            container.innerHTML = html;
        }

        fetchNotes();
    </script> -->

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
        const ide = getParameterByName('ide');
        const s = getParameterByName('s');
    
        if (ide && s) {
          fetch(`get_notes_bulletin.php?ide=${ide}&s=${s}`)
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
                <th>Matière</th>
                <th>DS 1</th>
                <th>DS 2</th>
                <th>Moyenne DS</th>
                <th>Exam</th>
                <th>Coef</th>
                <th>Total</th>
                <th>Appréciations</th>
              </tr>
            </thead>
            <tbody>
        `;

        let sommeTotaux = 0;
    let nombreMatieres = data.length;
    let totalCoef = 0;

    data.forEach(note => {
        let coef = parseFloat(note.coefficient) || 0;
        totalCoef += coef;

        let ds1 = parseFloat(note.ds1) || 0;
        let ds2 = parseFloat(note.ds2) || 0;
        let exam = parseFloat(note.exam) || 0;

        let dsTotal = (ds1 + ds2) / 2;
        let dsTotalCof = dsTotal * coef;
        let examCof = exam * coef;
        let totalNoteMat = (dsTotalCof + examCof) / 3;
        totalNoteMat = Math.round(totalNoteMat);

        sommeTotaux += totalNoteMat;

        let evaluation = "";
        if (totalNoteMat < 10) evaluation = "Très faible";
        else if (totalNoteMat < 12) evaluation = "Passable";
        else evaluation = "Très bien";

        html += `
          <tr>
            <td>${note.nom_matiere ?? '-'}</td>
            <td>${note.ds1 ?? '-'}</td>
            <td>${note.ds2 ?? '-'}</td>
            <td>${dsTotal}</td>
            <td>${note.exam ?? '-'}</td>
            <td>${note.coefficient ?? '-'}</td>
            <td>${totalNoteMat}</td>
            <td>${evaluation}</td>
          </tr>
        `;
    });

    // Calcul de la moyenne générale
    let moyenneGenerale = nombreMatieres > 0 ? (sommeTotaux / nombreMatieres).toFixed(2) : 0;
    let evalGenerale = "";
    if (moyenneGenerale < 10) evalGenerale = "Très faible";
    else if (moyenneGenerale < 12) evalGenerale = "Passable";
    else evalGenerale = "Très bien";

    html += `
        <tr>
            <th>Total Général</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>${totalCoef}</th>
            <th>${moyenneGenerale}</th>
            <th>${evalGenerale}</th>
        </tr>
        
    </tbody>
    </table>
    `;

    container.innerHTML = html;
      }


  // Récupérer la session 's' depuis l'URL
const sessionS = getParameterByName('s');

// Modifier le titre du bulletin selon la session
if (sessionS) {
    const bulletinTitle = document.getElementById('bulletinTitle');
    let sessionText = '';
    switch(sessionS) {
        case '1':
            sessionText = "1er Trimestre";
            break;
        case '2':
            sessionText = "2ème Trimestre";
            break;
        case '3':
            sessionText = "3ème Trimestre";
            break;
        default:
            sessionText = "Trimestre inconnu";
    }
    bulletinTitle.textContent = `Bulletin des notes ${sessionText} ${<?php echo json_encode($classe['nom_classe']); ?>}`;
}
        

  </script>
</body>
</html>
