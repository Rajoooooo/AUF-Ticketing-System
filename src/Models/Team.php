<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Team extends BaseModel
{
    public function getAllTeams()
    {
        $sql = "SELECT * FROM team ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTeam($name)
    {
        $sql = "INSERT INTO team (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }

    public function deleteTeam($id)
    {
        $sql = "DELETE FROM team WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function updateTeam($id, $name)
    {
        $sql = "UPDATE team SET name = :name, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id, 'name' => $name]);
    }
}
