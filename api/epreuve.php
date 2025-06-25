<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$epreuve = new Epreuve($db);

$message = "";
$nbr = "0";
$codeResponse = "0";
$response = array();
$response["data"] = array();

switch ($api) {
    case 'GET':
        if(isset($_GET["idEpreuve"]) && $_GET["idEpreuve"] !=""){
            $res = $epreuve->readEpreuve($_GET["idEpreuve"]);
            if ($res) {
                $response["data"]["epreuves"] = $res;
            } else {
                $response["data"]["epreuves"] = ["Epreuves récupérés"];
            }
        } else {
            $limit = 0;
            if(isset($_GET["limit"]) && $_GET["limit"] !=""){
                $limit = $_GET["limit"];

            }  
            
            $res = $epreuve->readEpreuve($limit);
            if ($res) {
                $response["data"]["epreuves"] = $res;
            } else {
                $response["data"]["epreuves"] = ["Epreuves récupérées avec succès"];
            }
        }
        $nbr = $epreuve->nombreEpreuve();
        $response["nbr_elements"] = $nbr;
        $rsponse["message"] = "Nombre d'épreuves récupéré avec succès";
        $response["code"] = 100;

        break;
    case 'POST':
        if ($_POST["action"] == 'UPDATE_EPREUVE') {
            
            if (isset($_POST["file_pdf"]) && $_POST["file_pdf"] != "" && isset($_POST['matiere']) && isset($_POST['annee']) && isset($_FILES['file_pdf'])) {
                $new_file_pdf = $_FILES['file_pdf']['name'];
                $upload_dir = 'api/assets/fichiers';
                $upload_file = $upload_dir . basename($new_file_pdf);

                if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $upload_file)) {
                    $newData = [
                        'matiere' => $_POST['matiere'],
                        'annee' => $_POST['annee'],
                        'type' => $_POST['type'],
                        'file_pdf' => $new_file_pdf
                    ];

                    if ($epreuve->updateEpreuve($_POST["file_pdf"], $newData)) {
                        $response["code"] = "100";
                        $response["message"] = "Épreuve modifiée avec succès";
                    } else {
                        $response["code"] = "101";
                        $response["message"] = "Erreur lors de la modification de l'épreuve";
                    }
                } else {
                    $response["code"] = "102";
                    $response["message"] = "Erreur lors du téléchargement du fichier";
                }
            } else {
                $response["code"] = "103";
                $response["message"] = "Tous les champs sont requis";
            }

        }else if($_POST ["action"] == 'NOMBRE_EPREUVE'){
            if (isset($_POST["idEpreuve"]) && $_POST["idEpreuve"] != ""){
                $nbr = $epreuve->nombreEpreuve();
            }
        }else if($_POST["action"] == 'DELETE_EPREUVE'){
            if (isset($_POST["file_pdf"]) && $_POST["file_pdf"] != "") {
                $result = $enseignant->deleteEpreuve($_POST["file_pdf"]);
                if ($result) {
                    $response["deleted"] = $_POST["file_pdf"];
                    $response["message"] = "Epreuve supprimée avec succès";
                    $response["code"] = 100;
                } else {
                    $response["deleted"] = 0;
                    $response["message"] = "Échec de la suppression de l'épreuve";
                    $response["code"] = 0;
                }
            } else {
                $response["message"] = " Le nom du fichier est requis";
                $response["code"] = 0;
            }
        }
       
        break;
    default:
        $response["code"] = "$codeResponse";
        $response["message"] = "Cette requête n'est pas autorisée";
        break;
}
echo json_encode($response);
