<?php
// Datenbank-Zugangsdaten werden aus Umgebungsvariablen geladen,
// damit keine Zugangsdaten im Repository landen.
// Siehe .env.example für die benötigten Variablen.
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'rehaklinik0';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Im Portfolio-/Demo-Kontext reicht eine einfache Fehlermeldung;
    // in Produktion sollte der Fehler geloggt statt ausgegeben werden.
    http_response_code(500);
    die('Datenbankverbindung fehlgeschlagen.');
}
