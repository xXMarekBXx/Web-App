<?php
session_start();

if (!isset($_SESSION['loggedin'])) 
{
    header('Location: LogIn.php');
    exit();
}

require_once "connect.php";

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$categories = [];
$success_message = "";
$error_message = "";

$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno)
 {
    echo "Error: " . $connection->connect_errno . " Reason: " . $connection->connect_error;
    exit();
}

$stmt = $connection->prepare("SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc())
{
    $categories[] = $row;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $categoryId = $_POST['selectCategory'];
    $comment = $_POST['comment'];

    if ($amount > 0 && !empty($date) && !empty($categoryId)) 
    {
        $stmt = $connection->prepare("INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $userId, $categoryId, $amount, $date, $comment);

        if ($stmt->execute()) {
            $success_message = "Income successfully added!";
        } else {
            $error_message = "Failed to add income. Please try again.";
        }
        $stmt->close();
    } else {
        $error_message = "Please fill in all required fields.";
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Add Income</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
    <main>
        <h2>Incomes User: <?php echo htmlspecialchars($username); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h2>

        <h1>Add Income</h1>       

        <?php
        if (!empty($success_message)) 
        {
            echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
        }
        if (!empty($error_message)) 
        {
            echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
        }
        ?>

        <form action="Incomes.php" method="post">
            <p>
                <label for="amount">Amount:</label>
                <input id="amount" type="number" placeholder="amount" name="amount" step="0.01" min="0" required>
            </p>
            <p>
                <label for="date">Enter the Incomes date:</label>
                <input id="date" type="date" name="date" value="<?php echo date('Y-m-d'); ?>" min="2000-01-01" required>
            </p>
            <p>
                <label for="selectCategory">Select Category:</label>
                <select name="selectCategory" id="selectCategory" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="comment">Comment:</label>
                <p>
                <textarea id="comment" name="comment" rows="4" cols="50" placeholder="Optional comment"></textarea>
                    </p>
            </p>
            <p>
            <button class="buttonStyle">
                <a type="button" class="button">Add</a>
            </button>
            </p> 
            <p>
                <button class="buttonStyle">
                    <a href="Menu.php" class="button">Back to Menu</a>
                </button>
            </p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
