<?php



$db_connexion = new PDO('mysql:host=localhost;dbname=projet_1', 'root', '');


if (!empty($_POST['site_nom'])) {

    $nom_site = $_POST["site_nom"];
    $requete = $db_connexion->prepare(
        "INSERT INTO options_site (site_nom) 
        VALUES (:site_nom)");
    $requete->bindValue(':site_nom', $nom_site);
    $requete->execute();
    $requete->closeCursor();
    header('Location: form_options_ip.php'); 
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Tableau des adresses ip</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css.css">
    <link rel="stylesheet" type="text/css" href="checkbox.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>




<body>
    <nav class="navbar navbar-expand-lg bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/projet2/BDD2/menuprincipal.php">Menu principal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="http://localhost/projet2/BDD2/tableau_ip.php">Tableau des adresses IP</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/form_options_ip.php"><strong>Formulaire</strong></a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/liste_site.php">Liste sites</a>
                </div>
            </div>
        </div>
    </nav>


    <br><br><br>

    <form action="http://localhost/projet2/BDD2/form_options_ip.php" method="post">
        <label for="equipement">Ajouter un site dans la liste déroulante</label>
        <input type="text" name="site_nom" id="site_nom">
        <input type="submit" name="action" class="btn btn-outline-success" value="Ajouter">
    </form>

    

        


    <script type="text/javascript" src="../js/bootstrap.min.js"></script>

    <body>

</html>


<!-- 
- Récupérer les informations de la base de données avec une requette préparée et PDO::FETCH_ASSOC : $db_connexion->fetchAll(PDO::FETCH_ASSOC); voir :
        - https://aymeric-cucherousset.fr/php-utilisation-de-pdo/ 
        ou 
        - https://www.php.net/manual/fr/pdostatement.fetchall.php
-->