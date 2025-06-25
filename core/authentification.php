<?php

class Authentification{

    private string $table = "administrateur";
    private string $tableEns = "enseignant";
    private string $tableAp = "apprenant";
    private string $tableUni = "universite";
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

//Authentification des admins
    public function authentificate(string $emailAdmin, string $passwordAdmin):bool | array
    {
        $query = "SELECT emailAdmin, passwordAdmin FROM " . $this->table . " WHERE emailAdmin = :emailAdmin LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailAdmin', $emailAdmin);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordAdmin, $row['passwordAdmin'])) {
                return $row; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }

    //Authentification des enseignants
    public function authentificateEns(string $emailEnseignant, string $passwordEnseignant):bool | array
    {
        $query = "SELECT emailEnseignant, passwordEnseignant FROM " . $this->tableEns . " WHERE emailEnseignant = :emailEnseignant LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailEnseignant', $emailEnseignant);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordEnseignant, $row['passwordEnseignant'])) {
                return $row; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }

    //Authentification des apprenants
    public function authentificateUni(string $emailUni, string $passwordUni):bool | array
    {
        $query = "SELECT emailUni, passwordUni FROM " . $this->tableUni . " WHERE emailUni = :emailUni LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailUni', $emailUni);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordUni, $row['passwordUni'])) {
                return $row; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }

//Authentification des universités
    public function authentificateAp(string $emailAp, string $passwordAp):bool | array
    {
        $query = "SELECT emailAp, passwordAp FROM " . $this->tableAp . " WHERE emailAp = :emailAp LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':emailAp', $emailAp);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($passwordAp, $row['passwordAp'])) {
                return $row; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }

  
}