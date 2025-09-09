<?php  
// helper_php/login_post.php
// NOTE: session_start() MUST be called in login.php (not here).

// Config
$MAX_ATTEMPTS = 5;
$LOCK_DURATION = 30; // seconds (30 seconds) - change as needed

// Messages & data available to login.php
$emailErrorMassage    = "";
$passwordErrorMassage = "";
$sessionMessage       = "";
$remainingLockTime    = 0;

// If a user was locked earlier in this session, check DB to show countdown on subsequent visits
if (isset($_SESSION['locked_user_id'])) {
    $lockedId = (int) $_SESSION['locked_user_id'];
    $connCheck = new mysqli("localhost:3307", "root", "", "ppp_system");
    if (!$connCheck->connect_error) {
        $stmtC = $connCheck->prepare("SELECT lock_time FROM users WHERE id = ?");
        $stmtC->bind_param("i", $lockedId);
        $stmtC->execute();
        $resC = $stmtC->get_result();
        if ($resC && $resC->num_rows === 1) {
            $r = $resC->fetch_assoc();
            $lockTimeVal = (int)$r['lock_time'];
            if (time() < $lockTimeVal) {
                $remainingLockTime = $lockTimeVal - time();
                $sessionMessage = "Too many login attempts. Try again in <span id='countdown'>{$remainingLockTime}</span> seconds.";
            } else {
                // Lock expired: reset in DB and clear session locked marker
                $resetS = $connCheck->prepare("UPDATE users SET login_attempts = 0, lock_time = 0 WHERE id = ?");
                $resetS->bind_param("i", $lockedId);
                $resetS->execute();
                unset($_SESSION['locked_user_id']);
            }
        } else {
            // user not found: remove session lock
            unset($_SESSION['locked_user_id']);
        }
        $stmtC->close();
    }
    $connCheck->close();
}

// Handle POST login attempt
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // DB connection
    $conn = new mysqli("localhost:3307", "root", "", "ppp_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";
    $valid = true;

    if ($email === "") {
        $emailErrorMassage = "Email is required.";
        $valid = false;
    }
    if ($password === "") {
        $passwordErrorMassage = "Password is required.";
        $valid = false;
    }

    if ($valid) {
        // ✅ Align with registration.php (no first_name/last_name)
        $stmt = $conn->prepare("SELECT id, username, email, company, contact_number, password, role, login_attempts, lock_time FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userId = (int)$user['id'];
            $dbLockTime = (int)$user['lock_time'];
            $dbAttempts = (int)$user['login_attempts'];

            // If DB says user is locked, show countdown and store locked_user_id in session
            if (time() < $dbLockTime) {
                $remainingLockTime = $dbLockTime - time();
                $sessionMessage = "Too many login attempts. Try again in <span id='countdown'>{$remainingLockTime}</span> seconds.";
                $_SESSION['locked_user_id'] = $userId;
            } else {
                // Normal flow: not locked
                if (password_verify($password, $user['password'])) {
                    // Successful login: reset counters and clear session locked id
                    $reset = $conn->prepare("UPDATE users SET login_attempts = 0, lock_time = 0 WHERE id = ?");
                    $reset->bind_param("i", $userId);
                    $reset->execute();
                    $reset->close();

                    // Clear any locked marker in session
                    if (isset($_SESSION['locked_user_id']) && $_SESSION['locked_user_id'] == $userId) {
                        unset($_SESSION['locked_user_id']);
                    }

                    // ✅ Save profile info to session
                    $_SESSION['user_id']        = $userId;
                    $_SESSION['username']       = $user['username'];
                    $_SESSION['email']          = $user['email'];
                    $_SESSION['company']        = $user['company'];
                    $_SESSION['contact_number'] = $user['contact_number'];
                    $_SESSION['role']           = $user['role'];
                    $_SESSION['last_activity']  = time();

                    // Redirect by role
                    if ($user['role'] === 'admin') {
                        header("Location: lgu-staff-dashboard.php");
                    } elseif ($user['role'] === 'manager') {
                        header("Location: managerpage.php");
                    } else {
                        header("Location: private-dashboard.php");
                    }
                    exit();
                } else {
                    // Wrong password: increment login attempts in DB
                    $attempts = $dbAttempts + 1;
                    $newLockTime = 0;

                    if ($attempts >= $MAX_ATTEMPTS) {
                        $newLockTime = time() + $LOCK_DURATION;
                        $remainingLockTime = $LOCK_DURATION;
                        $sessionMessage = "Too many login attempts. Try again in <span id='countdown'>{$remainingLockTime}</span> seconds.";
                        $_SESSION['locked_user_id'] = $userId; // remember which user is locked
                    } else {
                        // Only show generic incorrect password (no attempt count)
                        $passwordErrorMassage = "Incorrect password.";
                    }

                    $upd = $conn->prepare("UPDATE users SET login_attempts = ?, lock_time = ? WHERE id = ?");
                    $upd->bind_param("iii", $attempts, $newLockTime, $userId);
                    $upd->execute();
                    $upd->close();
                }
            }
        } else {
            $emailErrorMassage = "Email not registered.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
