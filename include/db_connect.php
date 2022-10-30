<?php 
//defini les constantes d'environnements
    define("DBUSER", "root");
    define("DBPASS", "");
    define("DBHOST", "localhost");
    define("DBNAME", "formulaire");
//data source name
    $dsn = "mysql:dbname=".DBNAME."; host=".DBHOST;

try {
    //connect into the database
    $db = new PDO($dsn, DBUSER, DBPASS);
    
    //defini le jeux de caractere
    $db->exec("SET NAMES utf8");

    //defini le valeur de retour entant que des tableaux associatifs
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Error".$e->getMessage());
}


?>