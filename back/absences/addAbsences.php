<?php
  require_once "../database.php";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matiere_id = $_POST['matiere_id'];
    $classe_id = $_POST['classe_id'];
    $date_today = date("Y-m-d");
    $eleve_ids = $_POST['eleve_id'];
    $minutes = $_POST['minutes'];

    foreach ($eleve_ids as $index => $eleve_id) {
        $minute = intval($minutes[$index]);

        if ($minute > 0) {
            $stmt = $pdo->prepare("INSERT INTO absences (
                                    eleve_id, 
                                    classe_id, 
                                    matiere_id, 
                                    minutes, 
                                    date_absence) 
                                    VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $eleve_id, 
                $classe_id, 
                $matiere_id, 
                $minute, 
                $date_today
            ]);
        }
    }

     // Affichage d’un message de succès
     echo "
        <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                 <div style='width: 500px; height: 100px; background:green; border-radius: 10px; padding: 20px'>
                         <h4>Classe ajoutée avec succès ! </h4>
                         <a href='../../classes/absences.php?id=". $classe_id ."'>Retour</a>
                 </div> 
        </div> 
    ";
} else {
     // Affichage d’un message de succès
     echo "
        <div style='width: 100%; display: flex; justify-content: center; align-items: center'>
                 <div style='width: 500px; height: 100px; background: red; border-radius: 10px; padding: 20px'>
                         <h4>Méthode non autorisée. ! </h4>
                         <a href='../../classes/absences.php?id=". $classe_id ."'>Retour</a>
                 </div> 
        </div> 
    ";
    
}