<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];
$universite = new Universite($db);
$admin = new Admin($db);

$response = array();
$response["data"] = array();

// Fonction pour vérifier l'authentification
function isAuthenticated($admin) {
    // Vous devez ajouter une logique pour vérifier si l'utilisateur est authentifié
    // Par exemple, en utilisant des sessions ou des tokens JWT
    // Retourne true si authentifié, sinon false
    return true; // Changer ceci en fonction de votre logique d'authentification
}

if (!isAuthenticated($admin)) {
    $response["message"] = "Accès refusé. Authentification requise.";
    echo json_encode($response);
    exit();
}

switch ($api) {
    case 'GET':
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $result = $universite->read($_GET["id"]);
        } else {
            $result = $universite->read();
        }

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $response["data"][] = $row;
            }
            $response["nbr_elements"] = count($response["data"]);
            $response["code"] = 100;
            $response["message"] = "Universités récupérées avec succès";
        } else {
            $response["nbr_elements"] = 0;
            $response["code"] = 0;
            $response["message"] = "Aucune donnée";
        }
        break;

    case 'POST':
        if (isset($_POST["action"])) {
            if ($_POST["action"] == 'CREATE_UNIVERSITY') {
                $result = $universite->createUniversity($_POST);
                if ($result) {
                    $response["inserted_id"] = $db->lastInsertId();
                    $response["message"] = "Université créée avec succès";
                    $response["code"] = 100;
                } else {
                    $response["inserted_id"] = 0;
                    $response["message"] = "Échec de la création de l'université";
                    $response["code"] = 0;
                }
            } else if ($_POST["action"] == 'UPDATE_UNIVERSITY') {
                if (isset($_POST["idUni"]) && $_POST["idUni"] != "") {
                    $result = $universite->updateUniversity($_POST["idUni"], $_POST);
                    if ($result) {
                        $response["message"] = "Université mise à jour avec succès";
                        $response["code"] = 100;
                    } else {
                        $response["message"] = "Échec de la mise à jour de l'université";
                        $response["code"] = 0;
                    }
                } else {
                    $response["message"] = "ID de l'université requis";
                    $response["code"] = 0;
                }
            } else if ($_POST["action"] == 'AUTHENTIFICATE') {
                if (isset($_POST['emailEnseignant']) && isset($_POST['passwordEnseignant'])) {
                    // Récupérer les données POST
                    $email = $_POST['emailEnseignant'];
                    $password = $_POST['passwordEnseignant'];
                    
                    // Authentifier l'utilisateur
                    $authResult = $enseignant->authentificate($email, $password);
    
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
                $response["code"] = "$codeResponse";
                $response["message"] = "action est requis";
            }
        }else if ($_POST["action"] == 'DELETE_UNIVERSITY') {
                if (isset($_POST["idUni"]) && $_POST["idUni"] != "") {
                    $result = $universite->deleteUniversity($_POST["idUni"]);
                    if ($result) {
                        $response["deleted_id"] = $_POST["idUni"];
                        $response["message"] = "Université supprimée avec succès";
                        $response["code"] = 100;
                    } else {
                        $response["deleted_id"] = 0;
                        $response["message"] = "Échec de la suppression de l'université";
                        $response["code"] = 0;
                    }
                } else {
                    $response["message"] = "ID de l'université requis";
                    $response["code"] = 0;
                }
            } else {
                $response["message"] = "Action non reconnue";
                $response["code"] = 0;
            }
        break;

    default:
        $response["code"] = 0;
        $response["message"] = "Cette requête n'est pas autorisée";
        break;
}

echo json_encode($response);
?>
