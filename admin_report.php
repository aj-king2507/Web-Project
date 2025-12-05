<?php
include('db_connect.php'); // Database connection

// Query to get the number of appointments
$query_appointments = "SELECT COUNT(*) AS total_appointments FROM appointment";
$result_appointments = mysqli_query($conn, $query_appointments);
$row_appointments = mysqli_fetch_assoc($result_appointments);
$total_appointments = $row_appointments['total_appointments'];

// Query to get the number of completed appointments
$query_completed_appointments = "SELECT COUNT(*) AS completed FROM appointment WHERE status = 'Completed'";
$result_completed_appointments = mysqli_query($conn, $query_completed_appointments);
$row_completed_appointments = mysqli_fetch_assoc($result_completed_appointments);
$completed_appointments = $row_completed_appointments['completed'];

// Query to get the number of therapists
$query_therapists = "SELECT COUNT(*) AS total_therapists FROM therapist";
$result_therapists = mysqli_query($conn, $query_therapists);
$row_therapists = mysqli_fetch_assoc($result_therapists);
$total_therapists = $row_therapists['total_therapists'];

// Query to get total revenue
$query_revenue = "SELECT SUM(amount) AS total_revenue FROM payment WHERE status = 'Completed'";
$result_revenue = mysqli_query($conn, $query_revenue);
$row_revenue = mysqli_fetch_assoc($result_revenue);
$total_revenue = $row_revenue['total_revenue'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Report</title>
    <link rel="stylesheet" href="assets/css/admin_report.css"> <!-- Link to your existing styles -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js library -->
</head>
<body style="background-color: #f8f8f8;">

<!-- Header -->
<header class="admin-header">
        <div class="dashboard-logo">OpalGlow</div>
        <div class="header-right">
            <span class="admin-badge">👤 Admin</span>
            <a href="admin_dashboard.php" class="back-btn">← Dashboard</a>
            <a href="logout.php" class="logout-btn">⏻ Logout</a>
        </div>
</header>

<!-- Main Content -->
<div class="content-container" style="margin-top: 20px; padding: 20px; background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2>Salon Analytics Overview</h2>
    
    <!-- Data Display Section -->
    <div style="display: flex; justify-content: space-around;">
        <div style="text-align: center;">
            <h4>Total Appointments</h4>
            <p><?php echo $total_appointments; ?></p>
        </div>
        <div style="text-align: center;">
            <h4>Completed Appointments</h4>
            <p><?php echo $completed_appointments; ?></p>
        </div>
        <div style="text-align: center;">
            <h4>Total Therapists</h4>
            <p><?php echo $total_therapists; ?></p>
        </div>
        <div style="text-align: center;">
            <h4>Total Revenue (USD)</h4>
            <p><?php echo number_format($total_revenue, 2); ?></p>
        </div>
    </div>

    <hr>

    <!-- Charts Section -->
    <h3>Visual Analytics</h3>

    <!-- Pie Chart for Appointment Status -->
    <div style="width: 48%; float: left;">
        <canvas id="appointmentPieChart"></canvas>
    </div>
    
    <!-- Bar Chart for Therapist Availability -->
    <div style="width: 48%; float: right;">
        <canvas id="therapistBarChart"></canvas>
    </div>

    <div style="clear: both;"></div>
</div>

<!-- Footer -->
<footer style="background-color: #6a1b9a; color: white; padding: 10px; text-align: center; margin-top: 20px;">
    <p>&copy; 2025 OpalGlow Salon. All rights reserved.</p>
</footer>

<script>
    // Pie Chart for Appointment Status (Completed vs Pending)
    const ctx1 = document.getElementById('appointmentPieChart').getContext('2d');
    const appointmentPieChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                label: 'Appointment Status',
                data: [<?php echo $completed_appointments; ?>, <?php echo $total_appointments - $completed_appointments; ?>],
                backgroundColor: ['#4CAF50', '#FFC107'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' appointments';
                        }
                    }
                }
            }
        }
    });

    // Bar Chart for Therapist Availability
    const ctx2 = document.getElementById('therapistBarChart').getContext('2d');
    const therapistBarChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Therapist 1', 'Therapist 2', 'Therapist 3', 'Therapist 4', 'Therapist 5', 'Therapist 6'],
            datasets: [{
                label: 'Therapists Available',
                data: [5, 4, 6, 3, 2, 5],  // Static data, modify as needed
                backgroundColor: '#6a1b9a',
                borderColor: '#6a1b9a',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
