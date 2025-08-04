<?php
// Inclusion du fichier de connexion à la base de données
require_once "../database.php";

// Vérifie si le formulaire a été soumis
if (isset($_POST['enregistrer'])) {
     // Récupération des données envoyées par le formulaire via la méthode POST
     $nom_classe = $_POST['nom_classe'];
     $niveau = $_POST['niveau'];
     $annee_debut = $_POST['annee_debut'];
     $annee_fin = $_POST['annee_fin'];
     $salle = $_POST['salle'];

     // Préparation d’une requête SQL pour vérifier si le nom de la classe existe déjà
     $sql_nom_classe = "SELECT nom_classe FROM classes WHERE nom_classe = ?";
     $stmt_nom_classe = $pdo->prepare($sql_nom_classe);
     $stmt_nom_classe->execute([$nom_classe]);

     // Si aucune classe avec ce nom n'existe, on procède à l'insertion
     if ( $stmt_nom_classe->rowCount() == 0 ) {
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO classes (nom_classe, niveau, annee_debut, annee_fin, salle)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Exécution de la requête avec les données du formulaire
        $stmt->execute([$nom_classe, $niveau, $annee_debut, $annee_fin, $salle]);

        // Affichage d’un message de succès
        echo "
                <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                        <div style='width: 300px; height: 100px; background:green; border-radius: 10px; padding: 20px'>
                                <p>Classe ajoutée avec succès ! </p>
                                <a href='../../classes/add.php'>Retour</a>
                        </div> 
                </div> 
        ";
     } else {
        // Si le nom de la classe existe déjà, on affiche un message d'erreur
        echo "
                <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                        <div style='width: 300px; height: 100px; background:red; border-radius: 10px; padding: 20px'>
                                <p>Le nom de la classe existe </p>
                                <a href='../../classes/add.php'>Retour</a>
                        </div> 
                </div> 
     ";
     }
}
