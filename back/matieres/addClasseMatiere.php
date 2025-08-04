<?php
// Inclusion du fichier de connexion à la base de données
require_once "../database.php";

// Vérifie si le formulaire a été soumis
if (isset($_POST['enregistrer'])) {
     // Récupération des données envoyées par le formulaire via la méthode POST
     $matiere_id = $_POST['matiere_id'];

     // Préparation d’une requête SQL pour vérifier si le nom de la classe existe déjà
     $sql_matiere_id = "SELECT matiere FROM classes WHERE matiere = ?";
     $stmt_matiere_id = $pdo->prepare($sql_matiere_id);
     $stmt_matiere_id->execute([$matiere_id]);

     // Si aucune classe avec ce nom n'existe, on procède à l'insertion
     if ( $stmt_matiere_id->rowCount() == 0 ) {
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO matieres (classes)
        VALUES (?)";
        $stmt = $pdo->prepare($sql);

        // Exécution de la requête avec les données du formulaire
        $stmt->execute([$matiere_id]);

        // Affichage d’un message de succès
        echo "
                <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                        <div style='width: 300px; height: 100px; background:green; border-radius: 10px; padding: 20px'>
                                <h1> Matière ajoutée avec succès ! </h1>
                                <a href='../../matieres/add.php'>Retour</a>
                        </div> 
                </div> 
        ";
     } else {
        // Si le nom de la classe existe déjà, on affiche un message d'erreur
        echo "
                <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                        <div style='width: 300px; height: 100px; background:red; border-radius: 10px; padding: 20px'>
                                <h1>Le nom de la matiere existe </h1>
                                <a href='../../matieres/add.php'>Retour</a>
                        </div> 
                </div> 
     ";
     }
}
