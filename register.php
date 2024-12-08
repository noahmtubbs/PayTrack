<?php
include('includes/db_connect.php');
session_start();

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden</h1>";
    echo "<p>You do not have permission to access this page. Please <a href='login.php'>log in</a> with an admin account. Refer to admin.</p>";
    exit();
}

$success_message = ""; // Initialize success message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']); // 'Admin' or 'Employee'
    $employee_first_name = trim($_POST['first_name'] ?? '');
    $employee_last_name = trim($_POST['last_name'] ?? '');
    $error_message = "";

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif ($role === 'Employee' && (empty($employee_first_name) || empty($employee_last_name))) {
        $error_message = "First and Last Name are required for Employee registration.";
    } else {
        // Check if the username already exists
        $sql = "SELECT * FROM Users WHERE Username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username is already taken.";
        } else {
            $employee_id = null;

            if ($role === 'Employee') {
                // Verify the employee exists in the Employee table
                $sql_employee = "SELECT EmployeeID FROM Employee WHERE FirstName = ? AND LastName = ?";
                $stmt_employee = $conn->prepare($sql_employee);
                $stmt_employee->bind_param("ss", $employee_first_name, $employee_last_name);
                $stmt_employee->execute();
                $employee_result = $stmt_employee->get_result();

                if ($employee_result->num_rows > 0) {
                    $employee_data = $employee_result->fetch_assoc();
                    $employee_id = $employee_data['EmployeeID'];
                } else {
                    $error_message = "No matching employee found for the given name.";
                }
            }

            if (empty($error_message)) {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the Users table
                $sql_insert = "INSERT INTO Users (Username, Password, Role, EmployeeID) VALUES (?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sssi", $username, $hashed_password, $role, $employee_id);

                if ($stmt_insert->execute()) {
                    // Set success message
                    $success_message = "User account created successfully!";
                } else {
                    $error_message = "Error creating the account. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Register New User</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Create a New User Account</h2>
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" name="confirm_password" id="confirm_password" required><br><br>

            <label for="role">Role:</label><br>
            <select name="role" id="role" required onchange="toggleEmployeeFields(this.value)">
                <option value="Admin">Admin</option>
                <option value="Employee">Employee</option>
            </select><br><br>

            <div id="employee-fields" style="display: none;">
                <label for="first_name">Employee First Name:</label><br>
                <input type="text" name="first_name" id="first_name"><br><br>

                <label for="last_name">Employee Last Name:</label><br>
                <input type="text" name="last_name" id="last_name"><br><br>
            </div>

            <input type="submit" value="Create User">
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.</p>
    </footer>

    <?php if (!empty($success_message)): ?>
        <script>
            alert("<?php echo $success_message; ?>");
            window.location.href = 'admin_dashboard.php'; // Redirect if necessary
        </script>
    <?php endif; ?>

    <script>
        // Toggle employee fields based on role
        function toggleEmployeeFields(role) {
            const employeeFields = document.getElementById('employee-fields');
            employeeFields.style.display = role === 'Employee' ? 'block' : 'none';
        }
    </script>
</body>
</html>