<?php
//notre session php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: profil.php");
    exit;
}

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
            die("l'adress email est incorrecte");
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
            die("le user et/ou le mot de passe est incorrect");
        }
        //here w've a user we can verify his passwd
        if (!password_verify($_POST["pass"], $user["pass"])) {
            //le mot de passe est incorrect
            die("le user et/ou le mot de passe est incorrect");
        }
        //now the email and the passwd is OK we can open the session
        $_SESSION["user"] = [
            "id" => $user["id"],
            "nom" => $user["nom"],
            "email" => $user["email"],
        ];
        //on redirige vers la page profil
        header("Location: profil.php");
    } else {
        die("Le formulaire n'est pas complet");
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

    </div>
</body>

</html>