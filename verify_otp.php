<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = $_POST['otp'];

    if (isset($_SESSION['reset_otp']) && isset($_SESSION['otp_expiry'])) {
        if (time() > $_SESSION['otp_expiry']) {
            echo "OTP expired. Try again.";
        } elseif ($otp == $_SESSION['reset_otp']) {
            header("Location: reset_password.php");
            exit();
        } else {
            echo "Invalid OTP.";
        }
    } else {
        echo "No OTP session found.";
    }
}
?>
<form method="post">
  <input type="text" name="otp" placeholder="Enter OTP" required>
  <button type="submit">Verify</button>
</form>
