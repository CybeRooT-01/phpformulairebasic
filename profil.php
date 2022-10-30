<?php
//demmarer la session
session_start();
include("include/header.php");
include("include/nav.php")
?>
<h1>Profil de <?= $_SESSION["user"]["nom"] ?></h1>
<p>Email: <?= $_SESSION["user"]["email"] ?></p>