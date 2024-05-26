<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: views/homepage.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotiverse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="logo">
        <img src="img/Quoti.png" alt="quotiverse">
    </div>
    <div class="text">
        <h1><i>Quotiverse</i></h1>
        <p><b>Post</b> your favorite quote <br>or create your own.</p>
    </div>

    <!-- Login form -->
    <div class="form-container" id="loginForm">
        <?php
     
          
            if (isset($_SESSION['login_errors'])) {
                foreach ($_SESSION['login_errors'] as $error) {
                    echo "<div class='alert alert-danger' style='width: 364px;text-align:center;margin-left:19px;'>$error</div>";
                }
                unset($_SESSION['login_errors']); 
            }
        ?>
        <form method="post" action="backend/controllers/login_controllers.php" id="loginForm">
            <div class="content">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" placeholder="name@example.com">
                    <label for="loginEmail">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control"  name="password" placeholder="Password">
                    <label for="loginPassword">Password</label>
                </div>
                <div class="btn-login">
                    <input type="submit" class="btn btn-primary" value="Login" name="login">
                </div>
                <div class="line"></div>
                <div class="mt-3">
                    <p>Don't have an account? <a href="backend/register.php" id="showRegisterForm">Create One</a></p>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="javascript/index.js"></script>
</body>
</html>
