<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include_once('../core/initialize.php');

try {
    $data = json_decode(file_get_contents("php://input"), true); // Ajoutez `true` pour convertir en tableau associatif

    if (!isset($data['idEpreuve'])) {
        throw new Exception('Données de téléchargement manquantes');
    }

    $idEpreuve = $data['idEpreuve'];
    $dateTelechargement = date('Y-m-d H:i:s');
    $nombreTelechargement = 1;

    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Check if there's an existing record for this idEpreuve on the same day
    $sqlCheck = "SELECT * FROM telechargement WHERE idEpreuve = ? AND DATE(dateTelechargement) = CURDATE()";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $idEpreuve);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Update existing record
        $sqlUpdate = "UPDATE telechargement SET nombreTelechargement = nombreTelechargement + 1 WHERE idEpreuve = ? AND DATE(dateTelechargement) = CURDATE()";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $idEpreuve);
        $stmtUpdate->execute();
    } else {
        // Insert new record
        $sqlInsert = "INSERT INTO telechargement (idEpreuve, dateTelechargement, nombreTelechargement) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("isi", $idEpreuve, $dateTelechargement, $nombreTelechargement);
        $stmtInsert->execute();
    }

    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Téléchargement enregistré']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage(), 'receivedData' => $data]);
}
