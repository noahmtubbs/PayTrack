<?php
// Include the database connection
include('includes/db_connect.php');
session_start();

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden</h1>";
    echo "<p>You do not have permission to access this page. Please <a href='login.php'>log in</a> with an admin account.</p>";
    exit();
}

$message = "";

// Check if an employee is selected for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_employee'])) {
    $employee_id = intval($_POST['employee_id']);

    // Begin a transaction to ensure both DELETE queries succeed or fail together
    $conn->begin_transaction();

    try {
        // Delete related records in the Payroll table
        $sql_payroll = "DELETE FROM Payroll WHERE EmployeeID = ?";
        $stmt_payroll = $conn->prepare($sql_payroll);
        $stmt_payroll->bind_param("i", $employee_id);

        if (!$stmt_payroll->execute()) {
            throw new Exception("Error deleting from payroll: " . $stmt_payroll->error);
        }

        // Delete the employee record
        $sql_employee = "DELETE FROM Employee WHERE EmployeeID = ?";
        $stmt_employee = $conn->prepare($sql_employee);
        $stmt_employee->bind_param("i", $employee_id);

        if (!$stmt_employee->execute()) {
            throw new Exception("Error deleting employee: " . $stmt_employee->error);
        }

        // Commit the transaction if both deletes were successful
        $conn->commit();
        $message = "<p style='color: green;'>Employee deleted successfully.</p>";

    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        $conn->rollback();
        $message = "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch all employees
$sql_fetch = "SELECT EmployeeID, FirstName, LastName FROM Employee";
$result = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Employee</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to PayTrack</h1>
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
        <h2>Delete Employee</h2>
        <?php echo $message; ?>

        <?php if ($result->num_rows > 0): ?>
            <!-- Form to delete employee -->
            <form method="POST" action="">
                <label for="employee_id">Select Employee to Delete:</label><br>
                <select name="employee_id" id="employee_id" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['EmployeeID']); ?>">
                            <?= htmlspecialchars($row['FirstName'] . " " . $row['LastName']); ?>
                        </option>
                    <?php endwhile; ?>
                </select><br><br>

                <input type="submit" name="delete_employee" value="Delete Employee" class="btn">
            </form>
        <?php else: ?>
            <p style="color: red;">No employees available for deletion.</p>
        <?php endif; ?>

        <br>
        <!-- Back Button -->
        <a href="employee_list.php" class="btn">Back to Employee List</a>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.</p>
    </footer>
</body>
</html>