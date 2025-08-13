<?php
require "../database.php";
session_start();

// Vérifier si responsable est connecté
if (!isset($_SESSION['responsable_id'])) {
    header("Location: ../signin.php");
    exit();
}

// Récupérer la classe
$id_classe = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les eleve_id à supprimer
$eleves = $_POST['eleves'] ?? [];

if (!empty($eleves)) {
    // Préparer la requête avec IN pour plusieurs IDs
    $placeholders = implode(',', array_fill(0, count($eleves), '?'));
    $sql = "DELETE FROM eleves WHERE eleve_id IN ($placeholders) AND classe_id = ?";
    $stmt = $pdo->prepare($sql);

    // Ajouter la classe à la fin des paramètres
    $params = array_merge($eleves, [$id_classe]);
    if ($stmt->execute($params)) {
        $_SESSION['message'] = count($eleves) . " élèves supprimés avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression.";
    }
} else {
    $_SESSION['message'] = "Aucun élève sélectionné.";
}

// Rediriger vers la page de la classe
header("Location: ../supprimer.php?id=" . $id_classe);
exit();
?>
