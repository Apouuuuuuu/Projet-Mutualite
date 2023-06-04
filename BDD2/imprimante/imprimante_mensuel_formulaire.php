<?php
include("traitement_imprimante.php");
?>


<!DOCTYPE html>
<html>

<head>
    <title>Tableau mensuel d'ajout</title>
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
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/imprimante_mensuel_formulaire.php"><strong>Tableau mensuel</strong></a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/cout_ht_imprimante.php">Formulaire</a>
                    <a class="nav-link" href="http://localhost/projet2/BDD2/imprimante/liste_site.php">Liste sites</a>
                </div>
            </div>
        </div>
    </nav>



    <br><br><br>
    

            <form method="GET" action="imprimante_mensuel_tab.php">
                <label for="annee_page">Année:</label>
                <select name="annee_page" id="annee_page">
                    <?php
                    
                    $anneeActuelle = '2020';
                    for ($annee = $anneeActuelle; $annee <= 2099; $annee++) {
                        echo "<option value=\"$annee\">$annee</option>";
                    }

                    $moisNoms = [
                        "01" => 'janvier',
                        "02" => 'février',
                        "03" => 'mars',
                        "04" => 'avril',
                        "05" => 'mai',
                        "06" => 'juin',
                        "07" => 'juillet',
                        "08" => 'août',
                        "09" => 'septembre',
                        "10" => 'octobre',
                        "11" => 'novembre',
                        "12" => 'décembre'
                    ];


                    for ($annee = $anneeActuelle; $annee <= 2099; $annee++) {
                        echo "<option value=\"$annee\">$annee</option>";
                    }
                    ?>
                </select>

                <label for="mois_page">Mois:</label>
                <select name="mois_page" id="mois_page">
                    <?php
                    foreach ($moisNoms as $numero => $nom) {
                        echo "<option value=\"$numero\">$nom</option>";
                    }
                    ?>
                </select>

                <input type="submit" class="btn btn-outline-dark" name="aller_page" value="Aller à la page">
            </form>




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