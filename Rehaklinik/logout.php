<?php
require_once __DIR__ . '/Server/auth.php';

$_SESSION = [];
session_destroy();
header('Location: login.html');
exit;
