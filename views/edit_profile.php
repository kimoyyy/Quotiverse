<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotiverse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> 
    <link rel="stylesheet" href="../css/post_quote.css">
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
          <img src="../img/Quoti.png" alt="Quotiverse">
          <a class="navbar-brand" href="javascript:void(0)"><i>Quotiverse</i></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link" href="homepage.php">Home</a>
              </li>
            </ul>
            <a class="btn btn-success ml-5" href="post_quote.php">Post a quote</a>
            <a class="btn btn-success" href="profile.php">Profile</a>
              <a class="btn btn-danger" href="../backend/controllers/logout.php"><span class="material-symbols-outlined">
                logout
                </span></a>
          </div>
        </div>
        </div>
        </div>
      </nav>

    <!--edit profile form-->
    <div class="form-container">
        <?php
            if (isset($_SESSION['edit_error'])) {
                foreach ($_SESSION['edit_error'] as $error) {
                    echo "<div class='alert alert-danger' style='width: 364px;text-align:center;margin-left:19px;'>$error</div>";
                }
                unset($_SESSION['edit_error']);
            } elseif (isset($_SESSION['edit_success'])) {
                echo "<div class='alert alert-success' style='width: 364px;text-align:center;margin-left:19px;'>". $_SESSION['edit_success'] ."</div>";
                
            }
            unset($_SESSION['edit_success']);
        ?>
        <form method="post" action="../backend/controllers/edit_controllers.php">
            <div class="content">
              <h4>Edit Profile</h4>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="fullname" placeholder="fullname">
                    <label>Enter new Full name:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="email" placeholder="email">
                    <label>Enter new email:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="current_password" placeholder="current_password">
                    <label>Enter current password:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="new_password" placeholder="new_password">
                    <label>Enter new password:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="confirm_password">
                    <label>Confirm new password:</label>
                 </div>
                <div class="btn-login">
                    <input type="submit" class="btn btn-primary" value="Edit profile" name="edit_profile">
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="javascript/index.js"></script>
</body>
</html>
