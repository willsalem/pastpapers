<?php

class Transactions
{
    private string $table = "transactions";
    private PDO $pdo;

    private int $id;
    private string $type;
    private int $id_entreprises;
    private int $id_clients;
    private int $id_cartes;
    private string $montant;
    private string $cashback;
    private string $monnaie;
    private string $date;

    private string $val_data = "
        type = :type,
        id_clients = :id_clients,
        id_entreprises = :id_entreprises,
        id_cartes = :id_cartes,
        cashback = :cashback,
        monnaie = :monnaie,
        montant = :montant,
        date = :date";

    public function __construct(PDO $pDO)
    {
        $this->pdo = $pDO;
    }

    public function read(int $id = 0, int $id_entreprises = 0, int $id_clients = 0, int $id_cartes = 0): bool|PDOStatement
    {
        try {
            $req = "SELECT * FROM " . $this->table;
            $conditions = [];
            $params = [];

            if ($id !== 0) {
                $conditions[] = "id = :id";
                $params[':id'] = $id;
            }
            if ($id_entreprises !== 0) {
                $conditions[] = "id_entreprises = :id_entreprises";
                $params[':id_entreprises'] = $id_entreprises;
            }
            if ($id_clients !== 0) {
                $conditions[] = "id_clients = :id_clients";
                $params[':id_clients'] = $id_clients;
            }
            if ($id_cartes !== 0) {
                $conditions[] = "id_cartes = :id_cartes";
                $params[':id_cartes'] = $id_cartes;
            }

            if (count($conditions) > 0) {
                $req .= " WHERE " . implode(" AND ", $conditions);
            }

            $req .= " ORDER BY date DESC";
            $stmt = $this->pdo->prepare($req);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            return false;
        }
    }

    public function save(int $id = 0): bool
    {
        try {
            $this->dataSaveTransactions();
            if ($id !== 0) {
                $req = "UPDATE " . $this->table . " SET " . $this->val_data . " WHERE id = :id";
                $this->id = $id;
            } else {
                $req = "INSERT INTO " . $this->table . " SET " . $this->val_data;
            }

            $stmt = $this->pdo->prepare($req);

            if ($id !== 0) {
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            }

            return $this->bind_data($stmt);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function dataSaveTransactions(): void
    {
        $this->type = $_POST["type"];
        $this->id_entreprises = $_POST["id_entreprises"];
        $this->id_clients = $_POST["id_clients"];
        $this->id_cartes = $_POST["id_cartes"];
        $this->montant = $_POST["montant"];
        $this->cashback = $_POST["cashback"];
        $this->monnaie = $_POST["monnaie"];
        $this->date = $_POST["date"];
    }

    private function bind_data(PDOStatement $stmt): bool
    {
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':id_entreprises', $this->id_entreprises);
        $stmt->bindParam(':id_clients', $this->id_clients);
        $stmt->bindParam(':id_cartes', $this->id_cartes);
        $stmt->bindParam(':montant', $this->montant);
        $stmt->bindParam(':cashback', $this->cashback);
        $stmt->bindParam(':monnaie', $this->monnaie);
        $stmt->bindParam(':date', $this->date);

        return $stmt->execute();
    }

    public function delete(int $id = 0): bool
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
