<?php

namespace App\Models;

use PDO;

class User {

    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Retrieve user by email
    public function getUserByEmail($email) {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
