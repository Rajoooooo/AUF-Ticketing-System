<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Ticket;

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        session_start();

        // Ensure the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Fetch tickets from the database
        $ticketModel = new Ticket();
        $tickets = $ticketModel->getAllTickets(); // Retrieve all tickets

        // Pass data to the view
        $template = 'dashboard';
        $data = [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'],
            'tickets' => $tickets, // Tickets data for the dashboard
        ];

        echo $this->render($template, $data);
    }
}
