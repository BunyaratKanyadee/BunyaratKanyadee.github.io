<?php
require_once __DIR__ . '/connection.php';

// Step 1: Prepare the SQL query
$stmt = $conn->prepare("SELECT * FROM therapeut");
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Step 2: Execute the query
$stmt->execute();
if ($stmt->errno) {
    die("Error executing the statement: " . $stmt->error);
}

// Step 3: Get the result set
$therapeut_result = $stmt->get_result();
if ($therapeut_result === false) {
    die("Error getting the result set: " . $stmt->error);
}

// Step 4: The caller (therapeuten.php) loops over $therapeut_result->fetch_assoc()

// Close the prepared statement
$stmt->close();
