<?php
session_start(); 
if (!isset($_SESSION['loggedin'])) 
{
    header('Location: LogIn.php');
    exit();
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$currentEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'Unknown Email';

require_once "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno)
 {
    echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $newEmail = trim($_POST['newEmail']);

    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $newEmail, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) 
    {
        $error_message = "This email is already in use. Please choose another.";
    } 
    else 
    {
        $stmt = $connection->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $newEmail, $userId);
        $stmt->execute();

        $_SESSION['email'] = $newEmail;

        $success_message = "Email successfully updated!";
    }
    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Email</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>

    <main>

        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <h2>Change Email for User: <?php echo htmlspecialchars($userId); ?> (Current Email: <?php echo htmlspecialchars($currentEmail); ?>)</h2>
                    
                    <?php
                    if (isset($error_message)) 
                    {
                        echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    if (isset($success_message)) 
                    {
                        echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
                    }
                    ?>
                </div>

                <div class="col-sm-12 col-md-12">
                    <form action="ChangeEmail.php" method="post">
                        <p>
                            <label for="newEmail">New Email:</label>
                            <input id="newEmail" type="email" name="newEmail" placeholder="Enter new email" required>
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
