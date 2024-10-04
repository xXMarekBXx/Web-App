<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $email = trim($_POST['userEmail']);
    $pass = trim($_POST['userPass']);

    if (empty($email) || empty($pass)) {
        $_SESSION['login_error'] = 'empty_fields';
    } else {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) 
        {
            echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
        } 
        else 
        {       
            $stmt = $connection->prepare("SELECT id, username, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) 
            {
                $stmt->bind_result($user_id, $username, $hashed_password);
                $stmt->fetch();

                if (password_verify($pass, $hashed_password)) 
                {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $user_id;

                    header("Location: Menu.php");
                    exit();
                } 
                else 
                {
                    $_SESSION['login_error'] = 'invalid_credentials';
                }
            } 
            else 
            {
                $_SESSION['login_error'] = 'invalid_credentials';
            }

            $stmt->close();
        }

        $connection->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Log In</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <style>
        .form-group 
        {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            justify-content: center;
        }

        .form-group label 
        {
            width: 150px;
            margin-right: 10px;
            text-align: right;
        }

        .form-group input 
        {
            width: 250px;
            padding: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <main>
        <h1>Log In</h1>

        <h2 class="gradient-text">If you already have an account, log in here</h2>

        <?php
        if (isset($_SESSION['login_error'])) 
        {
            if ($_SESSION['login_error'] === 'empty_fields') {
                echo '<p style="color: red;">All fields are required.</p>';
            } else {
                echo '<p style="color: red;">Incorrect credentials! Please try again.</p>';
            }
            unset($_SESSION['login_error']);
        }
        ?>

        <form action="index.php" method="post">
            <div class="form-group">
                <label for="userEmail">Email:</label>
                <input id="userEmail" type="email" placeholder="user email" name="userEmail" required 
                oninvalid="this.setCustomValidity('Please enter a valid email address. An email should contain @, a domain, and a dot (e.g., example@domain.com).')" 
                oninput="setCustomValidity('')">
            </div>

            <div class="form-group">
                <label for="userPass">Password:</label>
                <input id="userPass" type="password" placeholder="password" name="userPass" required 
                oninvalid="this.setCustomValidity('Please enter your password')" 
                oninput="setCustomValidity('')">
            </div>

            <p>
                <button class="button buttonStyle" type="submit">Log In</button>
            </p>
        </form>

        <h2 class="gradient-text">If not, register here</h2>

        <p>
            <button class="buttonStyle">
                <a href="Registration.php" class="button">Back to Registration</a>
            </button>
        </p>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
