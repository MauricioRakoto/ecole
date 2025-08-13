<?php
require "../back/database.php";
session_start();

header('Content-Type: application/json');

// Récupérer paramètres URL
$id_eleve = isset($_GET['ide']) ? intval($_GET['ide']) : 0;
$session = isset($_GET['s']) ? intval($_GET['s']) : 0;

$sql = "SELECT 
    m.nom_matiere,
    m.coefficient,
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
JOIN matieres m ON n.matiere_id = m.matiere_id
WHERE n.session = ?  
  AND e.eleve_id = ?   
GROUP BY m.nom_matiere, m.coefficient
ORDER BY m.nom_matiere;
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$session, $id_eleve]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner en JSON
echo json_encode($notes);
