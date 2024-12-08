<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your username
$password = "root"; // Replace with your password
$dbname = "PayTrack";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
$message = "";
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql_delete = "DELETE FROM Users WHERE UserID = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "<p style='color: green;'>User with ID $delete_id has been successfully deleted.</p>";
    } else {
        $message = "<p style='color: red;'>Failed to delete user. Please try again.</p>";
    }

    $stmt->close();
}

// Fetch users for the table
$sql = "SELECT UserID, Username, Role FROM Users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List - PayTrack</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Action Button Styles */
        .action-buttons a {
            padding: 8px 12px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            display: inline-block;
            text-align: center;
        }

        /* Delete Button */
        .action-buttons .delete {
            background-color: #DC3545; /* Red */
            color: white;
        }
        .action-buttons .delete:hover {
            background-color: #a71d2a; /* Darker Red */
        }

        /* Smooth Transition */
        .action-buttons a {
            transition: background-color 0.3s ease;
        }

        /* Message Styling */
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>User List</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Display Messages -->
        <div class="message">
            <?= $message; ?>
        </div>

        <!-- User Table -->
        <section>
            <h2>Users</h2>
            <table class="grid-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["UserID"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["Username"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["Role"]) . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a href='user_list.php?delete_id=" . $row["UserID"] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No users found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> PayTrack. All rights reserved.
    </footer>
</body>
</html>