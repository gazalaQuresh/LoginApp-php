<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow GET, POST, and OPTIONS requests
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow Content-Type and Authorization headers

// Other headers you may want to set
header("Content-Type: application/json");

session_start(); // Start session

// Include database connection
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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate user input
    $data = json_decode(file_get_contents("php://input"), true);
    if (empty($data['email'])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }


    if (empty($data['password'])) {
        $errors['password'] = "Password is required";
    }

    $email = $data['email'];
    // Query to check if the user exists
    $sql = "SELECT * FROM Users WHERE email = '$email'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($data['password'], $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            // Send success JSON response
            $response = array("success" => true, "message" => "User Login successfully");
            echo json_encode($response);
        } else {
            // Incorrect password
            $response = array("success" => false, "message" => "Incorrect email or password");
            echo json_encode($response);
        }
    } else {
        // User not found
        $response = array("success" => false, "message" => "User not found");
        echo json_encode($response);
    }
}
