<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Comment extends BaseModel
{
    public function viewTicket($id)
{
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
        header('Location: /login-form');
        exit();
    }

    // Models for Ticket and Comment
    $ticketModel = new \App\Models\Ticket();
    $commentModel = new \App\Models\Comment();

    // Fetch the ticket details
    $ticket = $ticketModel->getTicketByIdWithDetails($id);

    if (!$ticket) {
        $_SESSION['err'] = "Ticket not found.";
        header('Location: /dashboard');
        exit();
    }

    // Fetch raw comments data
    $rawComments = $commentModel::getCommentsByTicketId($id);

    // Process raw comments to include user names
    $comments = array_map(function ($comment) {
        $userModel = new \App\Models\User(); // Assuming a User model exists
        $user = $userModel->getUserById($comment['user_id']); // Fetch user details
        return [
            'comment_text' => $comment['comment_text'],
            'created_at' => $comment['created_at'],
            'user_name' => $user['name'] ?? 'Unknown User', // Add user name to the comment
        ];
    }, $rawComments);

    // Include current logged-in user's name
    $currentUser = $_SESSION['user'];

    // Render the view.mustache template
    $template = 'view';
    $data = [
        'title' => 'View Ticket',
        'ticket' => $ticket,
        'comments' => $comments,
        'currentUser' => $currentUser,
    ];

    echo $this->render($template, $data);
}


    public function addComment($ticketId, $commentText, $userId)
{
    // Prepare the SQL query to insert the comment with user_id
    $stmt = self::getDbConnection()->prepare("
        INSERT INTO comments (ticket_id, comment_text, user_id) 
        VALUES (:ticket_id, :comment_text, :user_id)
    ");
    
    // Bind the values to the SQL statement
    $stmt->bindParam(':ticket_id', $ticketId);
    $stmt->bindParam(':comment_text', $commentText);
    $stmt->bindParam(':user_id', $userId);
    
    // Execute the query
    return $stmt->execute();
}


public static function getCommentsByTicketId($ticketId)
{
    $stmt = self::getDbConnection()->prepare("
        SELECT 
            c.comment_text, 
            c.created_at, 
            u.name AS user_name 
        FROM 
            comments c
        LEFT JOIN 
            users u ON c.user_id = u.id
        WHERE 
            c.ticket_id = :ticket_id
    ");
    $stmt->execute(['ticket_id' => $ticketId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}