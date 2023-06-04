<?php
include("traitement.php"); // on appelle le fichier
?>
<!DOCTYPE html>
<html>

<head>
    <title>Archives sortie de parc</title>
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
                    <a class="nav-link" href="http://localhost/projet2/BDD2/tableau.php">Tableau de base</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/archive.php"><strong>Tableau des archives</strong></a>
                </div>
            </div>
        </div>
    </nav>

    <?php

    function filtreBouttonActive()
    {
        return isset($_GET['filtre_ad']) || isset($_GET['filtre_Compta']) || isset($_GET['filtre_sophos']) || isset($_GET['filtre_Glpi']) || isset($_GET['filtre_decheterie']) || isset($_GET['filtre_cse']);
    }

    function filtreRechercheActif()
    {
        return isset($_GET['s']) && !empty($_GET['s']);
    }


    if (filtreRechercheActif() && !filtreBouttonActive()) {
        $recherche = htmlspecialchars($_GET['s']);
        $requete1 = $db_connexion->prepare(
            'SELECT * 
            FROM ordinateur
            WHERE (pc_nom LIKE :recherche 
            OR pc_numserie LIKE :recherche
            OR pc_com LIKE :recherche
            OR pc_date LIKE :recherche)
            AND delete_d = 0

            ORDER BY pc_id DESC'
        ); // enlever pour injection SQL le $recherche
        $requete1->bindValue(':recherche', '%' . $recherche . '%');
        $requete1->execute();
        $liste_pc = $requete1->fetchAll(PDO::FETCH_ASSOC);
        $requete1->closeCursor();
    } else if (filtreBouttonActive() && !filtreRechercheActif()) {

        $va = '';
        if (isset($_GET['filtre_ad'])) {
            $va .= ' AND pc_ad = 1';
        }
        if (isset($_GET['filtre_sophos'])) {
            $va .= ' AND pc_sophos = 1';
        }
        if (isset($_GET['filtre_Compta'])) {
            $va .= ' AND pc_compta = 1';
        }
        if (isset($_GET['filtre_Glpi'])) {
            $va .= ' AND pc_glpi = 1';
        }
        if (isset($_GET['filtre_decheterie'])) {
            $va .= ' AND pc_decheterie = 1';
        }
        if (isset($_GET['filtre_cse'])) {
            $va .= ' AND pc_cse = 1';
        }
        $requete1 = $db_connexion->prepare(
            'SELECT * 
        FROM ordinateur
        WHERE 1 = 1' . $va . '
        AND delete_d = 0
        ORDER BY pc_id DESC'
        ); // enlever pour injection SQL le $recherche

        $requete1->execute();
        $liste_pc = $requete1->fetchAll(PDO::FETCH_ASSOC);
        $requete1->closeCursor();
    } else if (filtreBouttonActive() && filtreRechercheActif()) {

        $va = '';
        if (isset($_GET['filtre_ad'])) {
            $va .= ' AND pc_ad = 1';
        }
        if (isset($_GET['filtre_sophos'])) {
            $va .= ' AND pc_sophos = 1';
        }
        if (isset($_GET['filtre_Compta'])) {
            $va .= ' AND pc_compta = 1';
        }
        if (isset($_GET['filtre_Glpi'])) {
            $va .= ' AND pc_glpi = 1';
        }
        if (isset($_GET['filtre_decheterie'])) {
            $va .= ' AND pc_decheterie = 1';
        }
        if (isset($_GET['filtre_cse'])) {
            $va .= ' AND pc_cse = 1';
        }

        $recherche = htmlspecialchars($_GET['s']);
        $requete1 = $db_connexion->prepare(
            'SELECT * 
            FROM ordinateur
            WHERE 1 = 1' . $va . '
            AND (pc_nom LIKE :recherche 
            OR pc_numserie LIKE :recherche
            OR pc_com LIKE :recherche
            OR pc_date LIKE :recherche)
            AND delete_d = 0
            ORDER BY pc_id DESC'
        ); // enlever pour injection SQL le $recherche
        $requete1->bindValue(':recherche', '%' . $recherche . '%');
        $requete1->execute();
        $liste_pc = $requete1->fetchAll(PDO::FETCH_ASSOC);
        $requete1->closeCursor();
    } else {

        $liste_pc = $db_connexion->query('SELECT * from ordinateur WHERE delete_d = 0');
    }
    ?>

    <br><br><br>
    <form method="post">
        <input type="submit" class="btn btn-outline-dark" name="download" value="Télécharger le fichier CSV">
    </form>
    <?php
    if (isset($_POST["download"])) {


        $dsn = "mysql:host=127.0.0.1;dbname=projet_1";
        $pdo = new PDO($dsn, "root", "");


        $stmt = $pdo->query("SELECT * FROM ordinateur");

        $fp = fopen("Tableau_sortie_parc.csv", "w"); // Nom + droit d'écriture (write)
        fputcsv($fp, ["ID", "Nom", "Numero de serie", "Date de sortie", "AD", "Sophos", "Compta", "GLPI", "Decheterie", "CSE", "Commentaire", "Archivage"]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($fp, $row);
        }

        fclose($fp);
        $pdo = null;


        header('Location: Tableau_sortie_parc.csv');
    }
    ?>
    <form action="" method="GET">
    <input type="hidden" name="s" value='<?= (isset($_GET['s'])?$_GET['s']:null)?>'>
        <div class="container">
            <ul class="ks-cboxtags">
                <li><input type="checkbox" id="checkboxOne" name="filtre_ad" <?php if (isset($_GET['filtre_ad'])) {
                                                                                    echo "checked";
                                                                                } ?>><label for="checkboxOne">Filtre AD</label></li>
                <li><input type="checkbox" id="checkboxTwo" name="filtre_Compta" <?php if (isset($_GET['filtre_Compta'])) {
                                                                                        echo "checked";
                                                                                    } ?>><label for="checkboxTwo">Filtre Compta</label></li>
                <li><input type="checkbox" id="checkboxThree" name="filtre_sophos" <?php if (isset($_GET['filtre_sophos'])) {
                                                                                        echo "checked";
                                                                                    } ?>><label for="checkboxThree">Filtre Sophos</label></li>
                <li><input type="checkbox" id="checkboxFour" name="filtre_Glpi" <?php if (isset($_GET['filtre_Glpi'])) {
                                                                                    echo "checked";
                                                                                } ?>><label for="checkboxFour">Filtre Glpi</label></li>
                <li><input type="checkbox" id="checkboxFive" name="filtre_decheterie" <?php if (isset($_GET['filtre_decheterie'])) {
                                                                                            echo "checked";
                                                                                        } ?>><label for="checkboxFive">Filtre Décheterie</label></li>
                <li><input type="checkbox" id="checkboxSix" name="filtre_cse" <?php if (isset($_GET['filtre_cse'])) {
                                                                                    echo "checked";
                                                                                } ?>><label for="checkboxSix">Filtre CSE</label></li>
                <li><input type="submit" class="btn btn-outline-white" value="Filtrer"></label></li>
            </ul>

        </div>
    </form>

    <br>
    <div class="d-flex align-items-center justify-content-center h-75 flex-column gap-5">
        <form method="GET" class="d-flex">
            <?php if (isset($_GET['filtre_ad'])) {
                ?> <input type="hidden" name="filtre_ad" value='1'> 
                
                <?php
            } ?>
            <?php if (isset($_GET['filtre_Compta'])) {
                ?> <input type="hidden" name="filtre_Compta" value='1'> 
                
                <?php
            } ?>
            <?php if (isset($_GET['filtre_sophos'])) {
                ?> <input type="hidden" name="filtre_sophos" value='1'> 
                
                <?php
            } ?>
            <?php if (isset($_GET['filtre_Glpi'])) {
                ?> <input type="hidden" name="filtre_Glpi" value='1'> 
                
                <?php
            } ?>
            <?php if (isset($_GET['filtre_decheterie'])) {
                ?> <input type="hidden" name="filtre_decheterie" value='1'> 
                
                <?php
            } ?>
            <?php if (isset($_GET['filtre_cse'])) {
                ?> <input type="hidden" name="filtre_cse" value='1'> 
                
                <?php
            } ?>
            

            <input class="form-control me-1" type="search" value='<?= (isset($_GET['s'])?$_GET['s']:null)?>' placeholder="Rechercher un pc" name="s" aria-label="Search">
            <button class="btn btn-dark" type="submit" name="envoyer_recherche">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>


    <body>


        <table class="table table-dark table-striped">
            <!--Première ligne du tableau-->
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">État</th>
                    <th scope="col">Nom PC</th>
                    <th scope="col">Numéro de série</th>
                    <th scope="col">Date de sortie</th>
                    <th scope="col">AD</th>
                    <th scope="col">Sophos</th>
                    <th scope="col">Compta</th>
                    <th scope="col">GLPI</th>
                    <th scope="col">Décheterie</th>
                    <th scope="col">CSE</th>
                    <th scope="col">Commentaire</th>
                    <th scope="col">Ajouter au tableau</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($liste_pc as $pc) { // récupère toutes les données de la table ordinateur
                    //  echo "Pc_ID: " . $pc["pc_id"] . " Pc_Etat : " . $pc["pc_etat"] . " Pc_Nom : " . $pc["pc_nom"] . " Pc_Numserie : " . $pc["pc_numserie"] . " Pc_Ad : " . $pc["pc_ad"] . " Pc_Sophos : " . $pc["pc_sophos"] . " Pc_Comta : " . $pc["pc_compta"] . " Pc_Glpi : " . $pc["pc_glpi"] . " Pc_decheterie : " . $pc["pc_decheterie"] . " Pc_cse : " . $pc["pc_cse"] . " Pc_Com : " . $pc["pc_com"] . "<br />";
                ?>
                    <form action="" method="POST">
                        <tr>
                            <td><?= $pc["pc_id"] ?></td>
                            <td><span class="<?= ($pc["pc_ad"] == 1 & $pc["pc_sophos"] == 1 & $pc["pc_compta"] == 1 & $pc["pc_glpi"] == 1 ? "boutoncssgreen" : "boutoncssred") ?>"></span></td>
                            <!--Fonction ternaire -->
                            <td><INPUT type="text" NAME="pc_nom" readonly="readonly" value=<?= $pc["pc_nom"] ?>></td>
                            <td><INPUT NAME="pc_numserie" type="text" readonly="readonly" value=<?= $pc["pc_numserie"] ?>></td>
                            <td><INPUT NAME="pc_date" type="text" readonly="readonly" value=<?= $pc["pc_date"] ?>></td>
                            <td><INPUT name="ad_pc" class="checkboxcss" TYPE="checkbox" onclick="return false;" <?= ($pc["pc_ad"] == 1 ? "checked" : "") ?> /></p>
                            <td><INPUT NAME="sophos_pc" class="checkboxcss" TYPE="CHECKBOX" onclick="return false;" <?= ($pc["pc_sophos"] == 1 ? "checked" : "") ?>></p>
                            <td><INPUT NAME="compta_pc" class="checkboxcss" TYPE="CHECKBOX" onclick="return false;" <?= ($pc["pc_compta"] == 1 ? "checked" : "") ?>></p>
                            <td><INPUT NAME="glpi_pc" class="checkboxcss" TYPE="CHECKBOX" onclick="return false;" <?= ($pc["pc_glpi"] == 1 ? "checked" : "") ?>></p>
                            <td><INPUT NAME="decheterie_pc" class="checkboxcss" TYPE="CHECKBOX" onclick="return false;" <?= ($pc["pc_decheterie"] == 1 ? "checked" : "") ?>></p>
                            <td><INPUT NAME="cse_pc" class="checkboxcss" TYPE="CHECKBOX" onclick="return false;" <?= ($pc["pc_cse"] == 1 ? "checked" : "") ?>></p>
                            <td><textarea NAME="pc_com" pcs="3" cols="25" placeholder="Écrire un commentaire." readonly="readonly"><?= $pc["pc_com"] ?></textarea></td>
                            <td><button type="submit" name='action' value='ajoutertab' class="btn btn-outline-dark">Ajouter</button></td>
                            <input type="hidden" name="pc_id" value="<?= $pc["pc_id"] ?>">
                        </tr>
                    </form>
                <?php

                }
                ?>
                <script type="text/javascript" src="../js/bootstrap.min.js"></script>

                <body>

</html>

<!-- 
- Récupérer les informations de la base de données avec une requette préparée et PDO::FETCH_ASSOC : $db_connexion->fetchAll(PDO::FETCH_ASSOC); voir :
        - https://aymeric-cucherousset.fr/php-utilisation-de-pdo/ 
        ou 
        - https://www.php.net/manual/fr/pdostatement.fetchall.php
-->