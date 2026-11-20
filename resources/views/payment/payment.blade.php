```blade
<!-- payment.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashfree Payment</title>
</head>
<body>
    <h2>Pay EMI</h2>

    <button id="payBtn">Pay Now</button>

    <script src="https://sdk.cashfree.com/js/ui/2.0.0/cashfree.js"></script>
    <script>
        document.getElementById('payBtn').addEventListener('click', async function () {
            try {
                // ✅ Step 1: Create order using your backend
                const response = await fetch("{{ url('/create-order') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        order_amount: 100, // Dynamic amount
                        customer_id: "USER123",
                        customer_email: "user@gmail.com",
                        customer_phone: "9999999999"
                    })
                });

                const data = await response.json();

                if (data.success && data.payment_session_id) {
                    const cashfree = Cashfree({ mode: "sandbox" }); // or "production"

                    // ✅ Step 2: Start Payment
                    cashfree.pay({
                        paymentSessionId: data.payment_session_id,
                        redirectTarget: "_self" // same tab
                    });

                    // ✅ Step 3: Polling or Payment success listener
                    // Cashfree JS SDK allows listening to events
                    cashfree.on("paymentSuccess", async function (event) {
                        console.log("Payment Success", event);
                        
                        // ✅ Step 4: Call backend to update EMI
                        await fetch("{{ url('/update-emi') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                user_id: 1,
                                chit_group_id: 5,
                                chit_id: 12,
                                order_id: data.order_id,
                                amount: 100,
                                status: "Done"
                            })
                        });

                        // ✅ Step 5: Redirect to success page
                        window.location.href = "{{ url('/payment-success') }}";
                    });

                    cashfree.on("paymentFailure", function (event) {
                        console.log("Payment Failed", event);
                        alert("Payment Failed! Please try again.");
                    });
                } else {
                    alert("Something went wrong while creating the order.");
                }
            } catch (err) {
                console.error("Error:", err);
            }
        });
    </script>
</body>
</html>
```
