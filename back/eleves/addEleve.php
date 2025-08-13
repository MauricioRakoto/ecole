<?php
require_once "../database.php";

if (isset($_POST['enregistrer'])) {
    // Récupération des données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $lieu_naissance = $_POST['lieu_naissance'];
    $classe_id = $_POST['classe_id'];
    $status = $_POST['status'];
    $adresse = $_POST['adresse'];
    $tel = $_POST['tel'];
    $origine = $_POST['origine'];
    $annee_scolaire = $_POST['annee_scolaire'];
    $date_inscrit = $_POST['date_inscrit'];

    // Donnée du père
    $nom_pere = $_POST['nom_pere'];
    $profession_pere = $_POST['profession_pere'];
    $tel_pere = $_POST['tel_pere'];

    // Donnée de la mère
    $nom_mere = $_POST['nom_mere'];
    $profession_mere = $_POST['profession_mere'];
    $tel_mere = $_POST['tel_mere'];

    // Donnée du tuteur
    $nom_tuteur = $_POST['nom_tuteur'];
    $profession_tuteur = $_POST['profession_tuteur'];
    $tel_tuteur = $_POST['tel_tuteur'];

    // Vérification si l'élève existe déjà
    $check = $pdo->prepare("SELECT COUNT(*) 
        FROM eleves 
        WHERE nom_eleve = ? 
        AND prenom_eleve = ? 
        AND date_naissance = ? 
        AND classe_id = ? 
        AND annee_scolaire = ?
    ");
    $check->execute([
        $nom, 
        $prenom, 
        $date_naissance, 
        $classe_id, 
        $annee_scolaire
    ]);
    
    $exists = $check->fetchColumn();

    if ($exists > 0) {
        echo "
            <div style='color:red;'>
                <p>⚠ L'élève existe déjà dans cette classe pour cette année scolaire.</p>
                <a href='../../inscription'>Retour</a>
            </div>
        ";
    } else {
        // Requête d'insertion
        $sql = "INSERT INTO eleves (
            nom_eleve, 
            prenom_eleve, 
            sexe_eleve, 
            date_naissance, 
            lieu_naissance, 
            status, 
            adresse,
            tel_eleve,
            etablissement_orign,
            annee_scolaire,
            date_inscrit,
            nom_pere,
            profession_pere,
            tel_pere,
            nom_mere,
            profession_mere,
            tel_mere,
            nom_tuteur,
            profession_tuteur,
            tel_tuteur,
            classe_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom, 
            $prenom, 
            $sexe, 
            $date_naissance,
            $lieu_naissance,
            $status,
            $adresse,
            $tel,
            $origine,
            $annee_scolaire,
            $date_inscrit,
            $nom_pere,
            $profession_pere,
            $tel_pere,
            $nom_mere,
            $profession_mere,
            $tel_mere,
            $nom_tuteur,
            $profession_tuteur,
            $tel_tuteur,
            $classe_id
        ]);

        echo "
            <div style='color:green;'>
                <p>✅ Inscription terminée avec succès !</p>
                <a href='../../inscription'>Retour</a>
            </div>
        ";
    }
}
