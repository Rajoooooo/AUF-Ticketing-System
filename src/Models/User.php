<?php

namespace App\Models;

use PDO;

class User extends BaseModel
{
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($data)
    {
        $sql = "INSERT INTO users (name, email, phone, password, last_password) 
                VALUES (:name, :email, :phone, :password, :last_password)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);    
        return $this->db->lastInsertId();
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add this method to handle login by email
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $data)
{
    $sql = "UPDATE users 
            SET name = :name, email = :email, phone = :phone, password = :password 
            WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);

    return $stmt->execute();
}


    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
}
