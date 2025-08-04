<?php
  
    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'ecole';
    $username = 'root';
    $password = ''; // à adapter selon ton WAMP/XAMPP

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }  catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
    }
    