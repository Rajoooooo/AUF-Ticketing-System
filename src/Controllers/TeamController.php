<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Team;

class TeamController extends BaseController
{
    public function showTeamTable()
    {
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Fetch all teams
        $teamModel = new Team();
        $teams = $teamModel->getAllTeams();

        // Prepare the template and data for the view
        $template = 'team';
        $data = [
            'title' => 'Team Table',
            'user' => $_SESSION['user'],
            'teams' => $teams
        ];

        // Render the view
        echo $this->render($template, $data);
    }

    public function showCreateNewTeam()
    {
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
            header('Location: /login-form');
            exit();
        }

        // Render the create team view
        $template = 'create-team';
        $data = [
            'title' => 'Create Team',
            'user' => $_SESSION['user']
        ];
        echo $this->render($template, $data);
    }

    public function createTeam()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['team-name']);

            if (!empty($name)) {
                $teamModel = new Team();
                $teamModel->createTeam($name);

                $_SESSION['success'] = 'Team successfully created.';
            } else {
                $_SESSION['error'] = 'Team name cannot be empty.';
            }

            header('Location: /team');
            exit();
        }
    }

    public function deleteTeam($id)
    {
        session_start();

        $teamModel = new Team();
        $teamModel->deleteTeam($id);

        $_SESSION['success'] = 'Team successfully deleted.';
        header('Location: /team');
        exit();
    }

    public function updateTeam($id)
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['team-name']);

            if (!empty($name)) {
                $teamModel = new Team();
                $teamModel->updateTeam($id, $name);

                $_SESSION['success'] = 'Team successfully updated.';
            } else {
                $_SESSION['error'] = 'Team name cannot be empty.';
            }

            header('Location: /team');
            exit();
        }
    }
}
