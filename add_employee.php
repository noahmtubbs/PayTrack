<?php
include('includes/db_connect.php');
session_start();

// Only allow logged-in admins to access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden</h1>";
    echo "<p>You do not have permission to access this page. Please <a href='login.php'>log in</a> with an admin account.</p>";
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $employee_type = trim($_POST['employee_type']);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);
    $salary = str_replace(',', '', trim($_POST['salary']));
    $hourly_rate = !empty($_POST['hourly_rate']) ? str_replace(',', '', trim($_POST['hourly_rate'])) : null;
    $tax_rate = str_replace(',', '', trim($_POST['tax_rate']));
    $hire_date = trim($_POST['hire_date']);

    // Validate inputs
    if (!is_numeric($salary) || ($hourly_rate !== null && !is_numeric($hourly_rate)) || !is_numeric($tax_rate)) {
        $message = "<p style='color: red;'>Salary, Hourly Rate, and Tax Rate must be numeric values.</p>";
    } else {
        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO Employee (FirstName, LastName, EmployeeType, Department, Position, Salary, HourlyRate, TaxRate, HireDate) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdids", $first_name, $last_name, $employee_type, $department, $position, $salary, $hourly_rate, $tax_rate, $hire_date);

        if ($stmt->execute()) {
            $message = "<p style='color: green;'>New employee added successfully.</p>";
        } else {
            $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Add New Employee</title>
</head>
<body>
    <header>
        <h1>Add New Employee</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="employee_list.php">Employee List</a></li>
                <li><a href="add_employee.php">Add Employee</a></li>
                <li><a href="delete_employee.php">Delete Employee</a></li>
                <li><a href="payroll_calculation.php">Payroll Calculation</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Enter Employee Information</h2>
        <?php echo $message; ?>
        <form method="POST" action="">
            <label for="first_name">First Name:</label><br>
            <input type="text" name="first_name" id="first_name" required><br><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" name="last_name" id="last_name" required><br><br>

            <label for="employee_type">Employee Type:</label><br>
            <input type="text" name="employee_type" id="employee_type" required><br><br>

            <label for="department">Department:</label><br>
            <input type="text" name="department" id="department" required><br><br>

            <label for="position">Position:</label><br>
            <input type="text" name="position" id="position" required><br><br>

            <label for="salary">Salary:</label><br>
            <input type="text" name="salary" id="salary" required><br><br>

            <label for="hourly_rate">Hourly Rate (Optional):</label><br>
            <input type="text" name="hourly_rate" id="hourly_rate"><br><br>

            <label for="tax_rate">Tax Rate (%):</label><br>
            <input type="text" name="tax_rate" id="tax_rate" required><br><br>

            <label for="hire_date">Hire Date:</label><br>
            <input type="date" name="hire_date" id="hire_date" required><br><br>

            <input type="submit" value="Add Employee">
        </form>
        <br>
        <!-- Back Button -->
        <a href="employee_list.php" class="btn">Back to Employee List</a>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.</p>
    </footer>
</body>
</html>