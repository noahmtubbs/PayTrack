<?php
// Include database connection
include 'includes/db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $employee_type = $_POST['employee_type'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    // Insert employee data into the Employee table
    $sql = "INSERT INTO Employee (FirstName, LastName, EmployeeType, Department, Position, Salary)
            VALUES ('$first_name', '$last_name', '$employee_type', '$department', '$position', '$salary')";

    if ($conn->query($sql) === TRUE) {
        echo "New employee added successfully.";
        // Redirect back to the employee list page
        header("Location: employee_list.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
