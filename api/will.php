<?php
// enseignant.php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


class Enseignant {
    public function add_epreuve() {
        // Récupérer les données POST
        $data = json_decode(file_get_contents('php://input'), true);

        $matiere = $data['matiere'];
        $annee = $data['annee'];
        $file = $data['file_pdf']; // Assurez-vous de gérer correctement le fichier

        // Connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'pastpapers');

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO epreuves (matiere, annee, file) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $matiere, $annee, $file_pdf);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Epreuve ajoutée avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'épreuve.']);
        }

        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'add_epreuve') {
    $enseignant = new Enseignant();
    $enseignant->add_epreuve();
}
?>
