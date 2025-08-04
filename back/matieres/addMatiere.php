<?php
// Inclusion du fichier de connexion à la base de données
require_once "../database.php";

// Vérifie si le formulaire a été soumis
if (isset($_POST['enregistrer'])) {
     // Récupération des données envoyées par le formulaire via la méthode POST
     $nom_matiere = $_POST['nom_matiere'];
     $coefficient = $_POST['coefficient'];

     // Préparation d’une requête SQL pour vérifier si le nom de la classe existe déjà
     $sql_nom_matiere = "SELECT nom_matiere FROM matieres WHERE nom_matiere = ?";
     $stmt_nom_matiere = $pdo->prepare($sql_nom_matiere);
     $stmt_nom_matiere->execute([$nom_matiere]);

     // Si aucune classe avec ce nom n'existe, on procède à l'insertion
     if ( $stmt_nom_matiere->rowCount() == 0 ) {
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO matieres (nom_matiere, coefficient)
        VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);

        // Exécution de la requête avec les données du formulaire
        $stmt->execute([
                $nom_matiere,
                $coefficient
        ]);

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
                                <h1>Le nom de la classe existe </h1>
                                <a href='../../matieres/add.php'>Retour</a>
                        </div> 
                </div> 
     ";
     }
}
