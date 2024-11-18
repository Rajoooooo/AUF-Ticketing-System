<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class UserController extends BaseController
{
    public function showUserTable()
    {
        session_start();
        
        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Prepare the template and data for the view
        $template = 'users';
        $data = [
            'title' => 'User table',
            'user' => $_SESSION['user']
        ];

        // Render the ticket view for users
        echo $this->render($template, $data);
    }
}