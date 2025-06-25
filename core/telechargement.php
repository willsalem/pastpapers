<?php
class Telechargement {
    private PDO $pdo;
    private string $table = "telechargement";

    public int $idTelechargement;
    public int $idEpreuve;
    public string $dateTelechargement;
    public int $nombreTelechargement;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create() {
        // Vérifiez si un téléchargement pour cette épreuve existe déjà
        $query = "SELECT idTelechargement FROM " . $this->table . " WHERE idEpreuve = :idEpreuve";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':idEpreuve', $this->idEpreuve);
        $stmt->execute();

        $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRecord) {
            // Mise à jour du nombre de téléchargements si l'entrée existe déjà
            return $this->incrementDownloadCount($existingRecord['idTelechargement']);
        } else {
            // Création d'un nouvel enregistrement si aucune entrée n'existe
            $query = "INSERT INTO " . $this->table . " (idEpreuve, dateTelechargement, nombreTelechargement) VALUES (:idEpreuve, :dateTelechargement, :nombreTelechargement)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':idEpreuve', $this->idEpreuve);
            $stmt->bindParam(':dateTelechargement', $this->dateTelechargement);
            $stmt->bindParam(':nombreTelechargement', $this->nombreTelechargement);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
    }

    

    public function incrementDownloadCount($idTelechargement) {
        // Incrémentation du nombre de téléchargements
        $query = "UPDATE " . $this->table . " SET nombreTelechargement = nombreTelechargement + 1 WHERE idTelechargement = :idTelechargement";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':idTelechargement', $idTelechargement);

        return $stmt->execute();
    }

    public function readOne() {
        $query = "SELECT e.file_pdf, t.dateTelechargement FROM " . $this->table . " t, epreuve e, apprenant a 
        WHERE t.idEpreuve = e.idEpreuve AND t.id = a.id";
        $stmt = $this->pdo->prepare($query);

        //$stmt->bindParam(':idEpreuve', $this->idEpreuve);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
