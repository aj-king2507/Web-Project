<?php

require 'vendor/autoload.php';
use JsonSchema\Validator;

// Example: Replace with DB fetch if needed
$appointments = [
    [
        "id" => "1",
        "customer_name" => "Sara",
        "phone" => "+23057356712",
        "email" => "Sara.123@gmail.com",
        "service" => "Japanese Head Spa",
        "time_slot" => "11:00",
        "appointment_date" => "2026-04-24"
    ],
    [
        "id" => "2",
        "customer_name" => "Gaby",
        "phone" => "+23053457128",
        "email" => "GabyS@gmail.com",
        "service" => "Keratin Hair Treatment",
        "time_slot" => "09:00",
        "appointment_date" => "2026-04-25"
    ],
    [
        "id" => "3",
        "customer_name" => "Gaby",
        "phone" => "+23057456578",
        "email" => "GabyS@gmail.123", // INVALID
        "service" => "Keratin Hair Treatment",
        "time_slot" => "11:00",
        "appointment_date" => "2026-04-25"
    ]
];

// Convert to JSON object
$data = json_decode(json_encode($appointments));

// Load schema
$schema = json_decode(file_get_contents('appointments-schema.json'));

// Validate
$validator = new Validator();
$validator->validate($data, $schema);

if ($validator->isValid()) {

    file_put_contents(
        'appointments.json',
        json_encode($appointments, JSON_PRETTY_PRINT)
    );

    echo "JSON file created successfully";

} else {

    echo "JSON validation failed:<br>";

    foreach ($validator->getErrors() as $error) {
        echo "[{$error['property']}] {$error['message']}<br>";
    }

}
