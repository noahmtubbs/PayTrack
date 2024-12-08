<?php
include('includes/db_connect.php');
session_start();

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden</h1>";
    echo "<p>You do not have permission to access this page. Please <a href='login.php'>log in</a> with an admin account.</p>";
    exit();
}

// Handle export action
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    // Reuse the SQL query with filters (if any)
    $employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : null;
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    // Build the SQL query dynamically
    $sql = "SELECT e.FirstName, e.LastName, p.PayPeriodStart, p.PayPeriodEnd, p.BaseSalary, p.HoursWorked, p.OvertimePay, p.Bonus, p.Deductions, p.PensionContribution, p.BenefitsContribution, p.NetPay 
            FROM Payroll p 
            INNER JOIN Employee e ON p.EmployeeID = e.EmployeeID 
            WHERE 1=1";

    if ($employee_id) {
        $sql .= " AND p.EmployeeID = $employee_id";
    }
    if ($start_date && $end_date) {
        $sql .= " AND p.PayPeriodStart >= '$start_date' AND p.PayPeriodEnd <= '$end_date'";
    }
    $sql .= " ORDER BY p.PayPeriodStart DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="filtered_payroll_data.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write CSV headers
        fputcsv($output, [
            'First Name', 'Last Name', 'Pay Period Start', 'Pay Period End', 'Base Salary',
            'Hours Worked', 'Overtime Pay', 'Bonus', 'Deductions', 'Pension Contribution',
            'Benefits Contribution', 'Net Pay'
        ]);

        // Write rows to CSV
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['FirstName'], $row['LastName'], $row['PayPeriodStart'], $row['PayPeriodEnd'],
                number_format($row['BaseSalary'], 2), number_format($row['HoursWorked'], 2),
                number_format($row['OvertimePay'], 2), number_format($row['Bonus'], 2),
                number_format($row['Deductions'], 2), number_format($row['PensionContribution'], 2),
                number_format($row['BenefitsContribution'], 2), number_format($row['NetPay'], 2)
            ]);
        }

        fclose($output);
        exit();
    } else {
        echo "No payroll data available for export.";
        exit();
    }
}

// Fetch payroll data for display
$employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : null;
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// Build the SQL query dynamically for display
$sql = "SELECT p.*, e.FirstName, e.LastName 
        FROM Payroll p 
        INNER JOIN Employee e ON p.EmployeeID = e.EmployeeID 
        WHERE 1=1";

if ($employee_id) {
    $sql .= " AND p.EmployeeID = $employee_id";
}
if ($start_date && $end_date) {
    $sql .= " AND p.PayPeriodStart >= '$start_date' AND p.PayPeriodEnd <= '$end_date'";
}
$sql .= " ORDER BY p.PayPeriodStart DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll History</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .actions a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Payroll History</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>All Payroll Records</h2>

        <!-- Payroll Table -->
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Pay Period Start</th>
                        <th>Pay Period End</th>
                        <th>Base Salary</th>
                        <th>Hours Worked</th>
                        <th>Overtime Pay</th>
                        <th>Bonus</th>
                        <th>Tax Deduction</th>
                        <th>Pension Contribution</th>
                        <th>Benefits Contribution</th>
                        <th>Net Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                            <td><?= htmlspecialchars($row['PayPeriodStart']); ?></td>
                            <td><?= htmlspecialchars($row['PayPeriodEnd']); ?></td>
                            <td><?= number_format($row['BaseSalary'], 2); ?></td>
                            <td><?= number_format($row['HoursWorked'], 2); ?></td>
                            <td><?= number_format($row['OvertimePay'], 2); ?></td>
                            <td><?= number_format($row['Bonus'], 2); ?></td>
                            <td><?= number_format($row['Deductions'], 2); ?></td>
                            <td><?= number_format($row['PensionContribution'], 2); ?></td>
                            <td><?= number_format($row['BenefitsContribution'], 2); ?></td>
                            <td><?= number_format($row['NetPay'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: red;">No payroll records found.</p>
        <?php endif; ?>

        <!-- Actions -->
        <div class="actions">
            <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
            <a href="?action=export&employee_id=<?= htmlspecialchars($employee_id) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="btn">Export Current View to CSV</a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>