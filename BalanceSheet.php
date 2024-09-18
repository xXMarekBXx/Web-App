<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance sheet</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <main>

        <form>
            <h1> Your balance sheet</h1>
            <label for="periodSelection">Select a period:</label>
            <select name="period" id="period" required>
                <option value="Current month">Current month</option>
                <option value="Last month">Last month</option>
                <option value="Current year">Current year</option>
                <option value="Custom">Custom</option>
            </select>
            <p>
                <label for="date">Enter the starting date:</label>
                <input id="startingDate" type="date" name="date" value="2021-08-07" min="2000-01-01">
            </p>
            <p>
                <label for="date">Enter the ending date:</label>
                <input id="endingDate" type="date" name="date" value="2022-08-07" min="2000-01-01">
            </p>

            <table>
                <!-- zamkniecie naglowka w grupe -->
                <thead>
                    <tr>
                        <!-- rowspan i colspan to zasiegi naglowkow tabeli -->
                        <th colspan="3">Incomes</th>
                    </tr>
                    <tr>
                        <!-- rowspan i colspan to zasiegi naglowkow tabeli -->
                        <th rowspan="2">Salary</th>
                        <th rowspan="2">Bank interest</th>
                        <th rowspan="2">Sale on Allegro</th>
                        <th rowspan="2">Other</th>
                    </tr>
                </thead>
                <!-- zamkniecie ciała tabeli w grupe -->
                <tbody>
                    <!-- tr kolumny -->
                    <tr>
                        <!-- td wiersze -->
                        <td>sum salary</td>
                        <td>sum bank interest</td>
                        <td>sum sale on allegro</td>
                        <td>sum other</td>
                    </tr>
                </tbody>
            </table>

            <p></p>
            <table>

                <!-- zamkniecie naglowka w grupe -->
                <thead>
                    <tr>
                        <!-- rowspan i colspan to zasiegi naglowkow tabeli -->
                        <th colspan="3">Expense</th>
                    </tr>
                    <tr>
                        <!-- rowspan i colspan to zasiegi naglowkow tabeli -->
                        <th rowspan="2">Food</th>
                        <th rowspan="2">Apertment</th>
                        <th rowspan="2">Transport</th>
                        <th rowspan="2">Telecommunication</th>
                        <th rowspan="2">Healthcare</th>
                        <th rowspan="2">Clothes</th>
                        <th rowspan="2">Hygiene</th>
                        <th rowspan="2">Kids</th>
                        <th rowspan="2">Entertainment</th>
                        <th rowspan="2">Tour</th>
                        <th rowspan="2">Trainings</th>
                        <th rowspan="2">Books</th>
                        <th rowspan="2">Savings</th>
                        <th rowspan="2">For golden autumn - retirement</th>
                        <th rowspan="2">Debt repayment</th>
                        <th rowspan="2">Donation</th>
                        <th rowspan="2">Other</th>
                    </tr>
                </thead>
                <!-- zamkniecie ciała tabeli w grupe -->
                <tbody>
                    <!-- tr kolumny -->
                    <tr>
                        <!-- td wiersze -->
                        <td>sum Food</td>
                        <td>sum Apertment</td>
                        <td>sum Transport</td>
                        <td>sum Telecommunication</td>
                        <td>sum Healthcare</td>
                        <td>sum Clothes</td>
                        <td>sum Hygiene</td>
                        <td>sum Kids</td>
                        <td>sum Entertainment</td>
                        <td>sum Tour</td>
                        <td>sum Trainings</td>
                        <td>sum Books</td>
                        <td>sum Savings</td>
                        <td>sum Retirement</td>
                        <td>sum Debt repayment</td>
                        <td>sum Donation</td>
                        <td>sum other</td>
                    </tr>
                </tbody>
            </table>
            <p>
                <label for="date">Balance:</label>
            </p>
            <p>
                <label for="date">Be careful, you are in debt!</label>
            </p>
            <p>
                <label for="date">Congratulations. You manage your finances great!</label>
            </p>
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