<?php

class Admin
{
    private string $table = "administrateur";
    private PDO $pdo;

    private int $idAdmin;
    private string $nomAdmin;
    private string $prenomAdmin;
    private string $telephoneAdmin;
    private string $emailAdmin;
    private string $sexeAdmin;
    private string $passwordAdmin;
    
    private string $val_data = " 
    nomAdmin = :nomAdmin,
    prenomAdmin = :prenomAdmin,
    telephoneAdmin = :telephoneAdmin, 
    emailAdmin = :emailAdmin,
    sexeAdmin = :sexeAdmin,
    passwordAdmin = :passwordAdmin ";

    public function __construct(PDO $pDO)
    {
        $this->pdo = $pDO;
    }

    public function read(int $idAdmin = 0): bool|PDOStatement
    {
        $req = "SELECT * FROM " . $this->table;

        if (isset($idAdmin) && $idAdmin == !0) {
            $this->idAdmin = $idAdmin;
            $req .= " WHERE id = ?";
        }

        $stmt = $this->pdo->prepare($req);

        if (isset($idAdmin) && $idAdmin == !0) {
            $stmt->bindParam(1, $this->idAdmin);
        }

        $stmt->execute();

        return $stmt;
    }

    public function save(int $idAdmin = 0): bool
    {

        if (isset($idAdmin) && $idAdmin == !0) {
            $req = "UPDATE " . $this->table . " SET " . $this->val_data . " WHERE idAdmin = :idAdmin";
            $this->idAdmin = $idAdmin;
        } else {
            $req = "INSERT INTO " . $this->table . " SET " . $this->val_data;
        }

        $this->dataSaveAdmin();

        $stmt = $this->pdo->prepare($req);


        if (isset($idAdmin) && $idAdmin == !0) {
            $stmt->bindParam(':idAdmin', $this->idAdmin, PDO::PARAM_INT);
        }

        $this->clean_data();

        if ($idAdmin == 0) {
            $this->passwordAdmin = password_hash($this->passwordAdmin, PASSWORD_BCRYPT);
        }

        return $this->bind_data($stmt);
    }

    private function dataSaveAdmin(): void
    {
    
        $this->nomAdmin = $_POST["nomAdmin"] ?? '';
        $this->prenomAdmin = $_POST["prenomAdmin"] ?? '';
        $this->telephoneAdmin = $_POST["telephoneAdmin"] ?? '';
        $this->emailAdmin = $_POST["emailAdmin"] ?? '';
        $this->sexeAdmin = $_POST["sexeAdmin"] ?? '';
        $this->passwordAdmin = $_POST["passwordAdmin"] ?? '';

    }

    /** 
     * @return void
     */
    public function clean_data(): void
    {
        $this->nomAdmin = htmlspecialchars(strip_tags($this->nomAdmin));
        $this->prenomAdmin = htmlspecialchars(strip_tags($this->prenomAdmin));
        $this->telephoneAdmin = htmlspecialchars(strip_tags($this->telephoneAdmin));
        $this->emailAdmin = htmlspecialchars(strip_tags($this->emailAdmin));
        $this->sexeAdmin = htmlspecialchars(strip_tags($this->sexeAdmin));
        $this->passwordAdmin = htmlspecialchars(strip_tags($this->passwordAdmin));

    }

    /**
     * @param false|PDOStatement $stmt
     * @return bool
     */
    public function bind_data(false|PDOStatement $stmt): bool
    {
        $stmt->bindParam(':nomAdmin', $this->nomAdmin);
        $stmt->bindParam(':prenomAdmin', $this->prenomAdmin);
        $stmt->bindParam(':telephoneAdmin', $this->telephoneAdmin);
        $stmt->bindParam(':emailAdmin', $this->emailAdmin);
        $stmt->bindParam(':sexeAdmin', $this->sexeAdmin);
        $stmt->bindParam(':passwordAdmin', $this->passwordAdmin);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(int $idAdmin = 0): bool
    {
        $query = "DELETE FROM " . $this->table . " WHERE idAdmin = :idAdmin";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':idAdmin', $idAdmin, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

   //Fonction pour permettre à l'administrateur de s'authentifier
    public function authentificate(string $emailAdmin, string $passwordAdmin):bool | array
    {
        $query = "SELECT * FROM " . $this->table . " WHERE emailAdmin = :emailAdmin LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailAdmin', $emailAdmin);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordAdmin, $row['passwordAdmin'])) {
                return $row; // Authentication successful
            } else {
                return false; // Password is incorrect
            }
        } else {
            return false; // Email not found
        }
    }


}
