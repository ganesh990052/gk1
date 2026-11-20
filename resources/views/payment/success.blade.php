<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="text-success">✅ Payment Successful</h1>
        <p>Thank you for your payment.</p>
        <p><strong>Order ID:</strong> {{ $order_id }}</p>
        <a href="https://chitfund.investifya.com/" class="btn btn-primary mt-3">Go to Dashboard</a>
    </div>
</body>
</html>
