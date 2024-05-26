<?php
session_start();

include_once '../db_conn.php';

if(isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $errors = [];

    if (empty($fullname) || empty($email) || empty($password)) {
        $errors[] = "All fields are required";
    }
    if (empty($fullname)) {
        $errors[] = "Full name is required";
    }
    if (empty($email)) {
        $errors[] = "Email address is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
      
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email already exists";
    }

    $sql = "SELECT * FROM Users WHERE fullname = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $fullname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Full name already exists";
    }

    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors; 
        header("Location: ../register.php"); 
        exit();
    } else {
        $sql = "INSERT INTO Users (fullname, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $fullname, $email, $password);
        mysqli_stmt_execute($stmt);
        
        // Retrieve the user_id of the newly registered user
        $user_id = mysqli_insert_id($conn);
        
        $_SESSION['registered_success'] = "Registered successfully";
        $_SESSION['user_id'] = $user_id; // Store user_id in session
        header("Location: ../register.php");
        exit();
    }
}
?>
