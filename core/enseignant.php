<?php

class Enseignant
{
    private string $table = "enseignant";
    private PDO $pdo;

    private int $idEnseignant;
    private string $nomEnseignant;
    private string $prenomEnseignant;
    private string $telephoneEnseignant;
    private string $matiere;
    private string $emailEnseignant;
    private string $sexeEnseignant;
    private string $passwordEnseignant;

    private string $val_data = 
    "   nomEnseignant = :nomEnseignant,
        prenomEnseignant = :prenomEnseignant,
        telephoneEnseignant = :telephoneEnseignant,
        matiere = :matiere,
        emailEnseignant = :emailEnseignant,
        sexeEnseignant = :sexeEnseignant,
        passwordEnseignant = :passwordEnseignant
    ";

    public function __construct(PDO $pDO)
    {
        $this->pdo = $pDO;
    }

    public function read(int $idEnseignant = 0, string $emailEnseignant = ""): bool|PDOStatement
    {
        $req = "SELECT idEnseignant, nomEnseignant, prenomEnseignant, telephoneEnseignant, emailEnseignant, matiere, sexeEnseignant
        FROM " . $this->table;
        

        if (isset($idEnseignant) && $idEnseignant == !0) {
            $this->idEnseignant = $idEnseignant;
            $req .= " WHERE id = ?";
        }

        if (isset($emailEnseignant) && $emailEnseignant == !"") {
            $this->emailEnseignant = $emailEnseignant;
            $req .= " WHERE email = ?";
        }

        $stmt = $this->pdo->prepare($req);

        if (isset($idEnseignant) && $idEnseignant == !0) {
            $stmt->bindParam(1, $this->id);
        }

        if (isset($emailEnseignant) && $emailEnseignant == !"") {
            $stmt->bindParam(1, $this->emailEnseignant);
        }

        $stmt->execute();

        return $stmt;
    }


    public function save(int $idEnseignant = 0): bool //Fonction pour envoyer les infos d'un enseignant dans la BDD
    {
 	//$this->logo = $logo;
        if (isset($idEnseignant) && $idEnseignant == !0) {
            $req = "UPDATE " . $this->table . " SET " . $this->val_data . " WHERE idEnseignant = :idEnseignant";
            $this->idEnseignant = $idEnseignant;
        } else {
            $req = "INSERT INTO " . $this->table . " SET " . $this->val_data;
        }

        $this->dataSaveEnseingnant();

        
        $stmt = $this->pdo->prepare($req);

        if (isset($idEnseignant) && $idEnseignant == !0) {
            $stmt->bindParam(':id', $this->idEnseignant, PDO::PARAM_INT);
        }

        $this->clean_data();

        if ($idEnseignant == 0) {
            $this->passwordEnseignant = password_hash($this->passwordEnseignant, PASSWORD_BCRYPT);
        }

        return $this->bind_data($stmt);
    }

    /**
     * @return void
     */
    private function dataSaveEnseingnant(): void
    {
        $this->nomEnseignant = $_POST["nomEnseignant"];
        $this->prenomEnseignant = $_POST["prenomEnseignant"];
        $this->emailEnseignant = $_POST["emailEnseignant"];
        $this->telephoneEnseignant = $_POST["telephoneEnseignant"];
        $this->sexeEnseignant = $_POST["sexeEnseignant"];
        $this->matiere = $_POST["matiere"];
        $this->passwordEnseignant = $_POST["passwordEnseignant"];

    }

    /**
     * @return void
     */
    public function clean_data(): void
    {
        $this->nomEnseignant = htmlspecialchars(strip_tags($this->nomEnseignant));
        $this->prenomEnseignant = htmlspecialchars(strip_tags($this->prenomEnseignant));
        $this->emailEnseignant = htmlspecialchars(strip_tags($this->emailEnseignant));
        $this->telephoneEnseignant = htmlspecialchars(strip_tags($this->telephoneEnseignant));
        $this->sexeEnseignant = htmlspecialchars(strip_tags($this->sexeEnseignant));
        $this->matiere = htmlspecialchars(strip_tags($this->matiere));
        $this->passwordEnseignant = htmlspecialchars(strip_tags($this->passwordEnseignant));

    }

    /**
     * @param false|PDOStatement $stmt
     * @return bool
     */
    public function bind_data(false|PDOStatement $stmt): bool
    {
        $stmt->bindParam(':nomEnseignant', $this->nomEnseignant);
        $stmt->bindParam(':prenomEnseignant', $this->prenomEnseignant);
        $stmt->bindParam(':emailEnseignant', $this->emailEnseignant);
        $stmt->bindParam(':telephoneEnseignant', $this->telephoneEnseignant);
        $stmt->bindParam(':sexeEnseignant', $this->sexeEnseignant);
        $stmt->bindParam(':matiere', $this->matiere);
        $stmt->bindParam(':passwordEnseignant', $this->passwordEnseignant);


        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(int $idEnseignant = 0): bool
    {
        $query = "DELETE FROM " . $this->table . " WHERE idEnseignant = :idEnseignant";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':idEnseignant', $idEnseignant, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
//Ajouter épreuve
    public function add_epreuve(array $data): bool  
    {
        // Affichage des erreurs PDO
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête SQL d'insertion
        $sql = "INSERT INTO epreuve (matiere, file_pdf, annee, typeEp) 
                VALUES (:matiere, :file_pdf, :annee, :typeEp)";
        $stmt = $this->pdo->prepare($sql);

        $filePath = "";

        if (isset($_FILES["file_pdf"]) && $_FILES["file_pdf"]["error"] === UPLOAD_ERR_OK) {
            $fileName = $_FILES["file_pdf"]['name'];
            $tempPath = $_FILES["file_pdf"]['tmp_name'];
            $fileSize = $_FILES["file_pdf"]['size'];
            $fileError = $_FILES["file_pdf"]['error'];

            $result = $this->epreuve($fileName, $tempPath, $fileSize, $fileError, "assets/fichiers/");

            if ($result['result']) {
                $filePath = $result['message'];
            } else {
                // Handle the file upload error here
                error_log("File upload error: " . $result['message']);
                return false;
            }
        } else {
            // Handle the case when no file is uploaded or an error occurred
            error_log("File not uploaded or upload error");
            return false;
        }

        // Logs pour débogage
        error_log("Matiere: " . $data['matiere']);
        error_log("File path: " . $filePath);
        error_log("Annee: " . $data['annee']);
        error_log("typeEp: " . $data['typeEp']);

        // Exécution de la requête
        try {
            return $stmt->execute([
                ':matiere' => $data['matiere'],
                ':typeEp' => $data['typeEp'],
                ':file_pdf' => $filePath,
                ':annee' => $data['annee']
            ]);
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            return false;
        }
    }



    
    //Gestion epreuve
    public function epreuve(string $fileName, string $tempPath, int $fileSize, int $fileError, string $path, string $old_path = ''): array
    {
        if (!empty($fileName)) {
            $upload_path = $path;
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $valid_extensions = ['pdf'];
    
            if (in_array($fileExt, $valid_extensions)) {
                if (!empty($old_path) && file_exists($old_path)) {
                    unlink($old_path);
                }
                if ($fileError === UPLOAD_ERR_OK) {
                    if (!file_exists($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }
                    $destinationPath = $upload_path . $fileName;
                    if (move_uploaded_file($tempPath, $destinationPath)) {
                        return [
                            'result' => true,
                            'message' => $destinationPath,
                        ];
                    } else {
                        return [
                            'result' => false,
                            'message' => "Le fichier n'a pas pu être téléchargé.",
                        ];
                    }
                } else {
                    return [
                        'result' => false,
                        'message' => "Erreur de téléchargement : " . $fileError,
                    ];
                }
            } else {
                return [
                    'result' => false,
                    'message' => "Seuls les fichiers PDF sont autorisés.",
                ];
            }
        } else {
            return [
                'result' => false,
                'message' => "Veuillez sélectionner un fichier.",
            ];
        }
    }
    

    public function deleteEpreuve(){
        $sql = "DELETE FROM epreuve WHERE file_pdf = :file_pdf";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':file_pdf', $file_pdf, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function alterEpreuve(){
        $sql = "UPDATE epreuve SET
        matiere = :matiere, 
        annee = :annee,
        file_pdf = :file_pdf";
    }

    //Fonction pour permettre à un enseignant de s'authentifier
    public function authentificate(string $emailEnseignant, string $passwordEnseignant):bool | array
    {
        $query = "SELECT * FROM " . $this->table . " WHERE emailEnseignant = :emailEnseignant LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailEnseignant', $emailEnseignant);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordEnseignant, $row['passwordEnseignant'])) {
                return $row; // Authentication successful
            } else {
                return false; // Password is incorrect
            }
        } else {
            return false; // Email not found
        }
    }
    
}
