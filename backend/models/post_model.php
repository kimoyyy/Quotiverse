<?php
session_start();

include_once '../db_conn.php';

if (isset($_POST['post'])) {
    $quote = $_POST["quote"];
    $author_id = $_SESSION["user_id"]; // Assuming you store the user's ID in the session

    if (empty($quote)) {
        $_SESSION["post_error"] = "Enter a quote";
        header("Location: ../../views/post_quote.php");
        exit();
    } else {
        $sql = "INSERT INTO quotes (quote_text, author_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $quote, $author_id); // Assuming author_id is an integer
        mysqli_stmt_execute($stmt);

        $_SESSION["post_success"] = "Quote successfully posted";
        header("Location: ../../views/post_quote.php");
        exit();
    }
}
?>
