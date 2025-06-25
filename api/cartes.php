<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$cartes = new Cartes($db);
$clients = new Clients($db);
$entreprises = new Entreprises($db);

$message = "";
$nbr = "0";
$codeResponse = "0";
$response = array();
$response["data"] = array();

switch ($api) {
    case 'GET':
        if (isset($_GET["id"]) && $_GET["id"] == !"") {
            $result = $cartes->read($_GET["id"]);
            
        } else if (isset($_GET["id_clients"]) && $_GET["id_clients"] == !"") {
     
            $result = $cartes->read(id_clients: $_GET["id_clients"]);
            
        }else if (isset($_GET["numero_cartes"]) && $_GET["numero_cartes"] == !"") {
     
            $result = $cartes->read(code: $_GET["numero_cartes"]);
        }
        
        else {
            $result = $cartes->read();
        }

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id_clients = $row["id_clients"];

                $result_clients = $clients->read($id_clients);

                if ($result_clients) {
                    while ($data_clients = $result_clients->fetch(PDO::FETCH_ASSOC)) {
                        $row["clients"] = $data_clients;
                    }
                }

                $id_entreprises = $row["id_entreprises"];

                $result_entreprises = $entreprises->read($id_entreprises);

               if ($result_entreprises) {
                    while ($data_entreprises = $result_entreprises->fetch(PDO::FETCH_ASSOC)) {
                        $row["entreprises"] = $data_entreprises;
                    }
                }


                $response["data"][] = $row;
            }
            $codeResponse = "100";
            $nbr = count($response["data"]);
            $response["nbr_elements"] = "$nbr";
            $response["code"] = "$codeResponse";
            $response["message"] = "Cartes récupérés avec succès";
        } else {
            $codeResponse = "0";
            $response["nbr_elements"] = "$nbr";
            $response["code"] = "$codeResponse";
            $response["message"] = "Aucune données";
        }


        break;
    case 'POST':
        if ($_POST["action"] == 'SAVE') {
	
            if (isset($_POST["id"]) && $_POST["id"] == !"") {
                $result = $cartes->save(id: $_POST["id"]);
            } else {
                $result = $cartes->save();
            }
 
            if ($result) {
                $response["inserted_id"] = isset($_POST["id"]) && $_POST["id"] == !"" ? $_POST["id"] : $db->lastInsertId();
                $message = isset($_POST["id"]) && $_POST["id"] == !"" ? "Cartes mis à jour avec succès" : "Cartes créé avec succès";
                $codeResponse = "100";
            } else {
                $response["inserted_id"] = "0";
                $message = "Echec de la création";
            }
            $response["message"] = "$message";
            $response["codeResponse"] = $codeResponse;
            
        } else if ($_POST["action"] == 'DELETE') {
            $delete = $cartes->delete(id: $_POST["id"]);
            if ($delete) {
                $response["inserted_id"] = $_POST["id"];
                $message = "Cartes supprimé avec succès";
                $response["message"] = "$message";
                $codeResponse = "100";
            } else {
                $response["inserted_id"] = "0";
                $message = "Echec de la suppression";
                $response["message"] = "$message";
            }

        } else {
            $response["code"] = "$codeResponse";
            $response["message"] = "action est requis";
        }
        break;

    default:
        $response["code"] = "$codeResponse";
        $response["message"] = "Cette requête n'est pas autorisé";
        break;
}
echo json_encode($response);