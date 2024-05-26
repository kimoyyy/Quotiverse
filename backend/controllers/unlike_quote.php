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

    // Delete the like from the database
    $delete_like_query = "DELETE FROM likes WHERE quote_id = $quote_id AND user_id = $user_id";
    mysqli_query($conn, $delete_like_query);

    // Fetch the updated like count
    $like_count_query = "SELECT COUNT(*) AS like_count FROM likes WHERE quote_id = $quote_id";
    $like_count_result = mysqli_query($conn, $like_count_query);
    $like_count_row = mysqli_fetch_assoc($like_count_result);
    $like_count = (int)$like_count_row['like_count'];

    // Return the updated like count
    echo $like_count;
} else {
    // If quote_id is not set in the POST request, return an error message
    http_response_code(400); // Bad Request
    echo "Quote ID is not set";
    exit();
}
?>
