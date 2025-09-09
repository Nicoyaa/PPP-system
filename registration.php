<?php   
require_once "./helper_php/registration_post.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
  <img src="assets/logo.jpg" alt="Logo" class="logo">

  <div class="login-form">
    <h2>Create Account</h2>

    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-error">
        <?php echo $errorMessage; ?>
      </div>
    <?php endif; ?>

    <form action="registration.php" method="POST" autocomplete="off">
      <!-- Username -->
      <div class="form-group">
        <input type="text" name="username" placeholder="Username" 
          value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
      </div>

      <!-- Email -->
      <div class="form-group">
        <input type="email" name="email" placeholder="Email" 
          value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
      </div>

      <!-- Company / Organization -->
      <div class="form-group">
        <input type="text" name="company" placeholder="Company / Organization" 
          value="<?= isset($_POST['company']) ? htmlspecialchars($_POST['company']) : '' ?>" required>
      </div>

      <!-- Contact Number -->
      <div class="form-group">
        <input type="text" name="contact_number" placeholder="Contact Number" 
          value="<?= isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number']) : '' ?>" required>
      </div>

      <!-- Password -->
      <div class="form-group password-wrapper">
        <input type="password" id="password" name="password" placeholder="Password" required>
        <i class="fa-solid fa-eye toggle-password" data-target="password" 
           style="cursor:pointer;position:absolute;right:20px;top:50%;transform:translateY(-50%);"></i>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn">REGISTER</button>
    </form>

    <div class="links">
      <p>Already have an account? <a href="login.php">Login here →</a></p>
    </div>
  </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
