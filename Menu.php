<?php
session_start();
require_once "connect.php";

$userId = $_SESSION['user_id'];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) 
{
    header("Location: LogIn.php");
    exit();
}

$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno != 0) {
    echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
} 
else
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        
        if (isset($_POST['logout']) && $_POST['logout'] == 'true') {
            session_unset();
            session_destroy();
            header("Location: LogIn.php");
            exit();
        }
    }

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

$connection->close();
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
        <h2>Logged In User: <?php echo htmlspecialchars($username); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h2>

        <?php
        
        if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) 
        {
            echo '<h1 class="gradient-text">Welcome, ' . htmlspecialchars($_SESSION['username']). '!</h1>';
            echo '<h1 class="gradient-text">-----------------------------------------------------</h1>';
            echo '<h1 class="gradient-text">MENU</h1>';
            echo '<h1 class="gradient-text">-----------------------------------------------------</h1>';
        } 
        ?>

        <h2 class="gradient-text">Choose one of the options:</h2>

        <p>
            <button class="buttonStyle">
                <a href="Incomes.php" type="button" class="button">Add Income</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="Expenses.php" type="button" class="button">Add Expense</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="BalanceSheet.php" type="button" class="button">View the balance sheet</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="Settings.php" type="button" class="button">Settings</a>
            </button>
        </p>
        <p>
            <button class="buttonStyle">
                <a href="LogIn.php" type="button" class="button">Log Out</a>
            </button>
        </p>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
