<?php
// Read JSON file
$json = file_get_contents("appointments.json");

// Convert JSON to PHP array
$data = json_decode($json, true);

// Check if JSON is valid
if ($data === null) {
    die("Invalid JSON file");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments</title>
</head>
<body>

<h2>Appointments List</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Service</th>
        <th>Time</th>
        <th>Date</th>
    </tr>

    <?php foreach ($data as $appointment): ?>
    <tr>
        <td><?= $appointment['id'] ?></td>
        <td><?= $appointment['customer_name'] ?></td>
        <td><?= $appointment['phone'] ?></td>
        <td><?= $appointment['email'] ?></td>
        <td><?= $appointment['service'] ?></td>
        <td><?= $appointment['time_slot'] ?></td>
        <td><?= $appointment['appointment_date'] ?></td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
