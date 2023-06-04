<!doctype html>
<html>
<head>
<title>
BDD 2
</title>
<meta charset="utf-8">
</head>
<body>
<h1>
Bases de données PDO
</h1>

<?php
require("connectBDD2.php");
$dsn="mysql:dbname=".BASE.";host=".SERVER;
    try{
      $connexion=new PDO($dsn,USER,PASSWD);
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo 'Connexion établie' ;
      
    } catch(PDOException $e){ // catch l'error
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }
?>
</body>
</html>

