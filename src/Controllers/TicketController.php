<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Ticket;
use App\Models\Requester;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;

class TicketController extends BaseController
{
    // Show the form to create a new ticket
    public function showTicketForm()
    {
        session_start();

        // Check login
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $teamModel = new Team();
        $teams = $teamModel->getAllTeams();

        // Capture the session message and clear it
        $message = $_SESSION['msg'] ?? null;
        unset($_SESSION['msg']); // Clear the message after use

        // Capture the error message and clear it
        $error = $_SESSION['err'] ?? null;
        unset($_SESSION['err']); // Clear the error after use

        $template = 'ticket-form';
        $data = [
            'title' => 'Create a New Ticket',
            'user' => $_SESSION['user'],
            'teams' => $teams,
            'err' => $error,
            'msg' => $message
        ];

        echo $this->render($template, $data);
    }

    // Create a new ticket
    public function createTicket()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $subject = $_POST['subject'];
            $comment = $_POST['comment'];
            $team = $_POST['team'];
            $priority = $_POST['priority'];

            $requesterModel = new Requester();
            $ticketModel = new Ticket();

            // Insert or retrieve requester
            $requesterId = $requesterModel->findOrCreate($name, $email, $phone);

            if (!$requesterId) {
                $_SESSION['err'] = "Failed to create requester.";
                header('Location: /ticket-form');
                exit();
            }

            // Create ticket
            $ticketData = [
                'title' => $subject,
                'body' => $comment,
                'requester' => $requesterId,
                'team' => $team === 'none' ? null : $team,
                'priority' => $priority
            ];

            if ($ticketModel->createTicket($ticketData)) {
                $_SESSION['msg'] = "Ticket successfully created.";
            } else {
                $_SESSION['err'] = "Failed to create ticket.";
            }

            header('Location: /dashboard');
        }
    }

    // Show all tickets
    public function showAllTickets()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $tickets = $ticketModel->getAllTickets();

        $template = 'dashboard';
        $data = [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'],
            'tickets' => $tickets
        ];

        echo $this->render($template, $data);
    }

    // Delete a ticket
    public function deleteTicket($id)
    {
        $ticketModel = new Ticket();
        $ticketModel->deleteTicket($id);

        header('Location: /dashboard');
    }

    // Show all open tickets
    public function showOpenTable()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $openTickets = $ticketModel->getOpenTickets();

        $template = 'open';
        $data = [
            'title' => 'Open Tickets',
            'user' => $_SESSION['user'],
            'allTicket' => $openTickets
        ];

        echo $this->render($template, $data);
    }

    // Show all solved tickets
    public function showSolvedTable()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $solvedTickets = $ticketModel->getSolvedTickets();

        $template = 'solved';
        $data = [
            'title' => 'Solved Tickets',
            'user' => $_SESSION['user'],
            'allTicket' => $solvedTickets
        ];

        echo $this->render($template, $data);
    }

    // Show all closed tickets
    public function showClosedTable()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $closedTickets = $ticketModel->getClosedTickets();

        $template = 'closed';
        $data = [
            'title' => 'Closed Tickets',
            'user' => $_SESSION['user'],
            'allTicket' => $closedTickets
        ];

        echo $this->render($template, $data);
    }

    // Show all pending tickets
    public function showPendingTable()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $pendingTickets = $ticketModel->getPendingTickets();

        $template = 'pending';
        $data = [
            'title' => 'Pending Tickets',
            'user' => $_SESSION['user'],
            'allTicket' => $pendingTickets
        ];

        echo $this->render($template, $data);
    }

    // Show all unassigned tickets
    public function showUnassignedTable()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $template = 'unassigned';
        $data = [
            'title' => 'Unassigned Tickets',
            'user' => $_SESSION['user']
        ];

        echo $this->render($template, $data);
    }

    // Show all my tickets
    public function showMyticketsTable()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $template = 'mytickets';
        $data = [
            'title' => 'My Tickets',
            'user' => $_SESSION['user']
        ];

        echo $this->render($template, $data);
    }

    // Show the form to set ticket
    public function showSetTicketForm($id)
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $teamModel = new Team();
        $teamMemberModel = new TeamMember();

        $ticket = $ticketModel->getTicketById($id);

        if (!$ticket) {
            $_SESSION['err'] = 'Ticket not found.';
            header('Location: /dashboard');
            exit();
        }

        $teams = $teamModel->getAllTeams();
        $teamMembers = !empty($ticket['team']) ? $teamMemberModel->getMembersByTeamId($ticket['team']) : [];

        $template = 'set-ticket';
        $data = [
            'title' => 'Set Ticket',
            'ticket' => [
                'id' => $ticket['id'],
                'status' => [
                    'open' => $ticket['status'] === 'open',
                    'pending' => $ticket['status'] === 'pending',
                    'solved' => $ticket['status'] === 'solved',
                    'closed' => $ticket['status'] === 'closed',
                ],
            ],
            'teams' => $teams,
            'teamMembers' => $teamMembers,
            'user' => $_SESSION['user']
        ];

        echo $this->render($template, $data);
    }

    public function updateTicketStatus($id)
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newStatus = $_POST['status'];
            $team = $_POST['team'] ?? null;
            $teamMember = $_POST['team_member'] ?? null;

            if (!in_array($newStatus, ['open', 'pending', 'solved', 'closed'])) {
                $_SESSION['err'] = 'Invalid status selected.';
                header("Location: /set-ticket/{$id}");
                exit();
            }

            $ticketModel = new Ticket();

            // Update status
            $statusUpdated = $ticketModel->updateStatus($id, $newStatus);

            // Optionally assign team and member if provided
            if ($team || $teamMember) {
                $ticketModel->assignTeamAndMember($id, $team, $teamMember);
            }

            if ($statusUpdated) {
                $_SESSION['msg'] = 'Ticket status updated successfully.';
            } else {
                $_SESSION['err'] = 'Failed to update ticket status.';
            }

            header('Location: /dashboard');
            exit();
        }
    }
    public function fetchTeamMembers($teamId)
    {
        $teamMemberModel = new TeamMember();
        $members = $teamMemberModel->getMembersByTeamId($teamId);
        echo json_encode($members);
    }

}
