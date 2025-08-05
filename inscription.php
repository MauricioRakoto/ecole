<?php 

    require "./back/database.php";

    session_start();

    if (!isset($_SESSION['responsable_id'])) {
        header("Location: signin.php");
        exit();
    }

    if ($_SESSION['compte'] !== "Surveillant") {
        header("Location: home.php");
    }


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

    <!-- Barre de navigation supérieure -->
    <?php require './includes/navbar.php' ?>

    <!-- Contenu principal avec barre latérale -->
    <div class="container-xxl position-relative d-flex p-0" style="margin-top: 100px;">
        
        <!-- Barre latérale -->
        <div class="sidebar" style="width: 200px; padding: 0 20px;">
            <nav class="navbar bg-light">
                <div class="navbar-nav w-100" style="margin-top: 25px">
                    <ul>
                        <li><a href="./home.php" class="nav-link">Accueil</a></li>
                        <li><a href="./classes/" class="nav-link">Classes</a></li>
                        <li><a href="./inscription.php" class="nav-link active">Inscription</a></li>
                        <li><a href="./matieres/" class="nav-link">Matières</a></li>
                        <li><a href="index.php" class="nav-link">Bulletins</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Zone droite : formulaire d'inscription -->
        <div class="col-right">
            <div class="tp inscrit">
                <div class="title">
                    <h3>Page d'inscription</h3>
                </div>
                <!-- Choix de mode d'ajout -->
                <div class="modes">
                    <button class="btn-add active">Saisir</button>
                    <button class="btn-add">Importer</button>
                </div>
            </div>

            <!-- Formulaire d'ajout d'élève -->
            <div class="bd-add">
                <form action="./back/eleves/addEleve.php" method="POST">
                    
                    <!-- Informations sur l'élève -->
                    <h6 style="margin-top: 20px;">ELEVE</h6>
                    <div class="datas" style="display: block;">

                        <!-- Nom et prénom -->
                        <div style="display: flex; gap: 10px">
                            <div class="form-group" style="width: 370px">
                                <div class="label"><h4>Nom</h4></div>
                                <div class="input"><input type="text" placeholder="Nom" name="nom" required></div>
                            </div>
                            <div class="form-group" style="width: 370px">
                                <div class="label"><h4>Prénom</h4></div>
                                <div class="input"><input type="text" name="prenom" placeholder="Prénom" required></div>
                            </div>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="datas" style="margin-top: 10px">

                            <!-- Sexe -->
                            <div class="form-group">
                                <div class="label"><h4>Sexe</h4></div>
                                <div class="select" required>
                                    <select name="sexe">
                                        <option>Garçon</option>
                                        <option>Fille</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Date & lieu de naissance -->
                            <div class="form-group">
                                <div class="label"><h4>Date de naissance</h4></div>
                                <div class="input"><input type="date" name="date_naissance" required></div>
                            </div>
                            <div class="form-group">
                                <div class="label"><h4>Lieu de naissance</h4></div>
                                <div class="input"><input type="text" name="lieu_naissance" required></div>
                            </div>

                            <!-- Classe -->
                            <div class="form-group">
                                <div class="label"><h4>Classe</h4></div>
                                <div class="select">
                                    <select name="classe_id">
                                        <!-- Liste dynamique des classes depuis la base -->
                                        <?php 
                                            require "./back/database.php";
                                            $sql = "SELECT classe_id, nom_classe FROM classes";
                                            $stmt = $pdo->query($sql);
                                            while ($classe = $stmt->fetch()) {
                                        ?>
                                            <option value="<?php echo $classe['classe_id'] ?>"><?php echo $classe['nom_classe'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Statut, Adresse, Contact, Établissement précédent -->
                            <div class="form-group">
                                <div class="label"><h4>Status</h4></div>
                                <div class="select" name="status">
                                    <select name="status">
                                        <option>Passant</option>
                                        <option>Doublant</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="label"><h4>Adresse</h4></div>
                                <div class="input"><input type="text" placeholder="Adresse" name="adresse" required></div>
                            </div>

                            <div class="form-group">
                                <div class="label"><h4>Contact</h4></div>
                                <div class="input"><input type="text" placeholder="Contact" name="tel" required></div>
                            </div>

                            <div class="form-group">
                                <div class="label"><h4>Etablissement d'origine</h4></div>
                                <div class="input"><input type="text" name="origine" required></div>
                            </div>

                            <!-- Année scolaire et date d’inscription -->
                            <div class="form-group">
                                <div class="label"><h4>Année scolaire</h4></div>
                                <div class="input"><input type="text" name="annee_scolaire" required></div>
                            </div>

                            <div class="form-group">
                                <div class="label"><h4>Date d'inscription</h4></div>
                                <div class="input"><input type="date" name="date_inscrit" required></div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur le père -->
                    <h6>PERE</h6>
                    <div class="datas" style="display: block;">
                        <div style="">
                            <div class="form-group" style="width: 370px">
                                <div class="label"><h4>Nom du père</h4></div>
                                <div class="input"><input type="text" placeholder="Nom du père" name="nom_pere"></div>
                            </div>
                        </div>
                        <div class="datas" style="margin-top: 10px">
                            <div class="form-group">
                                <div class="label"><h4>Profession du père</h4></div>
                                <div class="input"><input type="text" placeholder="Profession du père" name="profession_pere"></div>
                            </div>
                            <div class="form-group">
                                <div class="label"><h4>Tél du père</h4></div>
                                <div class="input"><input type="number" placeholder="Tel du père" name="tel_pere"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur la mère -->
                    <h6>MERE</h6>
                    <div class="datas" style="display: block">
                        <div>
                            <div class="form-group" style="width: 380px">
                                <div class="label"><h4>Nom du mère</h4></div>
                                <div class="input"><input type="text" placeholder="Nom du mère" name="nom_mere" required></div>
                            </div>
                        </div>
                        <div class="datas" style="margin-top: 10px">
                            <div class="form-group">
                                <div class="label"><h4>Profession du mère</h4></div>
                                <div class="input"><input type="text" placeholder="Profession du mère" name="profession_mere" required></div>
                            </div>
                            <div class="form-group">
                                <div class="label"><h4>Tél du mère</h4></div>
                                <div class="input"><input type="text" placeholder="Tel du mère" name="tel_mere" required></div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur le tuteur -->
                    <h6>TUTEUR</h6>
                    <div class="datas" style="display: block">
                        <div>
                            <div class="form-group" style="width: 380px">
                                <div class="label"><h4>Nom du tuteur</h4></div>
                                <div class="input"><input type="text" placeholder="Nom du tuteur" name="nom_tuteur"></div>
                            </div>
                        </div>
                        <div class="datas" style="margin-top: 10px">
                            <div class="form-group">
                                <div class="label"><h4>Profession du mère</h4></div>
                                <div class="input"><input type="text" placeholder="Profession du tuteur" name="profession_tuteur"></div>
                            </div>
                            <div class="form-group">
                                <div class="label"><h4>Tél du tuteur</h4></div>
                                <div class="input"><input type="text" placeholder="Tel du tuteur" name="tel_tuteur"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons de soumission -->
                    <div class="bottom">
                        <button class="btn-add" name="enregistrer" type="submit">Terminer</button>
                        <a href="./home.html" class="btn-add">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script src="./assets/js/main.js"></script>
</body>
</html>
