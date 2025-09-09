<?php
$resetMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost:3307", "root", "", "ppp_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST["email"]);
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        $resetMessage = "Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $resetMessage = "Password must be at least 6 characters.";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        if ($stmt->execute()) {
            $resetMessage = "Password successfully updated. <a href='login.php' style='color:yellow'>Login here</a>";
        } else {
            $resetMessage = "Failed to update password.";
        }
        $stmt->close();
    }

    $conn->close();
}
?>
