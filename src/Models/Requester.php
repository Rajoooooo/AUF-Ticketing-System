<?php

namespace App\Models;

use PDO;
use Exception;

class Requester {
    public $id = null;
    public $name = '';
    public $email = '';
    public $phone = '';

    private $db = null;

    public function __construct($conn = null) {
        // Accept a database connection through the constructor
        $this->db = $conn;

        if ($this->db === null) {
            throw new Exception("Database connection not provided.");
        }
    }

    public function save(): Requester {
        $sql = "INSERT INTO requester (name, email, phone) VALUES (:name, :email, :phone)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);

        if (!$stmt->execute()) {
            throw new Exception("Failed to save requester: " . implode(", ", $stmt->errorInfo()));
        }

        $this->id = $this->db->lastInsertId();
        return $this;
    }

    public static function find($id, $conn): ?Requester {
        $sql = "SELECT * FROM requester WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        $requester = new static($conn);
        $requester->populateObject($result);
        return $requester;
    }

    public static function findAll($conn): array {
        $sql = "SELECT * FROM requester ORDER BY id DESC";
        $stmt = $conn->query($sql);

        $requesters = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $requester = new static($conn);
            $requester->populateObject($row);
            $requesters[] = $requester;
        }

        return $requesters;
    }

    public static function delete($id, $conn): bool {
        $sql = "DELETE FROM requester WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function populateObject(array $data): void {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
