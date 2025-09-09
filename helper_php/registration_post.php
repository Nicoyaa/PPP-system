<?php 
require 'db.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username       = trim($_POST['username']);
    $email          = trim($_POST['email']);
    $company        = trim($_POST['company']);
    $contact_number = trim($_POST['contact_number']);
    $password       = $_POST['password'];

    // --- Check duplicate email ---
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        $errorMessage = "⚠️ Email is already registered.";
    }
    $checkEmail->close();

    // --- Check duplicate username ---
    if (empty($errorMessage)) {
        $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();
        $checkUsername->store_result();
        if ($checkUsername->num_rows > 0) {
            $errorMessage = "⚠️ Username is already taken.";
        }
        $checkUsername->close();
    }

    // --- Check duplicate password (hash comparison) ---
    if (empty($errorMessage)) {
        $checkPass = $conn->prepare("SELECT password FROM users");
        $checkPass->execute();
        $result = $checkPass->get_result();
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $errorMessage = "⚠️ Password is already in use. Please choose another.";
                break;
            }
        }
        $checkPass->close();
    }

    // --- Strong password validation ---
    if (empty($errorMessage)) {
        if (
            strlen($password) < 8 ||
            !preg_match("/[A-Z]/", $password) ||
            !preg_match("/[a-z]/", $password) ||
            !preg_match("/[0-9]/", $password) ||
            !preg_match("/[^A-Za-z0-9]/", $password)
        ) {
            $errorMessage = "⚠️ Password is too weak. Must include: 
            8+ chars, uppercase, lowercase, number, special symbol.";
        }
    }

    // --- Insert if no errors ---
    if (empty($errorMessage)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, company, contact_number, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $company, $contact_number, $hashed);

        if ($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit();
        } else {
            $errorMessage = "❌ Registration failed. Try again.";
        }
        $stmt->close();
    }
}
?>
