<?php
// FIXED INCLUDE LINE
include _DIR_ . '/db.php';

$message = "";

// CREATE APPOINTMENT
if(isset($_POST['create'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $service = $_POST['service'];
    $timeslot = $_POST['timeslot'];
    $date = $_POST['date'];

    if(empty($name) || empty($phone) || empty($email) || empty($service) || empty($timeslot) || empty($date)){
        $message = "<p style='color:red;'>All fields are required.</p>";
    } else {
        $query = "INSERT INTO appointments (customer_name, phone, email, service, time_slot, appointment_date)
                  VALUES ('$name', '$phone', '$email', '$service', '$timeslot', '$date')";
        mysqli_query($conn, $query);
        $message = "<p style='color:green;'>Appointment successfully booked!</p>";
    }
}

// DELETE APPOINTMENT
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM appointments WHERE id=$id");
    $message = "<p style='color:orange;'>Appointment deleted.</p>";
}

// GET ALL APPOINTMENTS
$appointments = mysqli_query($conn, "SELECT * FROM appointments");
?>

<h2>Book Appointment</h2>
<?= $message ?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name"><br><br>
    <input type="text" name="phone" placeholder="Phone Number"><br><br>
    <input type="email" name="email" placeholder="Email"><br><br>

    <!-- SERVICES WITHOUT PRICES -->
    <select name="service">
        <option selected disabled>Select Service</option>
        <option value="Japanese Head Spa">Japanese Head Spa</option>
        <option value="Keratin Hair Treatment">Keratin Hair Treatment</option>
        <option value="Glow Revival Facial">Glow Revival Facial</option>
        <option value="Microdermabrasion">Microdermabrasion</option>
        <option value="Serenity Massage">Serenity Massage</option>
        <option value="Body Scrub & Wrap">Body Scrub & Wrap</option>
    </select><br><br>

    <!-- UPDATED TIME SLOTS -->
    <select name="timeslot">
        <option selected disabled>Select Time Slot</option>
        <option>09:00</option>
        <option>10:00</option>
        <option>11:00</option>
        <option>13:00</option>
        <option>14:00</option>
    </select><br><br>

    <label>Select Date:</label><br>
    <input type="date" name="date"><br><br>

    <button name="create">Book Appointment</button>
</form>

<br><hr>

<h2>All Appointments</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Service</th>
    <th>Time Slot</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($appointments)) { ?>
<tr>
    <td><?= $row['customer_name']; ?></td>
    <td><?= $row['phone']; ?></td>
    <td><?= $row['email']; ?></td>
    <td><?= $row['service']; ?></td>
    <td><?= $row['time_slot']; ?></td>
    <td><?= $row['appointment_date']; ?></td>
    <td><a href="?delete=<?= $row['id']; ?>">Delete</a></td>
</tr>
<?php } ?>
</table>