<?php
require 'vendor/autoload.php';

use JsonSchema\Validator;

// File paths
$jsonFile = 'appointments.json';
$schemaFile = 'appointments-schema.json';

// Check if files exist
if (!file_exists($jsonFile)) {
    die("appointments.json file not found");
}

if (!file_exists($schemaFile)) {
    die("appointments-schema.json file not found");
}

// Load JSON
$data = json_decode(file_get_contents($jsonFile));

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Invalid JSON data in appointments.json");
}

// Load schema
$schema = json_decode(file_get_contents($schemaFile));

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Invalid JSON schema");
}

// Validate
$validator = new Validator();
$validator->validate($data, $schema);

// Output result
if ($validator->isValid()) {
    echo "JSON is valid";
} else {
    echo "JSON is invalid<br><br>";

    foreach ($validator->getErrors() as $error) {
        echo "[{$error['property']}] {$error['message']}<br>";
    }
}
?>
