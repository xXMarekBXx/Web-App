<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: LogIn.php');
    exit();
}

require_once "connect.php";

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$categories = [];
$paymentMethods = [];
$success_message = "";
$error_message = "";

$connection = new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno)
 {
    die("Connection failed: " . $connection->connect_error);
}

$stmt = $connection->prepare("SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) 
{
    $categories[] = $row;
}
$stmt->close();

$stmt = $connection->prepare("SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) 
{
    $paymentMethods[] = $row;
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $categoryId = $_POST['selectItem'];
    $paymentMethodId = $_POST['selectPaymentMethod'];
    $comment = $_POST['comment'];

    if ($amount > 0 && !empty($date) && !empty($categoryId) && !empty($paymentMethodId)) 
    {
        $stmt = $connection->prepare("INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidsi", $userId, $categoryId, $paymentMethodId, $amount, $date, $comment);

        if ($stmt->execute()) 
        {
            $success_message = "Expense successfully added!";
        } 
        else 
        {
            $error_message = "Failed to add expense. Please try again.";
        }
        $stmt->close();
    } 
    else
    {
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
    <title>Add Expense</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
    <main>
        <h2>Expenses User: <?php echo htmlspecialchars($username); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h2>

        <h1>Add Expense</h1>

        <?php if ($success_message): ?>
            <p style="color: green; font-size: 20px; text-shadow: 1px 1px 2px black;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p style="color: red; font-size: 20px; text-shadow: 1px 1px 2px black;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="Expenses.php" method="post">
            <p>
                <label for="amount">Amount:</label>
                <input id="amount" type="number" placeholder="amount" name="amount" step="0.01" min="0" required>
            </p>
            <p>
                <label for="date">Enter the Expenses date:</label>
                <input id="date" type="date" name="date" value="<?php echo date('Y-m-d'); ?>" min="2000-01-01" required>
            </p>

            <p>
                <label for="selectItem">Select Category:</label>
                <select name="selectItem" id="selectItem" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="selectPaymentMethod">Select Payment Method:</label>
                <select name="selectPaymentMethod" id="selectPaymentMethod" required>
                    <?php foreach ($paymentMethods as $method): ?>
                        <option value="<?php echo htmlspecialchars($method['id']); ?>"><?php echo htmlspecialchars($method['name']); ?></option>
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
            <a href="Menu.php" class="button">Back to Menu</a>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
