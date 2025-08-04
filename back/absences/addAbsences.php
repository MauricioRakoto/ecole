<?php
   require "../back/database.php";

   // Traitement de l'insertion si le formulaire est soumis
   if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['absences'])) {
       $stmt = $pdo->prepare("INSERT INTO absences (id_eleve, id_matiere, minutes, date) VALUES (?, ?, ?, ?)");
   
       foreach ($_POST['absences'] as $absence) {
           if (!empty($absence['id_eleve']) && !empty($absence['id_matiere']) && !empty($absence['minutes']) && !empty($absence['date'])) {
               $stmt->execute([
                   $absence['id_eleve'],
                   $absence['id_matiere'],
                   $absence['minutes'],
                   $absence['date']
               ]);
           }
       }
   
       echo "<p>Absences enregistrées avec succès.</p>";
   }