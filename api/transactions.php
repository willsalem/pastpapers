<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$transactions = new Transactions($db);
$clients = new Clients($db);
$entreprises = new Entreprises($db);
$cartes = new Cartes($db);

$message = "";
$nbr = "0";
$codeResponse = "0";
$response = array();
$response["data"] = array();

switch ($api) {
    case 'GET':
        if (isset($_GET["id"]) && $_GET["id"] == !"") {
            $result = $transactions->read($_GET["id"]);
        }else  if (isset($_GET["id_cartes"]) && $_GET["id_cartes"] == !"") {
            $result = $transactions->read(id_cartes: $_GET["id_cartes"]);
            
        }
        else  if (isset($_GET["id_clients"]) && $_GET["id_clients"] == !"") {
            $result = $transactions->read(id_clients: $_GET["id_clients"]);
            
        }
         else {
            $result = $transactions->read();
        }

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id_clients = $row["id_clients"];

                $result_clients = $clients->read($id_clients);

                if ($result_clients) {
                    while ($data = $result_clients->fetch(PDO::FETCH_ASSOC)) {
                        $row["clients"] = $data;
                    }
                }

                $id_clients = $row["id_clients"];

                $result_clients = $clients->read($id_clients);

                if ($result_clients) {
                    while ($data = $result_clients->fetch(PDO::FETCH_ASSOC)) {
                        $row["clients"] = $data;
                    }
                }

                 $id_cartes = $row["id_cartes"];

                $result_cartes = $cartes->read($id_cartes);

                if ($result_clients) {
                    while ($data = $result_cartes->fetch(PDO::FETCH_ASSOC)) {
                        $row["cartes"] = $data;
                    }
                }

                $id_cartes = $row["id_cartes"];

                $result_cartes = $cartes->read($id_cartes);

                if ($result_cartes) {
                    while ($data = $result_cartes->fetch(PDO::FETCH_ASSOC)) {
                        $row["cartes"] = $data;
                    }
                }

                $id_entreprises = $row["id_entreprises"];

                $result_entreprises = $entreprises->read($id_entreprises);

                if ($result_entreprises) {
                    while ($data = $result_entreprises->fetch(PDO::FETCH_ASSOC)) {

                      
                        $row["entreprises"] = $data;

                    }
                }

                $response["data"][] = $row;
            }
            $codeResponse = "100";
            $nbr = count($response["data"]);
            $response["nbr_elements"] = "$nbr";
            $response["code"] = "$codeResponse";
            $response["message"] = "Transactions récupérés avec succès";
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
                $result = $transactions->save(id: $_POST["id"]);
            } else {
                $result = $transactions->save();
            }

            if ($result) {
                $response["inserted_id"] = isset($_POST["id"]) && $_POST["id"] == !"" ? $_POST["id"] : $db->lastInsertId();
                $message = isset($_POST["id"]) && $_POST["id"] == !"" ? "Transaction mis à jour avec succès" : "Transaction créé avec succès";
                $codeResponse = "100";
            } else {
                $response["inserted_id"] = "0";
                $message = "Echec de la création";
            }
            $response["message"] = "$message";
        } else if ($_POST["action"] == 'DELETE') {
            $delete = $operation->delete(id: $_POST["id"]);
            if ($delete) {
                $response["inserted_id"] = $_POST["id"];
                $message = "Transaction supprimé avec succès";
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