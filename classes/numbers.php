<?php 

    require "../back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }


    $id_classe = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($_SESSION['compte'] !== "Surveillant") {
        header("Location: eleves" . ".php" . "?" . "id" . "=" . $id_classe);
    }

    $sql_classes = "SELECT * FROM classes WHERE classe_id = ? ";
    $stmt_classes = $pdo->prepare($sql_classes);
    $stmt_classes->execute([$id_classe]);
    $classes = $stmt_classes->fetchAll();

    $sql_eleves = "SELECT eleve_id,  numero, nom_eleve, prenom_eleve, sexe_eleve FROM eleves WHERE classe_id = ? ORDER BY eleve_id ASC";
    $stmt_eleves = $pdo->prepare($sql_eleves);
    $stmt_eleves->execute([$id_classe]);
    $eleves = $stmt_eleves->fetchAll();

     // Récupérer un compte
     $sql_compte = "SELECT image FROM responsable WHERE responsable_id = ?";
     $stmt_compte = $pdo->prepare($sql_compte);
     $stmt_compte->execute([$_SESSION['responsable_id']]);
     $comptes = $stmt_compte->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<?php require '../includes/head.php' ?>
<body>
    <?php require '../includes/navbar.php' ?>

    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
        
        <div class="sidebar" style="width: 200px; padding: 0 20px; " >
            <nav class="navbar bg-light">
                <div class="navbar-nav w-100" style="margin-top: 25px">
                    <div class="nav-top">
                        <div class="nav-l">
                            <h3>Classe</h3>
                        </div>
                        <div class="nav-r">
                            <a class="df-jc-ac" href="./">X</a>
                        </div>
                    </div>
                    <ul>
                        <li>
                            <a href="./eleves.php?id=<?php echo $id_classe ?>" class="nav-link ">Eleves</a>
                        </li>
                        <li>
                            <a href="./numbers.php?id=<?php echo $id_classe ?>" class="nav-link active">Numéros</a>
                        </li>
                        <li>
                            <a href="./absences.php?id=<?php echo $id_classe ?>" class="nav-link">Absences</a>
                        </li>
                        <li>
                            <a href="./cours.php?id=<?php echo $id_classe ?>" class="nav-link">Cours</a>
                        </li>
                        <li>
                            <a href="./notes.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Notes</a>
                        </li>
                        <li>
                            <a href="./renvoyer.php?id=<?php echo $id_classe ?>" class="nav-link">Renvoyer</a>
                        </li>
                        <li>
                            <a href="./bulletins.php?id=<?php echo $id_classe ?>&s=1" class="nav-link">Bulletins</a>
                        </li>
                        <li>
                            <a href="./supprimer.php?id=<?php echo $id_classe ?>" class="nav-link">Supprimer</a>
                        </li>
                    </ul>
                    
                    
                </div>
            </nav>
        </div>
        <div class="col-right">
            <?php if (count($classes) > 0):  ?>
                <div class="tp">
                    <div class="col-classe-l">
                        <?php foreach ( $classes as $classe ): ?>

                            <h1>Modifiers le numéro des Eleves dans la Classe <?php echo $classe['nom_classe'] ?></h1>
                            <h3>Année Scolaire: <?php echo $classe['annee_debut'] ?> - <?php echo $classe['annee_fin'] ?> </h3>
                            <h3>Salle: <?php echo $classe['salle'] ?></h3>

                        <?php endforeach; ?>
                    </div>
                </div>
              
                    
                <div class="bd">
                    <?php if (count($eleves) > 0):  ?>
                        <form action="../back/eleves/editNumeros.php?id=<?= $id_classe ?>" method="POST" class="d-block w-100 auto">
                            <table class="table table-hover">
                                <thead>
                                            <tr>
                                                <th class="col">#</th>
                                                <th class="col">Matricule</th>
                                                <th class="col">Nom & Prénom</th>
                                                <th class="col">Sexe</th>
                                                <th class="col">Numéro</th>
                                            </tr>
                                </thead>
                                <tbody>
                                            
                                            <?php foreach ( $eleves as $eleve ): ?>
                                                <tr>
                                                    <th scope="row">
                                                        <?php echo !empty($eleve['numero']) ? $eleve['numero'] : 0; ?>
                                                    </th>
                                                    <td>
                                                        <?php echo $eleve['eleve_id'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $eleve['nom_eleve'] ?>
                                                        <?php echo $eleve['prenom_eleve'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $eleve['sexe_eleve'] ?>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="input" name="numbers[<?= $eleve['eleve_id'] ?>]" value="<?= $eleve['numero'] ?>">
                                                    </td>
                                                </tr>
                                            
                                            <?php endforeach; ?>
                                        
                                </tbody>
                            </table>
                            <div class="submit">
                            <button type="submit" href="./print-classe.html" class="btn btn-primary" name="modifier">Terminer</button>
                            </div>
                        </form> 

                    <?php else: ?>
                        <h5>Aucun élève trouvé</h5>
                    <?php endif; ?>   
                    
                </div>
                <div class="bt">
                    
                </div>
               
            <?php else: ?>
                <h5>Aucun classe trouvé</h5>
            <?php endif; ?>
            
            
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const numeroInputs = document.querySelectorAll('input[name^="numbers["]');
        let count = 1;

        // Trier les inputs selon leur position dans le tableau
        const inputsArray = Array.from(numeroInputs);
        inputsArray.sort((a, b) => {
            // Trier selon l'ID de l'élève (dans name="numbers[ID]")
            const idA = parseInt(a.name.match(/\d+/)[0]);
            const idB = parseInt(b.name.match(/\d+/)[0]);
            return idA - idB;
        });

        // Assigner les valeurs par ordre croissant
        inputsArray.forEach(input => {
            input.value = count++;
        });
    });
</script>

</body>
</html>