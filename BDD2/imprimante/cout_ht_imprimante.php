<?php


$db_connexion = new PDO('mysql:host=localhost;dbname=projet_1', 'root', '');


if (!empty($_POST['imprimante_ref']) && !empty($_POST['imprimante_nb_prix']) && !empty($_POST['imprimante_couleur_prix']) && !empty($_POST['imprimante_numserie'])) {

    $ref_imprimante = $_POST['imprimante_ref'];
    $imprimante_nb_prix = $_POST['imprimante_nb_prix'];
    $imprimante_couleur_prix = $_POST['imprimante_couleur_prix'];
    $numserie_imprimante = $_POST['imprimante_numserie'];
    $loc_mens_prix_imprimante = $_POST['imprimante_loc_mens_prix'];
    

    $requete = $db_connexion->prepare("INSERT INTO imprimante (imprimante_ref, imprimante_nb_prix, imprimante_couleur_prix, imprimante_numserie, imprimante_site, imprimante_loc_mens_prix) VALUES (:imprimante_ref, :imprimante_nb_prix, :imprimante_couleur_prix, :imprimante_numserie, 5, :imprimante_loc_mens_prix)");
    $requete->bindValue(':imprimante_ref', $ref_imprimante);
    $requete->bindValue(':imprimante_nb_prix', $imprimante_nb_prix);
    $requete->bindValue(':imprimante_couleur_prix', $imprimante_couleur_prix);
    $requete->bindValue(':imprimante_numserie', $numserie_imprimante);
    $requete->bindValue(':imprimante_loc_mens_prix', $loc_mens_prix_imprimante);
    $requete->execute();
    $requete->closeCursor();
    // var_dump($ref_imprimante, $imprimante_nb_prix, $imprimante_couleur_prix, $numserie_imprimante);
    // die();
    echo '<script>alert("L\'imprimante ' . $ref_imprimante . ' a été ajoutée. \n Prix d\'une feuille en noir et blanc : ' . $imprimante_nb_prix . ' \n Prix d\'une feuille en couleur : ' . $imprimante_couleur_prix . ' \n Numéro de série : ' . $numserie_imprimante . ' \n Prix mensuel de location : ' . $loc_mens_prix_imprimante . '");</script>';
}

if (!empty($_POST['site_nom'])) {

    $nom_site = $_POST["site_nom"];
    $requete = $db_connexion->prepare("INSERT INTO options_site (site_nom) VALUES (:site_nom)");
    $requete->bindValue(':site_nom', $nom_site);
    $requete->execute();
    $requete->closeCursor();
    header('Location: cout_ht_imprimante.php.'); 
}

$requeterecupsite = $db_connexion->prepare("SELECT * FROM options_site");
$requeterecupsite->execute();
$liste_site = $requeterecupsite->fetchALL(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>

    <title>Cout hors taxe</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css.css">
    <link rel="stylesheet" type="text/css" href="../checkbox.css">
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
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/tableau_imprimante.php">Couts HT</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/imprimante_mensuel_formulaire.php">Tableau mensuel</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/cout_ht_imprimante.php"><strong>Formulaire</strong></a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/liste_site.php">Liste sites</a>
                </div>
            </div>
        </div>
    </nav>


    <br><br><br>

<!-- Formulaire pour ajouter une imprimante -->
    <form action="http://localhost/projet2/BDD2/imprimante/cout_ht_imprimante.php" method="post">
    <p><strong>Formulaire de couts hors taxe</strong></p>
    <label for="imprimante_ref">Ajouter une référence d'imprimante</label>
    <input type="text" name="imprimante_ref"><br>

    <label for="imprimante_numserie">Numéro de série de l'imprimante</label>
    <input type="text" name="imprimante_numserie"><br>

    <label for="imprimante_nb_prix">Prix d'impression en noir et blanc</label>
    <input type="text" name="imprimante_nb_prix"><br>
    
    <label for="imprimante_couleur_prix">Prix d'impression en couleur</label>
    <input type="text" name="imprimante_couleur_prix"><br>

    <label for="imprimante_loc_mens_prix">Prix de location de l'imprimante</label>
    <input type="text" name="imprimante_loc_mens_prix"><br>

    <input type="submit" name="action" class="btn btn-outline-success" value="Ajouter">      
</form>




<!-- Formulaire pour ajouter un site -->
<form action="http://localhost/projet2/BDD2/imprimante/cout_ht_imprimante.php" method="post">
        <label for="equipement">Ajouter un site dans la liste déroulante</label>
        <input type="text" name="site_nom" id="site_nom">
        <input type="submit" name="action" class="btn btn-outline-success" value="Ajouter">
 </form>


    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>

    <body>

</html>
