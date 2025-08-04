<?php 

    require "./back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    // Récupérer les 20 derniers élèves inscrits
    $sql = "SELECT eleve_id, 
                nom_eleve, 
                prenom_eleve, 
                sexe_eleve, 
                date_inscrit
            FROM eleves 
            ORDER BY eleve_id DESC 
            LIMIT 20";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $eleves = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<?php require './includes/head.php' ?>
<body>
    
    <?php require './includes/navbar.php' ?>
    
    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
        
        <div class="sidebar" style="width: 200px; padding: 0 20px; " >
            <nav class="navbar bg-light">
                <div class="navbar-nav w-100" style="margin-top: 25px">
                    <ul>
                        <li>
                            <a href="home.php" class="nav-link active">Accueil</a>
                        </li>
                        <li>
                            <a href="./classes/" class="nav-link">Classes</a>
                        </li>
                        <li>
                            <a href="./inscription.php" class="nav-link">Inscription</a>
                        </li>
                        <li>
                            <a href="./matieres/" class="nav-link">Matières</a>
                        </li>
                        <li>
                            <a href="index.php" class="nav-link">Bulletins</a>
                        </li>
                    </ul>
                    
                    
                </div>
            </nav>
        </div>

        <div class="col-right">
            <div class="tp">
                <div class="col-classe-l">
                    <h1>Liste des Inscripts</h1>
                </div>
            </div>

            <div class="bd" style="margin-top: 0">

                <?php if (count($eleves) > 0):  ?>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col">Matricule</th>
                                <th class="col">Nom & Prénom</th>
                                <th class="col">Sexe</th>
                                <th class="col">Date Inscrit</th>
                                <th class="col">Classes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $eleves as $eleve ): ?>
                                <tr>
                                    <th scope="row">
                                        <?php echo $eleve['eleve_id'] ?>
                                    </th>
                                    <td>
                                        <?php echo $eleve['nom_eleve'] ?>
                                        <?php echo $eleve['prenom_eleve'] ?>
                                    </td>
                                    <td>
                                        <?php echo $eleve['sexe_eleve'] ?>
                                    </td>
                                    <td>
                                        <?php echo $eleve['date_inscrit'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h5>Aucun élève inscrit</h5>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php require './includes/footer.php' ?>
</body>
</html>