<?php 
session_start();
if (!isset($_SESSION["email"])) {
    header("location: /login.php");
}

if ($_SESSION["role"] == "user") {
    header("location: /user_page.php");
}
if ($_SESSION["role"] == "manager") {
    header("location: /managerpage.php");
}

include 'db.php';
$allUsers = [];
$result = $conn->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    $allUsers[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        :root {
            --card-bg: #1f2937;
            --card-text: #f9fafb;
            --body-bg: #1f2937;
            --table-header: #374151;
            --primary-btn: #2563eb;
            --primary-btn-hover: #1d4ed8;
        }

        body.light {
            --card-bg: #ffffff;
            --card-text: #1f2937;
            --body-bg: #f9fafb;
            --table-header: #e5e7eb;
            --primary-btn: #3b82f6;
            --primary-btn-hover: #2563eb;
        }

        body {
            margin: 0;
            background: var(--body-bg);
            color: var(--card-text);
            font-family: 'Segoe UI', sans-serif;
            transition: background 0.3s, color 0.3s;
        }

        .card {
            background: var(--card-bg) !important;
            color: var(--card-text) !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4);
            border: none;
        }

        .card-header {
            background: var(--table-header);
            color: #fff;
            font-weight: bold;
        }

        .btn-primary, .btn-info {
            background: var(--primary-btn);
            border: none;
        }

        .btn-primary:hover, .btn-info:hover {
            background: var(--primary-btn-hover);
        }

        table {
            background: var(--card-bg);
            color: var(--card-text);
        }

        thead {
            background: var(--table-header);
            color: #fff;
        }

        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card my-5 p-3">
            <h2><?php echo $_SESSION["username"]; ?>'s only access</h2>
            <div class="d-flex align-items-center justify-content-end">
                <a href="admin_dashboard.php" class="btn btn-info mr-2">Back to Dashboard</a>
                <a href="logout.php" class="btn btn-warning">Logout</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                User Role Management System
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between pb-3">
                    <h4>All Users</h4>
                    <a href="create.php" class="btn btn-primary">Create User</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td><?= $user["username"]; ?></td>
                                <td><?= $user["email"]; ?></td>
                                <td><?= $user["role"]; ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-outline-primary mr-2">Edit</a>
                                    <a href="delete.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>
