<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../backend/db_conn.php';

$random_quote_sql = "SELECT quote_text, author_name FROM random_quotes ORDER BY RAND() LIMIT 1";
$random_quote_result = mysqli_query($conn, $random_quote_sql);
$random_quote = mysqli_fetch_assoc($random_quote_result);


$sql = "SELECT q.quote_id, q.quote_text, q.created_at, u.fullname AS author_name
        FROM quotes q
        INNER JOIN users u ON q.author_id = u.user_id
        ORDER BY q.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../css/homepage.css">
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
</nav>

<!-- Left side: Random Quote Generator -->
<div class="random-container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card" style="width: 430px">
                <div class="random card-body" style="width:400px; height: 260px; position: relative;text-align:center;">
                    <!-- Content inside the fixed card body -->
                    <h5 class="card-title">Random Quote</h5>
                    <p class="card-text" style="font-size: 20px; font-weight: bold;"><?php echo $random_quote['quote_text']; ?></p>
                    <p class="card-text" style="font-size: 16px;"><small class="text-muted">- <?php echo $random_quote['author_name']; ?></small></p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Display's posts -->
<div class="post-container mt-5">
    <div class="postcard row">
        <div class="col-md-6">
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

                    // Check if the current user has liked this quote
                    $user_id = $_SESSION['user_id'];
                    $check_like_query = "SELECT COUNT(*) AS user_like_count FROM likes WHERE quote_id = $quote_id AND user_id = $user_id";
                    $check_like_result = mysqli_query($conn, $check_like_query);
                    $check_like_row = mysqli_fetch_assoc($check_like_result);
                    $user_like_count = $check_like_row['user_like_count'];
                    
                    ?>
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card <?php if ($firstCard) echo 'fixed-card';?>"> 
                                <div class="card-body">
                                    <p class="card-text text-center"><?php echo $row['quote_text']; ?></p>
                                    <hr>
                                    <p class="author card-text">Author: <?php echo $row['author_name']; ?></p>
                                    <p class="creat_at card-text">Created at: <?php echo $row['created_at']; ?></p>
                                    <?php if ($user_like_count > 0): ?>
                                        <!-- Unlike button -->
                                        <button class="btn btn-danger unlike-button" data-quote-id="<?php echo $quote_id; ?>">Unlike</button>
                                    <?php else: ?>
                                        <!-- Like button -->
                                        <button class="btn btn-primary like-button" data-quote-id="<?php echo $quote_id; ?>">Like</button>
                                    <?php endif; ?>
                                    <!-- Display like count -->
                                    <span class="like-count" data-quote-id="<?php echo $quote_id; ?>"><?php echo $like_count; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $firstCard = false;
                }
            } else {
                // If there are no quotes, display a message
                echo "<p class='text-center'>No quotes found</p>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Like button click event
        $(document).on('click', '.like-button', function () {
            var $button = $(this);
            var quoteId = $button.data("quote-id");
            $.ajax({
                type: "POST",
                url: "../backend/controllers/like_quote.php",
                data: {quote_id: quoteId},
                success: function (response) {
                    $(".like-count[data-quote-id='" + quoteId + "']").text(response);
                    $button.removeClass('like-button btn-primary').addClass('unlike-button btn-danger').text('Unlike');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Unlike button click event
        $(document).on('click', '.unlike-button', function () {
            var $button = $(this);
            var quoteId = $button.data("quote-id");
            $.ajax({
                type: "POST",
                url: "../backend/controllers/unlike_quote.php",
                data: {quote_id: quoteId},
                success: function (response) {
                    $(".like-count[data-quote-id='" + quoteId + "']").text(response);
                    $button.removeClass('unlike-button btn-danger').addClass('like-button btn-primary').text('Like');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

</body>
</html>
