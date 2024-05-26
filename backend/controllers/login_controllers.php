<?php
// Start session
session_start();

// Include database connection
include_once '../db_conn.php';

// Check if login form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

    // Check for empty fields
    if (empty($email) || empty($password)) {
        $errors[] = "Please provide your email and password";
    } else {
        // Prepare and execute SQL query
        $sql = "SELECT user_id, email, password FROM Users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if email exists in database
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variable and redirect to homepage
                $_SESSION['user_id'] = $user['user_id']; // Store user_id in session
                $_SESSION['email'] = $user['email']; // Optionally store email in session
                $_SESSION['fullname'] = $user['fullname'];
                header("Location: ../../views/homepage.php");
                exit();
            } else {
                $errors[] = "Invalid password";
            }
        } else {
            $errors[] = "Email not found";
        }
    }

    // Set session variable for errors and redirect to index.php
    $_SESSION['login_errors'] = $errors;
    header("Location: ../../index.php");
    exit();
}
?>
