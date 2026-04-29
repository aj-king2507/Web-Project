<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$customer_id = $_POST['customer_id'] ?? null;
$therapist_id = $_POST['therapist_id'] ?? null;
$service_id = $_POST['service_id'] ?? null;
$start_datetime = $_POST['start_datetime'] ?? null;
$notes = trim($_POST['notes'] ?? '');

// Basic validation
if (!$customer_id || !$therapist_id || !$service_id || !$start_datetime) {
    die("Missing required fields");
}

try {

    // 1. Get service duration
    $stmt = $pdo->prepare("SELECT duration_minutes FROM service WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Invalid service");
    }

    $duration = $service['duration_minutes'];

    // 2. Calculate end time
    $start = new DateTime($start_datetime);
    $end = clone $start;
    $end->modify("+{$duration} minutes");

    $end_datetime = $end->format("Y-m-d H:i:s");

    // 3. Insert appointment
    $stmt = $pdo->prepare("
        INSERT INTO appointment 
        (customer_id, therapist_id, service_id, start_datetime, end_datetime, status, notes)
        VALUES (?, ?, ?, ?, ?, 'Booked', ?)
    ");

    $stmt->execute([
        $customer_id,
        $therapist_id,
        $service_id,
        $start_datetime,
        $end_datetime,
        $notes
    ]);

    echo "Booking successful";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
