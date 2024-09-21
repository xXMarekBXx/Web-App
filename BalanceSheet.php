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
    </style>
    <script>
        function toggleDateInputs() {
            const periodSelect = document.getElementById('period');
            const dateInputs = document.getElementById('dateInputs');
            dateInputs.style.display = (periodSelect.value === 'Custom') ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleDateInputs(); // Initial check
        });
    </script>
</head>

<body>

    <main>
        <?php
        session_start();
        $username = $_SESSION['username'] ?? 'Guest'; // Domyślnie 'Guest' jeśli nie ma w sesji
        $userId = $_SESSION['user_id'] ?? 'N/A'; // Domyślnie 'N/A' jeśli nie ma w sesji
        ?>

        <h2 class="header">Balance Sheet User: <?php echo htmlspecialchars($username); ?> (ID: <?php echo htmlspecialchars($userId); ?>)</h2>

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

            <p>
                <button class="buttonStyle">
                    <a class="button">Show</a>
                </button>
            </p>

            <p>
                <label for="balance">Balance:</label>
            </p>
            <p>
                <label for="debtWarning">Be careful, you are in debt!</label>
            </p>
            <p>
                <label for="congratulations">Congratulations. You manage your finances great!</label>
            </p>
        </form>

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
