<?php
session_start();
//si on a un user inutile de revenir
if (isset($_SESSION["user"])) {
    header("Location:profil.php");
    exit;
}
//verifying if the form has been sent
if (!empty($_POST)) {
    //verifying si les inputs requis ont éte
    if (
        isset($_POST["name"], $_POST["email"], $_POST["pass"]) &&
        !empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["pass"])
    ) {
        //here all's done
        //on recupere le nom 
        $nom = $_POST["name"];

        //veryfing if the email is a veritable mail (We hate regexp...)
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            //l'email form is not validate
            die("L'adress Email est incorrecte");
        }
        //here w're sure that email is done

        //hashing the passwd with argon2id algorithm
        $pass = password_hash($_POST["pass"], PASSWORD_ARGON2ID);

        //verify if the user already exist in the database
        //connexion into the db
        require_once("include/db_connect.php");
        $sql = "SELECT * FROM users WHERE email =:email";
        $requete = $db->prepare($sql);
        $requete->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
        $requete->execute();
        $user = $requete->fetch();
        if ($_POST["email"] == isset($user["email"])) {
            die("cet utilisateur existe deja");
        }
        
        //----------------now w've a name, a mail and a pass we can add the user in the database


        //la requete d'ajoute (NEVER TRUST USER INPUT) donc on fais des requetes preparés
        $sql = "INSERT INTO users(nom, email, pass) VALUES(:nom, :email, '$pass')";

        //prepare the request
        $requete = $db->prepare($sql);

        //unjecting the variables
        $requete->bindValue(":nom", $nom, PDO::PARAM_STR);
        $requete->bindValue(":email", $_POST["email"], PDO::PARAM_STR);

        //on execute la requete
        $requete->execute();

        //recupere l'id du dernier inscrit
        $id = $db->lastInsertId();
        $_SESSION["user"] = [
            "id" => $id,
            "nom" => $_POST["name"],
            "email" => $_POST["email"],
        ];
        //on redirige le user dans la page de profile
        header("Location: profil.php");
    } else {
        die("le formulaire est incomplet");
    }
}
?>
<!DOCTYPE HTML>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles/inscription.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>inscriptions</title>
</head>

<body>
    <div class=" page-login">
        <section class="section-left">
            <div class="bouton">
                <h3>Bienvenu dans notre page de connexion</h3>
                <p class="text-para">Nous sommes heureux de vous revoir geek<br>Vous avez deja un compte
                    ?<br>Connectez-vous</p>
            </div>
            <a href="connexion.php" target="_blank"> <button class="btn-sing">Connexion</button></a>
        </section>
        <section class="section-right">
            <div class="account">
                <h4>Créér un compte</h4>
                <div class="reseaux-sociaux">
                    <p>
                        <span><a><img src="img/google.png " draggable="false"></a></span>
                        <span><a><img src="img/facebook.png" draggable="false"></a></span>
                        <span><a><img src=" img/LinkedIn.png" draggable="false"></a></span>
                    </p>
                </div>
            </div>
            <form method="post">
                <div class="input-group">
                    <span><img src="img/person.png" draggable="false"></span>
                    <input type="text" name="name" placeholder="Nom" required>
                </div>
                <div class="input-group">
                    <span><img src="img/Email.png" draggable="false"></span>
                    <input type="text" name="email" placeholder="E-mail" autocomplete="off" required>
                </div>
                <div class="input-group">
                    <span><img src="img/passwd.png" draggable="false"></span>
                    <input type="password" name="pass" placeholder="Mot de passe" required>
                </div>
                <div>
                    <button type="submit" class="btn-connexion">S'inscrire</button>
                </div>
            </form>
        </section>
    </div>
</body>

</html>