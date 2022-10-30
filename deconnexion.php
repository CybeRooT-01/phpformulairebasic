<?php
//demarre la session
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: connexion.php");
    exit;
}

//supprimer la variable session (user session seulement)
unset($_SESSION["user"]);
header("Location: connexion.php");
?>