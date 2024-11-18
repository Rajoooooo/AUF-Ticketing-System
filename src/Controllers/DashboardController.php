<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        // Start the session
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the data for rendering
        $template = 'dashboard';
        $data = [
            'title' => '',
            'user' => $_SESSION['user'] // Passing the user session data
        ];

        // Render the dashboard view
        $output = $this->render($template, $data);
        return $output;
    }
}
