<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTrack - Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */

        .workflow {
            text-align: center;
            padding: 40px 20px;
            background-color: #F9F9F9;
            margin: 40px 0;
        }

        .workflow h2 {
            margin-bottom: 20px;
        }

        .workflow-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .workflow-step {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .workflow-step h4 {
            color: #007BFF;
            margin: 10px 0;
        }



        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        /* Hero Banner Styling */
        .hero {
            background: linear-gradient(to right, #007BFF, #0056b3);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .hero .btn {
            padding: 15px 30px;
            font-size: 1rem;
            color: white;
            background-color: #FF5733;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .hero .btn:hover {
            background-color: #C70039;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }

        /* Features Section Styling */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }

        .feature-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .feature-card h3 {
            color: #007BFF;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #333;
            font-size: 1rem;
        }

        /* Call-to-Action Section */
        .cta {
            background: #F9F9F9;
            text-align: center;
            padding: 40px 20px;
            border-top: 2px solid #007BFF;
        }

        .cta h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .cta .btn {
            padding: 15px 30px;
            font-size: 1rem;
            color: white;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta .btn:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: white;
            font-size: 0.9rem;
        }

        /* Button Group */
        .btn-group {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn-group a {
            padding: 15px 30px;
            font-size: 1rem;
            color: white;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-group a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <header class="hero">
    <h1>Welcome to PayTrack</h1>
    <p>Your one-stop solution for efficient payroll and employee management.</p>
    <div class="btn-group">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- For unauthenticated users -->
            <a href="login.php" class="btn">Login</a>
            <a href="contact.php" class="btn">Contact Us!</a>
            

        <?php else: ?>
            <!-- Redirect authenticated users based on their role -->
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <a href="admin_dashboard.php" class="btn">Go to Dashboard</a>
            <?php elseif ($_SESSION['role'] === 'Employee'): ?>
                <a href="employee_dashboard.php" class="btn">Go to Dashboard</a>
                 <!-- Add Login Button -->
            <a href="login.php" class="btn">Login</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>

    <!-- Main Content -->
    <div class="container">
        <!-- About Section -->
        <section>
            <h2>About PayTrack</h2>
            <p>
                PayTrack is designed to simplify the complexities of payroll management. 
                Whether you're an admin managing employees or an employee tracking your payroll,
                PayTrack ensures a seamless experience with powerful features.
            </p>
        </section>

        <!-- Key Features Section -->
        <section>
            <h2>Key Features</h2>
            <div class="features">
                <!-- Feature Cards -->
                <div class="feature-card">
                    <h3>Admin Control</h3>
                    <p>Manage employees, payroll, and user accounts with full authority.</p>
                </div>
                <div class="feature-card">
                    <h3>Employee Dashboard</h3>
                    <p>Employees can securely access and track their payroll details.</p>
                </div>
                <div class="feature-card">
                    <h3>Automated Payroll</h3>
                    <p>Seamlessly calculate salaries, deductions, and bonuses.</p>
                </div>
                <div class="feature-card">
                    <h3>Role-Based Access</h3>
                    <p>Separate admin and employee dashboards for tailored experiences.</p>
                </div>
                <div class="feature-card">
                    <h3>Secure Login</h3>
                    <p>Enjoy role-based access with state-of-the-art security features.</p>
                </div>
                <div class="feature-card">
                    <h3>Detailed Reports</h3>
                    <p>Generate insightful payroll reports for better decision-making.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Workflow Section -->
    <section class="workflow">
            <h2>How It Works</h2>
            <div class="workflow-steps">
                <div class="workflow-step">
                    <h4>Step 1</h4>
                    <p>Sign up as Admin to manage your organization.</p>
                </div>
                <div class="workflow-step">
                    <h4>Step 2</h4>
                    <p>Add employees and their details effortlessly.</p>
                </div>
                <div class="workflow-step">
                    <h4>Step 3</h4>
                    <p>Calculate payroll with one click.</p>
                </div>
                <div class="workflow-step">
                    <h4>Step 4</h4>
                    <p>Access payroll history and detailed reports.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Call-to-Action Section -->
    <div class="cta">
        <h2>Ready to Streamline Your Payroll Process?</h2>
        <div class="btn-group">
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?= date("Y"); ?> PayTrack. All rights reserved.
    </footer>
</body>
</html>