<?php
require 'db.php';

try {
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
        ORDER BY a.start_datetime DESC
    ");

    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error loading appointments: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <style>
        body { font-family: Arial; background:#f4f6f8; padding:20px; }
        table { width:100%; border-collapse: collapse; background:#fff; }
        th, td { padding:10px; border:1px solid #ddd; }
        th { background:#007BFF; color:white; }
    </style>
</head>

<body>

<h2>Appointments (OpalGlow)</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Therapist</th>
        <th>Service</th>
        <th>Start</th>
        <th>End</th>
        <th>Status</th>
        <th>Price</th>
        <th>Notes</th>
    </tr>

    <?php foreach ($appointments as $a): ?>
        <tr>
            <td><?= $a['appointment_id'] ?></td>
            <td><?= $a['customer_first'] . " " . $a['customer_last'] ?></td>
            <td><?= $a['therapist_first'] . " " . $a['therapist_last'] ?></td>
            <td><?= $a['service_name'] ?></td>
            <td><?= $a['start_datetime'] ?></td>
            <td><?= $a['end_datetime'] ?></td>
            <td><?= $a['status'] ?></td>
            <td><?= $a['price'] ?></td>
            <td><?= $a['notes'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
