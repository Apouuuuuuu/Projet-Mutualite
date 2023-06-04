<?php
require("connectBDD2.php");

//Input hidden
$db_connexion = new PDO('mysql:host=localhost;dbname=projet_1', 'root', '');

// Vérifie si les cases sont remplies ou pas
if (!empty($_POST['action'])) {

    if ($_POST['action'] == 'ajout') {
        //Conversion des données pour la BDD
        $nom_pc = $_POST["pc_nom"];
        $numserie_pc = $_POST["pc_numserie"];
        $date_pc = $_POST["pc_date"];
        $ad_pc = (!empty($_POST["ad_pc"]) ? 1 : 0); // Si le bouton est remplit =  1 (pour sauvegarder son état)
        $sophos_pc = (!empty($_POST["sophos_pc"]) ? 1 : 0);
        $compta_pc = (!empty($_POST["compta_pc"]) ? 1 : 0);
        $glpi_pc = (!empty($_POST["glpi_pc"]) ? 1 : 0);
        $decheterie_pc = (!empty($_POST["decheterie_pc"]) ? 1 : 0);
        $cse_pc = (!empty($_POST["cse_pc"]) ? 1 : 0);
        $com_pc = $_POST["pc_com"];
  

        //Requete SQL pour envoyer les données vers la BDD
        $requete = $db_connexion->prepare("INSERT INTO ordinateur ( pc_nom, pc_numserie, pc_date, pc_ad, pc_sophos, pc_compta, pc_glpi, pc_decheterie, pc_cse, pc_com) VALUES (:pc_nom, :pc_numserie, :pc_date, :pc_ad, :pc_sophos, :pc_compta, :pc_glpi, :pc_decheterie, :pc_cse, :pc_com)"); // préparation de la Requête SQL
        // Définit la valeur des variables
        $requete->bindValue(':pc_nom', $nom_pc);
        $requete->bindValue(':pc_numserie', $numserie_pc);
        $requete->bindValue(':pc_date', $date_pc);
        $requete->bindValue(':pc_ad', $ad_pc);
        $requete->bindValue(':pc_sophos', $sophos_pc);
        $requete->bindValue(':pc_compta', $compta_pc);
        $requete->bindValue(':pc_glpi', $glpi_pc);
        $requete->bindValue(':pc_decheterie', $decheterie_pc);
        $requete->bindValue(':pc_cse', $cse_pc);
        $requete->bindValue(':pc_com', $com_pc);
        $requete->execute(); // Exécution de la requête

        header('Location: tableau.php'); // Renvoit vers la même page après un F5 
    } else if ($_POST['action'] =='modification') {

        $id_pc = $_POST["pc_id"];
        $nom_pc = $_POST["pc_nom"];
        $numserie_pc = $_POST["pc_numserie"];
        $date_pc = $_POST["pc_date"];
        $ad_pc = (!empty($_POST["ad_pc"]) ? 1 : 0); // Si le bouton est remplit =  1 (pour sauvegarder son état)
        $sophos_pc = (!empty($_POST["sophos_pc"]) ? 1 : 0);
        $compta_pc = (!empty($_POST["compta_pc"]) ? 1 : 0);
        $glpi_pc = (!empty($_POST["glpi_pc"]) ? 1 : 0);
        $decheterie_pc = (!empty($_POST["decheterie_pc"]) ? 1 : 0);
        $cse_pc = (!empty($_POST["cse_pc"]) ? 1 : 0);
        $com_pc = $_POST["pc_com"];
        
        $requete = $db_connexion->prepare("UPDATE ordinateur SET pc_nom = :pc_nom, pc_numserie = :pc_numserie, pc_date = :pc_date, pc_ad = :pc_ad, pc_sophos = :pc_sophos, pc_compta = :pc_compta, pc_glpi = :pc_glpi, pc_decheterie = :pc_decheterie, pc_cse = :pc_cse, pc_com = :pc_com WHERE pc_id = :pc_id"); // préparation de la Requête SQL
        $requete->bindValue(':pc_id', $id_pc); // Définit la valeur de la variable :nom (de type chaîne de caractères) égale à 'Dupont'
        $requete->bindValue(':pc_nom', $nom_pc);
        $requete->bindValue(':pc_numserie', $numserie_pc);
        $requete->bindValue(':pc_date', $date_pc);
        $requete->bindValue(':pc_ad', $ad_pc);
        $requete->bindValue(':pc_sophos', $sophos_pc);
        $requete->bindValue(':pc_compta', $compta_pc);
        $requete->bindValue(':pc_glpi', $glpi_pc);
        $requete->bindValue(':pc_decheterie', $decheterie_pc);
        $requete->bindValue(':pc_cse', $cse_pc);
        $requete->bindValue(':pc_com', $com_pc);
        $requete->execute(); // Exécution de la requête
        $requete->closeCursor(); // Ferme le curseur associé à la requête

        header('Location: tableau.php'); // Renvoit vers la même page après un F5 

    } else if ($_POST['action'] == 'archiver') { // Faire un update et le montrer si = 1 et pas si =0 pour éviter de perdre la donnée dans la BDD
        
        $id_pc = $_POST["pc_id"];
        $requete = $db_connexion->prepare("UPDATE ordinateur SET delete_d = :delete_d WHERE pc_id = :pc_id"); // préparation de la Requête SQL
        $requete->bindValue(':delete_d', 0);
        $requete->bindValue(':pc_id', $id_pc);
        $requete->execute(); // Exécution de la requête
        $requete->closeCursor(); // Ferme le curseur associé à la requête
        header('Location: tableau.php'); // Renvoit vers la même page après un F5 
        
    } else if ($_POST['action'] == 'ajoutertab') { // Faire un update et le montrer si = 1 et pas si =0 pour éviter de perdre la donnée dans la BDD
        
        $id_pc = $_POST["pc_id"];
        $requete = $db_connexion->prepare("UPDATE ordinateur SET delete_d = :delete_d WHERE pc_id = :pc_id"); // préparation de la Requête SQL
        $requete->bindValue(':delete_d', 1);
        $requete->bindValue(':pc_id', $id_pc);
        $requete->execute(); // Exécution de la requête
        $requete->closeCursor(); // Ferme le curseur associé à la requête
        header('Location: archive.php'); // Renvoit vers la même page après un F5 
    }
    die(); // Break
} 
?>
