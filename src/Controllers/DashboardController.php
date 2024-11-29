<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Ticket;

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        session_start();

        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        $ticketModel = new Ticket();
        $tickets = $ticketModel->getAllTickets();

        $data = [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'],
            'tickets' => $tickets
        ];

        echo $this->render('dashboard', $data);
    }
}
