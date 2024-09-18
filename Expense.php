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
        <h1> Add Expense</h1>
        <form>
            <p>
                <label for="amount">Amount:</label>
                <input id="amount" type="number" placeholder="amount" name="amount" step="00.1" min="0" required>
            </p>
            <p>
                <label for="date">Enter the Incomes date:</label>
                <input id="date" type="date" name="date" value="2024-09-13" min="2000-01-01">
            </p>

            <p>
                <label for="peymentMethodSelection">Select payment method:</label>
            </p>

            <p>
                <select name="selectCategory" id="selectCategory" required>
                    <option value="cash">Cash</option>
                    <option value="debitCard">Debit card</option>
                    <option value="creditCard">Credit card</option>
                </select>
            </p>

            <p>
                <label for="categorySelection">Select Category:</label>
            </p>

            <p>
                <select name="selectItem" id="selectItem" required>
                    <option value="food">Food</option>
                    <option value="apertment">Apertment</option>
                    <option value="transport">Transport</option>
                    <option value="telecommunication">Telecommunication</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="clothes">Clothes</option>
                    <option value="hygiene">Hygiene</option>
                    <option value="kids">Kids</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="tour">Tour</option>
                    <option value="trainings">Trainings</option>
                    <option value="books">Books</option>
                    <option value="savings">Savings</option>
                    <option value="retirement">For golden autumn - retirement</option>
                    <option value="debtRepayment">Debt repayment</option>
                    <option value="donation">Donation</option>
                    <option value="other">Other</option>

                </select>
            </p>

            <p>
                <label for="comment">Comment:</label>
            </p>
            <textarea id="comment" rows="4" cols="50"></textarea>
            <button class="buttonStyle">
                <a href="#" type="button" class="button">Add</a>
            </button>
            <button class="buttonStyle">
                <a href="Menu.php" type="button" class="button">Back to Menu</a>
            </button>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>