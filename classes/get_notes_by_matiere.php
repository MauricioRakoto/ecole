<?php
require "../back/database.php";

// if (!isset($_GET['matiere_id'])) {
//     echo json_encode([]);
//     exit;
// }

$matiere_id = $_GET['m'];
$id = $_GET['id'];
$s = $_GET['s'];

$sql = "SELECT 
        e.eleve_id,
        e.numero,
        e.nom_eleve,
        e.prenom_eleve,
        e.sexe_eleve,
        MAX(CASE WHEN n.type = 'DS 1' THEN n.note END) AS ds1,
        MAX(CASE WHEN n.type = 'DS 2' THEN n.note END) AS ds2,
        MAX(CASE WHEN n.type = 'Exam' THEN n.note END) AS exam,
        ROUND((
            COALESCE(MAX(CASE WHEN n.type = 'DS 1' THEN n.note END), 0) +
            COALESCE(MAX(CASE WHEN n.type = 'DS 2' THEN n.note END), 0) +
            COALESCE(MAX(CASE WHEN n.type = 'Exam' THEN n.note END), 0)
        ) / 
        NULLIF(
            (CASE WHEN MAX(CASE WHEN n.type = 'DS 1' THEN n.note END) IS NOT NULL THEN 1 ELSE 0 END +
             CASE WHEN MAX(CASE WHEN n.type = 'DS 2' THEN n.note END) IS NOT NULL THEN 1 ELSE 0 END +
             CASE WHEN MAX(CASE WHEN n.type = 'Exam' THEN n.note END) IS NOT NULL THEN 1 ELSE 0 END),
            0
        ), 2) AS moyenne
    FROM notes n
    JOIN eleves e ON n.eleve_id = e.eleve_id
    JOIN classes c ON n.classe_id = c.classe_id
    WHERE n.matiere_id = ? AND n.classe_id = ? AND n.session = ?
    GROUP BY e.eleve_id, e.numero, e.nom_eleve, e.prenom_eleve, e.sexe_eleve, c.nom_classe
    ORDER BY e.nom_eleve
";

$stmt = $pdo->prepare($sql);
$stmt->execute(
    [
        $matiere_id,
        $id,
        $s
    ]
);

$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($notes);
