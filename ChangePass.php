<?php
session_start();

if (!isset($_SESSION['loggedin']))
 {
    header('Location: LogIn.php');
    exit();
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

require_once "connect.php";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno) 
{
    echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if ($newPassword !== $confirmPassword) {
        $error_message = "New passwords do not match.";
    } 
    else
    {
       
        $stmt = $connection->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($currentPassword, $hashed_password))
        {
           
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $newHashedPassword, $userId);
            $stmt->execute();
            $stmt->close();
           
            $success_message = "Password successfully updated!";
        } 
        else 
        {            
            $error_message = "Current password is incorrect.";
        }
    }
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>

    <main>

        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12 col-md-12">
                    
                    <h2>Change Password for User: <?php echo htmlspecialchars($userId); ?></h2>
                    
                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    if (isset($success_message)) {
                        echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
                    }
                    ?>
                </div>

                <div class="col-sm-12 col-md-12">
                    <form action="ChangePass.php" method="post">
                        <p>
                            <label for="currentPassword">Current Password:</label>
                            <input id="currentPassword" type="password" name="currentPassword" placeholder="Enter current password" required>
                        </p>
                        <p>
                            <label for="newPassword">New Password:</label>
                            <input id="newPassword" type="password" name="newPassword" placeholder="Enter new password" required>
                        </p>
                        <p>
                            <label for="confirmPassword">Confirm New Password:</label>
                            <input id="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm new password" required>
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
