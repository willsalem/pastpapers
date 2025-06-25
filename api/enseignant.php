<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$enseignant = new Enseignant($db);

$message = "";
$nbr = "0";
$codeResponse = "0";
$response = array();
$response["data"] = array();

switch ($api) {
    case 'GET':
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $result = $enseignant->read($_GET["id"]);
        } else {
            $result = $enseignant->read();
        }

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if (isset($_GET["email"]) && $_GET["email"] != "" && isset($_GET["password"]) && $_GET["password"] != "") {
                    if (password_verify($_GET["password"], $row["password"])) {
                        $response["data"][] = $row;
                    }
                } else {
                    $response["data"][] = $row;
                }
            }
            $codeResponse = "100";
            $nbr = count($response["data"]);
            $response["nbr_elements"] = "$nbr";
            $response["code"] = "$codeResponse";
            $response["message"] = "Enseignant récupérés avec succès";
        } else {
            $codeResponse = "0";
            $response["nbr_elements"] = "$nbr";
            $response["code"] = "$codeResponse";
            $response["message"] = "Aucune donnée";
        }
        
        

        break;
    case 'POST':
        if ($_POST["action"] == 'SAVE_ENSEIGNANT') {
            $check_email = null;
            $check_phone = null;
            $path_image = "";
            

            if ($check_email != null && $check_email->rowCount() > 0) {
                $response["code"] = "101";
                $response["message"] = "Cet email est déjà utilisé";
            } else if ($check_phone != null && $check_phone->rowCount() > 0) {
                $response["code"] = "101";
                $response["message"] = "Ce numéro a déjà été utilisé";
            } else {
                if (isset($_POST["idEnseignant"]) && $_POST["idEnseignant"] != "") {
                    $result = $enseignant->save($_POST["idEnseignant"]);
                } else {
                    $result = $enseignant->save();
                }

                if ($result) {
                    $response["inserted_id"] = isset($_POST["idEnseignant"]) && $_POST["idEnseignant"] != "" ? $_POST["idEnseignant"] : $db->lastInsertId();
                    $message = isset($_POST["idEnseignant"]) && $_POST["idEnseignant"] != "" ? "Enseignant mis à jour avec succès" : "Enseignant créé avec succès";
                    $codeResponse = "100";
                } else {
                    $response["inserted_id"] = "0";
                    $message = "Échec de la création";
                }
                $response["message"] = "$message";
            }
        } else if ($_POST["action"] == 'UPDATE_PASSWORD') {
            $update = $enseignant->update_password($_POST["email"], $_POST["new_password"]);
            $response["inserted_id"] = $_POST["id"];
            if ($update) {
                $message = "Mot de passe mis à jour avec succès";
                $codeResponse = "100";
            } else {
                $message = "Échec de la mise à jour";
            }
            $response["message"] = "$message";
        } else if ($_POST["action"] == 'DELETE') {
            $delete = $enseignant->delete($_POST["id"]);
            if ($delete) {
                $response["inserted_id"] = $_POST["id"];
                $message = "Enseignant supprimé avec succès";
                $response["message"] = "$message";
                $codeResponse = "100";
            } else {
                $response["inserted_id"] = "0";
                $message = "Échec de la suppression";
                $response["message"] = "$message";
            }
        } else if ($_POST["action"] == 'ADD_EPREUVE') {
            if (isset($_POST['matiere']) && isset($_POST['annee']) && isset($_FILES['file_pdf'])&& isset($_POST['typeEp'])) {
                try {
                    $result = $enseignant->add_epreuve([
                        'matiere' => $_POST['matiere'],
                        'annee' => $_POST['annee'],
                        'typeEp' => $_POST['typeEp'],
                        'file_pdf' => $_FILES['file_pdf']
                    ]);
                    if ($result) {
                        $response["code"] = "100";
                        $response["message"] = "Epreuve ajoutée avec succès";
                    } else {
                        $response["code"] = "101";
                        $response["message"] = "Erreur lors de l'ajout de l'épreuve";
                    }
                } catch (Exception $e) {
                    $response["code"] = "101";
                    $response["message"] = $e->getMessage();
                }
            } else {
                $response["code"] = "102";
                $response["message"] = "Tous les champs sont requis";
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
        }else if($_POST["action"] == 'ALTER_EPREUVE'){
            if (isset($_POST["file_pdf"]) && $_POST["file_pdf"] != "") {
                $result = $universite->alterEpreuve($_POST["file_pdf"], $_POST);
                if ($result) {
                    $response["message"] = "Epreuve mise à jour avec succès";
                    $response["code"] = 100;
                } else {
                    $response["message"] = "Échec de la mise à jour de l'épreuve";
                    $response["code"] = 0;
                }
            } else {
                $response["message"] = " Le nom du fichier est requis";
                $response["code"] = 0;
            }
        }
         else if ($_POST["action"] == 'AUTHENTIFICATE') {
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
        break;
    default:
        $response["code"] = "$codeResponse";
        $response["message"] = "Cette requête n'est pas autorisée";
        break;
}
echo json_encode($response);
