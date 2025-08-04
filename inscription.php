<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Métadonnées de base et liens CSS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Barre de navigation supérieure -->
    <nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
        <a href="./home.php" class="text-primary" style="display: flex; gap: 10px; align-items: center">
            <img src="./assets/img/logo.png" alt="logo" style="width: 35px">
            <h3 style="font-size: 20px">Students</h3>
        </a>

        <!-- Menu utilisateur -->
        <div class="menu">
            <button class="btn-menu" id="menu">M</button>
            <div class="menu-name">
                <h4>Mauricio Stanic</h4>
            </div>
            <div class="menu-modal">
                <div class="modal-top">
                    <div class="tp-image">
                        <div class="image">Image</div>
                    </div>
                    <div class="tp-name">
                        <h4>Mauricio Stanic</h4>
                    </div>
                    <div class="tp-compte">
                        <h4>Compte: Surveillant</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <ul class="modal-links">
                        <li><a href="#">Mon profile</a></li>
                        <li><a href="#">Paramètre</a></li>
                        <li><a href="#">Se déconnecter</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

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
