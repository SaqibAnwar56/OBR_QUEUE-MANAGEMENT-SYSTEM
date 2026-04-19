<?php
/**
 * check_token_status.php
 * Called by menu.php JS every 8 seconds when token is 'waiting'
 * Returns current token status so page can auto-unlock ordering
 */
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['cust_id'])) {
    echo json_encode(['status' => 'no_session']); exit;
}

$p2 = $conn->real_escape_string($_SESSION['cust_phone']);
$n2 = $conn->real_escape_string($_SESSION['cust_name']);

$tq = $conn->query("
    SELECT status, token_number
    FROM queue
    WHERE (phone='$p2' OR name='$n2')
    AND status IN ('waiting','serving')
    ORDER BY id DESC LIMIT 1
");
$tok = $tq ? $tq->fetch_assoc() : null;

if (!$tok) {
    echo json_encode(['status' => 'no_token']); exit;
}

echo json_encode([
    'status'       => strtolower($tok['status']),
    'token_number' => $tok['token_number']
]);
?>