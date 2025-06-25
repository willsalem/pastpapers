<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Inclure votre fichier de connexion à la base de données
include_once('../core/initialize.php');

// Créez une connexion à la base de données
$database = new PD();
$db = $database->getConnection();

// Préparez votre requête SQL
$query = "SELECT COUNT(*) as nbr_elements FROM epreuves";
$stmt = $db->prepare($query);
$stmt->execute();

// Récupérer le nombre total d'épreuves
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_epreuves = $row['nbr_elements'];

// Envoyer la réponse en JSON
echo json_encode(array("nbr_elements" => $total_epreuves));
?>
