<?php
require "../back/database.php";
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['responsable_id'])) {
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$id_classe = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$session = isset($_GET['s']) ? (int) $_GET['s'] : 1;

if ($id_classe <= 0) {
    echo json_encode(['error' => 'Classe invalide']);
    exit();
}

$sql_notes = "SELECT 
                n.note, 
                n.session,
                e.eleve_id, 
                e.numero, 
                e.nom_eleve, 
                e.prenom_eleve, 
                e.sexe_eleve,
              m.nom_matiere
              FROM notes n
              INNER JOIN eleves e ON n.eleve_id = e.eleve_id
              INNER JOIN matieres m ON n.matiere_id = m.matiere_id
              WHERE n.classe_id = ? AND n.session = ?
              ORDER BY e.nom_eleve, m.nom_matiere";

$stmt = $pdo->prepare($sql_notes);
$stmt->execute([$id_classe, $session]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper les notes par élève
$eleves = [];

foreach ($rows as $row) {
    $id = $row['eleve_id'];

    if (!isset($eleves[$id])) {
        $eleves[$id] = [
            'eleve_id' => $row['eleve_id'],
            'numero' => $row['numero'],
            'nom_eleve' => $row['nom_eleve'],
            'prenom_eleve' => $row['prenom_eleve'],
            'sexe_eleve' => $row['sexe_eleve'],
            'notes' => []
        ];
    }

    $eleves[$id]['notes'][] = [
        'matiere' => $row['nom_matiere'],
        'note' => $row['note']
    ];
}

// Renvoyer un tableau indexé (pas associatif)
$eleves = array_values($eleves);

echo json_encode($eleves, JSON_UNESCAPED_UNICODE);
