<?php

class Universite
{
    private string $table = "universite";
    private PDO $pdo;

    private int $idUni;
    private string $nomUni;
    private string $adresseUni;
    private string $emailUni;
    private string $telephoneUni;
    private string $passwordUni;
    private string $logo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //Récupérer tous sur les universités sauf le password
    public function read(int $idUni = 0): bool|PDOStatement
    {
        $req = "SELECT idUni, nomUni, adresseUni, emailUni, telephoneUni, logo FROM " . $this->table;

        if ($idUni != 0) {
            $req .= " WHERE idUni = ?";
        }

        $stmt = $this->pdo->prepare($req);

        if ($idUni != 0) {
            $stmt->bindParam(1, $idUni, PDO::PARAM_INT);
        }

        $stmt->execute();
        //$uni = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stmt;
    }

    //Récupérer uniquement le nom des universites
    public function readName(){
        $req = "SELECT nomUni FROM" . $this->table;
        $res = $this->pdo->prepare($req);
        $res->execute();
        $uni = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $uni;
    }

    public function createUniversity(array $data): bool
    {
      
        $sql = "INSERT INTO universite (nomUni, adresseUni, telephoneUni, emailUni, passwordUni, logo) 
                VALUES (:nomUni, :adresseUni, :telephoneUni, :emailUni, :passwordUni, :logo)";
        $stmt = $this->pdo->prepare($sql);

        $hashedPassword = password_hash($data['passwordUni'], PASSWORD_BCRYPT);

        $imagePath = "";
        if (!empty($_FILES["logo_img"]["name"])) {
            $fileName = $_FILES["logo_img"]['name'];
            $tempPath = $_FILES["logo_img"]['tmp_name'];
            $fileSize = $_FILES["logo_img"]['size'];
            $fileError = $_FILES["logo_img"]['error'];

            $result = $this->add_logo($fileName, $tempPath, $fileSize, $fileError, "../paniers/images/");
            if ($result['result']) {
                $imagePath = $result['message'];
            }
        }

        return $stmt->execute([
            ':nomUni' => $data['nomUni'],
            ':adresseUni' => $data['adresseUni'],
            ':telephoneUni' => $data['telephoneUni'],
            ':emailUni' => $data['emailUni'],
            ':passwordUni' => $hashedPassword,
            ':logo' => $imagePath
        ]);
    }

    public function updateUniversityWithLogo(int $idUni, array $data, array $logo = null): bool
    {
        
        $sql = "UPDATE universite SET 
                nomUni = :nomUni, 
                adresseUni = :adresseUni, 
                telephoneUni = :telephoneUni, 
                emailUni = :emailUni, 
                passwordUni = :passwordUni";

        if ($logo) {
            $sql .= ", logo = :logo";
        }

        $sql .= " WHERE idUni = :idUni";

        $stmt = $this->pdo->prepare($sql);

        $hashedPassword = password_hash($data['passwordUni'], PASSWORD_BCRYPT);

        $params = [
            ':idUni' => $idUni,
            ':nomUni' => $data['nomUni'],
            ':adresseUni' => $data['adresseUni'],
            ':telephoneUni' => $data['telephoneUni'],
            ':emailUni' => $data['emailUni'],
            ':passwordUni' => $hashedPassword
        ];

        if ($logo) {
            $fileContent = file_get_contents($logo['tmp_name']);
            $params[':logo'] = $fileContent;
        }

        return $stmt->execute($params);
    }

    public function deleteUniversity(int $idUni): bool
    {
       
        $sql = "DELETE FROM universite WHERE idUni = :idUni";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idUni', $idUni, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function add_logo(string $fileName, string $tempPath, int $fileSize, int $fileError, string $path, string $old_path = ''): array
    {
        if (!empty($fileName)) {
            $upload_path = $path;
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $valid_image_extensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];

            if (in_array($fileExt, $valid_image_extensions)) {
                if (!empty($old_path) && file_exists($old_path)) {
                    unlink($old_path);
                }
                if ($fileError === UPLOAD_ERR_OK) {
                    if (move_uploaded_file($tempPath, $upload_path . $fileName)) {
                        return [
                            'result' => true,
                            'message' => $upload_path . $fileName,
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
                    'message' => "Seuls les fichiers JPG, JPEG, PNG sont autorisés.",
                ];
            }
        } else {
            return [
                'result' => false,
                'message' => "Veuillez sélectionner un fichier.",
            ];
        }
    }

    public function add_filiere(array $data): bool
    {
      
        $req = "INSERT INTO filiere (idFiliere, nomFiliere) VALUES (:idFiliere, :nomFiliere)";
        $stmt = $this->pdo->prepare($req);
        return $stmt->execute([
            ':idFiliere' => $data['idFiliere'],
            ':nomFiliere' => $data['nomFiliere']
        ]);
    }

    public function authentificate(string $emailUni, string $passwordUni): bool|array
    {
        $query = "SELECT * FROM " . $this->table . " WHERE emailUni = :emailUni LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailUni', $emailUni);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordUni, $row['passwordUni'])) {
                return $row; // Authentification réussie
            }
        }

        return false; // Échec de l'authentification
    }
}
?>
