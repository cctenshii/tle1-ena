<?php
/** @var $db mysqli */
require_once('includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($db, $_POST["username"]);
    $caption = mysqli_real_escape_string($db, $_POST["caption"]);

    // handle image upload
    $image = "";
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $target_file;
    }

    $query = "INSERT INTO posts (username, caption, image) VALUES ('$username', '$caption', '$image')";
    $result = mysqli_query($db, $query) or die('Error ' . mysqli_error($db) . ' with query ' . $query);

    if ($result === TRUE) {
        header("Location: social.php"); // redirect to feed
        exit;
    } else {
        echo "Error saving post.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">

    <title>Create a Post</title>
</head>
<body>
<h2 class=" post-h2">Create a Post</h2>
<a class="back-button" href="social.php">↩</a>
<form class="post-form" action="post.php" method="post" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Your username" required><br><br>
    <textarea name="caption" placeholder="Write a caption..." required></textarea><br><br>
    <input type="file" name="image" accept="image/*"><br><br>
    <button class="post-button" type="submit">Post</button>
</form>
</body>
</html>
