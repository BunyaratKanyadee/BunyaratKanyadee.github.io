<?php
require_once __DIR__ . '/Server/auth.php';
require_once __DIR__ . '/Server/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['pswd'] ?? '';

if ($email === '' || $password === '') {
    header('Location: login.html?error=1');
    exit;
}

$stmt = $conn->prepare('SELECT benutzer_id, password_hash, name FROM benutzer WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($user && password_verify($password, $user['password_hash'])) {
    // Session-ID nach dem Login neu erzeugen (schützt vor Session Fixation)
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['benutzer_id'];
    $_SESSION['user_name'] = $user['name'];
    header('Location: index.php');
    exit;
}

header('Location: login.html?error=1');
exit;
