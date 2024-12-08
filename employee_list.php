<?php
session_start();
include('includes/db_connect.php');

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch employees
$sql = "SELECT EmployeeID, FirstName, LastName, Department, Position FROM Employee";
$result = $conn->query($sql);

$message = ""; // Feedback message for delete actions
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #000080;
            color: white;
        }

        .btn-edit,
        .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #007BFF;
            color: white;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #8B0000;
            color: white;
        }

        .btn-delete:hover {
            background-color: #b30000;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Employees</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Employee List</h2>
        <?= $message; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['EmployeeID']); ?></td>
                            <td><?= htmlspecialchars($row['FirstName']); ?></td>
                            <td><?= htmlspecialchars($row['LastName']); ?></td>
                            <td><?= htmlspecialchars($row['Department']); ?></td>
                            <td><?= htmlspecialchars($row['Position']); ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?= $row['EmployeeID']; ?>" class="btn-edit">Edit</a>
                                <!-- Optional Delete Button -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>