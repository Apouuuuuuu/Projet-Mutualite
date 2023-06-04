<?php
require("../connectBDD2.php");

//Input hidden
$db_connexion = new PDO('mysql:host=localhost;dbname=projet_1', 'root', '');

// Vérifie si les cases sont remplies ou pas
if (!empty($_POST['action'])) {

    if ($_POST['action'] == 'modifiercoutht') {

        $id_imprimante = $_POST["imprimante_id"];
        $numserie_imprimante = $_POST["imprimante_numserie"];
        $ref_imprimante = $_POST["imprimante_ref"];
        $site_imprimante = $_POST["imprimante_site"];
        $loc_mens_imprimante = $_POST["imprimante_loc_mens_prix"];
        $nb_imprimante = $_POST["imprimante_nb_prix"];
        $couleur_imprimante = $_POST["imprimante_couleur_prix"];

        $requete = $db_connexion->prepare("UPDATE imprimante SET imprimante_numserie = :imprimante_numserie, imprimante_ref = :imprimante_ref, imprimante_site = :imprimante_site, imprimante_loc_mens_prix = :imprimante_loc_mens_prix, imprimante_nb_prix = :imprimante_nb_prix, imprimante_couleur_prix = :imprimante_couleur_prix WHERE imprimante_id = :imprimante_id"); // préparation de la Requête SQL
        $requete->bindValue(':imprimante_id', $id_imprimante); // Définit la valeur de la variable :nom (de type chaîne de caractères) égale à 'Dupont'
        $requete->bindValue(':imprimante_numserie', $numserie_imprimante);
        $requete->bindValue(':imprimante_site', $site_imprimante);

        $requete->bindValue(':imprimante_ref', $ref_imprimante);
        $requete->bindValue(':imprimante_loc_mens_prix', $loc_mens_imprimante);
        $requete->bindValue(':imprimante_nb_prix', $nb_imprimante);
        $requete->bindValue(':imprimante_couleur_prix', $couleur_imprimante);

        $requete->execute(); // Exécution de la requête
        $requete->closeCursor(); // Ferme le curseur associé à la requête

        header('Location: tableau_imprimante.php'); // Renvoit vers la même page après un F5 

    } else if ($_POST['action'] == 'supprimercoutht') {
        $id_imprimante = $_POST["imprimante_id"];
        $requete = $db_connexion->prepare("UPDATE imprimante SET imprimante_delete = :imprimante_delete WHERE imprimante_id = :imprimante_id"); // préparation de la Requête SQL
        $requete->bindValue(':imprimante_delete', 1);
        $requete->bindValue(':imprimante_id', $id_imprimante);
        $requete->execute(); // Exécution de la requête
        $requete->closeCursor();
        header('Location: tableau_imprimante.php');

    } else if ($_POST['action'] == 'modifiermensuel') {
        $id_imprimante = $_POST["imprimante_id"];
        $nb_quantite = $_POST["quantite_nb"];
        $couleur_quantite = $_POST["quantite_couleur"];
        $numserie_imprimante = $_POST["imprimante_numserie"];
        $site_imprimante = $_POST["imprimante_site"];
        $ref_imprimante = $_POST["imprimante_ref"];
    
        $requete = $db_connexion->prepare("UPDATE quantite_mensuel_impression
            INNER JOIN imprimante ON imprimante.imprimante_id = quantite_mensuel_impression.id_imprimante
            SET quantite_mensuel_impression.quantite_nb = :quantite_nb,
            quantite_mensuel_impression.quantite_couleur = :quantite_couleur,
            imprimante.imprimante_numserie = :imprimante_numserie,
            imprimante.imprimante_site = :imprimante_site,
            imprimante.imprimante_ref = :imprimante_ref
            WHERE imprimante.imprimante_id = :imprimante_id");
    
        $requete->bindValue(':quantite_nb', $nb_quantite);
        $requete->bindValue(':quantite_couleur', $couleur_quantite);
        $requete->bindValue(':imprimante_numserie', $numserie_imprimante);
        $requete->bindValue(':imprimante_site', $site_imprimante);
        $requete->bindValue(':imprimante_ref', $ref_imprimante);
        $requete->bindValue(':imprimante_id', $id_imprimante);
    
        $requete->execute();
        $requete->closeCursor();
    
        header('Location: imprimante_mensuel_formulaire.php');
    } else if ($_POST['action'] == 'modifiersite') {

        $id_site = $_POST["site_id"];
        $nom_site = $_POST["site_nom"];
  

        $requete = $db_connexion->prepare("UPDATE options_site SET site_nom = :site_nom WHERE site_id = :site_id"); // préparation de la Requête SQL
        $requete->bindValue(':site_id', $id_site);
        $requete->bindValue(':site_nom', $nom_site);

        $requete->execute(); // Exécution de la requête
        $requete->closeCursor(); // Ferme le curseur associé à la requête
        
        header('Location: liste_site.php'); // Renvoit vers la même page après un F5 

    } else if ($_POST['action'] == 'supprimersite') {

        $id_site = $_POST["site_id"];
    
        $requete = $db_connexion->prepare("DELETE FROM options_site WHERE site_id = :site_id"); // préparation de la Requête SQL
        $requete->bindValue(':site_id', $id_site);
    
        $requete->execute(); // Exécution de la requête
        $requete->closeCursor(); // Ferme le curseur associé à la requête
    
        header('Location: liste_site.php'); // Renvoit vers la même page après un F5 
    }
    
    die(); // Break
}
