<?php
$resetMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost:3307", "root", "", "ppp_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST["email"]);
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Simulate sending a reset link (mocked for local environment)
        // Redirect user to reset form with email in query (in production use secure token)
        header("Location: reset_password.php?email=" . urlencode($email));
        exit();
    } else {
        $resetMessage = "Email not found in our records.";
    }

    $stmt->close();
    $conn->close();
}
?>
