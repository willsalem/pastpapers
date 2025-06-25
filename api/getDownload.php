<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once('../core/initialize.php');

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'pastpapers');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$sql = "SELECT SUM(nombreTelechargement) AS totalDownloads FROM telechargement";
$result = $conn->query($sql);

$totalDownloads = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalDownloads = $row['totalDownloads'];
}

$conn->close();

echo json_encode(['success' => true, 'totalDownloads' => $totalDownloads]);
?>
