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

    //Route for Users
    $router->get('/users', '\App\Controllers\UserController@showUserTable');

    // Run the router
    $router->run();

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
