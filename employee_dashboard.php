<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php"); // Redirect if not logged in or not an employee
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional Styling Using Existing Colors */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        section {
            margin-bottom: 20px;
        }
        section h3 {
            margin-bottom: 10px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .card {
            background: #f9f9f9; /* Adjust if your original style has a different background */
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            padding: 10px 15px;
            color: white; /* Use the original link button color */
            border-radius: 4px;
        }
        .card a:hover {
            opacity: 0.9; /* Subtle hover effect */
        }
    </style>
</head>
<body>
    <header>
        <h1>Employee Dashboard</h1>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php">Dashboard</a></li>
                <li><a href="view_payroll.php">View My Payroll</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <!-- Payroll Section -->
        <section>
            <h3>Your Payroll Information</h3>
            <div class="cards">
                <div class="card">
                    <p>View your latest payroll details and history.</p>
                    <a href="view_payroll.php" class="btn">View Payroll</a>
                </div>
            </div>
        </section>

        <!-- Account Management Section -->
        <section>
            <h3>Account Management</h3>
            <div class="cards">
                <div class="card">
                    
              
                <div class="card">
                    <p>Change your account password for security.</p>
                    <a href="change_password.php" class="btn">Change Password</a>
                </div>
            </div>
        </section>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.
    </footer>
</body>
</html>