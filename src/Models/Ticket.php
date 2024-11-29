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
        $sql = "DELETE FROM ticket WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
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

        public function getOpenTickets()
    {
        $query = "
            SELECT 
        t.id AS ticket_id,
        t.title,
        t.status, -- Include the status field
        t.priority,
        r.name AS requester_name,
        tm.name AS team_name,
        u.name AS team_member_name
    FROM 
        ticket t
    LEFT JOIN 
        requester r ON t.requester = r.id
    LEFT JOIN 
        team tm ON t.team = tm.id
    LEFT JOIN 
        team_member tmm ON t.team_member = tmm.id
    LEFT JOIN 
        users u ON tmm.user = u.id
    WHERE 
        t.status = 'open'
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingTickets()
    {
        $query = "
            SELECT 
                t.id AS ticket_id,
                t.title,
                t.body,
                t.status,
                t.priority,
                r.name AS requester_name,
                tm.name AS team_name,
                u.name AS team_member_name
            FROM 
                ticket t
            LEFT JOIN 
                requester r ON t.requester = r.id
            LEFT JOIN 
                team tm ON t.team = tm.id
            LEFT JOIN 
                team_member tmm ON t.team_member = tmm.id
            LEFT JOIN 
                users u ON tmm.user = u.id
            WHERE 
                t.status = 'pending'
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSolvedTickets()
    {
        $query = "
            SELECT 
                t.id AS ticket_id,
                t.title,
                t.status,
                t.priority,
                r.name AS requester_name,
                tm.name AS team_name,
                u.name AS team_member_name
            FROM 
                ticket t
            LEFT JOIN 
                requester r ON t.requester = r.id
            LEFT JOIN 
                team tm ON t.team = tm.id
            LEFT JOIN 
                team_member tmm ON t.team_member = tmm.id
            LEFT JOIN 
                users u ON tmm.user = u.id
            WHERE 
                t.status = 'solved'
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClosedTickets()
    {
        $query = "
            SELECT 
                t.id AS ticket_id,
                t.title,
                t.status,
                t.priority,
                r.name AS requester_name,
                tm.name AS team_name,
                u.name AS team_member_name
            FROM 
                ticket t
            LEFT JOIN 
                requester r ON t.requester = r.id
            LEFT JOIN 
                team tm ON t.team = tm.id
            LEFT JOIN 
                team_member tmm ON t.team_member = tmm.id
            LEFT JOIN 
                users u ON tmm.user = u.id
            WHERE 
                t.status = 'closed'
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $query = "SELECT id, name FROM users WHERE role IN ('admin', 'member')";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignTeamAndMember($ticketId, $teamId, $teamMember)
    {
        $sql = "UPDATE ticket 
                SET team = :team, team_member = :teamMember, updated_at = NOW() 
                WHERE id = :ticketId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'team' => $teamId,
            'teamMember' => $teamMember,
            'ticketId' => $ticketId
        ]);
    }

}
