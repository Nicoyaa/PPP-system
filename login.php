<?php
session_start(); // must be here
require_once "./helper_php/login_post.php"; // sets $emailErrorMassage, $passwordErrorMassage, $sessionMessage, $remainingLockTime
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* small UI hint: disabled button look */
    .btn[disabled] {
      opacity: 0.6;
      cursor: not-allowed;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="login-form">
    <img src="assets/logo.jpg" alt="Logo" style="width: 100px; height: 100px; margin-bottom: 10px; border-radius: 50%;">
    <h2>Member Login</h2>

    <!-- Lockout message or errors -->
    <?php if (!empty($sessionMessage)): ?>
      <div class="alert alert-error" style="color:#b71c1c; background:#ffcdd2; padding:10px; border-radius:5px; margin-bottom:15px;">
        <?php echo $sessionMessage; ?>
      </div>
    <?php elseif (!empty($emailErrorMassage) || !empty($passwordErrorMassage)): ?>
      <div class="alert alert-error" style="color:#b71c1c; background:#ffcdd2; padding:10px; border-radius:5px; margin-bottom:15px;">
        <?php 
          if (!empty($emailErrorMassage)) echo $emailErrorMassage . "<br>";
          if (!empty($passwordErrorMassage)) echo $passwordErrorMassage;
        ?>
      </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="login.php" method="POST" id="loginForm">
      <div class="form-group">
        <input type="email" name="email" placeholder="Email" required <?php if(isset($email)) echo "value='".htmlspecialchars($email)."'"; ?>>
      </div>
      <div class="form-group password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
      </div>
      <button type="submit" class="btn" id="loginBtn" <?php if (!empty($remainingLockTime) && $remainingLockTime > 0) echo 'disabled'; ?>>LOGIN</button>
    </form>

    <div class="links">
      <p><a href="forgot_password.php">Forgot Password?</a></p>
      <p><a href="registration.php">Create your Account →</a></p>
    </div>
  </div>
</div>

<script src="script/login.js"></script>

<!-- Countdown script: updates #countdown inside PHP message -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const countdownSpan = document.getElementById("countdown");
  const loginBtn = document.getElementById("loginBtn");

  if (countdownSpan) {
    let timeLeft = parseInt(countdownSpan.textContent, 10);
    if (isNaN(timeLeft) || timeLeft <= 0) {
      // nothing to do
      return;
    }

    // Ensure button is disabled while counting down
    if (loginBtn) loginBtn.disabled = true;

    const tick = setInterval(() => {
      timeLeft--;
      if (timeLeft > 0) {
        countdownSpan.textContent = timeLeft;
      } else {
        clearInterval(tick);
        // Re-enable and refresh the page to get fresh server-side state
        if (loginBtn) loginBtn.disabled = false;
        // Optional: refresh to remove lock server-side (if expired)
        window.location.reload();
      }
    }, 1000);
  }
});
</script>

</body>
</html>
