<?php

class Cartes
{
    private string $table = "cartes";
    private PDO $pdo;

    private int $id;
    private string $numero_cartes;
    private string $id_clients;
    private string $id_entreprises;
    private string $solde;


    private string $val_data = " 
       id_clients = :id_clients,
       id_entreprises = :id_entreprises,
       numero_cartes = :numero_cartes,
       solde = :solde";

    public function __construct(PDO $pDO)
    {
        $this->pdo = $pDO;
    }

    public function read(int $id = 0, int $id_clients = 0, string $numero_cartes = ""): bool|PDOStatement
    {
        $req = "SELECT * FROM " . $this->table;

        if (isset($id) && $id == !0) {
            $this->id = $id;
            $req .= " WHERE id = ?";
        }
            if (isset($id_clients) && $id_clients == !0) {
            $this->id_clients = $id_clients;
            $req .= " WHERE id_clients = ?";
        }
        
        
        if (isset($numero_cartes) && $numero_cartes == !0) {
            $this->numero_cartes = $numero_cartes;
            $req .= " WHERE numero_cartes = ?";
        }

        $stmt = $this->pdo->prepare($req);

        if (isset($id) && $id == !0) {
            $stmt->bindParam(1, $this->id);
        }
         if (isset($id_clients) && $id_clients == !0) {
            $stmt->bindParam(1, $this->id_clients);
        }
        
         if (isset($numero_cartes) && $numero_cartes == !0) {
            $stmt->bindParam(1, $this->numero_cartes);
        }

        $stmt->execute();

        return $stmt;
    }
    
    
    
    
    public function check_if_cartes_exist(string $id_clients = "", $id_entreprises = ""): bool|PDOStatement
    {
        $req = "SELECT * FROM " . $this->table;

        $req .= " WHERE id_clients = ? AND id_entreprises = ? LIMIT 1";

        $stmt = $this->pdo->prepare($req);

        $this->id_clients = $id_clients;
        $this->id_entreprises = $id_entreprises;

        $stmt->bindParam(1, $this->id_clients);
        $stmt->bindParam(1, $this->id_entreprises);

        $stmt->execute();

        return $stmt;
    }

    
    public function save(int $id = 0): bool
    {
        $bytes = random_bytes(16);
        $uniqueId = bin2hex($bytes);

        if (isset($id) && $id == !0) {
            $req = "UPDATE " . $this->table . " SET " . $this->val_data . " WHERE id = :id";
            $this->id = $id;
        } else {
            $req = "INSERT INTO " . $this->table . " SET " . $this->val_data;
        }

        $this->dataSaveCartes();

        $stmt = $this->pdo->prepare($req);


        if (isset($id) && $id == !0) {
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        }

        $this->clean_data();

        return $this->bind_data($stmt);
    }

    function dataSaveCartes(): void
    {
    
        $this->id_clients = $_POST["id_clients"];
        $this->id_entreprises = $_POST["id_entreprises"];
        $this->solde = $_POST["solde"];
        $this->numero_cartes = $uniqueId;

    }

    /**
     * @return void
     */
    public function clean_data(): void
    {
        $this->id_clients = htmlspecialchars(strip_tags($this->id_clients));
        $this->id_entreprises = htmlspecialchars(strip_tags($this->id_entreprises));
        $this->solde = htmlspecialchars(strip_tags($this->solde));
        $this->numero_cartes = htmlspecialchars(strip_tags($this->numero_cartes));

    }

    /**
     * @param false|PDOStatement $stmt
     * @return bool
     */
    public function bind_data(false|PDOStatement $stmt): bool
    {
        $stmt->bindParam(':id_clients', $this->id_clients);
        $stmt->bindParam(':id_entreprises', $this->id_entreprises);
        $stmt->bindParam(':solde', $this->solde);
        $stmt->bindParam(':numero_cartes', $this->numero_cartes);

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

}
