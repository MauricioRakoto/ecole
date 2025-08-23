<nav class="navbar navbar-expand sticky-top" style="display: flex; justify-content: space-between; margin: 0; padding: 10px">
    <a  href="./home.php" class="text-primary" style="display: flex; gap: 10px; align-items: center">
        <img src="http://<?= $host ?>/app-ecole/assets/img/logo-ecole" alt="logo" style="width: 35px">
        <h3 style="font-size: 20px">Ecole</h3>
    </a>
    <div class="menu">
            <?php foreach ( $comptes as $compte ): ?>
            <button class="icone" id="menu" style="border: 0; background: none; overflow: hidden">
                <?php if (!empty($compte['image'])): ?>
                    <img src="http://<?= $host ?>/app-ecole/assets/img/<?= $compte['image'] ?>" alt="Image" width="25">
                <?php else: ?>
                    M
                <?php endif; ?>
            </button>
            <?php endforeach; ?>   
            <div class="menu-name">
                <h4><?= $_SESSION['username'] ?></h4>
            </div>
            <div class="menu-modal">
                <div class="modal-top">
                    <div class="tp-image">
                    <?php foreach ( $comptes as $compte ): ?>
                        <?php if (!empty($compte['image'])): ?>
                            <div class="photo">
                                <img src="http://<?= $host ?>/app-ecole/assets/img/<?= $compte['image'] ?>" alt="Image" width="35">
                            </div>
                            <?php else: ?>
                                <div class="image">
                                    M
                                </div>
                            <?php endif; ?>
                       
                        <?php endforeach; ?>   
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
                            <a href="./profile.php">Mon profile</a>
                        </li>
                        <li>
                            <a href="#">Paramètre</a>
                        </li>
                        <li>
                            <a href="./guides.php">Guides</a>
                        </li>
                        <li>
                            <a href="./back/responsable/logout.php">Se déconnecter</a>
                        </li>
                    </ul>
                </div>
            </div>
    </div>
</nav>