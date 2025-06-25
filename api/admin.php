<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$admin = new Admin($db);
$universite = new Universite($db);

$message = "";
$nbr = 0;
$codeResponse = 0;
$response = array();
$response["data"] = array();

// Vérifiez si l'utilisateur est un administrateur
/*$isAdmin = $admin->authenticate(); // Vous devez implémenter cette méthode dans la classe Admin

if (!$isAdmin) {
    $response["message"] = "Accès refusé. Authentification requise.";
    echo json_encode($response);
    exit();
}*/

switch ($api) {
    case 'GET':
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $result = $admin->read($_GET["id"]);
        } else {
            $result = $admin->read();
        }

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $response["data"][] = $row;
            }
            $codeResponse = 100;
            $nbr = count($response["data"]);
            $response["nbr_elements"] = $nbr;
            $response["code"] = $codeResponse;
            $response["message"] = "Admins récupérés avec succès";
        } else {
            $response["nbr_elements"] = $nbr;
            $response["code"] = $codeResponse;
            $response["message"] = "Aucune donnée";
        }

        // Récupération d'universités de BDD
      /*  $result = $universite->read();

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $response["data"][] = $row;
            }
            $codeResponse = 100;
            $nbr = count($response["data"]);
            $response["nbr_elements"] = $nbr;
            $response["code"] = $codeResponse;
            $response["message"] = "Universités récupérées avec succès";
        } else {
            $response["nbr_elements"] = $nbr;
            $response["code"] = $codeResponse;
            $response["message"] = "Aucune donnée d'université trouvée";
        }*/
        break;

    case 'POST':
        if (isset($_POST["action"])) {
            if ($_POST["action"] == 'SAVE_ADMIN') {
                if (isset($_POST["idAdmin"]) && $_POST["idAdmin"] != "") {
                    $result = $admin->save($_POST["idAdmin"]);
                } else {
                    $result = $admin->save();
                }

                if ($result) {
                    $response["inserted_id"] = isset($_POST["idAdmin"]) && $_POST["idAdmin"] != "" ? $_POST["idAdmin"] : $db->lastInsertId();
                    $message = isset($_POST["idAdmin"]) && $_POST["idAdmin"] != "" ? "Admin mis à jour avec succès" : "Admin créé avec succès";
                    $codeResponse = 100;
                } else {
                    $response["inserted_id"] = 0;
                    $message = "Échec de la création";
                }
                $response["message"] = $message;
                $response["codeResponse"] = $codeResponse;

            } else if ($_POST["action"] == 'DELETE_ADMIN') {
                $delete = $admin->delete($_POST["idAdmin"]);
                if ($delete) {
                    $response["deleted_id"] = $_POST["idAdmin"];
                    $message = "Admin supprimé avec succès";
                    $codeResponse = 100;
                } else {
                    $response["deleted_id"] = 0;
                    $message = "Échec de la suppression";
                }
                $response["message"] = $message;
                $response["codeResponse"] = $codeResponse;

            } /*else if ($_POST["action"] == 'CREATE_UNIVERSITY') {
                $result = $universite->createUniversity($_POST);
                if ($result) {
                    $response["inserted_id"] = $db->lastInsertId();
                    $message = "Université créée avec succès";
                    $codeResponse = 100;
                } else {
                    $response["inserted_id"] = 0;
                    $message = "Échec de la création de l'université";
                }
                $response["message"] = $message;
                $response["codeResponse"] = $codeResponse;

            } else if ($_POST["action"] == 'UPDATE_UNIVERSITY') {
                if (isset($_POST["idUni"]) && $_POST["idUni"] != "") {
                    $result = $universite->updateUniversity($_POST["idUni"], $_POST);
                    if ($result) {
                        $message = "Université mise à jour avec succès";
                        $codeResponse = 100;
                    } else {
                        $message = "Échec de la mise à jour de l'université";
                    }
                    $response["message"] = $message;
                    $response["codeResponse"] = $codeResponse;
                } else {
                    $response["message"] = "ID de l'université requis";
                }

            } else if ($_POST["action"] == 'DELETE_UNIVERSITY') {
                if (isset($_POST["idUni"]) && $_POST["idUni"] != "") {
                    $result = $universite->deleteUniversity($_POST["idUni"]);
                    if ($result) {
                        $response["deleted_id"] = $_POST["idUni"];
                        $message = "Université supprimée avec succès";
                        $codeResponse = 100;
                    } else {
                        $response["deleted_id"] = 0;
                        $message = "Échec de la suppression de l'université";
                    }
                    $response["message"] = $message;
                    $response["codeResponse"] = $codeResponse;
                } else {
                    $response["message"] = "ID de l'université requis";
                }
            } else {
                $response["code"] = $codeResponse;
                $response["message"] = "Action non reconnue";
            }*/
        
        } else {
            $response["code"] = $codeResponse;
            $response["message"] = "Action est requise";
        }
        break;

        // Vérifier si la méthode de la requête est POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier si les données POST requises sont présentes
            if (isset($_POST['emailAdmin']) && isset($_POST['passwordAdmin'])) {
                // Récupérer les données POST
                $emailAdmin = $_POST['emailAdmin'];
                $passwordAdmin = $_POST['passwordAdmin'];
                
                // Authentifier l'utilisateur
                $authResult = $admin->authentificate($emailAdmin, $passwordAdmin);

                // Vérifier le résultat de l'authentification
                if ($authResult !== false) {
                    // Authentification réussie
                    $response = array(
                        'success' => true,
                        'message' => 'Authentification réussie',
                        'user' => $authResult
                    );
                } else {
                    // Authentification échouée
                    $response = array(
                        'success' => false,
                        'message' => 'Authentification échouée. Veuillez vérifier vos identifiants.'
                    );
                }
            } else {
                // Données POST manquantes
                $response = array(
                    'success' => false,
                    'message' => 'Paramètres manquants. Veuillez fournir une adresse e-mail et un mot de passe.'
                );
            }
        } else {
            // Méthode de requête incorrecte
            $response = array(
                'success' => false,
                'message' => 'Méthode de requête incorrecte. Cette API accepte uniquement les requêtes POST.'
            );
        }
    
    default:
        $response["code"] = $codeResponse;
        $response["message"] = "Cette requête n'est pas autorisée";
        break;
    } 

echo json_encode($response);
?>
