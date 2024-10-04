<?php
session_start();


if (!isset($_SESSION['loggedin'])) {
    header('Location: LogIn.php');
    exit();
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Settings</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>

    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12">                    
                    <h4 style="color: gray;">Settings for User: <?php echo htmlspecialchars($username); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h4>
                </div>

                <div class="col-sm-12 col-md-12">
                    <h2 class="gradient-text">What do You want to change in Your profile?</h2>';
                    <p>
                        <button class="buttonStyle">
                            <a href="ChangeLogin.php" type="button" class="button">Change My Login</a>
                        </button>
                    </p>

                    <p>
                        <button class="buttonStyle">
                            <a href="ChangeEmail.php" type="button" class="button">Change My Email</a>
                        </button>
                    </p>

                    <p>
                        <button class="buttonStyle">
                            <a href="ChangePass.php" type="button" class="button">Change My Password</a>
                        </button>
                    </p>
                </div>

                <div class="col-sm-12 col-md-12">                   
                    <button class="buttonStyle">
                        <a href="Menu.php" type="button" class="button">Back</a>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
