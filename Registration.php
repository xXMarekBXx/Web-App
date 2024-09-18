<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registration</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
    <main>
        <h1 class="gradient-text">Create an Account</h1>

        <h2 class="gradient-text">For registration enter your data below</h2>

        <?php
        session_start();
        require_once "connect.php";

        function validate_password($password) 
        {
            return preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d\S]{8,}$/', $password);
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass'])) 
            {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $pass = $_POST['pass'];

                if (!validate_password($pass)) 
                {
                    echo '<p style="color: red;">Password must be at least 8 characters long and include both letters and numbers.</p>';
                } 
                else 
                {

                    $connection = new mysqli($host, $db_user, $db_password, $db_name);

                    if ($connection->connect_errno != 0) 
                    {
                        echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
                    } 
                    else 
                    {
                        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                        $stmt->bind_param("ss", $name, $email);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) 
                        {
                            echo '<p style="color: red;">Username or email already exists.</p>';
                        } 
                        else 
                        {
                            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

                            $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                            $stmt->bind_param("sss", $name, $email, $hashed_password);

                            if ($stmt->execute()) 
                            {
                                header("Location: LogIn.php");
                                exit();
                            } 
                            else 
                            {
                                echo '<p style="color: red;">Registration failed. Please try again.</p>';
                            }
                            $stmt->close();
                        }
                        $stmt->close();
                    }
                    $connection->close();
                }
            } 
        }
        ?>

        <form action="Registration.php" method="post">
            <p>
                <label for="name">Name:</label>
                <input id="name" type="text" placeholder="name" name="name" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input id="email" type="email" placeholder="email" name="email" required>
            </p>
            <p>
                <label for="pass">Password:</label>
                <input id="pass" type="password" placeholder="password" name="pass" required>
            </p>
            <p>
                <button type="submit" class="button">Register</button>
            </p>
        </form>

        <h3 class="gradient-text">If you have an account, go to the Login page</h3>

        <p>
            <a href="LogIn.php" class="button">Go Back</a>
        </p>
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
