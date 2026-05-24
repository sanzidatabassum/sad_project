<?php
// Add error logging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
error_log("Contact form submission started");

require_once 'db_config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required_fields = ['name', 'phone', 'message'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            error_log("Missing field: " . $field);
            throw new Exception("Missing required field: {$field}");
        }
    }

    // Get and sanitize form data
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = trim($_POST['message']);

    error_log("Processing contact form for: $name, Phone: $phone, Email: $email");

    // Prepare and execute the insertion
    $sql = "INSERT INTO contact_messages (name, phone, email, message) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Database error: " . $conn->error);
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $phone, $email, $message);
    
    if (!$stmt->execute()) {
        error_log("Execute error: " . $stmt->error);
        throw new Exception('Failed to send message: ' . $stmt->error);
    }

    error_log("Contact message saved successfully");
    
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully! We will contact you soon.'
    ]);

} catch (Exception $e) {
    error_log("Error occurred: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?> 