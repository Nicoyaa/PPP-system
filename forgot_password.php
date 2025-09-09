<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $_SESSION['reset_email'] = $email;

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Randomly choose method: email link or OTP (for demo you can decide)
        $method = rand(0,1) ? "otp" : "link";

        if ($method == "otp") {
            $otp = rand(100000, 999999);
            $_SESSION['reset_otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 300; // 5 minutes expiry

            // send OTP via email
            mail($email, "Your OTP Code", "Your OTP is: $otp (valid for 5 minutes)");

            header("Location: verify_otp.php");
            exit();
        } else {
            $token = bin2hex(random_bytes(16));
            $_SESSION['reset_token'] = $token;

            // Save token to DB (optional)
            $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email=?");
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();

            $resetLink = "http://localhost/reset_password.php?token=$token";
            mail($email, "Password Reset", "Click here to reset your password: $resetLink");

            echo "A reset link has been sent to your email.";
        }
    } else {
        echo "Email not found.";
    }
}
?>
<form method="post">
  <input type="email" name="email" placeholder="Enter your email" required>
  <button type="submit">Send Reset</button>
</form>
