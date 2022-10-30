<nav>
    <ul>
        <?php if (isset($_SESSION["user"])):?>
        <li>
            <a href="deconnexion.php">Se deconnecter</a>
        </li>
        <?php endif?>
    </ul>
</nav>