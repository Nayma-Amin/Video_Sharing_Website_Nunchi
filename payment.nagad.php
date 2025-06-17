<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay with Nagad</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Pay with Nagad</h1>
    </header>

    <div class="payment-form">
        <h2>Enter Nagad Details</h2>
        
        <form id="nagadForm">
            <label for="nagadNumber">Nagad Account Number:</label>
            <input type="text" id="nagadNumber" name="nagadNumber" required>

            <label for="securityCode">Security Code:</label>
            <input type="password" id="pin" name="pin" maxlength="4" required>
        </form>
        <div id="paymentMessage"></div>
        
        <button class="payment-button" id="payButton">Pay Now</button>
    </div>

    <script src="script.js"></script>
</body>
</html>