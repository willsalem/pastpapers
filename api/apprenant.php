<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$apprenant = new Apprenant($db); 

$response = array();

try {
    switch ($api) {
        case 'GET':
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $result = $apprenant->read($_GET["id"]);
            } else if (isset($_GET["phone"]) && !empty($_GET["phone"]) && isset($_GET["password"]) && !empty($_GET["password"])) {
                $result = $apprenant->read(phone: $_GET["phone"]);
            } else {
                $result = $apprenant->read();
            }

            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if (isset($_GET["phone"]) && !empty($_GET["phone"]) && isset($_GET["password"]) && !empty($_GET["password"])) {
                        if (password_verify($_GET["password"], $row["password"])) {
                            $response["data"][] = $row;
                        }
                    } else {
                        $response["data"][] = $row;
                    }
                }
                $response["nbr_elements"] = count($response["data"]);
                $response["code"] = "100";
                $response["message"] = "Apprenants récupérés avec succès";
            } else {
                $response["nbr_elements"] = 0;
                $response["code"] = "0";
                $response["message"] = "Aucune donnée";
            }
            break;
        case 'POST':
            if (isset($_POST["action"])) {
                switch ($_POST["action"]) {
                    case 'SAVE':
                        $check_emailAp = isset($_POST["emailAp"]) && !empty($_POST["emailAp"]) ? $apprenant->check_emailAp(emailAp: $_POST["emailAp"]) : null;
                        if ($check_emailAp && $check_emailAp->rowCount() > 0) {
                            $response["code"] = "101";
                            $response["message"] = "Cet email est déjà utilisé";
                        } else {
                            $result = $apprenant->save();
                            if ($result) {
                                $response["inserted_id"] = $db->lastInsertId();
                                $response["code"] = "100";
                                $response["message"] = "Apprenant créé avec succès";
                            } else {
                                $response["inserted_id"] = "0";
                                $response["code"] = "0";
                                $response["message"] = "Echec de la création";
                            }
                        }
                        break;
                    case 'UPDATE_PASSWORD':
                        $update = $apprenant->update_password(emailAp: $_POST["emailAp"], new_password: $_POST["new_password"]);
                        if ($update) {
                            $response["code"] = "100";
                            $response["message"] = "Mot de passe mis à jour avec succès";
                        } else {
                            $response["code"] = "0";
                            $response["message"] = "Echec de la mise à jour";
                        }
                        break;
                    case 'DELETE':
                        $delete = $apprenant->delete(id: $_POST["id"]);
                        if ($delete) {
                            $response["code"] = "100";
                            $response["message"] = "Apprenant supprimé avec succès";
                        } else {
                            $response["code"] = "0";
                            $response["message"] = "Echec de la suppression";
                        }
                        break;
                    default:
                        $response["code"] = "0";
                        $response["message"] = "Action non reconnue";
                        break;
                }
            } else {
                $response["code"] = "0";
                $response["message"] = "Action requise";
            }
            break;
        default:
            $response["code"] = "0";
            $response["message"] = "Méthode non autorisée";
            break;
    }
} catch (Exception $e) {
    $response["code"] = "0";
    $response["message"] = "Erreur: " . $e->getMessage();
}

echo json_encode($response);
?>
