<?php

namespace App\Models;

use Exception;
use PDO;

class Ticket {
    private $db;

    public $title = '';
    public $body = '';
    public $requester = null;
    public $team = null;
    public $team_member = null;
    public $status = '';
    public $priority = '';
    public $rating = '';

    public function __construct($data = null) {
        $dbConnection = new DatabaseConnection(
            'mysql', // Database type
            '127.0.0.1', // Host
            '3306', // Port
            'sample_helpdeskdb', // Database name
            'root', // Username
            '' // Password
        );

        // Assign the PDO connection to $this->db
        $this->db = $dbConnection->connect();

        if ($this->db === null) {
            throw new Exception("Failed to connect to the database.");
        }

        // Initialize object properties if $data is provided
        if ($data) {
            $this->title = $data['title'] ?? null;
            $this->body = $data['body'] ?? null;
            $this->requester = $data['requester'] ?? null;
            $this->team = $data['team'] ?? null;
            $this->team_member = $data['team_member'] ?? null;
            $this->status = $data['status'] ?? 'open';
            $this->priority = $data['priority'] ?? 'low';
        }
    }

    public function save(): Ticket {
        $sql = "INSERT INTO ticket (title, body, requester, team, team_member, status, priority)
                VALUES (:title, :body, :requester, :team, :team_member, :status, :priority)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':requester', $this->requester);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':team_member', $this->team_member);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':priority', $this->priority);

        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        $id = $this->db->lastInsertId();
        return self::find($id);
    }

    public static function find($id): ?Ticket {
        $self = new static;
        $sql = "SELECT * FROM ticket WHERE id = :id";
        $stmt = $self->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch result as associative array
        if (!$result) {
            return null;
        }

        $self->populateObject($result);
        return $self;
    }

    public static function findAll(): array {
        $self = new static;
        $sql = "SELECT * FROM ticket ORDER BY id DESC";
        $stmt = $self->db->query($sql);

        $tickets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Fetch as associative array
            $ticket = new static($row);
            $tickets[] = $ticket;
        }

        return $tickets;
    }

    public static function changeStatus($id, $status): bool {
        $self = new static;
        $sql = "UPDATE ticket SET status = :status WHERE id = :id";
        $stmt = $self->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function delete($id): bool {
        $self = new static;
        $sql = "DELETE FROM ticket WHERE id = :id";
        $stmt = $self->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function setRating($id, $rating): bool {
        $self = new static;
        $sql = "UPDATE ticket SET rating = :rating WHERE id = :id";
        $stmt = $self->db->prepare($sql);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function setPriority($id, $priority): bool {
        $self = new static;
        $sql = "UPDATE ticket SET priority = :priority WHERE id = :id";
        $stmt = $self->db->prepare($sql);
        $stmt->bindParam(':priority', $priority);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function displayStatusBadge(): string {
        $badgeType = '';
        switch ($this->status) {
            case 'open':
                $badgeType = 'danger';
                break;
            case 'pending':
                $badgeType = 'warning';
                break;
            case 'solved':
                $badgeType = 'success';
                break;
            case 'closed':
                $badgeType = 'info';
                break;
        }

        return '<div class="badge badge-' . $badgeType . '" role="badge"> ' . ucfirst($this->status) . '</div>';
    }

    public function populateObject(array $data): void {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function update($id): Ticket {
        $sql = "UPDATE ticket SET team_member = :team_member, title = :title, body = :body,
                requester = :requester, team = :team, status = :status, priority = :priority
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':team_member', $this->team_member);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':requester', $this->requester);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':priority', $this->priority);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        return self::find($id);
    }

    public function getAllTickets(): array {
        $sql = "SELECT * FROM ticket ORDER BY id DESC";
        $stmt = $this->db->query($sql);

        if ($stmt === false) {
            throw new Exception("Failed to retrieve tickets.");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
    }
}
