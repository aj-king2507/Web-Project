<?php
require 'db.php';
require 'vendor/autoload.php';

use JsonSchema\Validator;

try {

    // Fetch full appointment data with joins
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
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = json_decode(json_encode($appointments));

    $schema = json_decode(file_get_contents("appointments-schema.json"));

    $validator = new Validator();
    $validator->validate($data, $schema);

    if ($validator->isValid()) {
        echo "JSON is valid";
    } else {
        echo "JSON is invalid <br><br>";
        foreach ($validator->getErrors() as $error) {
            echo "[{$error['property']}] {$error['message']}<br>";
        }
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
