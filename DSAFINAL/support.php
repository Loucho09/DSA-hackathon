<?php
// Simple support endpoint: expects POST name, email, message; returns JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Invalid method']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($name === '' || $email === '' || $message === '') {
    echo json_encode(['ok' => false, 'error' => 'Please fill all fields.']);
    exit;
}

// Configure support destination
$to = 'support@skincareshop.com';
$subject = 'Support Request from '.$name;
$body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";
$headers = 'From: '.$email . "\r\n" . 'Reply-To: '.$email . "\r\n";

$sent = @mail($to, $subject, $body, $headers);
if ($sent) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Failed to send email.']);
}

