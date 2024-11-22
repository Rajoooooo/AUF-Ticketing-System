<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Ticket;
use App\Models\Requester;
use App\Models\Team;
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

        $template = 'ticket-form';
        $data = [
            'title' => 'Create a New Ticket',
            'user' => $_SESSION['user'],
            'teams' => $teams,
            'err' => $_SESSION['err'] ?? null,
            'msg' => $_SESSION['msg'] ?? null
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
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'open';
        $data = [
            'title' => 'Open Tickets',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for open tickets
        echo $this->render($template, $data);
    }

    //show all solved ticket
    public function showSolvedTable()
    {
        session_start();
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'solved';
        $data = [
            'title' => 'Solved Tickets',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for solved tickets
        echo $this->render($template, $data);
    }

    //show all closed ticket 
    public function showClosedTable()
    {
        session_start();
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'closed';
        $data = [
            'title' => 'Closed Tickets',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for closed tickets
        echo $this->render($template, $data);
    }

    //show all pending ticket 
    public function showPendingTable()
    {
        session_start();
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'pending';
        $data = [
            'title' => 'Pending Tickets',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for pending tickets
        echo $this->render($template, $data);
    }

    //show all unassigned ticket 
    public function showUnassignedTable()
    {
        session_start();
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'unassigned';
        $data = [
            'title' => 'Unassigned Tickets',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for unassigned tickets
        echo $this->render($template, $data);
    }


    //show all my tickets
    public function showMyticketsTable()
    {
        session_start();
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'mytickets';
        $data = [
            'title' => 'My Tickets',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for myticket tickets
        echo $this->render($template, $data);
    }


    // Method to simulate fetching teams from the database
    private function getTeams()
    {
        return [
            ['id' => '1', 'name' => 'Support Team'],
            ['id' => '2', 'name' => 'Development Team'],
            ['id' => '3', 'name' => 'HR Team']
        ];
    }
}
