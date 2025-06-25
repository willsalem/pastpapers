<?php

class Apprenant
{
    private string $table = "apprenant";
    private PDO $pdo;

    private int $id;
    private string $nomAp;
    private string $prenomAp;
    private string $telephoneAp;
    private string $emailAp;
    private string $sexeAp;
    private string $passwordAp;
    
    


    private string $val_data = 
    "   nomAp = :nomAp,
        prenomAp = :prenomAp,
        telephoneAp = :telephoneAp, 
        emailAp = :emailAp,
        sexeAp = :sexeAp,
        passwordAp = :passwordAp
     ";
     
     
    public function __construct(PDO $pDO)
    {
        $this->pdo = $pDO;
    }

    public function read(int $id = 0, string $emailAp = "",string $telephoneAp =""): bool|PDOStatement
    {
    
        $req = "SELECT * FROM " . $this->table;

        if (isset($id) && $id == !0) {
            $this->id = $id;
            $req .= " WHERE id = ?";
        }

        if (isset($emailAp) && $emailAp == !"") {
            $this->emailAp = $emailAp;
            $req .= " WHERE emailAp = ?";
        }

        $stmt = $this->pdo->prepare($req);

        if (isset($id) && $id == !0) {
            $stmt->bindParam(1, $this->id);
        }

        if (isset($emailAp) && $telephoneAp == !0){
            $stmt->bindParam(1, $this->emailAp);
        }

        $stmt->execute();

        return $stmt;
    }

    public function check_emailAp(string $emailAp = ""): bool|PDOStatement
    {
        $req = "SELECT * FROM " . $this->table;

        $req .= " WHERE emailAp = ? LIMIT 1";

        $stmt = $this->pdo->prepare($req);

        $this->emailAp = $emailAp;

        $stmt->bindParam(1, $this->emailAp);

        $stmt->execute();

        return $stmt;
    }
    
    
    
    
   /* public function check_phone(string $phone = ""): bool|PDOStatement
    {
        $req = "SELECT * FROM " . $this->table;

        $req .= " WHERE phone = ? LIMIT 1";

        $stmt = $this->pdo->prepare($req);

        $this->phone = $phone;

        $stmt->bindParam(1, $this->phone);

        $stmt->execute();

        return $stmt;
    }


    public function check_phone_number(string $code_telephone = "", string $phone = ""): bool|PDOStatement
    {
        $req = "SELECT * FROM " . $this->table;

        $req .= " WHERE code_telephone = ? AND telephone = ? LIMIT 1";

        $stmt = $this->pdo->prepare($req);

        $this->code_telephone = $code_telephone;
        $this->telephone = $telephone;

        $stmt->bindParam(1, $this->code_telephone);
        $stmt->bindParam(2, $this->telephone);

        $stmt->execute();

        return $stmt;
    }*/

    /**
     * Met à jour le mot de passe d'un utilisateur.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $currentPassword Le mot de passe actuel de l'utilisateur.
     * @param string $newPassword Le nouveau mot de passe de l'utilisateur.
     * @return bool Renvoie true si le mot de passe a été mis à jour avec succès, sinon false.
     */

    public function updatePassword(string $emailAp, string $passwordAp, string $newPassword): bool
    {
        // Requête pour obtenir le mot de passe actuel à partir de l'e-mail
        $sql = "SELECT passwordAp FROM apprenant WHERE emailAp = :emailAp";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':emailAp', $emailAp, PDO::PARAM_STR);
        $stmt->execute();
        $apprenant = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe actuel est correct
        if ($apprenant && password_verify($passwordAp, $apprenant['passwordAp'])) {
            // Hacher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe dans la base de données
            $sql = "UPDATE apprenant SET passwordAp = :newPassword WHERE emailAp = :emailAp";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newPassword', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':emailAp', $emailAp, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false; // Échec de la vérification du mot de passe ou utilisateur non trouvé
    }


    public function save(int $id = 0): bool
    {

        if (isset($id) && $id == !0) {
            $req = "UPDATE " . $this->table . " SET " . $this->val_data . " WHERE id = :id";
            $this->id = $id;
        } else {
            $req = "INSERT INTO " . $this->table . " SET " . $this->val_data;
        }
        $this->dataSaveApprenant();

        $stmt = $this->pdo->prepare($req);
 
        //$stmt -> execute();
        if (isset($id) && $id == !0) {
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        }

        $this->clean_data();

        if ($id == 0) {
            $this->passwordAp = password_hash($this->passwordAp, PASSWORD_BCRYPT);
        }

        return $this->bind_data($stmt);
    }

    /**
     * @return void
     */
    function dataSaveApprenant(): void
    {
        $this->nomAp = $_POST["nomAp"];
        $this->prenomAp = $_POST["prenomAp"];
        $this->telephoneAp = $_POST["telephoneAp"];
        $this->emailAp = $_POST["emailAp"];
        $this->sexeAp = $_POST["sexeAp"];
        $this->passwordAp = $_POST["passwordAp"];
        

    }

    /**
     * @return void
     */
    public function clean_data(): void
    {
        $this->nomAp = htmlspecialchars(strip_tags($this->nomAp));
        $this->prenomAp = htmlspecialchars(strip_tags($this->prenomAp));
        $this->telephoneAp = htmlspecialchars(strip_tags($this->telephoneAp));
        $this->emailAp = htmlspecialchars(strip_tags($this->emailAp));
        $this->sexeAp = htmlspecialchars(strip_tags($this->sexeAp));
        $this->passwordAp = htmlspecialchars(strip_tags($this->passwordAp));
        
    }

    /**
     * @param false|PDOStatement $stmt
     * @return bool
     */
    public function bind_data(false|PDOStatement $stmt): bool
    {
        $stmt->bindParam(':nomAp', $this->nomAp);
        $stmt->bindParam(':prenomAp', $this->prenomAp);
        $stmt->bindParam(':telephoneAp', $this->telephoneAp);
        $stmt->bindParam(':emailAp', $this->emailAp);
        $stmt->bindParam(':sexeAp', $this->sexeAp);
        $stmt->bindParam(':passwordAp', $this->passwordAp);


        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(int $id = 0): bool
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //Fonction assurant le téléchargement d'un fichier

    /*
    $fileName nom du fichier
    $tempPath chemin temporaire du fichier
    $fileSize taille du fichier
    $fileError code d'erreur associé au fichier 
    */ 

    public function telecharger(string $imageInputName, string $destinationPath, string $oldImagePath = ""): array
    {
        $fileName = $_FILES[$imageInputName]['name']; // Nom du fichier image
        $tempPath = $_FILES[$imageInputName]['tmp_name']; // Chemin temporaire du fichier image
        $fileSize = $_FILES[$imageInputName]['size']; // Taille du fichier image
        $fileError = $_FILES[$imageInputName]['error']; // Code d'erreur associé au fichier image

        $result = add_images($fileName, $tempPath, $fileSize, $fileError, $destinationPath, $oldImagePath); // Appel à la fonction add_images pour le traitement de l'image

        if ($result['result']) { // Si le traitement de l'image est réussi
            $document = $result['message']; // Chemin de l'image traitée
            return array(
                'success' => true,
                'message' => 'Téléchargement effectué avec succès',
                'document' => $document
            );
        } else {
            return array(
                'success' => false,
                'message' => $result['message'] // Message d'erreur si le traitement échoue
            );
        }
    }


        //Fonction pour permettre à un apprenant de s'authentifier
    public function authentificate(string $emailAp, string $passwordAp):bool | array
    {
        $query = "SELECT * FROM " . $this->table . " WHERE emailAp = :emailAp LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailAp', $emailAp);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordAp, $row['passwordAp'])) {
                return $row; // Authentication successful
            } else {
                return false; // Password is incorrect
            }
        } else {
            return false; // Email not found
        }
    }
}
