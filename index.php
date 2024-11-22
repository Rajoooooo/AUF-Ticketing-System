<?php

require "vendor/autoload.php";
require "init.php";

global $conn;


try {
    $router = new \Bramus\Router\Router();

    //Route for homepage
    $router->get('/', '\App\Controllers\HomeController@index');
    
    //Route for login page
    $router->get('/login-form', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    //Route for dashboard
    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');

    //Route for ticket form
    $router->get('/ticket-form', '\App\Controllers\TicketController@showTicketForm');
    $router->post('/ticket-form', '\App\Controllers\TicketController@createTicket');
    $router->get('/ticket/delete/{id}', '\App\Controllers\TicketController@deleteTicket');

    //Route for Open tickets
    $router->get('/open', '\App\Controllers\TicketController@showOpenTable');

    //Route for Solved tickets
    $router->get('/solved', '\App\Controllers\TicketController@showSolvedTable');
    
    //Route for Closed tickets
    $router->get('/closed', '\App\Controllers\TicketController@showClosedTable');

    //Route for Pending tickets
    $router->get('/pending', '\App\Controllers\TicketController@showPendingTable');

    //Route for Unassigned tickets
    $router->get('/unassigned', '\App\Controllers\TicketController@showUnassignedTable');

    //Route for My tickets
    $router->get('/mytickets', '\App\Controllers\TicketController@showMyticketsTable');

    //Route for Team
    $router->get('/team', '\App\Controllers\TeamController@showTeamTable');
    $router->get('/create-team', '\App\Controllers\TeamController@showCreateNewTeam');
    $router->post('/create-team', '\App\Controllers\TeamController@createTeam');
    $router->post('/team/delete/{id}', '\App\Controllers\TeamController@deleteTeam');
    $router->post('/team/update/{id}', '\App\Controllers\TeamController@updateTeam');


    //Route for Users
    $router->get('/users', '\App\Controllers\UserController@showUserTable');
    $router->get('/create-user', '\App\Controllers\UserController@showCreateUsersForm');
    $router->post('/new-user', '\App\Controllers\UserController@storeUser');
    $router->get('/edit-user/{id}', '\App\Controllers\UserController@editUserForm');
    $router->post('/update-user/{id}', '\App\Controllers\UserController@updateUser');
    $router->get('/delete-user/{id}', '\App\Controllers\UserController@deleteUser');
    





    // Run the router
    $router->run();

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
