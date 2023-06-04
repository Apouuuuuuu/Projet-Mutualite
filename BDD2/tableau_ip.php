<?php
include("traitement_ip.php"); // on appelle le fichier



function filtreRechercheActif()
{
    return isset($_GET['s']) && !empty($_GET['s']);
}


if (filtreRechercheActif()) {
    $recherche = htmlspecialchars($_GET['s']);
    $requete1 = $db_connexion->prepare(
        'SELECT ip.ip_id, ip.ip_ip, ip.ip_vlan, ip.ip_site, ip.ip_commentaire, ip.ip_delete, options_site.site_nom, options_site.site_id
        FROM ip, options_site
        WHERE ip.ip_site = options_site.site_id
        AND (options_site.site_nom LIKE :recherche 
        OR ip_site LIKE :recherche
        OR ip_vlan LIKE :recherche
        OR ip_commentaire LIKE :recherche)
        AND ip_delete = 0
        ORDER BY ip_id ASC'
    ); // enlever pour injection SQL le $recherche
    $requete1->bindValue(':recherche', '%' . $recherche . '%');
    $requete1->execute();
    $liste_ip = $requete1->fetchAll(PDO::FETCH_ASSOC);
    $requete1->closeCursor();
} else {

    $requete1 = $db_connexion->query(
    'SELECT ip.ip_id, ip.ip_ip, ip.ip_vlan, ip.ip_site, ip.ip_commentaire, ip.ip_delete, options_site.site_nom, options_site.site_id
    from ip, options_site 
    WHERE ip.ip_site = options_site.site_id
    AND ip_delete = 0
    ORDER BY ip_id ASC');
    $requete1->execute();
    $liste_ip = $requete1->fetchAll(PDO::FETCH_ASSOC);
    $requete1->closeCursor();
}

$requeterecupsite = $db_connexion->prepare("SELECT * FROM options_site");
$requeterecupsite->execute();
$liste_site = $requeterecupsite->fetchALL(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>

    <title>Talbeau des adresses ip</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css.css">
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
                    <a class="nav-link" href="http://localhost/projet2/BDD2/tableau_ip.php"><strong>Tableau des adresses IP</strong></a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/form_options_ip.php">Formulaire</a>
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
        $stmt = $pdo->query("SELECT ip_id, ip_ip, ip_vlan, ip_site, ip_commentaire FROM ip WHERE ip_delete = 0");

        $fp = fopen("Adresse_Ip.csv", "w"); // Nom + droit d'écriture (write)
        fputcsv($fp, ["ID", "Adresse IP", "Site", "Vlan", "Commentaire"]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($fp, $row);
        }

        fclose($fp);
        $pdo = null;

        header('Location: Adresse_Ip.csv');
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
                <th scope="col">Adresse IP</th>
                <th scope="col">Site</th>
                <th scope="col">Vlan</th>
                <th scope="col">Commentaire</th>
                <th scope="col">Sauvegarder</th>
                <th scope="col">Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($liste_ip as $ip) { // récupère toutes les données de la table ordinateur
            ?>
                <form action="" method="POST">
                    <tr>
                        <td scope="row"><?= $ip["ip_id"] ?></td>
                        <INPUT type='hidden' name='ip_id' value='<?= $ip["ip_id"] ?>'/>
                        <!--Fonction ternaire -->
                        <td><INPUT type="text" NAME="ip_ip" value=<?= $ip["ip_ip"] ?>></td>
                        <td>
                            <select name='ip_site'> 
                                <?php
                               
                                foreach ($liste_site as $site) {
                                    echo "<option value='" . $site['site_id'] . "'";
                                    if ($site['site_id'] == $ip["ip_site"]) {
                                        echo "selected";
                                    }
                                    echo ">" . $site['site_nom'] . "</option>";
                                }
                                echo "</select>";
                                ?>
                            </select></td>
                        <td><INPUT NAME="ip_vlan" type="text" value=<?= $ip["ip_vlan"] ?>></td>
                        <td><textarea name="ip_commentaire" rows="2" placeholder="Écrire un commentaire."><?= $ip["ip_commentaire"] ?></textarea></td>
                        <td> <button type="submit" name='action' class="btn btn-outline-secondary" value='modification'><strong>Modifier</strong></button></td>
                        <td><button type="submit" name='action' class="btn btn-outline-dark" value='supprimer'><strong>Supprimer</strong></button></td>
                        <input type="hidden" name="ip_id" value="<?= $ip["ip_id"] ?>">
                    </tr>
                </form>
            <?php
            }

            ?>
            <form action="" method="POST">
                <tr>
                    <td><span NAME="aucun_nom"></td>
                    <td><INPUT type="text" NAME="ip_ip"></td>
                    <td>
                            <select name='ip_site'> 
                                <?php
                                foreach ($liste_site as $site) {
                                    echo "<option value='" . $site['site_id'] . "'";
                                    if ($site['site_id'] == $ip["ip_site"]) {
                                        echo "selected";
                                    }
                                    echo ">" . $site['site_nom'] . "</option>";
                                }
                                echo "</select>";
                                ?>
                            </select>
                        </td>
                    <td><INPUT NAME="ip_vlan" type="text"></td>
                    <td><textarea name="ip_commentaire" rows="2" placeholder="Écrire un commentaire."></textarea></td>
                    <td> <button type="submit" class="btn btn-outline-dark" name='action' value='ajout'><strong>Ajouter</strong></button></td>
                    <td></td>             
                </tr>
            </form>
        </tbody>
    </table>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>

    <body>

</html>


<!-- 
- Récupérer les informations de la base de données avec une requette préparée et PDO::FETCH_ASSOC : $db_connexion->fetchAll(PDO::FETCH_ASSOC); voir :
        - https://aymeric-cucherousset.fr/php-utilisation-de-pdo/ 
        ou 
        - https://www.php.net/manual/fr/pdostatement.fetchall.php
-->