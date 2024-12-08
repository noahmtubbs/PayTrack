<?php
include('includes/db_connect.php');
session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username and password.";
    } else {
        // Check if the user exists in the database
        $sql = "SELECT * FROM Users WHERE Username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['Password'])) {
                // Regenerate session ID for security
                session_regenerate_id();

                // Set session variables
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['Role'];
                $_SESSION['employee_id'] = $user['EmployeeID'];

                // Redirect based on role
                if ($user['Role'] === 'Admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: employee_dashboard.php");
                }
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PayTrack</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="main-header">
        <div class="logo">
            <h1>PayTrack</h1>
        </div>
        <nav class="main-nav">
            <ul>
                <li>
                    <a href="index.php">Back to Home</a>
                </li>
            </ul>
        </nav>
    </header>
</body>

</html>

    <main class="container">
        <section class="features">
            <div class="feature-card">
                <h2>Login</h2>
                <p>Access your account securely.</p>
                <form method="POST" action="" class="login-form">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="Enter your username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Login" class="btn">
                    </div>
                </form>

                <?php if (!empty($error_message)): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <p>&copy; <?= date("Y"); ?> PayTrack. All rights reserved.</p>
    </footer>
</body>
</html>