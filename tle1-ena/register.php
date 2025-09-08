<?php

/** @var $db mysqli */

if (isset($_POST['submit'])) {

    require_once('includes/connection.php');

    // Get form data
    $firstName = mysqli_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_escape_string($db, $_POST['lastName']);
    $phoneNumb = mysqli_escape_string($db, $_POST['phoneNumb']);
    $password = $_POST['password'];
    // Server-side validation
    $errors = [];

    if ($firstName === '') {
        $errors['firstName'] = "First name cannot be empty";
    }
    if ($lastName === '') {
        $errors['lastName'] = "Last name cannot be empty";
    }
    if ($phoneNumb === '') {
        $errors['phoneNumb'] = "Phone number cannot be empty";
    } else {
        $query = "SELECT * FROM users WHERE phoneNumb = '$phoneNumb'";

        $result = mysqli_query($db, $query)
        or die('Error ' . mysqli_error($db) . ' with query ' . $query);

        if (mysqli_fetch_assoc($result)) {
            $errors['phoneNumb'] = "This number already exists";
        }
    }
    if ($password === '') {
        $errors['password'] = "Password cannot be empty";
    } else {
        if (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters";
        }
    }
    // If data valid
    if (empty($errors)) {

        // create a secure password, with the PHP function password_hash()
        $securePassword = password_hash($password, PASSWORD_DEFAULT);

        // store the new user in the database.
        $query = "INSERT INTO `users`(phoneNumb, `password`, `first_name`, `last_name`)
            VALUES ('$phoneNumb', '$securePassword', '$firstName', '$lastName')";

        // If query succeeded
        $result = mysqli_query($db, $query)
        or die('Error ' . mysqli_error($db) . ' with query ' . $query);

        mysqli_close($db);
        // Redirect to login page
        if ($result) {
            header('Location: login.php');
            // Exit the code
            exit();
        }
    }

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">

    <title>Register</title>
</head>
<body>

<section class="section">
    <div class="container">
        <h2 class="title">Register With Phone Number</h2>

        <section class="columns">
            <form class="column" action="" method="post">

                <!-- First name -->
                <div class="field=">
                    <div class="field-label">
                        <label class="label" for="firstName">First name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="firstName" type="text" name="firstName"
                                       value="<?= htmlentities($firstName ?? '') ?>"/>
                                <span class="icon"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help">
                                <?= $errors['firstName'] ?? '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Last name -->
                <div class="field">
                    <div class="field-label">
                        <label class="label" for="lastName">Last name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="lastName" type="text" name="lastName"
                                       value="<?= htmlentities($lastName ?? '') ?>"/>
                                <span class="icon"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help">
                                <?= $errors['lastName'] ?? '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email -->
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

                <!-- Password -->
                <div class="field">
                    <div class="field-label">
                        <label class="label" for="password">Password</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="password" type="password" name="password"/>
                                <span class="icon"><i class="fas fa-lock"></i></span>
                            </div>
                            <p class="help">
                                <?= $errors['password'] ?? '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="field">
                    <div class="field-label"></div>
                    <div class="field-body">
                        <button class="button" type="submit" name="submit">
                            Register
                        </button>
                    </div>
                </div>
                <a class="button" href="index.php">&laquo; Go back</a>

            </form>
        </section>

    </div>
</section>
</body>
</html>