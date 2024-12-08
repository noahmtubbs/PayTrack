<?php
session_start();
include('includes/db_connect.php');

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch employee details for editing
$employee_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$employee_id) {
    die("Invalid Employee ID.");
}

$sql = "SELECT * FROM Employee WHERE EmployeeID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_changes'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);

    if (!$first_name || !$last_name || !$department || !$position) {
        $message = "<p class='error'>All fields are required.</p>";
    } else {
        $sql_update = "UPDATE Employee SET FirstName = ?, LastName = ?, Department = ?, Position = ? WHERE EmployeeID = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssssi", $first_name, $last_name, $department, $position, $employee_id);

        if ($stmt->execute()) {
            $message = "<p class='success'>Employee details updated successfully.</p>";
        } else {
            $message = "<p class='error'>Failed to update employee details. Please try again.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form {
            display: grid;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px 20px;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-save {
            background-color: #007BFF;
            color: white;
        }

        .btn-save:hover {
            background-color: #0056b3;
        }

        .btn-cancel {
            background-color: #8B0000;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #b30000;
        }

        .message {
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Employee</h1>
    </header>

    <div class="container">
        <h2>Edit Details for <?= htmlspecialchars($employee['FirstName'] . " " . $employee['LastName']); ?></h2>
        <?= $message; ?>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($employee['FirstName']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($employee['LastName']); ?>" required>

            <label for="department">Department:</label>
            <input type="text" name="department" id="department" value="<?= htmlspecialchars($employee['Department']); ?>" required>

            <label for="position">Position:</label>
            <input type="text" name="position" id="position" value="<?= htmlspecialchars($employee['Position']); ?>" required>

            <div class="button-group">
                <button type="submit" name="save_changes" class="btn-save">Save Changes</button>
                <a href="employee_list.php" class="btn-cancel" style="text-decoration: none; padding: 10px 20px; text-align: center;">Exit and Don't Save</a>
            </div>
        </form>
    </div>
</body>
</html>