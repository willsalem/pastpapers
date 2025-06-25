<?php
class Epreuve {

private PDO $pdo;
private string $table = "epreuve";

private int $idEpreuve;
private string $matiere;
private string $annee;
private string $typeEp;
private string $file_pdf;
private string $date_enregistrement;

private string $val_data = " 
matiere = :matiere,
annee = :annee,
typeEp = :typeEp,
file_pdf = :file_pdf";

public function __construct(PDO $pdo)
{
    $this->pdo = $pdo;
}

public function readEpreuve(int $limit = 0) {
    $query = "SELECT idEpreuve, matiere, annee, typeEp, file_pdf FROM " . $this->table . " ORDER BY date_enregistrement DESC";
    if ($limit > 0) { 
        $query .= " LIMIT :limit";
    }
    $stmt = $this->pdo->prepare($query);
    if ($limit > 0) { 
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteEpreuve($file_pdf) {
    $query = "DELETE FROM " . $this->table . " WHERE file_pdf = :file_pdf";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":file_pdf", $file_pdf);
    return $stmt->execute();
}

public function updateEpreuve($file_pdf, $newData) {
    $query = "UPDATE " . $this->table . " SET matiere = :matiere, annee = :annee, file_pdf = :new_file_pdf WHERE file_pdf = :file_pdf";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":matiere", $newData['matiere']);
    $stmt->bindParam(":annee", $newData['annee']);
    $stmt->bindParam(":new_file_pdf", $newData['file_pdf']);
    $stmt->bindParam(":file_pdf", $file_pdf);
    return $stmt->execute();
}
public function nombreEpreuve() {
    $query = "SELECT COUNT(*) as nbr_elements FROM " . $this->table;
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(); 
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['nbr_elements'];
}

}
