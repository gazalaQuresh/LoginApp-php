<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow GET, POST, and OPTIONS requests
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow Content-Type and Authorization headers

// Other headers you may want to set
header("Content-Type: application/json");

// MySQL database credentials
$host = 'localhost'; // MySQL server host
$dbname = 'signup-app'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Connect to the database using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("ERROR: Could not connect to the database. " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data and decode it as JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate the input fields
    $errors = array();

    if (empty($data['name'])) {
        $errors['name'] = "Username is required";
    }

    if (empty($data['email'])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($data['password'])) {
        $errors['password'] = "Password is required";
    }

    // If there are no validation errors, insert the user into the database
    if (empty($errors)) {
        $name = $data['name'];
        $email = $data['email'];

        $password = $data['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


        // Prepare and execute the SQL insert query
        $sql = "INSERT INTO Users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            $response = array("success" => true, "message" => "User registered successfully");
            echo json_encode($response);
        } else {
            $response = array("success" => false, "message" => "Error registering user: " . $conn->error);
            echo json_encode($response);
        }
    } else {
        // Return validation errors
        echo json_encode(array("success" => false, "errors" => $errors));
    }
} else {
    // Return error for invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

// Close the database connection
$conn->close();
