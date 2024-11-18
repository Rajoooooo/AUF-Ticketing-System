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
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data
        $template = 'ticket-form';
        $data = [
            'title' => 'Create a New Ticket',
            'user' => $_SESSION['user'], // Passing the user session data
            'err' => isset($_SESSION['err']) ? $_SESSION['err'] : null, // Optional error message
            'msg' => isset($_SESSION['msg']) ? $_SESSION['msg'] : null, // Optional success message
            'teams' => $this->getTeams() // Fetching teams data for the dropdown
        ];

        // Render the ticket form view
        echo $this->render($template, $data);
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
