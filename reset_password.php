<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'] ?? '';

    if ($email) {
        $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE email=?");
        $stmt->bind_param("ss", $newPass, $email);
        $stmt->execute();

        echo "Password updated successfully. <a href='login.php'>Login</a>";
        session_destroy();
    }
}
?>
<form method="post">
  <input type="password" name="password" placeholder="New Password" required>
  <button type="submit">Reset Password</button>
</form>
