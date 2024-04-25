<?php
session_start();
include('config/db_conn.php');

// Check if the form was submitted to add a student
if (isset($_POST['addUser'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $insert_query = "INSERT INTO student_regis (student_id, full_name, email, course) VALUES (?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $insert_query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssss", $student_id, $name, $email, $course);
    
    // Execute the statement
    $insert_result = mysqli_stmt_execute($stmt);

    // Check if the insert was successful
    if ($insert_result) {
        $_SESSION['status'] = "Student added successfully";
    } else {
        $_SESSION['status'] = "Error adding student: " . mysqli_error($conn);
    }

    header('Location: student_list.php');
    exit();
}

// Check if the form was submitted to delete a student
if (isset($_POST['deleteUserbtn'])) {
    // Get the ID of the student to be deleted
    $delete_id = $_POST['delete_student_id'];
    
    // Construct the SQL query to delete the student from the database
    $delete_query = "DELETE FROM student_regis WHERE student_id = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $delete_query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $delete_id);
    
    // Execute the delete query
    $delete_result = mysqli_stmt_execute($stmt);

    // Check if the delete was successful
    if ($delete_result) {
        $_SESSION['status'] = "Student deleted successfully";
    } else {
        $_SESSION['status'] = "Error deleting student: " . mysqli_error($conn);
    }

    header('Location: student_list.php');
    exit();
}

// If the form was not submitted to add or delete a student, redirect back to the student_list.php page
header('Location: student_list.php');
exit();
?>
