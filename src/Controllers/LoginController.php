<?php

namespace App\Controllers;

use App\Models\User;

class LoginController extends BaseController {

    // Show login form
    public function showLoginForm() {
        global $mustache; // Access global mustache instance
        echo $mustache->render('login-form');
    }

    // Handle login action
    public function login() {
        global $conn, $mustache; // Access global database connection and mustache instance
        session_start();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';

        // Input validation
        if (empty($email)) {
            $error = 'Please enter an email address';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address';
        } elseif (empty($password)) {
            $error = 'Please enter your password';
        } else {
            $userModel = new User($conn); // Use the global connection
            $user = $userModel->getUserByEmail($email); // This method is now defined

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['logged-in'] = true;
                $_SESSION['user'] = $user;
                header('Location: /dashboard');
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        }

        // Render login form with error
        echo $mustache->render('login-form', ['error' => $error]);
    }

    // Logout action
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login-form');
        exit();
    }
}
