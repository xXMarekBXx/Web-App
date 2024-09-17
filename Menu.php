<?php
session_start();
require_once "connect.php";

// Create a new connection to the database
$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle logout
        if (isset($_POST['logout']) && $_POST['logout'] == 'true') {
            session_unset();
            session_destroy();
            header("Location: LogIn.php");
            exit();
        }

        // Retrieve login details from POST request
        $login = $_POST['userName'];
        $email = $_POST['userEmail'];
        $pass = $_POST['userPass'];

        // Use prepared statements to prevent SQL Injection
        $stmt = $connection->prepare("SELECT username, password FROM users WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $login, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($username, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($pass, $hashed_password)) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                // Clear previous login attempts
                unset($_SESSION['previousUsername']);
                unset($_SESSION['previousEmail']);
                unset($_SESSION['previousPassword']);

                echo '<h1 class="gradient-text">Welcome, ' . htmlspecialchars($_SESSION['username']) . '!</h1>';
                echo '<h1 class="gradient-text">-----------------------------------------------------</h1>';
            } else {
                $_SESSION['login_error'] = true;
                $_SESSION['previousUsername'] = $login;
                $_SESSION['previousEmail'] = $email;
                $_SESSION['previousPassword'] = $pass;

                header("Location: LogIn.php");
                exit();
            }
        } else {
            $_SESSION['login_error'] = true;
            $_SESSION['previousUsername'] = $login;
            $_SESSION['previousEmail'] = $email;
            $_SESSION['previousPassword'] = $pass;

            header("Location: LogIn.php");
            exit();
        }

        $stmt->close(); // Close the prepared statement
    } else {
        $_SESSION['login_error'] = true;
        header("Location: LogIn.php");
        exit();
    }

    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Menu</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
    <main>
        <h1 class="gradient-text">MENU</h1>
        <h1 class="gradient-text">-----------------------------------------------------</h1>
        <h2 class="gradient-text">Choose one of the options:</h2>

        <p>
            <button class="buttonStyle">
                <a href="Income.html" type="button" class="button">Add Income</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="Expense.html" type="button" class="button">Add Expense</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="BalanceSheet.html" type="button" class="button">View the balance sheet</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="Settings.html" type="button" class="button">Settings</a>
            </button>
        </p>

        <form action="Menu.php" method="post">
            <input type="hidden" name="logout" value="true">
            <p>
                <button type="submit" class="button">Log Out</button>
            </p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
