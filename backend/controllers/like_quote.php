<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, return an error message
    http_response_code(401); // Unauthorized
    echo "User is not logged in";
    exit();
}

// Include the database connection file
include_once '../db_conn.php';

// Check if the quote_id is set in the POST request
if (isset($_POST['quote_id'])) {
    // Sanitize the quote_id to prevent SQL injection
    $quote_id = mysqli_real_escape_string($conn, $_POST['quote_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the user has already liked the quote
    $check_like_query = "SELECT COUNT(*) AS like_count FROM likes WHERE quote_id = $quote_id AND user_id = $user_id";
    $check_like_result = mysqli_query($conn, $check_like_query);
    $check_like_row = mysqli_fetch_assoc($check_like_result);
    $like_count = (int)$check_like_row['like_count'];

    if ($like_count == 0) {
        // If the user hasn't liked the quote yet, insert a new like
        $insert_like_query = "INSERT INTO likes (user_id, quote_id) VALUES ($user_id, $quote_id)";
        if (mysqli_query($conn, $insert_like_query)) {
            // Get the updated like count for the quote
            $get_like_count_query = "SELECT COUNT(*) AS like_count FROM likes WHERE quote_id = $quote_id";
            $get_like_count_result = mysqli_query($conn, $get_like_count_query);
            $get_like_count_row = mysqli_fetch_assoc($get_like_count_result);
            $like_count = (int)$get_like_count_row['like_count'];

            // Return the updated like count
            echo $like_count;
        } else {
            // If there's an error inserting the like, return an error message
            http_response_code(500); // Internal Server Error
            echo "Error liking quote: " . mysqli_error($conn);
        }
    } else {
        // If the user has already liked the quote, return an error message
        http_response_code(400); // Bad Request
        echo "User has already liked this quote";
    }
} else {
    // If quote_id is not set in the POST request, return an error message
    http_response_code(400); // Bad Request
    echo "Quote ID is not provided";
}
?>
