<?php
include('includes/db_connect.php');
session_start();

// Ensure the user is logged in as an employee
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$employee_id = intval($_SESSION['employee_id']); // Sanitize employee ID

// Fetch employee's first and last name
$sql_name = "SELECT FirstName, LastName FROM Employee WHERE EmployeeID = ?";
$stmt_name = $conn->prepare($sql_name);
$stmt_name->bind_param("i", $employee_id);
$stmt_name->execute();
$stmt_name->store_result();

if ($stmt_name->num_rows > 0) {
    $stmt_name->bind_result($first_name, $last_name);
    $stmt_name->fetch();
} else {
    $first_name = "Unknown";
    $last_name = "Employee";
}

// Fetch payroll data for the logged-in employee
$sql = "SELECT 
            PayPeriodStart, PayPeriodEnd, BaseSalary, HoursWorked, 
            OvertimePay, Bonus, Deductions, PensionContribution, 
            BenefitsContribution, NetPay
        FROM Payroll 
        WHERE EmployeeID = ? 
        ORDER BY PayPeriodStart DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payroll</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            font-size: 1.2em;
            color: #555;
        }
        .back-btn {
            display: inline-block;
            margin: 10px 0 20px 0;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Payroll Summary for <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h1>
    </header>

    <div class="container">
        <a href="employee_dashboard.php" class="back-btn">Back to Dashboard</a>
        <h2>Payroll History</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Pay Period Start</th>
                        <th>Pay Period End</th>
                        <th>Base Salary</th>
                        <th>Hours Worked</th>
                        <th>Overtime Pay</th>
                        <th>Bonus</th>
                        <th>Deductions</th>
                        <th>Pension Contribution</th>
                        <th>Benefits Contribution</th>
                        <th>Net Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['PayPeriodStart']); ?></td>
                            <td><?php echo htmlspecialchars($row['PayPeriodEnd']); ?></td>
                            <td><?php echo number_format($row['BaseSalary'], 2); ?></td>
                            <td><?php echo number_format($row['HoursWorked'], 2); ?></td>
                            <td><?php echo number_format($row['OvertimePay'], 2); ?></td>
                            <td><?php echo number_format($row['Bonus'], 2); ?></td>
                            <td><?php echo number_format($row['Deductions'], 2); ?></td>
                            <td><?php echo number_format($row['PensionContribution'], 2); ?></td>
                            <td><?php echo number_format($row['BenefitsContribution'], 2); ?></td>
                            <td><?php echo number_format($row['NetPay'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No payroll records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>