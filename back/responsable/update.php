<?php
require "../database.php";
session_start();

if (!isset($_SESSION['responsable_id'])) {
    header("Location: signin.php");
    exit();
}

$responsable_id = $_SESSION['responsable_id'];



// Traitement de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $compte = $_POST['compte'];
    $image_name = "";

    

    // Upload image si un fichier est envoyé
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../assets/img/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
       
    }

    // Met à jour les données (et image si fournie)
    if (!empty($image_name)) {
        $sql = "UPDATE responsable 
                    SET nom_responsable = ?, 
                    prenom_responsable = ?, 
                    username = ?, 
                    compte = ?, 
                    image = ? 
                WHERE responsable_id = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom, 
            $prenom, 
            $username, 
            $compte, 
            $image_name, 
            $responsable_id
        ]);
    } else {
        $sql = "UPDATE responsable 
            SET nom_responsable = ?, 
                prenom_responsable = ?, 
                username = ?, 
                compte = ? 
            WHERE responsable_id = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom, 
            $prenom, 
            $username, 
            $compte, 
            $responsable_id
        ]);
    }

    // Mise à jour session
    $_SESSION['nom_responsable'] = $nom;
    $_SESSION['prenom_responsable'] = $prenom;
    $_SESSION['username'] = $username;
    $_SESSION['compte'] = $compte;

    header("Location: ../../home" . ".php"); // Recharge la page
    exit();
}

// // Récupération du compte actuel
// $sql = "SELECT * FROM responsable WHERE responsable_id = ?";
// $stmt = $pdo->prepare($sql);
// $stmt->execute([$responsable_id]);
// $compte = $stmt->fetch();