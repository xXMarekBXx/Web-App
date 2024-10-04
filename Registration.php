<?php
session_start();
require_once "connect.php";

function validate_password($password) 
{
    return preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d\S]{8,}$/', $password);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass'])) 
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];

        if (!validate_password($pass)) 
        {
            $error_message = 'Password must be at least 8 characters long and include both letters and numbers.';
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
                    $error_message = 'Username or email already exists.';
                } 
                else 
                {
                    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

                    $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $email, $hashed_password);

                    if ($stmt->execute()) 
                    {
                        $newUserId = $connection->insert_id;

                        $defaultIncomesQuery = "SELECT name FROM incomes_category_default";
                        $defaultIncomesResult = $connection->query($defaultIncomesQuery);

                        if ($defaultIncomesResult->num_rows > 0) 
                        {
                            while ($row = $defaultIncomesResult->fetch_assoc()) 
                            {
                                $incomeCategoryName = $row['name'];
                                $stmt = $connection->prepare("INSERT INTO incomes_category_assigned_to_users (user_id, name) VALUES (?, ?)");
                                $stmt->bind_param("is", $newUserId, $incomeCategoryName);
                                $stmt->execute();
                            }
                        }

                        $defaultPaymentMethodsQuery = "SELECT name FROM payment_methods_default";
                        $defaultPaymentMethodsResult = $connection->query($defaultPaymentMethodsQuery);

                        if ($defaultPaymentMethodsResult->num_rows > 0) 
                        {
                            while ($row = $defaultPaymentMethodsResult->fetch_assoc()) 
                            {
                                $paymentMethodName = $row['name'];
                                $stmt = $connection->prepare("INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES (?, ?)");
                                $stmt->bind_param("is", $newUserId, $paymentMethodName);
                                $stmt->execute();
                            }
                        }

                        $defaultExpensesQuery = "SELECT name FROM expenses_category_default";
                        $defaultExpensesResult = $connection->query($defaultExpensesQuery);

                        if ($defaultExpensesResult->num_rows > 0) 
                        {
                            while ($row = $defaultExpensesResult->fetch_assoc()) 
                            {
                                $expensesCategoryName = $row['name'];
                                $stmt = $connection->prepare("INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES (?, ?)");
                                $stmt->bind_param("is", $newUserId, $expensesCategoryName);
                                $stmt->execute();
                            }
                        }

                        header("Location: index.php");
                        exit();
                    } 
                    else 
                    {
                        $error_message = 'Registration failed. Please try again.';
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registration</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <style>
        /* Dodatkowy styl, aby wyrównać pola Name, Email i Password */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            justify-content: center;
        }

        .form-group label {
            width: 150px; /* Ustal stałą szerokość dla etykiet */
            margin-right: 10px;
            text-align: right;
        }

        .form-group input {
            width: 250px; /* Ustal szerokość dla pól tekstowych */
            padding: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <main>
        <h1 class="gradient-text">Create an Account</h1>
        <h2 class="gradient-text">For registration enter your data below</h2>

        <?php
        if (!empty($error_message)) 
        {
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>

        <form action="Registration.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input id="name" type="text" placeholder="name" name="name" required 
                oninvalid="this.setCustomValidity('Please enter your name')" 
                oninput="setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" type="email" placeholder="email" name="email" required 
                oninvalid="this.setCustomValidity('Please enter a valid email address. An email should contain @, a domain, and a dot (e.g., example@domain.com).')" 
                oninput="setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="pass">Password:</label>
                <input id="pass" type="password" placeholder="password" name="pass" required 
                oninvalid="this.setCustomValidity('Please enter a valid password. The password must be at least 8 characters long and include both letters and numbers.')" 
                oninput="setCustomValidity('')">
            </div>
            <p>
                <button type="submit" class="button">Register</button>
            </p>
        </form>

        <h3 class="gradient-text">If you have an account, go to the Login page</h3>

        <p>
            <a href="index.php" class="button">Go Back</a>
        </p>
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
