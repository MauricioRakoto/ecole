<?php
    require_once "../database.php";

    if (isset($_POST['modifier']) && isset($_POST['numbers'])) {
        $numeros = $_POST['numbers'];

        // Préparation de la requète
        $stmt = $pdo->prepare("UPDATE eleves SET numero = ? WHERE eleve_id = ?");

        // Mise à jour pour chaque élève
        foreach ($numeros as $eleve_id => $nouveau_numero) {
            if (is_numeric($nouveau_numero)) {
                $stmt->execute([$nouveau_numero, $eleve_id]);
            }
        }

        echo "
                <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                        <div style='width: 300px; height: 100px; background:green; border-radius: 10px; padding: 20px'>
                            <p>Numéros mis à jour avec succés. </p>
                            <a href='../../classes/eleves.php?id='>Retour</a>
                        </div> 
                </div> 
        ";
    } else {
        echo "
            <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                <div style='width: 300px; height: 100px; background:red; border-radius: 10px; padding: 20px'>
                    <p>Aucune donnée reçue. </p>
                    <a href='../../classes/add.php'>Retour</a>
                </div> 
            </div> 
        ";
    }