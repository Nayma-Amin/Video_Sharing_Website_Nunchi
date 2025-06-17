var menuIcon = document.querySelector(".menu-icon")
var sidebar = document.querySelector(".sidebar")
var container = document.querySelector(".container")
var subscribeButton = document.querySelector('.subscribe-button');
var forgotPasswordButton = document.querySelector('.forgot-password-button');
var paymentButton = document.querySelector('.payment-button');
var backgroundVideo = document.querySelector('.background-video');
var payButton = document.getElementById("payButton");
var paymentbutton = document.getElementById("pButton");
var cng = document.querySelector('.cng');

menuIcon.onclick = function(){
    sidebar.classList.toggle("small-sidebar")
    container.classList.toggle("large-container")
}

subscribeButton.onclick = function(){
    window.location.href = 'popup.php';
}

forgotPasswordButton.onclick = function(){
    window.location.href = 'forgot_password.php';
}

paymentbutton.onclick = function(){
    window.location.href = 'index.php';
}

cng.onclick = function(){
    window.location.href = 'edit profile.php';
}


paymentButton.onclick = function(){
    window.location.href = 'payment.bkash.php';
    var bKashNumber = document.getElementById("bKashNumber").value;
    var pin = document.getElementById("pin").value;

    if (bKashNumber.trim() === "" || pin.trim() === "") {
        document.getElementById("paymentMessage").innerHTML = "Please fill all fields.";
        return;
    }
    var paymentSuccess = Math.random() < 0.5; // Assuming 50% success rate

    if (paymentSuccess) {
        // Payment successful, redirect to success page
        window.location.href = "video-play.php";
    } else {
        // Payment failed, show message and redirect to current page
        document.getElementById("paymentMessage").innerText = "Payment failed. Please try again.";

    setTimeout(function() {
        document.getElementById("paymentMessage").innerHTML = "Payment successful!";
    }, 2000);
  }
}

payButton.onclick = function(){
    window.location.href = 'payment.nagad.php';
    var nagadNumber = document.getElementById("nagadNumber").value;
    var securityCode = document.getElementById("securityCode").value;

    if (nagadNumber.trim() === "" || securityCode.trim() === "") {
        document.getElementById("paymentMessage").innerHTML = "Please fill all fields.";
        return;
    }
    var paymentSuccess = Math.random() < 0.5; // Assuming 50% success rate

    if (paymentSuccess) {
        // Payment successful, redirect to success page
        window.location.href = "video-play.php";
    } else {
        // Payment failed, show message and redirect to current page
        document.getElementById("paymentMessage").innerText = "Payment failed. Please try again.";

    setTimeout(function() {
        document.getElementById("paymentMessage").innerHTML = "Payment successful!";
    }, 2000); 
}
};