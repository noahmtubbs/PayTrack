<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php"); // Redirect if not logged in or not an admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Card Styles */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
        }
        .card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <!-- Manage Users Section -->
        <section>
            <h3>Manage Users</h3>
            <div class="cards">
                <div class="card">
                    <p>Create new user accounts for employees or admins.</p>
                    <a href="register.php">Create Account</a>
                </div>
                <div class="card">
                    <p>View and manage all user accounts.</p>
                    <a href="user_list.php">Manage Users</a>
                </div>
                <div class="card">
                    <p>Update your account password for security.</p>
                    <a href="change_password.php">Change Password</a>
                </div>
            </div>
        </section>

        <!-- Manage Employees Section -->
        <section>
            <h3>Manage Employees</h3>
            <div class="cards">
                <div class="card">
                    <p>Add new employees to the system.</p>
                    <a href="add_employee.php">Add Employee</a>
                </div>
                <div class="card">
                    <p>View or edit the employee list.</p>
                    <a href="employee_list.php">Employee List</a>
                </div>
                <div class="card">
                    <p>Remove employees from the system.</p>
                    <a href="delete_employee.php">Remove Employees</a>
                </div>
            </div>
        </section>

        <!-- Payroll Section -->
        <section>
            <h3>Payroll Management</h3>
            <div class="cards">
                <div class="card">
                    <p>Calculate payroll for employees.</p>
                    <a href="payroll_calculation.php">Calculate Payroll</a>
                </div>
                <div class="card">
                    <p>View detailed payroll history.</p>
                    <a href="payroll_history.php">Payroll History</a>
                </div>
            </div>
        </section>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.
    </footer>
</body>
</html>