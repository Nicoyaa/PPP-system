<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "admin") {
    header("Location: /login.php");
    exit();
}

include 'db.php';

$id = $_GET['id']; // Assuming user ID is passed via GET

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $role = $_POST["role"];
    $updatePassword = !empty($_POST["password"]);
    
    if ($updatePassword) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $email, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $email, $role, $id);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: user_management.php");
    exit();
}

// Fetch current user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!-- Add HTML form prefilled with $user['email'], $user['role'] -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <form action="edit.php?id=<?= $id ?>" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select class="form-control" name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="user_management.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>