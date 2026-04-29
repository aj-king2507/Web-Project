<?php
require 'db.php';

header("Content-Type: application/json");
header('Content-Disposition: attachment; filename="appointments.json"');

$stmt = $pdo->prepare("
    SELECT 
        a.appointment_id,
        a.start_datetime,
        a.end_datetime,
        a.status,
        a.notes,

        c.first_name AS customer_first,
        c.last_name AS customer_last,

        t.first_name AS therapist_first,
        t.last_name AS therapist_last,

        s.name AS service_name,
        s.price

    FROM appointment a
    JOIN customer c ON a.customer_id = c.customer_id
    JOIN therapist t ON a.therapist_id = t.therapist_id
    JOIN service s ON a.service_id = s.service_id
");

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data, JSON_PRETTY_PRINT);
exit;
?>
