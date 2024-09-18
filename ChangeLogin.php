<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['loggedin'])) {
    header('Location: LogIn.php');
    exit();
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$currentUsername = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $newUsername = trim($_POST['newUsername']);

    if (!empty($newUsername)) 
    {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) 
        {
            echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
        } 
        else 
        {
            $stmt = $connection->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $newUsername);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) 
            {
                $errorMessage = 'The username is already taken. Please choose a different one.';
            } 
            else 
            {
                $stmt = $connection->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->bind_param("si", $newUsername, $userId);
                if ($stmt->execute()) 
                {
                    $_SESSION['username'] = $newUsername;
                    
                    $successMessage = 'Username updated successfully!';
                } 
                else 
                {
                    $errorMessage = 'Error updating username.';
                }
            }

            $stmt->close();
        }

        $connection->close();
    } else {
        $errorMessage = 'New username cannot be empty.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>

    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12">                    
                    <h2>Change Login for User: <?php echo htmlspecialchars($currentUsername); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h2>

                    <?php
                    if (!empty($errorMessage)) {
                        echo '<p style="color: red;">' . htmlspecialchars($errorMessage) . '</p>';
                    }
                    ?>

                    <?php
                    if (!empty($successMessage)) {
                        echo '<p style="color: green;">' . htmlspecialchars($successMessage) . '</p>';
                    }
                    ?>
                </div>

                <div class="col-sm-12 col-md-12">
                    <form action="ChangeLogin.php" method="post">
                        <p>
                            <label for="newUsername">New Login:</label>
                            <input id="newUsername" type="text" name="newUsername" placeholder="Enter new login" required>
                        </p>
                        <p>
                            <button type="submit" class="button">Submit Change</button>
                        </p>
                    </form>
                </div>

                <div class="col-sm-12 col-md-12">                 
                    <button class="buttonStyle">
                        <a href="Settings.php" type="button" class="button">Back to Settings</a>
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
