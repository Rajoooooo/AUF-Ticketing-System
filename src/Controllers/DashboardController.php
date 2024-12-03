<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Ticket;
use FPDF;

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

    file_put_contents('debug_dashboard_tickets.log', print_r($tickets, true));

    $data = [
        'tickets_json' => json_encode($tickets),
        'tickets' => $tickets
    ];

    echo $this->render('dashboard', $data);
}

    public function generateReport()
{
    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    // Validate date range
    if (!$startDate || !$endDate) {
        die('Please select a valid date range.');
    }

    $ticketModel = new \App\Models\Ticket();
    $tickets = $ticketModel->getTicketsByDateRange($startDate, $endDate);

    if (empty($tickets)) {
        die('No tickets found for the selected date range.');
    }

    $pdf = new \FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->AddFont('Times', '', 'times.php');
    $pdf->SetFont('Times', '', 12);

    $pdf->Cell(0, 10, 'AUF Helpdesk Tickets Report', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Date Range: $startDate to $endDate", 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell(40, 10, 'Requester', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Team', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Ticket Status', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Priority', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Assigned To', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Created At', 1, 1, 'C', true);
    

    $pdf->SetFont('Arial', '', 10);
    foreach ($tickets as $ticket) {
        $pdf->Cell(40, 10, $ticket['requester'], 1);
        $pdf->Cell(40, 10, $ticket['team'] ?: 'Unassigned', 1);
        $pdf->Cell(40, 10, ucfirst($ticket['status']), 1);
        $pdf->Cell(40, 10, ucfirst($ticket['priority']), 1);
        $pdf->Cell(40, 10, $ticket['team_member'] ?: 'Unassigned', 1);
        $pdf->Cell(50, 10, $ticket['created_at'], 1, 1);
    }

    $pdf->Output('D', 'Tickets_Report.pdf');
}


}


