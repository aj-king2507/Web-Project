<?php
session_start();
 require __DIR__ . '/vendor/autoload.php';
// `use` must be at file level
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// --- Optional: PHPMailer support if composer installed and mail_config present ---
$canSendMail = false;
if (file_exists(__DIR__ . '/vendor/autoload.php') && file_exists(__DIR__ . '/mail_config.php')) {
    $canSendMail = true;
    $mailConfig = require __DIR__ . '/mail_config.php';
}

// --- Database connection settings (as requested) ---
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'opalglow');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    // If DB is unreachable, we still render the page but show an admin-friendly error
    $_SESSION['contact_error'] = "System error: cannot connect to database. Please contact the administrator.";
    $db_ok = false;
} else {
    $db_ok = true;
    $mysqli->set_charset('utf8mb4');
}

// POST handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize + collect
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Keep old values for re-populating form
    $_SESSION['contact_old'] = ['name'=>$name,'email'=>$email,'phone'=>$phone,'subject'=>$subject,'message'=>$message];

    // Validation
    if ($name === '' || $email === '' || $message === '') {
        $_SESSION['contact_error'] = "Please fill in the required fields: name, email and message.";
        header("Location: contact_us.php");
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = "Please provide a valid email address.";
        header("Location: contact_us.php");
        exit;
    }

    // Insert into DB if available
    $insert_ok = false;
    if ($db_ok) {
        $sql = "INSERT INTO contact_us (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
            if ($stmt->execute()) {
                $insert_ok = true;
                $insert_id = $stmt->insert_id;
            } else {
                error_log("Contact insert failed: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Contact prepare failed: " . $mysqli->error);
        }
    }

    // Attempt to send email to admin if PHPMailer configured
    $mail_sent = false;
    if ($canSendMail) {
        try {
            $mail = new PHPMailer(true);
            // Server settings
            $mail->isSMTP();
            $mail->Host = $mailConfig['smtp_host'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['smtp_user'] ?? '';
            $mail->Password = $mailConfig['smtp_pass'] ?? '';
            $secure = strtolower($mailConfig['smtp_secure'] ?? 'tls');
            if ($secure === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port = $mailConfig['smtp_port'] ?? 587;

            $fromEmail = $mailConfig['from_email'] ?? ($mailConfig['smtp_user'] ?? 'no-reply@opalglow.com');
            $fromName  = $mailConfig['from_name'] ?? 'Opal Glow';

            $mail->setFrom($fromEmail, $fromName);
            $adminEmail = $mailConfig['admin_email'] ?? ($mailConfig['smtp_user'] ?? 'opalglowspa.beauty@gmail.com');
            $mail->addAddress($adminEmail);
            $mail->addReplyTo($email, $name);

            $mail->isHTML(false);
            $mail->Subject = "[Opal Glow Contact] " . ($subject ?: 'New message from website');
            $body  = "New contact form submission\n\n";
            $body .= "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message\n\n";
            $body .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\nUser Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? '') . "\n";
            $mail->Body = $body;

            $mail->send();
            $mail_sent = true;
        } catch (Exception $e) {
            error_log("PHPMailer error: " . $e->getMessage());
            $mail_sent = false;
        }
    }

    // Final flash message decision
    if ($insert_ok || $mail_sent) {
        $_SESSION['contact_success'] = "Thank you — we've received your message. We'll reply shortly.";
        // clear old
        unset($_SESSION['contact_old']);
    } else {
        // give helpful error message; if DB failed but mail available, say that
        if (!$db_ok && !$mail_sent) {
            $_SESSION['contact_error'] = "Sorry — we could not save or send your message. Please try again or call us at +230 1234 5678.";
        } elseif (!$insert_ok && $mail_sent) {
            $_SESSION['contact_success'] = "Message sent to the salon (email), but we couldn't save a copy in the database.";
            unset($_SESSION['contact_old']);
        } elseif ($insert_ok && !$mail_sent && $canSendMail) {
            $_SESSION['contact_success'] = "Message saved — but email sending failed. We'll still respond via email.";
            unset($_SESSION['contact_old']);
        } else {
            $_SESSION['contact_error'] = "Sorry — something went wrong. Please try again later or call +230 1234 5678.";
        }
    }

    // Close DB connection
    if ($db_ok) $mysqli->close();

    header("Location: contact_us.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Contact Us — Opal Glow</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="assets/css/contact_us.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <!-- header (same as your other pages) -->
  <header>
        <div class="logo">
            <h1>Opal Glow</h1>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact_us.php"class="active">Contact</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <span class="person-emoji">👤</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

  <main class="contact-page" style="padding-top:110px;">
    <section class="hero">
      <div class="hero-inner">
        <h1>Contact <span>Opal Glow</span></h1>
        <p class="lead">We’re here to help. Bookings, enquiries or feedback — reach out and we’ll get back to you quickly.</p>
      </div>
    </section>

    <section class="content grid">
      <aside class="contact-info card">
        <h2>Visit or Call</h2>

        <div class="info-row">
          <div class="label">Address</div>
          <div class="value">
            Opal Glow Beauty Salon<br>
            24 Rose Avenue, Port Louis, Mauritius
          </div>
        </div>

        <div class="info-row">
          <div class="label">Phone</div>
          <div class="value">
            <a href="tel:+23012345678">+230 230 1435</a>
          </div>
        </div>

        <div class="info-row">
          <div class="label">Email</div>
          <div class="value">
            <a href="mailto:opalglowspa.beauty@gmail.com">opalglowspa.beauty@gmail.com</a>
          </div>
        </div>

        <div class="info-row">
          <div class="label">Opening Hours</div>
          <div class="value">
            Mon - Fri: 09:00 — 17:00<br>
            Saturday: 09:00 — 12:30<br>
            Sunday: Closed
          </div>
        </div>

        <div class="info-row badges">
          <span class="pill">Trusted Therapists</span>
          <span class="pill">Premium Products</span>
          <span class="pill">Relaxing Environment</span>
        </div>

        <div class="map">
          <iframe title="Opal Glow location" src="https://www.openstreetmap.org/export/embed.html?bbox=57.5%2C-20%2C57.6%2C-19.9&amp;layer=mapnik" style="border:0;" loading="lazy"></iframe>
        </div>
      </aside>

      <div class="contact-form card">
        <h2>Send us a message</h2>

        <?php
        if (!empty($_SESSION['contact_error'])) {
            echo '<div class="flash flash-error">'.htmlspecialchars($_SESSION['contact_error']).'</div>';
            unset($_SESSION['contact_error']);
        }
        if (!empty($_SESSION['contact_success'])) {
            echo '<div class="flash flash-success">'.htmlspecialchars($_SESSION['contact_success']).'</div>';
            unset($_SESSION['contact_success']);
        }
        $old = $_SESSION['contact_old'] ?? [];
        ?>
        <form method="POST" action="contact_us.php" class="form">
          <div class="row">
            <label for="c_name">Full name *</label>
            <input id="c_name" name="name" required value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>">
          </div>

          <div class="row two-col">
            <div>
              <label for="c_email">Email *</label>
              <input id="c_email" name="email" type="email" required value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
            </div>
            <div>
              <label for="c_phone">Phone</label>
              <input id="c_phone" name="phone" placeholder="+23012345678" value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
            </div>
          </div>

          <div class="row">
            <label for="c_subject">Subject</label>
            <input id="c_subject" name="subject" placeholder="Enquiry about a treatment" value="<?php echo htmlspecialchars($old['subject'] ?? ''); ?>">
          </div>

          <div class="row">
            <label for="c_message">Message *</label>
            <textarea id="c_message" name="message" rows="6" required><?php echo htmlspecialchars($old['message'] ?? ''); ?></textarea>
          </div>

          <div class="row actions">
            <button type="submit" class="btn primary">Send message</button>
            <button type="reset" class="btn ghost">Reset</button>
          </div>
        </form>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div>© <?php echo date('Y'); ?> Opal Glow — All rights reserved.</div>
  </footer>

  <script>
    // small entrance animations
    document.addEventListener('DOMContentLoaded', function(){
      document.querySelectorAll('.card').forEach((el, i) => {
        el.style.transition = 'transform 420ms cubic-bezier(.2,.9,.2,1), opacity 420ms ease';
        el.style.transform = 'translateY(12px)';
        el.style.opacity = '0';
        setTimeout(()=>{ el.style.transform = 'translateY(0)'; el.style.opacity='1'; }, 120 + i*80);
      });
    });
  </script>
</body>
</html>
