<?php
session_start();

include_once '../db_conn.php';

if (isset($_POST['edit_profile'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_id = $_SESSION['user_id']; 

    $errors = [];
    if (empty($fullname)) {
        $errors[] = "Full name is required";
    }
    if (empty($email)) {
        $errors[] = "Email address is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    
    $sql = "SELECT * FROM Users WHERE fullname = ? AND user_id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $fullname, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Full name already exists";
    }

   
    $sql = "SELECT * FROM Users WHERE email = ? AND user_id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $email, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email already exists";
    }

   
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if (empty($current_password)) {
            $errors[] = "Current password is required to change the password.";
        }
        if (empty($new_password)) {
            $errors[] = "New password is required.";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "New password and confirm password do not match.";
        }

    
        if (empty($errors)) {
            $sql = "SELECT password FROM Users WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if (!password_verify($current_password, $user['password'])) {
                $errors[] = "Current password is incorrect.";
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['edit_error'] = $errors;
        header("Location: ../../views/edit_profile.php");
        exit();
    } else {
        // Update the user's profile in the database
        $sql = "UPDATE Users SET fullname = ?, email = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $fullname, $email, $user_id);

        if (!mysqli_stmt_execute($stmt)) {
            $_SESSION['edit_error'] = ["Error updating profile: " . mysqli_stmt_error($stmt)];
            header("Location: ../../views/edit_profile.php");
            exit();
        }

        // If new password is provided and verified, update it
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE Users SET password = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $hashed_password, $user_id);

            if (!mysqli_stmt_execute($stmt)) {
                $_SESSION['edit_error'] = ["Error updating password: " . mysqli_stmt_error($stmt)];
                header("Location: ../../views/edit_profile.php");
                exit();
            }
        }

        $_SESSION['edit_success'] = "Profile updated successfully";

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: ../../views/edit_profile.php");
        exit();
    }
}
?>
