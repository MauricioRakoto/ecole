<?php
require "../database.php";
session_start();

// Vérifier si responsable est connecté
if (!isset($_SESSION['responsable_id'])) {
    header("Location: ../signin.php");
    exit();
}

// Récupérer les eleve_id à supprimer
$matieres = $_POST['matieres'] ?? [];

if (!empty($matieres)) {
    // Préparer la requête avec IN pour plusieurs IDs
    $placeholders = implode(',', array_fill(0, count($matieres), '?'));
    $sql = "DELETE FROM matieres WHERE matiere_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);

    // Ajouter la classe à la fin des paramètres
    $params = array_merge($matieres);
    if ($stmt->execute($params)) {
        $_SESSION['message'] = count($matieres) . " matieres supprimés avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression.";
    }
} else {
    $_SESSION['message'] = "Aucun élève sélectionné.";
}

// Rediriger vers la page de la classe
header("Location: ../../matieres/");
exit();
?>
