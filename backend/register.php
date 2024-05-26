<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../views/homepage.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>Register</title>
</head>
<body>
<div class="logo">
    <img src="../img/Quoti.png" alt="quotiverse">
</div>
<div class="text">
    <h1><i>Quotiverse</i></h1>
    <p><b>Post</b> your favorite quote <br>or create your own.</p>
</div>

<!-- Register form -->
<div class="form-container" id="registerForm">
<?php


if (isset($_SESSION['register_errors'])) {
    foreach ($_SESSION['register_errors'] as $error) {
        echo "<div class='alert alert-danger' style='width: 364px; text-align: center; margin-left: 19px;'>$error</div>";
    }
    unset($_SESSION['register_errors']); 
} elseif (isset($_SESSION['registered_success'])) {
    echo "<div class='alert alert-success' style='width: 364px; text-align: center; margin-left: 19px;'>" . $_SESSION['registered_success'] . "</div>";
    unset($_SESSION['registered_success']);
}
?>

    <form method="post" action="controllers/register_controllers.php" id="registerForm">
        <div class="content">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="registerUsername" name="fullname" placeholder="Username">
                <label for="registerUsername">Full name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="registerEmail" name="email" placeholder="name@example.com">
                <label for="registerEmail">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <label for="registerPassword">Password</label>
            </div>
            <div class="btn-login">
                <input type="submit" class="btn btn-primary" name="register" value="Register">
            </div>
            <div class="line"></div>
            <div class="mt-3">
                <p>Already have an account? <a href="../index.php" id="showLoginForm">Login</a></p>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>