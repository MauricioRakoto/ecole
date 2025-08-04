<nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
    <a  href="./home.php" class="text-primary" style="display: flex; gap: 10px; align-items: center">
        <img src="./assets/img/logo.png" alt="logo" style="width: 35px">
        <h3 style="font-size: 20px">Ecole</h3>
    </a>
    <div class="menu">
            <button class="btn-menu" id="menu">
                M
            </button>
            <div class="menu-name">
                <h4><?= $_SESSION['username'] ?></h4>
            </div>
            <div class="menu-modal">
                <div class="modal-top">
                    <div class="tp-image">
                        <div class="image">
                            Image
                        </div>
                        
                    </div>
                    <div class="tp-name">
                        <h4><?= $_SESSION['username'] ?></h4>
                    </div>
                    <div class="tp-compte">
                        <h4>Compte: <?= $_SESSION['compte'] ?></h4>
                    </div>
                </div>
                <div class="modal-body">
                    <ul class="modal-links">
                        <li>
                            <a href="#">Mon profile</a>
                        </li>
                        <li>
                            <a href="#">Paramètre</a>
                        </li>
                        <li>
                            <a href="./back/responsable/logout.php">Se déconnecter</a>
                        </li>
                    </ul>
                </div>
            </div>
    </div>
</nav>