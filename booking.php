<?php
session_start();

/* ---------- Booking Handler ---------- */
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'opalglow');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $service_input = trim($_POST['service_type'] ?? '');
    $date  = trim($_POST['date'] ?? '');
    $time  = trim($_POST['time'] ?? '');

    if (!$name || !$email || !$service_input || !$date || !$time) {
        $_SESSION['booking_message'] = "<strong>Error:</strong> Please complete all required fields.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    $start_str = $date . ' ' . $time . ':00';
    $start_dt = DateTime::createFromFormat('Y-m-d H:i:s', $start_str);
    if (!$start_dt) {
        $_SESSION['booking_message'] = "<strong>Error:</strong> Invalid date/time format.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        $_SESSION['booking_message'] = "<strong>System error:</strong> Unable to contact database.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    $mysqli->set_charset('utf8mb4');

    /* Get service info */
    $stmt = $mysqli->prepare("SELECT service_id, duration_minutes, name, is_active FROM service WHERE (service_id = ? OR name = ?) LIMIT 1");
    $service_id_candidate = is_numeric($service_input) ? (int)$service_input : null;
    $stmt->bind_param('is', $service_id_candidate, $service_input);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        $_SESSION['booking_message'] = "<strong>Unavailable service:</strong> Please select another service.";
        $stmt->close();
        $mysqli->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    $service = $res->fetch_assoc();
    $stmt->close();

    if ((int)$service['is_active'] !== 1) {
        $_SESSION['booking_message'] = "<strong>Service not active.</strong>";
        $mysqli->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    $duration_minutes = (int)$service['duration_minutes'];
    $start_iso = $start_dt->format('Y-m-d H:i:s');
    $end_dt = clone $start_dt;
    $end_dt->modify("+{$duration_minutes} minutes");
    $end_iso = $end_dt->format('Y-m-d H:i:s');

    /* Find available therapist */
    $avail_sql = "
      SELECT ta.therapist_id
      FROM therapist_availability ta
      JOIN therapist t ON t.therapist_id = ta.therapist_id AND t.is_active = 1
      WHERE ta.start_datetime <= ? AND ta.end_datetime >= ?
      GROUP BY ta.therapist_id
      ORDER BY ta.therapist_id
      LIMIT 1
    ";
    $stmt = $mysqli->prepare($avail_sql);
    $stmt->bind_param('ss', $start_iso, $end_iso);
    $stmt->execute();
    $res = $stmt->get_result();
    $candidate_therapist = null;
    while ($row = $res->fetch_assoc()) {
        $therapist_id = (int)$row['therapist_id'];

        $overlap_sql = "
          SELECT 1 FROM appointment
          WHERE therapist_id = ?
            AND status != 'Cancelled'
            AND NOT (end_datetime <= ? OR start_datetime >= ?)
          LIMIT 1
        ";
        $s2 = $mysqli->prepare($overlap_sql);
        $s2->bind_param('iss', $therapist_id, $start_iso, $end_iso);
        $s2->execute();
        $r2 = $s2->get_result();
        if ($r2->num_rows === 0) {
            $candidate_therapist = $therapist_id;
            $s2->close();
            break;
        }
        $s2->close();
    }
    $stmt->close();

    if ($candidate_therapist === null) {
        $_SESSION['booking_message'] = "<strong>Requested slot unavailable.</strong>";
        $mysqli->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    /* Ensure user exists */
    $user_id = null;
    $s = $mysqli->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
    $s->bind_param('s', $email);
    $s->execute();
    $r = $s->get_result();
    if ($r->num_rows > 0) {
        $user_id = (int)$r->fetch_assoc()['user_id'];
    } else {
        $default_password_hash = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);
        $i = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone, password_hash, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $parts = explode(' ', $name, 2);
        $first = $parts[0]; $last = $parts[1] ?? '';
        $i->bind_param('sssss', $first, $last, $email, $phone, $default_password_hash);
        $i->execute();
        $user_id = (int)$i->insert_id;
        $i->close();
    }
    $s->close();

    /* Insert appointment */
    $ins = $mysqli->prepare("
      INSERT INTO appointment (user_id, therapist_id, service_id, start_datetime, end_datetime, status, notes, created_at)
      VALUES (?, ?, ?, ?, ?, 'Booked', ?, NOW())
    ");
    $notes = "Booked via public booking form";
    $service_id = (int)$service['service_id'];
    $ins->bind_param('iiisss', $user_id, $candidate_therapist, $service_id, $start_iso, $end_iso, $notes);
    $ins->execute();
    $appointment_id = $ins->insert_id;
    $ins->close();
    $mysqli->close();

    $start_formatted = $start_dt->format('D, j M Y H:i');
    $end_formatted = $end_dt->format('H:i');
    $_SESSION['booking_message'] = "<strong>Booking confirmed</strong><br>Your appointment (ID: {$appointment_id}) for <em>" . htmlspecialchars($service['name']) . "</em> is confirmed on {$start_formatted} — {$end_formatted}.";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Opal Glow Appointment Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/booking.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="logo">Opal Glow</div>
        <nav class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="services.php">Services</a>
            <a href="about_us.php">About Us</a>
            <a href="contact_us.php">Contact Us</a>
        </nav>

        <div class="user-area">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-icon">👤</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="booking-container">

    <!-- Left panel with image and branding -->
    <div class="booking-side">
        <div class="brand">
            <div class="logo">OG</div>
            <h1>Opal Glow</h1>
            <p>Luxury Beauty & Wellness</p>
        </div>
    </div>

    <!-- Form card -->
    <div class="form-card">

        <?php
        if (!empty($_SESSION['booking_message'])) {
            echo "<div class='booking-message'>".$_SESSION['booking_message']."</div>";
            unset($_SESSION['booking_message']);
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="appointment-form">

            <div class="two-col">
                <div class="form-field">
                    <label for="name">Full name*</label>
                    <input id="name" name="name" placeholder="Your full name" required />
                </div>
                <div class="form-field">
                    <label for="email">Email*</label>
                    <input id="email" name="email" type="email" placeholder="you@example.com" required />
                </div>
            </div>

            <div class="two-col">
                <div class="form-field">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" placeholder="+230-..." />
                </div>
                <div class="form-field">
                    <label for="service_type">Service*</label>
                    <select id="service_type" name="service_type" required>
                        <?php
                        $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                        if (!$mysqli->connect_errno) {
                            $res = $mysqli->query("SELECT service_id, name, duration_minutes FROM service WHERE is_active = 1 ORDER BY name");
                            while ($row = $res->fetch_assoc()) {
                                $label = htmlspecialchars($row['name']) . " ({$row['duration_minutes']} min)";
                                echo "<option value='".htmlspecialchars($row['service_id'])."'>$label</option>";
                            }
                            $mysqli->close();
                        } else {
                            echo "<option value=''>-- error loading services --</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="two-col">
                <div class="form-field">
                    <label for="date">Date*</label>
                    <input id="date" name="date" type="date" required 
                        min="<?php echo date('Y-m-d'); ?>" />
                </div>
                <div class="form-field">
                    <label for="time">Time*</label>
                    <input id="time" name="time" type="time" required />
                </div>
            </div>

            <div class="closing-info">
                <p>Note: Saturday closing time: 12:30. Closed on Sundays and public holidays.</p>
                <button type="submit" class="booking-btn">Book Your Glow Now</button>
            </div>

            <script>
                const dateInput = document.getElementById('date');
                const timeInput = document.getElementById('time');

                function updateTimeLimits() {
                    if (!dateInput.value) return;

                    // Parse selected date safely
                    const parts = dateInput.value.split('-'); // YYYY-MM-DD
                    const selectedDate = new Date(parts[0], parts[1]-1, parts[2]);
                    const today = new Date();

                    const day = selectedDate.getDay(); // 0 = Sunday, 6 = Saturday

                    if (day === 0) {
                        // Sunday → no booking
                        timeInput.value = '';
                        timeInput.disabled = true;
                        alert("Sundays are closed. Please select another day.");
                        return;
                    } else {
                        timeInput.disabled = false;
                    }

                    let minTime = '09:00';
                    let maxTime = '17:00';

                    if (day === 6) {
                        // Saturday → 9:00 to 11:00
                        maxTime = '11:00';
                    }

                    // If today, prevent past times
                    if (selectedDate.toDateString() === today.toDateString()) {
                        const hh = String(today.getHours()).padStart(2,'0');
                        const mm = String(today.getMinutes()).padStart(2,'0');
                        const nowTime = hh + ':' + mm;

                        if (day === 6) { // Saturday today
                            minTime = nowTime > '09:00' ? nowTime : '09:00';
                        } else {
                            minTime = nowTime > '09:00' ? nowTime : '09:00';
                        }
                    }

                    timeInput.min = minTime;
                    timeInput.max = maxTime;

                    // Reset time value if outside allowed range
                    if (timeInput.value) {
                        if (timeInput.value < minTime) timeInput.value = minTime;
                        if (timeInput.value > maxTime) timeInput.value = maxTime;
                    }
                }

                // Run on load and whenever date changes
                dateInput.addEventListener('change', updateTimeLimits);
                window.addEventListener('load', updateTimeLimits);
            </script>


        </form>
    </div>

</div>

</body>
</html>
