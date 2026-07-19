<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json; charset=utf-8');

function respond(array $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['success' => false, 'error' => 'Ungültige Anfrage.'], 405);
}

$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
    respond(['success' => false, 'error' => 'Ungültiges CSRF-Token. Bitte Seite neu laden.'], 403);
}

$action = $_POST['action'] ?? '';

$vorname = trim($_POST['vorname'] ?? '');
$nachname = trim($_POST['nachname'] ?? '');
$geburtsdatum = trim($_POST['geburtsdatum'] ?? '') ?: null;
$versichertennummer = trim($_POST['versichertennummer'] ?? '') ?: null;
$kontaktinfo = trim($_POST['kontaktinfo'] ?? '') ?: null;
$therapieart = trim($_POST['therapieart'] ?? '') ?: null;

try {
    switch ($action) {
        case 'create':
            if ($vorname === '' || $nachname === '') {
                respond(['success' => false, 'error' => 'Vor- und Nachname sind Pflichtfelder.'], 422);
            }

            $stmt = $conn->prepare(
                'INSERT INTO patient (vorname, nachname, geburtsdatum, versichertennummer, kontaktinfo, therapieart)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('ssssss', $vorname, $nachname, $geburtsdatum, $versichertennummer, $kontaktinfo, $therapieart);
            $stmt->execute();

            respond(['success' => true, 'patient' => [
                'patient_id' => $stmt->insert_id,
                'vorname' => $vorname,
                'nachname' => $nachname,
                'geburtsdatum' => $geburtsdatum,
                'versichertennummer' => $versichertennummer,
                'kontaktinfo' => $kontaktinfo,
                'therapieart' => $therapieart,
            ]]);
            break;

        case 'update':
            $patientId = (int)($_POST['patient_id'] ?? 0);
            if ($patientId <= 0) {
                respond(['success' => false, 'error' => 'Ungültige Patienten-ID.'], 422);
            }
            if ($vorname === '' || $nachname === '') {
                respond(['success' => false, 'error' => 'Vor- und Nachname sind Pflichtfelder.'], 422);
            }

            $stmt = $conn->prepare(
                'UPDATE patient
                 SET vorname = ?, nachname = ?, geburtsdatum = ?, versichertennummer = ?, kontaktinfo = ?, therapieart = ?
                 WHERE patient_id = ?'
            );
            $stmt->bind_param('ssssssi', $vorname, $nachname, $geburtsdatum, $versichertennummer, $kontaktinfo, $therapieart, $patientId);
            $stmt->execute();

            respond(['success' => true, 'patient' => [
                'patient_id' => $patientId,
                'vorname' => $vorname,
                'nachname' => $nachname,
                'geburtsdatum' => $geburtsdatum,
                'versichertennummer' => $versichertennummer,
                'kontaktinfo' => $kontaktinfo,
                'therapieart' => $therapieart,
            ]]);
            break;

        case 'delete':
            $patientId = (int)($_POST['patient_id'] ?? 0);
            if ($patientId <= 0) {
                respond(['success' => false, 'error' => 'Ungültige Patienten-ID.'], 422);
            }

            $stmt = $conn->prepare('DELETE FROM patient WHERE patient_id = ?');
            $stmt->bind_param('i', $patientId);
            $stmt->execute();

            respond(['success' => true]);
            break;

        default:
            respond(['success' => false, 'error' => 'Unbekannte Aktion.'], 400);
    }
} catch (mysqli_sql_exception $e) {
    // 1451 = foreign key constraint fails (Patient hat noch verknüpfte Termine)
    if ($e->getCode() === 1451) {
        respond(['success' => false, 'error' => 'Patient kann nicht gelöscht werden, solange noch Termine für ihn eingetragen sind.'], 409);
    }
    respond(['success' => false, 'error' => 'Datenbankfehler.'], 500);
}
