<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Include database connection
include('includes/db_connect.php');

// Variables
$message = "";
$user_id = $_SESSION['user_id'];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "<p style='color: red;'>All fields are required.</p>";
    } elseif ($new_password !== $confirm_password) {
        $message = "<p style='color: red;'>New passwords do not match.</p>";
    } else {
        // Fetch the current password from the database
        $sql = "SELECT Password FROM Users WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($current_password, $row['Password'])) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_sql = "UPDATE Users SET Password = ? WHERE UserID = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $message = "<p style='color: green;'>Password changed successfully.</p>";
                } else {
                    $message = "<p style='color: red;'>Failed to update password. Please try again.</p>";
                }

                $update_stmt->close();
            } else {
                $message = "<p style='color: red;'>Current password is incorrect.</p>";
            }
        } else {
            $message = "<p style='color: red;'>User not found.</p>";
        }

        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Form Styling */
        .change-password-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .change-password-form label {
            display: block;
            margin-bottom: 5px;
        }
        .change-password-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .change-password-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .change-password-form button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Change Password</h1>
        <nav>
            <ul>
                <li><a href="<?php echo ($_SESSION['role'] === 'Admin') ? 'admin_dashboard.php' : 'employee_dashboard.php'; ?>">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <form method="POST" class="change-password-form">
            <h2>Change Password</h2>
            <div class="message"><?= $message; ?></div>
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" required>
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>