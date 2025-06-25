<?php

global $db;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once('../core/initialize.php');

$api = $_SERVER['REQUEST_METHOD'];

$auth = new Authentification($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array("message" => "Données JSON invalides."));
        exit();
    }

    $debug_info = [];
    $debug_info['received_data'] = $inputData;
    
    if (!empty($inputData['emailAdmin']) && !empty($inputData['passwordAdmin'])) {
        $result = $auth->authentificate($inputData['emailAdmin'], $inputData['passwordAdmin']);
        $user_type = 'admin';
        $debug_info['attempted_auth'] = 'admin';
    } elseif (!empty($inputData['emailEnseignant']) && !empty($inputData['passwordEnseignant'])) {
        $result = $auth->authentificateEns($inputData['emailEnseignant'], $inputData['passwordEnseignant']);
        $user_type = 'enseignant';
        $debug_info['attempted_auth'] = 'enseignant';
    } elseif (!empty($inputData['emailAp']) && !empty($inputData['passwordAp'])) {
        $result = $auth->authentificateAp($inputData['emailAp'], $inputData['passwordAp']);
        $user_type = 'apprenant';
        $debug_info['attempted_auth'] = 'apprenant';
    } elseif (!empty($inputData['emailUni']) && !empty($inputData['passwordUni'])) {
        $result = $auth->authentificateUni($inputData['emailUni'], $inputData['passwordUni']);
        $user_type = 'universite';
        $debug_info['attempted_auth'] = 'universite';
    } else {
        http_response_code(400);
        echo json_encode(array(
            "message" => "Données d'authentification invalides ou manquantes.",
            "debug_info" => $debug_info
        ));
        exit();
    }

    if ($result) {
        http_response_code(200);
        unset($result['passwordAdmin']);
        unset($result['passwordEnseignant']);
        unset($result['passwordAp']);
        unset($result['passwordUni']);
        echo json_encode(array(
            "message" => "Connexion réussie.",
            "user_type" => $user_type,
            "user" => $result,
            "debug_info" => $debug_info
        ));
    } else {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Échec de la connexion. Email ou mot de passe incorrect.",
            "debug_info" => $debug_info
        ));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
