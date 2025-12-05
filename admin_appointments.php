<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Handle cancel appointment
if (isset($_GET['cancel_id'])) {
    $appointment_id = $_GET['cancel_id'];
    $cancel_query = "UPDATE appointment SET status='Cancelled' WHERE appointment_id = $appointment_id";
    if (mysqli_query($conn, $cancel_query)) {
        // Redirect to the same page to see updated data
        header("Location: admin_appointments.php?status=cancelled");
        exit();
    } else {
        $error_message = "Error cancelling appointment. Please try again.";
    }
}

// Fetch appointments data
$query = "SELECT * FROM appointment 
          JOIN users ON appointment.user_id = users.user_id 
          JOIN service ON appointment.service_id = service.service_id 
          JOIN therapist ON appointment.therapist_id = therapist.therapist_id 
          ORDER BY appointment.start_datetime DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Admin</title>
    <link rel="stylesheet" href="assets/css/admin_appointments.css">
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="header-container">
            <div class="logo">
                <h1>OpalGlow Admin Panel</h1>
            </div>
            <nav class="navbar">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2 class="page-title">Appointments</h2>

        <!-- Success/Failure Message Display -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'cancelled') { ?>
            <div class="alert success">Appointment has been successfully cancelled.</div>
        <?php } elseif (isset($error_message)) { ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Appointments Table -->
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Customer</th>
                    <th>Therapist</th>
                    <th>Service</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($appointment = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $appointment['appointment_id']; ?></td>
                        <td><?php echo $appointment['username']; ?></td>
                        <td><?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></td>
                        <td><?php echo $appointment['name']; ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($appointment['start_datetime'])); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($appointment['end_datetime'])); ?></td>
                        <td>
                            <span class="status <?php echo strtolower($appointment['status']); ?>">
                                <?php echo $appointment['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($appointment['status'] != 'Cancelled') { ?>
                                <a href="?cancel_id=<?php echo $appointment['appointment_id']; ?>" class="btn-cancel">Cancel</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 OpalGlow - All Rights Reserved</p>
    </footer>
</body>
</html>
