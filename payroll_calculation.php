<?php
// Include the database connection
include('includes/db_connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
    $pay_period_start = $_POST['pay_period_start'] ?? null;
    $pay_period_end = $_POST['pay_period_end'] ?? null;
    $bonus = isset($_POST['bonus']) ? floatval($_POST['bonus']) : 0;

    if (!$employee_id || !$pay_period_start || !$pay_period_end) {
        die("All fields are required. Please provide Employee ID, Pay Period Start, and Pay Period End.");
    }

    // Fetch employee details from the Employee table
    $sql = "SELECT * FROM Employee WHERE EmployeeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Employee not found. Please check the Employee ID.");
    }

    $employee = $result->fetch_assoc();

    // Get the necessary data
    $annual_salary = $employee['Salary'] ?? 0; // Default to 0 if not set
    $hourly_rate = $employee['HourlyRate'] ?? 0;
    $tax_rate = $employee['TaxRate'] ?? 0;
    $employee_type = $employee['EmployeeType'] ?? 'Full Time';

    $pension_contribution = 200;  // Fixed pension contribution
    $benefits_contribution_rate = 0.03; // 3% benefits contribution rate

    // Calculate the number of days in the pay period
    $start_date = new DateTime($pay_period_start);
    $end_date = new DateTime($pay_period_end);
    $days_in_period = $start_date->diff($end_date)->days + 1;

    // Calculate the yearly days (365 or 366 if leap year)
    $days_in_year = ($start_date->format('L') == 1) ? 366 : 365;

    // Calculate the proportion of the annual salary for the pay period
    $proportional_salary = ($annual_salary / $days_in_year) * $days_in_period;

    // Default overtime and hours worked
    $overtime_pay = 0;
    $hours_worked = 0;

    // If part-time, calculate hours worked
    if ($employee_type === "Part Time") {
        $hours_worked = isset($_POST['hours_worked']) ? floatval($_POST['hours_worked']) : 0;
        if ($hours_worked > 40) {
            $overtime_hours = $hours_worked - 40;
            $overtime_pay = $overtime_hours * $hourly_rate * 1.5; // Overtime at 1.5x hourly rate
        }
    }

    // Calculate deductions
    $tax_deduction = $proportional_salary * ($tax_rate / 100);
    $benefits_contribution = $proportional_salary * $benefits_contribution_rate;

    // Calculate net pay
    $net_pay = $proportional_salary + $overtime_pay + $bonus - $tax_deduction - $pension_contribution - $benefits_contribution;

    // Insert payroll data into the Payroll table
    $sql_insert = "INSERT INTO Payroll (EmployeeID, PayPeriodStart, PayPeriodEnd, BaseSalary, HoursWorked, OvertimePay, Bonus, Deductions, TaxRate, PensionContribution, BenefitsContribution, NetPay)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param(
        "issddddddddd",
        $employee_id,
        $pay_period_start,
        $pay_period_end,
        $proportional_salary,
        $hours_worked,
        $overtime_pay,
        $bonus,
        $tax_deduction,
        $tax_rate,
        $pension_contribution,
        $benefits_contribution,
        $net_pay
    );

    if ($stmt->execute()) {
        echo "Payroll data inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Payroll Calculation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Payroll Details</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="employee_list.php">Employee List</a></li>
                <li><a href="add_employee.php">Add Employee</a></li>
                <li><a href="delete_employee.php">Delete Employee</a></li>
                <li><a href="features.php">Features</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Enter Pay Period Information</h2>
        <form method="POST" action="">
            <label for="employee_id">Employee ID:</label><br>
            <input type="number" name="employee_id" id="employee_id" required><br><br>

            <label for="pay_period_start">Pay Period Start:</label><br>
            <input type="date" name="pay_period_start" id="pay_period_start" required><br><br>

            <label for="pay_period_end">Pay Period End:</label><br>
            <input type="date" name="pay_period_end" id="pay_period_end" required><br><br>

            <label for="bonus">Bonus:</label><br>
            <input type="number" name="bonus" id="bonus" value="0" required><br><br>

            <label for="hours_worked">Hours Worked (if Part Time):</label><br>
            <input type="number" name="hours_worked" id="hours_worked" value="0"><br><br>

            <input type="submit" value="Calculate Net Pay">
        </form>
    </div>
</body>
</html>
