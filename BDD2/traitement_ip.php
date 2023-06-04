<?php
require("connectBDD2.php");

$db_connexion = new PDO('mysql:host=localhost;dbname=projet_1', 'root', '');

// Vérifie si les cases sont remplies ou pas
if (!empty($_POST['action'])) {

    if ($_POST['action'] == 'ajout') {

        $ip_ip = $_POST['ip_ip'];
        // Vérifie si l'IP existe déjà dans la BDD
        $requete = $db_connexion->prepare("SELECT * FROM ip WHERE ip_ip = :ip_ip");
        $requete->bindParam(':ip_ip', $ip_ip);
        $requete->execute();
        $result = $requete->fetch();

        if (!$result) {
            $site_ip = $_POST['ip_site'];
            $vlan_ip = $_POST["ip_vlan"];
            $commentaire_ip = $_POST["ip_commentaire"];

            $requete = $db_connexion->prepare("INSERT INTO ip (ip_ip, ip_site, ip_vlan, ip_commentaire) 
    VALUES (:ip_ip, :ip_site, :ip_vlan, :ip_commentaire)");
            $requete->bindParam(':ip_ip', $_POST['ip_ip']);
            $requete->bindParam(':ip_site', $_POST['ip_site']);
            $requete->bindParam(':ip_vlan', $_POST['ip_vlan']);
            $requete->bindParam(':ip_commentaire', $_POST['ip_commentaire']);
            $requete->execute();
            header('Location: tableau_ip.php');
        } else {
            echo "<script>alert('L IP renseignée existe déjà.');</script>";
        }
    } else if ($_POST['action'] == 'modification') {

        $ip_id = $_POST['ip_id'];
        $ip_ip = $_POST['ip_ip'];

        // Récupère la valeur existante depuis la BDD
        $requete = $db_connexion->prepare("SELECT ip_id FROM ip WHERE ip_ip = :ip_ip AND ip_id != :ip_id");
$requete->bindParam(':ip_id', $ip_id);
$requete->bindParam(':ip_ip', $ip_ip);
$requete->execute();
$result = $requete->fetch();


if (!$result) {
    $site_ip = $_POST['ip_site'];
    $vlan_ip = $_POST["ip_vlan"];
    $commentaire_ip = $_POST["ip_commentaire"];

    $requete = $db_connexion->prepare("UPDATE ip SET ip_ip = :ip_ip, ip_site = :ip_site, ip_vlan = :ip_vlan, ip_commentaire = :ip_commentaire WHERE ip_id = :ip_id");
    $requete->bindParam(':ip_id', $ip_id);
    $requete->bindParam(':ip_ip', $ip_ip);
    $requete->bindParam(':ip_site', $site_ip);
    $requete->bindParam(':ip_vlan', $vlan_ip);
    $requete->bindParam(':ip_commentaire', $commentaire_ip);
    $requete->execute();
    header('Location: tableau_ip.php');
} else {
    echo "<script>alert('L IP renseignée existe déjà.');</script>";
}
} else if ($_POST['action'] == 'supprimer') {
        $id_ip = $_POST["ip_id"];
        $requete = $db_connexion->prepare("UPDATE ip SET ip_delete = :ip_delete WHERE ip_id = :ip_id"); // préparation de la Requête SQL
        $requete->bindValue(':ip_delete', 1);
        $requete->bindValue(':ip_id', $id_ip);
        $requete->execute(); // Exécution de la requête
        $requete->closeCursor();
        header('Location: tableau_ip.php');
    } 
}