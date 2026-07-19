<?php
// Legt einen neuen Login-Benutzer an.
// Aufruf:  php Server/create_user.php <email> <passwort> ["Anzeigename"]

require_once __DIR__ . '/connection.php';

if (php_sapi_name() !== 'cli') {
    die("Dieses Skript ist nur für die Kommandozeile gedacht.\n");
}

if ($argc < 3) {
    fwrite(STDERR, "Usage: php Server/create_user.php <email> <passwort> [\"Anzeigename\"]\n");
    exit(1);
}

$email = $argv[1];
$password = $argv[2];
$name = $argv[3] ?? '';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO benutzer (email, password_hash, name) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $email, $hash, $name);

if ($stmt->execute()) {
    echo "Benutzer '{$email}' wurde angelegt. Du kannst dich jetzt in login.html damit anmelden.\n";
} else {
    fwrite(STDERR, 'Fehler beim Anlegen: ' . $stmt->error . "\n");
    exit(1);
}

$stmt->close();
