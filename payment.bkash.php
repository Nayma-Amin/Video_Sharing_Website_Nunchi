<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay with bKash</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Pay with bKash</h1>
    </header>

    <div class="payment-form">

        <h2>Enter bKash Details</h2>
        
        <form id="bkashForm">
            <label for="bKashNumber">bKash Number:</label>
            <input type="text" id="bKashNumber" name="bKashNumber" required>

            <label for="pin">PIN:</label>
            <input type="password" id="pin" name="pin" maxlength="4" required>
        </form>
        <div id="paymentMessage"></div>
        
        <button class="payment-button" id="payButton">Pay Now</button>
    </div>

    <script src="script.js"></script>
</body>
</html>
