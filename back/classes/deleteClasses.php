<?php
require "../database.php";
session_start();

// Vérifier si responsable est connecté
if (!isset($_SESSION['responsable_id'])) {
    header("Location: ../signin.php");
    exit();
}

// Récupérer les eleve_id à supprimer
$classes = $_POST['classes'] ?? [];

if (!empty($classes)) {
    // Préparer la requête avec IN pour plusieurs IDs
    $placeholders = implode(',', array_fill(0, count($classes), '?'));
    $sql = "DELETE FROM classes WHERE classe_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);

    // Ajouter la classe à la fin des paramètres
    $params = array_merge($classes);
    if ($stmt->execute($params)) {
        $_SESSION['message'] = count($classes) . " classes supprimés avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression.";
    }
} else {
    $_SESSION['message'] = "Aucun élève sélectionné.";
}

// Rediriger vers la page de la classe
header("Location: ../../classes/");
exit();
?>
