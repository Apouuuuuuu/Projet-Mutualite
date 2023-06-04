<?php
define('USER',"root");
define('PASSWD',"");
define('SERVER',"127.0.0.1");
define('BASE',"projet_1");
function test():?PDO
{
    $dsn="mysql:dbname=".BASE.";host=".SERVER;
    try{
      $connexion=new PDO($dsn,USER,PASSWD);
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo 'Connexion établie' ; return $connexion;
      
    } catch(PDOException $e){ // catch l'error
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }
}
?>
