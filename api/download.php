<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

if(isset($_GET['file'])) {
    // Assurez-vous que le nom du fichier ne contient pas de caractères dangereux
    $filename = basename($_GET['file']);
    $file = __DIR__ . '/assets/fichiers/' . $filename;

    if(file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['error' => 'File not found']);
    }
} else {
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(['error' => 'No file specified']);
}