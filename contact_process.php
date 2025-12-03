<?php
// Connect to your database
$conn = new mysqli("localhost", "root", "", "opal_glow");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // SECURITY: prevent XSS
    $name    = htmlspecialchars(trim($_POST['client_name']));
    $email   = htmlspecialchars(trim($_POST['client_email']));
    $phone   = htmlspecialchars(trim($_POST['client_phone']));
    $service = htmlspecialchars(trim($_POST['service']));
    $message = htmlspecialchars(trim($_POST['message']));

    // SECURITY: SQL injection protection
    $stmt = $conn->prepare("INSERT INTO contact_messages (client_name, client_email, client_phone, service, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $client_name, $client_email, $client_phone, $service, $message);

    if ($stmt->execute()) {
        echo "<h2>Your message has been sent. Thank you for contacting Opal Glow!</h2>";
        echo "<p><a href='contact_us.php'>Back to Contact Page</a></p>";
    } else {
        echo "<h2>Error sending your message. Please try again.</h2>";
    }

    $stmt->close();
}

$conn->close();
?>
