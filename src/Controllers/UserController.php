<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;

class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showUserTable()
    {
        session_start();
        if (!isset($_SESSION['logged-in'])) {
            header('Location: /login-form');
            exit();
        }

        $users = $this->userModel->getAllUsers();
        $template = 'users';
        $data = ['title' => 'User Table', 'users' => $users];

        echo $this->render($template, $data);
    }

    public function showCreateUsersForm()
    {
        session_start();
        if (!isset($_SESSION['logged-in'])) {
            header('Location: /login-form');
            exit();
        }

        $template = 'create-user';
        $data = ['title' => 'Create User'];
        echo $this->render($template, $data);
    }

    public function storeUser()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'last_password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ];
            $this->userModel->createUser($data);
            header('Location: /users');
            exit();
        }
    }

    public function editUserForm($id)
    {
        session_start();
        $user = $this->userModel->getUserById($id);
        $template = 'edit-user';
        $data = ['title' => 'Edit User', 'user' => $user];
        echo $this->render($template, $data);
    }

    public function updateUser($id)
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'last_password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ];
            $this->userModel->updateUser($id, $data);
            header('Location: /users');
            exit();
        }
    }

    public function deleteUser($id)
    {
        session_start();
        $this->userModel->deleteUser($id);
        header('Location: /users');
        exit();
    }
}
