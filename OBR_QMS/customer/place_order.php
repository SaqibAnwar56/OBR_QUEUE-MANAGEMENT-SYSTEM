<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: orders.php'); exit;
}

header('Content-Type: application/json');
ob_start();

function respond($ok, $msg, $extra = []) {
    ob_end_clean();
    echo json_encode(array_merge(['success'=>$ok,'msg'=>$msg], $extra));
    exit;
}

if (!isset($_SESSION['cust_id'])) respond(false, 'Please login to place an order.');

$cust_name  = $_SESSION['cust_name'];
$cust_phone = $_SESSION['cust_phone'];

// Must have a token (any status) to order
$p2      = $conn->real_escape_string($cust_phone);
$n2      = $conn->real_escape_string($cust_name);
$tq      = $conn->query("SELECT id, token_number FROM queue WHERE (phone='$p2' OR name='$n2') AND status IN ('waiting','serving') ORDER BY id DESC LIMIT 1");
$tok_row = $tq ? $tq->fetch_assoc() : null;

if (!$tok_row) {
    respond(false, 'You need a queue token to order.', ['need_token' => true]);
}

// Read cart
$data  = json_decode(file_get_contents('php://input'), true);
if (empty($data)) $data = $_POST;
$items = $data['items'] ?? [];

if (empty($items)) respond(false, 'Cart is empty.');

// Validate items
$total = 0;
$valid_items = [];
foreach ($items as $item) {
    $did  = (int)($item['id'] ?? 0);
    if ($did <= 0) continue;
    $dq   = $conn->query("SELECT id, name, price, available FROM dishes WHERE id=$did LIMIT 1");
    $dish = $dq ? $dq->fetch_assoc() : null;
    if (!$dish || !$dish['available']) continue;
    $qty  = max(1, (int)($item['qty'] ?? 1));
    $sub  = round((float)$dish['price'] * $qty, 2);
    $total += $sub;
    $valid_items[] = ['dish_id'=>(int)$dish['id'],'name'=>$dish['name'],'qty'=>$qty,'sub'=>$sub];
}

if (empty($valid_items)) respond(false, 'No valid items found.');

$total = round($total, 2);
$n     = $conn->real_escape_string($cust_name);

// Save order
$sql = "INSERT INTO orders (customer_name, total_amount, created_at) VALUES ('$n', $total, NOW())";
if (!$conn->query($sql)) respond(false, 'Order failed: ' . $conn->error);
$order_id = (int)$conn->insert_id;

foreach ($valid_items as $vi) {
    $conn->query("INSERT INTO order_items (order_id, dish_id, quantity) VALUES ($order_id, {$vi['dish_id']}, {$vi['qty']})");
}

// Notify admin
$tok_str = ' (Token #'.str_pad($tok_row['token_number'],3,'0',STR_PAD_LEFT).')';
$summary = implode(', ', array_map(fn($v)=>$v['name'].' x'.$v['qty'], $valid_items));
$msg     = $conn->real_escape_string("New order #$order_id from $cust_name$tok_str — Rs.".number_format($total,0)." [Cash on Delivery] — ".count($valid_items)." item(s): $summary");
$conn->query("INSERT INTO notifications (message, is_read, created_at) VALUES ('$msg', 0, NOW())");

respond(true, 'Order placed!', ['order_id'=>$order_id,'total'=>$total,'items'=>count($valid_items)]);
?>