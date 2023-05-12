<?php
// Load configuration file
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"];
    $hobbies = $_POST["hobbies"];
    $gender = $_POST["gender"];
    $program = $_POST["program"];
    $terms = isset($_POST["terms"]);

    // Validate form data
    $errors = [];
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if (!$terms) {
        $errors[] = "You must agree to the terms and conditions";
    }

    // Check for errors
    if (count($errors) > 0) {
        // Display errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else {
        try {
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname, $port);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO students (firstName, lastName, email, password, address, hobbies, gender, program) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $firstName, $lastName, $email, password_hash($password, PASSWORD_DEFAULT), $address, $hobbies, $gender, $program);
            $stmt->execute();

            // Registration successful
            echo "<p>Registration successful</p>";
        } catch (Exception $e) {
            // Handle errors
            echo "<p>Registration failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        }

        // Close connection
        $conn->close();

        // Redirect to success page
        header("Location: SudRegistration.php");
        exit;
    }
}
