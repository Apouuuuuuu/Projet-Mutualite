<?php
include("traitement_imprimante.php");




if (isset($_GET['aller_page'])) {
    // Récupération de l'année et du mois sélectionnés
    $annee_page = $_GET['annee_page'];
    $mois_page = $_GET['mois_page'];
}




function filtreRechercheActif()
{
    return isset($_GET['s']) && !empty($_GET['s']);
}


if (filtreRechercheActif()) {
    $recherche = htmlspecialchars($_GET['s']);
    $requete1 = $db_connexion->prepare(
        "SELECT
        imprimante.imprimante_id,
        imprimante.imprimante_numserie,
        imprimante.imprimante_site,
        imprimante.imprimante_ref,
        quantite_mensuel_impression.quantite_nb,
        quantite_mensuel_impression.quantite_couleur,
        ROUND(imprimante.imprimante_nb_prix * quantite_mensuel_impression.quantite_nb, 2) AS imprimante_cout_nb_mois,
        ROUND(imprimante.imprimante_couleur_prix * quantite_mensuel_impression.quantite_couleur, 2) AS imprimante_cout_coul_mois,
        options_site.site_nom,
        ROUND((imprimante.imprimante_nb_prix * quantite_mensuel_impression.quantite_nb) + (imprimante.imprimante_couleur_prix * quantite_mensuel_impression.quantite_couleur), 2) AS imprimante_cout_total
        FROM
        imprimante,
        options_site,
        quantite_mensuel_impression
        WHERE  imprimante.imprimante_id = quantite_mensuel_impression.id_imprimante
        AND imprimante.imprimante_site = options_site.site_id
        AND quantite_mensuel_impression.date_donnee BETWEEN :debutperiode AND :finperiode
        AND (
            imprimante_ref LIKE :recherche
            OR options_site.site_nom LIKE :recherche
            OR imprimante_numserie LIKE :recherche
        )
        AND imprimante_delete = 0
        ORDER BY imprimante_id ASC"
    );
    // Le round 2 arrondit le résultat à 2 décimales
    // enlever pour injection SQL le $recherche
    $requete1->bindValue(':recherche', '%' . $recherche . '%');
    $requete1->execute();
    $liste_imprimante = $requete1->fetchAll(PDO::FETCH_ASSOC);
    $requete1->closeCursor();
} else {
    $premierjour = date("$annee_page-$mois_page-01");
    $dernierjour = date("$annee_page-$mois_page-31");

    $requete1 = $db_connexion->prepare(
        "SELECT
        imprimante.imprimante_id,
        imprimante.imprimante_numserie,
        imprimante.imprimante_site,
        imprimante.imprimante_ref,
        quantite_mensuel_impression.quantite_nb,
        quantite_mensuel_impression.quantite_couleur,
        ROUND(imprimante.imprimante_nb_prix * quantite_mensuel_impression.quantite_nb, 4) AS imprimante_cout_nb_mois,
        ROUND(imprimante.imprimante_couleur_prix * quantite_mensuel_impression.quantite_couleur, 4) AS imprimante_cout_coul_mois,
        options_site.site_nom,
        ROUND((imprimante.imprimante_nb_prix * quantite_mensuel_impression.quantite_nb) + (imprimante.imprimante_couleur_prix * quantite_mensuel_impression.quantite_couleur) + (imprimante.imprimante_loc_mens_prix), 4) AS imprimante_cout_total
        FROM
        imprimante,
        options_site,
        quantite_mensuel_impression
        WHERE  imprimante.imprimante_id = quantite_mensuel_impression.id_imprimante
        AND imprimante.imprimante_site = options_site.site_id
        AND quantite_mensuel_impression.date_donnee BETWEEN :debutperiode AND :finperiode
        AND imprimante_delete = 0
        ORDER BY imprimante_id ASC"
    );
    $requete1->bindValue(':debutperiode', $premierjour, PDO::PARAM_STR);
    $requete1->bindValue(':finperiode', $dernierjour, PDO::PARAM_STR);



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
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante_mensuel_formulaire.php"><strong>Tableau mensuel</strong></a>
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
    
        $premierjour = date("$annee_page-$mois_page-01");
        $dernierjour = date("$annee_page-$mois_page-31");
    
        $query = 'SELECT
            imprimante.imprimante_id,
            imprimante.imprimante_numserie,
            imprimante.imprimante_site,
            imprimante.imprimante_ref,
            quantite_mensuel_impression.quantite_nb,
            quantite_mensuel_impression.quantite_couleur,
            ROUND(imprimante.imprimante_nb_prix * quantite_mensuel_impression.quantite_nb, 2) AS imprimante_cout_nb_mois,
            ROUND(imprimante.imprimante_couleur_prix * quantite_mensuel_impression.quantite_couleur, 2) AS imprimante_cout_coul_mois,
            options_site.site_nom,
            ROUND((imprimante.imprimante_nb_prix * quantite_mensuel_impression.quantite_nb) + (imprimante.imprimante_couleur_prix * quantite_mensuel_impression.quantite_couleur), 2) AS imprimante_cout_total
        FROM
            imprimante,
            options_site,
            quantite_mensuel_impression
        WHERE
            imprimante.imprimante_id = quantite_mensuel_impression.id_imprimante
            AND imprimante.imprimante_site = options_site.site_id
            AND quantite_mensuel_impression.date_donnee BETWEEN "' . $premierjour . '" AND "' . $dernierjour . '"
            AND imprimante_delete = 0
        ORDER BY
            imprimante_id ASC';
    
        $stmt = $pdo->query($query);
    
        $fp = fopen("Tableau_Imprimante_Mensuel.csv", "w"); // Nom + droit d'écriture (write)
        fputcsv($fp, ["ID", "Num Serie", "Site", "Reference", "Quantite NB", "Quantite Couleur", "Cout NB Mois", "Cout Couleur Mois", "Nom Site", "Cout Total"]);
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($fp, $row);
        }
    
        fclose($fp);
        $pdo = null;
    
        header('Location: Tableau_Imprimante_Mensuel.csv');
    }
    
    ?>

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
                <th scope="col">Nb N&B imprimées</th>
                <th scope="col">Nb Coul imprimées</th>
                <th scope="col">Cout N&B</th>
                <th scope="col">Cout coul</th>
                <th scope="col">Total</th>
                <th scope="col">Modifier</th>

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
                                        echo " selected";
                                    }
                                    echo ">" . $site['site_nom'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input name="imprimante_ref" type="text" value="<?= $imprimante["imprimante_ref"] ?>"></td>
                        <td><input type="text" name="quantite_nb" value="<?= $imprimante["quantite_nb"] ?>"></td>
                        <td><input name="quantite_couleur" type="text" value="<?= $imprimante["quantite_couleur"] ?>"></td>
                        <td><input name="imprimante_cout_nb_mois" readonly type="text" value="<?= $imprimante["imprimante_cout_nb_mois"] ?>"></td>
                        <td><input name="imprimante_cout_coul_mois" readonly type="text" value="<?= $imprimante["imprimante_cout_coul_mois"] ?>"></td>
                        <td><input name="imprimante_cout_total_mois" readonly type="text" value="<?= $imprimante["imprimante_cout_total"] ?>"></td>
                        <td><button type="submit" name='action' class="btn btn-outline-secondary" value='modifiermensuel'><strong>Modifier</strong></button></td>
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