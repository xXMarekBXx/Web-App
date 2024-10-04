<?php
        session_start();
        require_once "connect.php";

        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno) {
            die("Connection to the database failed: " . $connection->connect_error);
        }

        $username = $_SESSION['username'] ?? 'Guest';
        $userId = $_SESSION['user_id'] ?? 'N/A';

        $resultIncomes = null;
        $categoriesIncomes = [];
        $totalIncomesAmount = 0;

        $resultExpenses = null;
        $categoriesExpenses = [];
        $totalExpensesAmount = 0;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $period = $_POST['period'] ?? 'Current month';

            switch ($period) {
                case 'Current month':
                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');
                    break;
                case 'Last month':
                    $startDate = date('Y-m-01', strtotime('first day of last month'));
                    $endDate = date('Y-m-t', strtotime('last day of last month'));
                    break;
                case 'Current year':
                    $startDate = date('Y-01-01');
                    $endDate = date('Y-12-31');
                    break;
                case 'Custom':
                    $startDate = $_POST['startingDate'];
                    $endDate = $_POST['endingDate'];
                    break;
            }

            $stmt = $connection->prepare("
                SELECT i.id, i.amount, i.date_of_income, i.income_comment, 
                       c.name AS category_name 
                FROM incomes i 
                JOIN incomes_category_assigned_to_users c ON i.income_category_assigned_to_user_id = c.id 
                WHERE i.user_id = ? AND i.date_of_income BETWEEN ? AND ?
            ");
            $stmt->bind_param("iss", $userId, $startDate, $endDate);
            $stmt->execute();
            $resultIncomes = $stmt->get_result();

            while ($row = $resultIncomes->fetch_assoc()) {
                $categoryName = $row['category_name'];
                $amount = $row['amount'];
                $totalIncomesAmount += $amount;

                if (!isset($categoriesIncomes[$categoryName])) {
                    $categoriesIncomes[$categoryName] = ['total' => 0, 'items' => []];
                }
                $categoriesIncomes[$categoryName]['total'] += $amount;
                $categoriesIncomes[$categoryName]['items'][] = $row;
            }

            $stmt = $connection->prepare("
                SELECT e.id, e.amount, e.date_of_expense, e.expense_comment, 
                       c.name AS category_name 
                FROM expenses e 
                JOIN expenses_category_assigned_to_users c ON e.expense_category_assigned_to_user_id = c.id 
                WHERE e.user_id = ? AND e.date_of_expense BETWEEN ? AND ?
            ");
            $stmt->bind_param("iss", $userId, $startDate, $endDate);
            $stmt->execute();
            $resultExpenses = $stmt->get_result();

            while ($row = $resultExpenses->fetch_assoc()) {
                $categoryName = $row['category_name'];
                $amount = $row['amount'];
                $totalExpensesAmount += $amount;

                if (!isset($categoriesExpenses[$categoryName])) {
                    $categoriesExpenses[$categoryName] = ['total' => 0, 'items' => []];
                }
                $categoriesExpenses[$categoryName]['total'] += $amount;
                $categoriesExpenses[$categoryName]['items'][] = $row;
            }
        }
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .header {
            color: gray;
        }
        .table-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: black;
            color: lightgreen;
        }
        td {
            color: darkgreen;
        }
        .sum-label {
            color: lightgreen;
        }
        .expense-label {
            color: blue;
        }
        .expense-sum-label {
            color: blue;
        }
        .balance-label {
            color: red;
            text-align: center;
            font-size: 1.5em;
            margin: 20px 0;
        }
        .summary-table {
            width: 80%;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .red {
            color: red;
        }
        .green {
            color: lightgreen;
        }
        .blue {
            color: blue;
        }
        .message {
            color: yellow;
            text-align: center;
            font-size: 2.5em;
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleDateInputs() {
            const periodSelect = document.getElementById('period');
            const dateInputs = document.getElementById('dateInputs');
            dateInputs.style.display = (periodSelect.value === 'Custom') ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleDateInputs();
        });
    </script>
</head>

<body>

    <main>        

        <h4 style="color: gray;">Balance Sheet User: <?php echo htmlspecialchars($username); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h4>

        <form method="POST">
            <h1>Your Balance Sheet</h1>
            <label for="periodSelection">Select a period:</label>            
            <select name="period" id="period" required onchange="toggleDateInputs()">
                <option value="Current month">Current month</option>
                <option value="Last month">Last month</option>
                <option value="Current year">Current year</option>
                <option value="Custom">Custom</option>
            </select>

            <div id="dateInputs" style="display: none;">
                <p>
                    <label for="startingDate">Enter the starting date:</label>
                    <input id="startingDate" type="date" name="startingDate" value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>" min="2000-01-01">
                </p>
                <p>
                    <label for="endingDate">Enter the ending date:</label>
                    <input id="endingDate" type="date" name="endingDate" value="<?php echo date('Y-m-d'); ?>" min="2000-01-01">
                </p>
            </div>

            <h3 style="color: lightblue;">And then confirm your selection with the button below:</h3>
            <p>
                <button class="buttonStyle">
                    <a href="#" onclick="this.closest('form').submit(); return false;" class="button">Show</a>
                </button>
            </p>
        </form>

        <p>
            <label for="incomesLabel" class="green"></label>
        </p>
        <h2 style="color: lightgreen;">Incomes:</h2>

        <div class="table-container">
            <?php if ($resultIncomes): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Comment</th>
                    </tr>
                    <?php
                    $resultIncomes->data_seek(0);
                    while ($row = $resultIncomes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_income']); ?></td>
                            <td><?php echo htmlspecialchars($row['income_comment']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="1"><strong class="sum-label">SUM:</strong></td>
                        <td colspan="4" class="sum-label"><?php echo number_format($totalIncomesAmount, 2, ',', ' '); ?></td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>

        <?php if ($categoriesIncomes): ?>
            <?php foreach ($categoriesIncomes as $category => $data): ?>
                <div class="table-container">
                    <table>
                        <tr>
                            <th colspan="4" style="text-align: center;"><?php echo htmlspecialchars($category); ?></th>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Comment</th>
                        </tr>
                        <?php foreach ($data['items'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['id']); ?></td>
                                <td><?php echo htmlspecialchars($item['amount']); ?></td>
                                <td><?php echo htmlspecialchars($item['date_of_income']); ?></td>
                                <td><?php echo htmlspecialchars($item['income_comment']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="1"><strong class="sum-label">SUM:</strong></td>
                            <td colspan="3" class="sum-label"><?php echo number_format($data['total'], 2, ',', ' '); ?></td>
                        </tr>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <p>
            <label for="expensesLabel" class="expense-label"></label>
        </p>
        <h2 style="color: blue;">Expenses:</h2>

        <div class="table-container">
            <?php if ($resultExpenses): ?>
                <table>
                    <tr>
                        <th class="blue">ID</th>
                        <th class="blue">Amount</th>
                        <th class="blue">Category</th>
                        <th class="blue">Date</th>
                        <th class="blue">Comment</th>
                    </tr>
                    <?php
                    $resultExpenses->data_seek(0);
                    while ($row = $resultExpenses->fetch_assoc()): ?>
                        <tr>
                            <td style="color: lightblue;"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td style="color: lightblue;"><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td style="color: lightblue;"><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td style="color: lightblue;"><?php echo htmlspecialchars($row['date_of_expense']); ?></td>
                            <td style="color: lightblue;"><?php echo htmlspecialchars($row['expense_comment']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="1"><strong class="expense-sum-label">SUM:</strong></td>
                        <td colspan="4" class="expense-sum-label"><?php echo number_format($totalExpensesAmount, 2, ',', ' '); ?></td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>

        <?php if ($categoriesExpenses): ?>
            <?php foreach ($categoriesExpenses as $category => $data): ?>
                <div class="table-container">
                    <table>
                        <tr>
                            <th colspan="4" style="text-align: center;"><?php echo htmlspecialchars($category); ?></th>
                        </tr>
                        <tr>
                            <th class="blue">ID</th>
                            <th class="blue">Amount</th>
                            <th class="blue">Date</th>
                            <th class="blue">Comment</th>
                        </tr>
                        <?php foreach ($data['items'] as $item): ?>
                            <tr>
                                <td style="color: lightblue;"><?php echo htmlspecialchars($item['id']); ?></td>
                                <td style="color: lightblue;"><?php echo htmlspecialchars($item['amount']); ?></td>
                                <td style="color: lightblue;"><?php echo htmlspecialchars($item['date_of_expense']); ?></td>
                                <td style="color: lightblue;"><?php echo htmlspecialchars($item['expense_comment']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="1"><strong class="expense-sum-label">SUM:</strong></td>
                            <td colspan="3" class="expense-sum-label"><?php echo number_format($data['total'], 2, ',', ' '); ?></td>
                        </tr>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="balance-label"></div>
        <h2 style="color: red;">BALANCE</h2>
        
        <table class="summary-table">
            <tr>
                <th class="green">Incomes</th>
                <th style="color: blue;">Expenses</th>
            </tr>
            <tr>            
                <td><?php echo number_format($totalIncomesAmount, 2, ',', ' '); ?></td>
                <td style="color: lightblue;"><?php echo number_format($totalExpensesAmount, 2, ',', ' '); ?></td>
            </tr>
            <tr>
                <th class="red">Difference</th>
                <th class="red" colspan="1">
                    <?php
                    $difference = $totalIncomesAmount - $totalExpensesAmount;
                    echo number_format($difference, 2, ',', ' ');
                    ?>
                </th>
            </tr>
        </table>
        
        <br></br>

        <div class="message">
            <?php if ($difference >= 0): ?>
                Your Balance is OK :)
            <?php else: ?>
                You have debts :(
            <?php endif; ?>
        </div>

        <form>
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