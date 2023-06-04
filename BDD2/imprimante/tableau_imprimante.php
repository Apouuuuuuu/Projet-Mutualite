<?php
include("traitement_imprimante.php"); 


function filtreRechercheActif()
{
    return isset($_GET['s']) && !empty($_GET['s']);
}


if (filtreRechercheActif()) {
    $recherche = htmlspecialchars($_GET['s']);
    $requete1 = $db_connexion->prepare(
        'SELECT imprimante.imprimante_id, imprimante.imprimante_site, imprimante.imprimante_ref, options_site.site_nom, options_site.site_id, imprimante.imprimante_loc_mens_prix, imprimante.imprimante_nb_prix, imprimante.imprimante_couleur_prix, imprimante.imprimante_numserie
        FROM imprimante
        WHERE imprimante.imprimante_site = options_site.site_id
        AND (
            imprimante_ref LIKE :recherche
        OR imprimante_loc_mens_prix LIKE :recherche
        OR imprimante_nb_prix LIKE :recherche
        OR imprimante_couleur_prix LIKE :recherche
        OR imprimante_service LIKE :recherche
        OR imprimante_centre LIKE :recherche
        OR imprimante_numserie LIKE :recherche
        )
        AND imprimante_delete = 0)
        ORDER BY imprimante_id ASC'
    ); // enlever pour injection SQL le $recherche
    $requete1->bindValue(':recherche', '%' . $recherche . '%');
    $requete1->execute();
    $liste_imprimante = $requete1->fetchAll(PDO::FETCH_ASSOC);
    $requete1->closeCursor();
} else {

    $requete1 = $db_connexion->query(
    'SELECT imprimante.imprimante_id, imprimante.imprimante_site, imprimante.imprimante_ref, options_site.site_nom, options_site.site_id, imprimante.imprimante_loc_mens_prix, imprimante.imprimante_nb_prix, imprimante.imprimante_couleur_prix, imprimante.imprimante_numserie
    from imprimante, options_site
    WHERE imprimante.imprimante_site = options_site.site_id
    AND imprimante_delete = 0
    ORDER BY imprimante_id ASC');
    $requete1->execute();
    $liste_imprimante = $requete1->fetchAll(PDO::FETCH_ASSOC);
    
    $requete1->closeCursor();

}
$requeterecupsite = $db_connexion->prepare("SELECT * FROM options_site");
$requeterecupsite->execute();
$liste_site = $requeterecupsite->fetchALL(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>

    <title>Tableau des imprimantes</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css.css">
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
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/tableau_imprimante.php"><strong>Couts HT</strong></a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/imprimante_mensuel_formulaire.php">Tableau mensuel</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/cout_ht_imprimante.php">Formulaire</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/liste_site.php">Liste sites</a>
                </div>
            </div>
        </div>
    </nav>



    <br><br><br>
    <form method="post">
        <input type="submit" class="btn btn-outline-dark" name="download" value="Télécharger le fichier CSV">
    </form>
    <?php

if (isset($_POST["download"])) {

    $dsn = "mysql:host=127.0.0.1;dbname=projet_1";
    $pdo = new PDO($dsn, "root", "");
    $stmt = $pdo->query("SELECT imprimante.imprimante_id, options_site.site_nom, imprimante.imprimante_numserie, imprimante.imprimante_ref, imprimante.imprimante_loc_mens_prix, imprimante.imprimante_nb_prix, imprimante.imprimante_couleur_prix FROM imprimante JOIN options_site ON imprimante.imprimante_site = options_site.site_id ORDER BY imprimante_id ASC");

    $fp = fopen("Cout_HT_Imprimante.csv", "w"); // Nom + droit d'écriture (write)
    fputcsv($fp, ["ID", "Numero de serie", "Site","Reference", "Location mensuel", "N&B", "Couleur"]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data = [
            $row['imprimante_id'],
            $row['imprimante_numserie'],
            $row['site_nom'],
            $row['imprimante_ref'],
            $row['imprimante_loc_mens_prix'],
            $row['imprimante_nb_prix'],
            $row['imprimante_couleur_prix']
        ];
        fputcsv($fp, $data);
    }
    fclose($fp);
    $pdo = null;
    header('Location: Cout_HT_Imprimante.csv');
}
    ?>


    <br>
    <div class="d-flex align-items-center justify-content-center flex-column gap-5">
        <form method="GET" class="d-flex mb-0">

            <input class="form-control me-1" type="search" value='<?= (isset($_GET['s']) ? $_GET['s'] : null) ?>' placeholder="Rechercher" name="s" aria-label="Search">
            <button class="btn btn-dark" type="submit" name="envoyer_recherche">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <table class="table table-dark table-striped">
        
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Num Serie</th>
                <th scope="col">Site</th>
                <th scope="col">Référence</th>
                <th scope="col">Location mensuelle</th>
                <th scope="col">Prix noir et blanc</th>
                <th scope="col">Prix couleur</th>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>

            </tr>
        </thead>
        <tbody>
        <?php
foreach ($liste_imprimante as $imprimante) { 
?>
    <form action="" method="POST">
        <tr>
            <td scope="row"><?= $imprimante["imprimante_id"] ?></td>
            <input type='hidden' name='imprimante_id' value='<?= $imprimante["imprimante_id"] ?>' />
            <td><input type="text" name="imprimante_numserie" value="<?= $imprimante["imprimante_numserie"] ?>"></td>
            <td>
                <select name='imprimante_site'>
                    <?php
                    foreach ($liste_site as $site) {
                        echo "<option value='" . $site['site_id'] . "'";
                        if ($site['site_id'] == $imprimante["imprimante_site"]) {
                            echo "selected";
                        }
                        echo ">" . $site['site_nom'] . "</option>";
                    }
                    ?>
                </select>
            </td>
            <td><input name="imprimante_ref" type="text" value="<?= $imprimante["imprimante_ref"] ?>"></td>
            <td><input name="imprimante_loc_mens_prix" type="text" value="<?= $imprimante["imprimante_loc_mens_prix"] ?>"></td>
            <td><input name="imprimante_nb_prix" type="text" value="<?= $imprimante["imprimante_nb_prix"] ?>"></td>
            <td><input name="imprimante_couleur_prix" type="text" value="<?= $imprimante["imprimante_couleur_prix"] ?>"></td>
            <td><button type="submit" name='action' class="btn btn-outline-secondary" value='modifiercoutht'><strong>Modifier</strong></button></td>
            <td><button type="submit" name='action' class="btn btn-outline-dark" value='supprimercoutht'><strong>Supprimer</strong></button></td>
            <input type="hidden" name="imprimante_id" value="<?= $imprimante["imprimante_id"] ?>">
        </tr>
    </form>
<?php
}
?>

          
        </tbody>
    </table>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>

    <body>

</html>


<!-- 
- Récupérer les informations de la base de données avec une requette préparée et PDO::FETCH_ASSOC : $db_connexion->fetchAll(PDO::FETCH_ASSOC); voir :
        - https://aymeric-cucherousset.fr/php-utilisation-de-pdo/ 
        ou 
        - https://www.php.net/manual/fr/pdostatement.fetchall.php
-->