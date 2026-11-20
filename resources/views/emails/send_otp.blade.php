<!DOCTYPE html>
<html>
<head>
    <title>OTP Email - MIEM Bro Chit Fund</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transform: translateZ(0); /* Fix for flickering in some email clients */
        }

        .header {
            background-color: #3498db;
            color: #ffffff;
            padding: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .otp-container {
            margin-top: 20px;
            padding: 10px;
            background-color: #f1c40f;
            border-radius: 10px;
            display: inline-block;
            position: relative;
            perspective: 1000px;
        }

        .otp {
            font-size: 36px;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transform-style: preserve-3d;
            position: relative;
            z-index: 1;
        }

        .otp:before, .otp:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            transform: rotateY(0deg);
            transition: transform 0.5s;
        }

        .otp:after {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transform: rotateY(-180deg);
        }

        .footer {
            background-color: #34495e;
            color: #ffffff;
            padding: 20px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MIEM Bro Chit Fund</h1>
        </div>
        <div class="content">
            <h2>Your One Time Password is: </h2>
            <div class="otp-container">
                <div class="otp">{{ $otp }}</div>
            </div>
            <p>This OTP is valid for 30 second Only & usable only once on MIEM Bro Chit Fund. T & C  </p>
        </div>
        <div class="footer">
            <p>Thank you for using MIEM Bro Chit Fund!</p>
        </div>
    </div>
</body>
</html>
