<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment Successful</title>
</head>
<body style="text-align:center; margin-top:100px;">
  <h1>✅ Payment Successful!</h1>
  <p>Order ID: {{ request('order_id') }}</p>
  <p>Status: {{ request('status') }}</p>
  <a href="https://investifya.com/">Go back to Dashboard</a>
</body>
</html>
