<?php 

    require "./back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    // Récupérer les 20 derniers élèves inscrits
    $sql = "SELECT a.*,
            b.classe_id,
            b.nom_classe
            FROM eleves a
            LEFT JOIN classes b 
            ON a.classe_id = b.classe_id
            ORDER BY a.eleve_id DESC 
            LIMIT 20";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $eleves = $stmt->fetchAll();

    // Récupérer un compte
    $sql_compte = "SELECT image FROM responsable WHERE responsable_id = ?";
    $stmt_compte = $pdo->prepare($sql_compte);
    $stmt_compte->execute([$_SESSION['responsable_id']]);
    $comptes = $stmt_compte->fetchAll();


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
                        <?php if ($_SESSION['compte'] == "Surveillant"):  ?>
                            <li>
                                <a href="./inscription.php" class="nav-link">Inscription</a>
                            </li>
                            <li>
                                <a href="./matieres/" class="nav-link">Matières</a>
                            </li>
                        <?php endif; ?>
                        
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
                                <th class="col">Classes</th>
                                <th class="col">Date Inscrit</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $eleves as $eleve ): ?>
                                <tr>
                                    <th scope="row">
                                        <?= $eleve['eleve_id'] ?>
                                    </th>
                                    <td>
                                        <?=  $eleve['nom_eleve'] ?>
                                        <?=  $eleve['prenom_eleve'] ?>
                                    </td>
                                    <td>
                                        <?=  $eleve['sexe_eleve'] ?>
                                    </td>
                                    <td>
                                        <?= $eleve['nom_classe']; ?>
                                    </td>

                                    <td>
                                        <?php 
                                        
                                            $get_date_inscrit = new DateTime($eleve['date_inscrit']);
                                            $date_inscrit = $get_date_inscrit->format('d-m-Y');
                                        
                                            echo $date_inscrit;

                                        ?>
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