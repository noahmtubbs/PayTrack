<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - PayTrack</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Contact Us</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="employee_dashboard.php">Employee Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="container">
        <section>
            <h2>Contact Information</h2>
            <p>If you have any questions, need assistance, or want to learn more about PayTrack, feel free to contact us:</p>
            <ul>
                <li><strong>Email:</strong> support@paytrack.com</li>
                <li><strong>Phone:</strong> +1 (800) 555-1234</li>
                <li><strong>Address:</strong> Somewhere at MTSU </li>
            </ul>
        </section>

        <section>
            <h2>Business Hours</h2>
            <p>Our team is here to assist you during the following hours:</p>
            <ul>
                <li>Monday - Friday: 9:00 AM - 6:00 PM</li>
                <li>Saturday: 10:00 AM - 2:00 PM</li>
                <li>Sunday: Closed</li>
            </ul>
        </section>

        <section>
            <h2>Send Us a Message</h2>
            <p>If you have specific questions or requests, use the form below to get in touch with us:</p>
            <form action="process_contact_form.php" method="POST">
                <label for="name">Your Name:</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Your Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="message">Message:</label><br>
                <textarea id="message" name="message" rows="5" required></textarea><br><br>

                <input type="submit" class="btn" value="Send Message">
            </form>
        </section>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.
    </footer>
</body>
</html>
