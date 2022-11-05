<?php
//notre session php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: profil.php");
    exit;
}
$_SESSION["error"] = [];
//on verfie si le formulaire a été envoyé
if (!empty($_POST)) {
    //on sait que le formulaire a ete envoyé
    //on verifie si les champs ont ete envoye
    if (
        isset($_POST["email"], $_POST["pass"]) &&
        !empty($_POST["email"]) && !empty($_POST["pass"])
    ) {
        //ici le formulaire est pret a étre traité
       
            //on verifie si l'email est correcte
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $_SESSION["error"][] = "l'adress email est incorrecte";
            }

            //verify if the email exist in our database

            require_once("include/db_connect.php");

            $sql = "SELECT * FROM users WHERE email =:email";
            $requete = $db->prepare($sql);
            $requete->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
            $requete->execute();
            $user = $requete->fetch();
            if (!$user) {
                //l'utilisateur n'existe pas dans la bd
                $_SESSION["error"][] = "le user et/ou le mot de passe est incorrect";
            }
            //here w've a user we can verify his passwd
            if (!password_verify($_POST["pass"], $user["pass"])) {
                //le mot de passe est incorrect
                $_SESSION["error"][] = "le user et/ou le mot de passe est incorrect";
            }
        //now the email and the passwd is OK we can open the session
        if ($_SESSION["error"] === []
        ) {
            $_SESSION["user"] = [
                "id" => $user["id"],
                "nom" => $user["nom"],
                "email" => $user["email"],
            ];
            //on redirige vers la page profil
            header("Location: profil.php");
        }
    } else {
        $_SESSION["error"][] = "Le formulaire n'est pas complet";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles/connexion.css" />
    <meta name="viewport" content="width=device-width, initial-scale=">
    <title> Connexion</title>
    <style>
    .error {
        font-size: 1.5em;
        animation-duration: 10s;
        animation-name: slide;
        opacity: 0;
        background-color: red;
        width: 100%;
        color: white;
    }

    @keyframes slide {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 0;
        }
    }
    </style>
</head>

<body>
    <div class="login">
        <form method="post">
            <h2>Connectez-vous</h2>
            <div class="textbox">
                <span><img src="img/Email.png" draggable="false"></span>
                <input type="text" placeholder="Email" name="email">
            </div>
            <div class="textbox">
                <span><img src="img/passwd.png" draggable="false"></span>
                <input type="password" placeholder="Mot de passe" name="pass">
            </div>
            <div class=>
                <a href="inscriptions.php" target="_blank">
                    <p>Pas de compte ? s'inscrire</p>
                </a>
            </div>
            <button type="submit" class="btn">Se connecter</button>

        </form>
        <?php
        if (isset($_SESSION["error"])) {
            foreach ($_SESSION["error"] as $message) {
        ?>
        <p class="error"><?= $message ?></p>
        <?php
            }
            unset($_SESSION["error"]);
        }
        ?>
    </div>
</body>

</html>