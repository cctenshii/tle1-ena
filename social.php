<?php
/** @var $db mysqli */

require_once('includes/connection.php');

$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($db, $query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">

    <title>Personal Social</title>
</head>
<body>
<header>
    <h1>ENA</h1>
    <div>
        <a class="button" href="logout.php">Logout</a>
        <a class="button" href="post.php">+ New Post</a>
    </div>
</header>
<main>
    <!-- Profile Section -->
    <section class="profile">
        <div>
            <a class="back-button" href="home.php">↩</a>
        </div>
        <div class="profile-info">
            <h2>@zhiwen</h2>
            <p>Building cool projects ✨</p>
        </div>
        <img src="images/cat-profile.jpg" alt="Profile Picture" class="profile-pic">
    </section>

    <!-- Feed Section -->
    <section class="feed">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <article class="post">
                <header class="post-header">
                    <h3>@<?= htmlspecialchars($row["username"]) ?></h3>
                </header>

                <?php if ($row["image"]): ?>
                    <img src="<?= htmlspecialchars($row["image"]) ?>" alt="Post image" class="post-image">
                <?php endif; ?>

                <div class="post-body">
                    <p><strong>@<?= htmlspecialchars($row["username"]) ?></strong>
                        <?= htmlspecialchars($row["caption"]) ?>
                    </p>
                    <small><?= $row["created_at"] ?></small>
                </div>
            </article>
        <?php endwhile; ?>
    </section>
</main>
<footer>
    <p>Copyright 2025 - Zhiwen en Sissi</p>
</footer>
</body>
</html>
