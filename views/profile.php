<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

include_once '../backend/db_conn.php';

$user_id = $_SESSION['user_id'];
$sql_profile = "SELECT fullname, email FROM users WHERE user_id = $user_id";
$result_profile = mysqli_query($conn, $sql_profile);
$row_profile = mysqli_fetch_assoc($result_profile);
$fullname = $row_profile['fullname'];
$email = $row_profile['email'];

$sql = "SELECT q.quote_id, q.quote_text, q.created_at, u.fullname AS author_name
        FROM quotes q
        INNER JOIN users u ON q.author_id = u.user_id
        WHERE q.author_id = $user_id
        ORDER BY q.created_at DESC";
$result = mysqli_query($conn, $sql);

if (isset($_POST['delete'])) {
  $quote_id = $_POST['quote_id'];

  // Prepare and bind the delete statement
  $delete_query = "DELETE FROM quotes WHERE quote_id = ?";
  $stmt = mysqli_prepare($conn, $delete_query);
  mysqli_stmt_bind_param($stmt, "i", $quote_id);


  if (mysqli_stmt_execute($stmt)) {
      echo "<script>alert('Quote deleted successfully');</script>";
  } else {
      echo "<p class='text-center'>Error deleting quote: " . mysqli_error($conn) . "</p>";
  }

  mysqli_stmt_close($stmt);
}

if (isset($_POST['update'])) {
  $quote_id = $_POST['quote_id'];
  $updated_quote = $_POST['edit_quote'];

  // Prepare and bind the update statement
  $update_query = "UPDATE quotes SET quote_text = ? WHERE quote_id = ?";
  $stmt = mysqli_prepare($conn, $update_query);
  mysqli_stmt_bind_param($stmt, "si", $updated_quote, $quote_id);

  if (mysqli_stmt_execute($stmt)) {
      echo "<script>alert('Quote updated successfully');</script>";
  } else {
      echo "<p class='text-center'>Error updating quote: " . mysqli_error($conn) . "</p>";
  }

  mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/profile.css">
    <title>Quotiverse</title>
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
      
      <!-- Profile Section -->
      <div class="profile-container" style="margin-top:100px">
          <div class="container">
              <div class="profile-card">
                  <div class="card-body">
                      <h5>Profile</h5>
                      <p class="name"><?php echo isset($fullname) ? $fullname : 'Full Name'; ?></p>
                      <p class="icon"><span class="material-symbols-outlined">person</span></p>
                      <hr>
                      <p class="email"><?php echo isset($email)? $email : 'Email' ?></p>
                      <p class="icon"><span class="material-symbols-outlined">mail</span></p>
                      <hr>
                      <a href="edit_profile.php" class="btn btn-primary edit-profile-button">Edit Profile</a>
                  </div>
              </div>
          </div>
      </div>
<!-- User's posts -->
<?php
if (mysqli_num_rows($result) > 0) {
    $firstCard = true;
    while ($row = mysqli_fetch_assoc($result)) {
        // Retrieve the number of likes for this post
        $quote_id = $row['quote_id'];
        $like_count_query = "SELECT COUNT(*) AS like_count FROM likes WHERE quote_id = $quote_id";
        $like_count_result = mysqli_query($conn, $like_count_query);
        $like_count_row = mysqli_fetch_assoc($like_count_result);
        $like_count = $like_count_row['like_count'];
        ?>
        <div class="row mb-4">
            <div class="col-lg-3 offset-lg-2">
                <div class="card <?php if ($firstCard) echo 'fixed-card';?>">
                    <div class="card-body">
                        <p class="card-text text-center"><?php echo $row['quote_text']; ?></p>
                        <hr>
                        <p class="author card-text">Author: <?php echo $row['author_name']; ?></p>
                        <p class="creat_at card-text">Created at: <?php echo $row['created_at']; ?></p>
                        <!-- Display the number of likes -->
                        <p class="like-count card-text">Likes: <?php echo $like_count; ?></p>
                        <form method="post">
                            <input type="hidden" name="quote_id" value="<?php echo $row['quote_id']; ?>">
                            <a href="edit_quote.php" class="btn btn-warning text-white">Edit</a>
                            <button type="submit" class="btn btn-danger delete-button" name="delete">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        <?php
        $firstCard = false;
    }
} else {
    echo "<p class='text text-center'>No quotes found</p>";
}
?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
