<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$telechargement = new Telechargement($db);
$epreuve = new Epreuve($db);
$apprenant = new Apprenant($db);

$message = "";
$nbr = "0";
$codeResponse = "0";
$response = array();
$response["data"] = array();

switch ($api) {
    case 'GET':
        if (isset($_GET["idEpreuve"]) && isset($_GET["id"])) {
            $telechargement->idTelechargement = $_GET["idEpreuve"];
            $result = $telechargement->readOne();
            if ($result) {
                $response["data"]["telechargement"] = $result;
                $response["message"] = "Téléchargement récupéré avec succès";
                $response["code"] = 100;
            } else {
                $response["message"] = "Aucun téléchargement trouvé";
                $response["code"] = 0;
            }
        } else {
            $results = $telechargement->readAll();
            if ($results) {
                $response["code"] = 100;
                $response["data"]["telechargements"] = $results;
                $nbr = count($response["data"]["telechargements"]);
                $response["nbr_elements"] = "$nbr";
                $response["message"] = "Liste des téléchargements récupérée avec succès";
               
            } else {
                $response["message"] = "Aucun téléchargement trouvé";
                $response["code"] = 0;
            }
        }
        break;

    // Exemple de débogage dans votre API PHP
    case 'POST':
        $rawData = file_get_contents('php://input');
        error_log("Données brutes reçues: " . $rawData);
        
        $data = json_decode($rawData, true);
        error_log("Données décodées: " . print_r($data, true));
    
        if (isset($data["action"]) && $data["action"] == 'TELECHARGEMENT') {
            error_log("Action TELECHARGEMENT détectée");
            
            if (isset($data["idEpreuve"])) {
                error_log("idEpreuve reçu: " . $data["idEpreuve"]);
                
                $telechargement->idEpreuve = $data["idEpreuve"];
                $telechargement->dateTelechargement = date('Y-m-d H:i:s');
                $telechargement->nombreTelechargement = 1;
    
                error_log("Tentative de création du téléchargement");
                $result = $telechargement->create();
                error_log("Résultat de la création: " . ($result ? "Succès" : "Échec"));
    
                if ($result) {
                    $response["message"] = "Téléchargement enregistré avec succès";
                    $response["code"] = 100;
                } else {
                    $response["message"] = "Erreur lors de l'enregistrement du téléchargement";
                    $response["code"] = 0;
                }
            } else {
                error_log("idEpreuve manquant dans les données reçues");
                $response["message"] = "idEpreuve est requis";
                $response["code"] = 0;
            }
        } else {
            error_log("Action non reconnue ou manquante");
            $response["message"] = "Action non reconnue ou manquante";
            $response["code"] = 0;
        }
        break;
    
    
}

echo json_encode($response);
?>
