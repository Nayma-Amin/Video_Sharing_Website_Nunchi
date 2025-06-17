<?php
$terms = "
<h1>Terms</h1>
<p>Welcome to NUNCHI!</p>
<p>These terms and conditions outline the rules and regulations for the use of NUNCHI's Website.</p>
<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use NUNCHI if you do not agree to take all of the terms and conditions stated on this page.</p>

<h2>License</h2>
<p>Unless otherwise stated, NUNCHI and/or its licensors own the intellectual property rights for all material on NUNCHI. All intellectual property rights are reserved. You may access this from NUNCHI for your own personal use subjected to restrictions set in these terms and conditions.</p>
<p>You must not:</p>
<ul>
    <li>Republish material from NUNCHI</li>
    <li>Sell, rent or sub-license material from NUNCHI</li>
    <li>Reproduce, duplicate or copy material from NUNCHI</li>
    <li>Redistribute content from NUNCHI</li>
</ul>

<p>This Agreement shall begin on the date hereof.</p>
<h2>User Content</h2>
<p>In these Website Standard Terms and Conditions, “User Content” shall mean any audio, video, text, images or other material you choose to display on this Website. By displaying User Content, you grant NUNCHI a non-exclusive, worldwide irrevocable, sub-licensable license to use, reproduce, adapt, publish, translate and distribute it in any and all media.</p>
<p>Your User Content must be your own and must not be infringing on any third party’s rights. NUNCHI reserves the right to remove any of your User Content from this Website at any time without notice.</p>

<h2>Paid User</h2>
<p>Pay 99tk per month to create an Creator channel on NUNCHI to enjoy all the previleges.</p>

<p>Previleges:</p>
<ul>
    <li>Upload your content on NUNCHI</li>
    <li>Download Videos to watch offline on NUNCHI</li>
    <li>Interact through Like/Dislike/Subscribe NUNCHI</li>
    <li>Communicate with creators on NUNCHI</li>
</ul>

<h2>Normal User</h2>
<p>Unsure about NUNCHI? Sign up as a normal user and enjoy watching videos online.</p>

<p>Benefits:</p>
<ul>
    <li>Experience watching content non-stop on NUNCHI</li>
    <li>Communicate with creators on NUNCHI</li>
</ul>

<p>Limitations:</p>
<ul>
    <li>You can not Upload your content on NUNCHI</li>
    <li>You can not Download Videos to watch offline on NUNCHI</li>
    <li>You can not Interact through Like/Dislike/Subscribe on NUNCHI</li>
</ul>

<h2>Thank you so much for visiting our website. We appreciate your time.</h2>
";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        h1, h2 {
            color: #333;
        }
        p, ul {
            color: #666;
        }
        li{
            list-style: none;
            padding: 0;
        }
    </style>
</head>
<body>
<nav class="flex-div">
        <div class="nav-left flex-div">
            <li><a href="index.php"><img src="imagesWS/logoWS.png" class="logo"></a></li>
        </div>
    <?php echo $terms; ?>
</body>
</html>
