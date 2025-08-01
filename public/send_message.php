<?php
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/helpers.php';
require_once __DIR__.'/../lib/auth.php';
ensure_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

if (!isset($_SESSION['store_id'])) {
    http_response_code(403);
    exit('Not logged in');
}

$message = sanitize_message($_POST['message'] ?? '');
if ($message === '') {
    http_response_code(400);
    exit('Message cannot be empty');
}

$pdo = get_pdo();
$parent = intval($_POST['parent_id'] ?? 0) ?: null;
$storeUserId = $_SESSION['store_user_id'] ?? null;
$stmt = $pdo->prepare("INSERT INTO store_messages (store_id, store_user_id, sender, message, parent_id, created_at, read_by_store, read_by_admin) VALUES (?, ?, 'store', ?, ?, NOW(), 1, 0)");
$stmt->execute([$_SESSION['store_id'], $storeUserId, $message, $parent]);

if (!empty($_POST['ajax'])) {
    echo json_encode(['success' => true]);
} else {
    header('Location: chat.php');
}
