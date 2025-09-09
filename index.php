<?php
/** @var $db mysqli */

require_once('includes/connection.php');

// required when working with sessions
session_start();

$login = false;
// Is user logged in?
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}

if (isset($_POST['submit'])) {

    // Get form data
    $password = $_POST['password'];
    $phoneNumb = mysqli_escape_string($db, $_POST['phoneNumb']);

    // Server-side validation
    $errors = [];

    if ($password == '') {
        $errors['password'] = 'Please fill in a password';
    }
    if ($phoneNumb == '') {
        $errors['phoneNumb'] = 'Phone number cannot be empty';
    }

    // If data valid
    if (empty($errors)) {
        // SELECT the user from the database, based on the email address.
        $query = "SELECT * FROM users WHERE phoneNumb = '$phoneNumb'";

        /** @var $db mysqli */
        $result = mysqli_query($db, $query)
        or die('Error ' . mysqli_error($db) . ' with query ' . $query);

        // check if the user exists
        if (mysqli_num_rows($result) == 1) {

            // Get user data from result
            $row = mysqli_fetch_assoc($result);

            // Check if the provided password matches the stored password in the database
            if (password_verify($password, $row['password'])) {

                // Store the user in the session
                $_SESSION['user'] = $phoneNumb;

                // Redirect to secure page
                header('Location: index.php');
                exit();
            } else {
                // Credentials not valid
                $errors['loginFailed'] = 'Username/password incorrect';
            }
            //error incorrect log in
        } else {
            // User doesn't exist
            $errors['loginFailed'] = 'Username/password incorrect';
        }
        //error incorrect log in

    }
}
?>
<!doctype html>
<html lang="en" data-theme="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    
    <title>ENA</title>
</head>
<body>
<header>
    <p>Everything you need</p>
    <img src="images/Logo-ENA.jpg" alt="Logo ENA">
    <h1>ENA</h1>
    <div>
        <a class="button" href="register.php">Create Account</a>
    </div>
</header>
<main>
    <section>
        <h2 class="title">Log in</h2>

        <?php if ($login) { ?>
            <p>Je bent ingelogd!</p>
            <p><a href="logout.php">Uitloggen</a> / <a href="secure.php">Naar secure page</a></p>
        <?php } else { ?>

            <section class="columns">
                <form class="column" action="" method="post">

                    <div class="field">
                        <div class="field-label">
                            <label class="label" for="phoneNumb">Phone number</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" id="phoneNumb" type="text" name="phoneNumb"
                                           value="<?= htmlentities($phoneNumb ?? '') ?>"/>
                                    <span class="icon"><i class="fas fa-envelope"></i></span>
                                </div>
                                <p class="help">
                                    <?= $errors['phoneNumb'] ?? '' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label">
                            <label class="label" for="password">Password</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" id="password" type="password" name="password"/>
                                    <span class="icon"><i class="fas fa-lock"></i></span>

                                    <?php if (isset($errors['loginFailed'])) { ?>
                                        <div class="notification">
                                            <button class="delete"></button>
                                            <?= $errors['loginFailed'] ?>
                                        </div>
                                    <?php } ?>

                                </div>
                                <p class="help">
                                    <?= $errors['password'] ?? '' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label"></div>
                        <div class="field-body">
                            <button class="button" type="submit" name="submit">Log
                                in
                            </button>
                        </div>
                    </div>
                </form>
            </section>

        <?php } ?>

    </section>
</main>
<footer>
    <p>Copyright 2025 - Zhiwen en Sissi</p>
</footer>
</body>
</html>
