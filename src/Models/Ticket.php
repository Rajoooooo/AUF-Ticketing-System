<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Ticket extends BaseModel
{
    public function createTicket($data)
    {
        $sql = "INSERT INTO ticket (title, body, requester, team, priority)
                VALUES (:title, :body, :requester, :team, :priority)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $data['title'],
            ':body' => $data['body'],
            ':requester' => $data['requester'],
            ':team' => $data['team'],
            ':priority' => $data['priority']
        ]);
    }

    public function getAllTickets()
    {
        $sql = "SELECT t.id, t.title, t.status, t.priority, t.created_at, 
                       r.name AS requester, tm.name AS team
                FROM ticket t
                LEFT JOIN requester r ON t.requester = r.id
                LEFT JOIN team tm ON t.team = tm.id
                WHERE t.deleted_at IS NULL
                ORDER BY t.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTicket($id)
    {
        $sql = "UPDATE ticket SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getTicketById($id)
    {
        $sql = "SELECT * FROM ticket WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE ticket SET status = :status, updated_at = NOW() WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

}
