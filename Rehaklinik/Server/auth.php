<?php
// Kleines Auth-Hilfsmodul: Session starten und Login-Pflicht durchsetzen.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.html');
        exit;
    }
}

function current_user_name(): ?string
{
    return $_SESSION['user_name'] ?? null;
}

function get_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
