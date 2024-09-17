<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Log In</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
    <main>
        <h1>Log In</h1>

        <h2 class="gradient-text">If you already have an account, log in here</h2>

        <?php
        session_start();

        if (isset($_SESSION['login_error'])) 
        {
            echo '<p style="color: red;">Incorrect credentials. Please try again.</p>';
            unset($_SESSION['login_error']);
        }

        $previousUsername = isset($_SESSION['previousUsername']) ? htmlspecialchars($_SESSION['previousUsername']) : '';
        $previousEmail = isset($_SESSION['previousEmail']) ? htmlspecialchars($_SESSION['previousEmail']) : '';
        $previousPassword = isset($_SESSION['previousPassword']) ? htmlspecialchars($_SESSION['previousPassword']) : '';
        ?>

        <form action="Menu.php" method="post">
            <p>
                <label for="userName">Name:</label>
                <input id="userName" type="text" placeholder="user name" name="userName" value="<?php echo $previousUsername; ?>" required>
            </p>
            <p>
                <label for="userEmail">Email:</label>
                <input id="userEmail" type="email" placeholder="user email" name="userEmail" value="<?php echo $previousEmail; ?>" required>
            </p>
            <p>
                <label for="userPass">Password:</label>
                <input id="userPass" type="password" placeholder="password" name="userPass" value="<?php echo $previousPassword; ?>" required>
            </p>

            <p>
                <button type="submit" class="button">Log In</button>
            </p>
        </form>

        <h2 class="gradient-text">If not, register here</h2>

        <form action="Registration.php" method="post">
            <p>
                <button type="submit" class="button">Back to Registration</button>
            </p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
