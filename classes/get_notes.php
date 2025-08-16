<?php
require "../back/database.php";
header('Content-Type: application/json');

$id_classe = isset($_GET['id']) ? intval($_GET['id']) : 0;
$session = isset($_GET['s']) ? intval($_GET['s']) : 1;

$sql = "SELECT 
            e.eleve_id, 
            e.numero, 
            e.nom_eleve, 
            e.prenom_eleve, 
            e.sexe_eleve,
            n.classe_id,
            m.matiere_id,
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
        INNER JOIN eleves e ON n.eleve_id = e.eleve_id
        INNER JOIN matieres m ON n.matiere_id = m.matiere_id
        WHERE n.classe_id = ? AND n.session = ?
        GROUP BY e.eleve_id, m.matiere_id
        ORDER BY e.nom_eleve, m.nom_matiere";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_classe, $session]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper par élève
$eleves = [];
foreach ($rows as $row) {
    $id = $row['eleve_id'];

    if (!isset($eleves[$id])) {
        $eleves[$id] = [
            "eleve_id" => $row["eleve_id"],
            "numero" => $row["numero"],
            "nom_eleve" => $row["nom_eleve"],
            "prenom_eleve" => $row["prenom_eleve"],
            "sexe_eleve" => $row["sexe_eleve"],
            "classe_id" => $row["classe_id"],
            "matieres" => []
        ];
    }

    $eleves[$id]["matieres"][] = [
        "matiere_id" => $row["matiere_id"],
        "nom_matiere" => $row["nom_matiere"],
        "coefficient" => $row["coefficient"],
        "ds1" => $row["ds1"],
        "ds2" => $row["ds2"],
        "exam" => $row["exam"],
        "moyenne" => $row["moyenne"]
    ];
}

echo json_encode(array_values($eleves));
