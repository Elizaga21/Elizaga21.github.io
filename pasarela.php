<?php
session_start();
include 'header.php';
require 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>   
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        form {
            width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            background-color: #000;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        #ideal-bank-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            flex-direction: column;
        }

        #ideal-bank-element {
            max-width: 300px;
            background-color: #fff; 
        border: 1px solid #000; 
            width: 100%;
            margin-top: 10px; 
        }
    </style>
</head>
<body>
<div id="ideal-bank-container">
    <div id="ideal-bank-element"></div>
    </div>
    <form id="payment-form" action="payment_process.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="card-number">Card Number</label>
            <input type="text" class="form-control" id="card-number" name="card-number" required>
        </div>

        <div class="form-group">
            <label for="expiration-date">Expiration Date</label>
            <input type="text" class="form-control" id="expiration-date" name="expiration-date" placeholder="Ej: 12/36" required>
        </div>

        <div class="form-group">
            <label for="cvc">CVC</label>
            <input type="text" class="form-control" id="cvc" name="cvc" placeholder="xxx" required>
        </div>

        <div class="form-group">
            <label for="name-on-card">Name on Card</label>
            <input type="text" class="form-control" id="name-on-card" name="name-on-card" required>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <select class="form-control" id="country" name="country" required>
                <option value="United States">United States</option>
                <option value="Canada">Canada</option>
                <option value="Mexico">Mexico</option>
                <option value="Brazil">Brazil</option>
                <option value="España">España</option>
            </select>
        </div>

        <input type="hidden" name="paymentMethodId" id="paymentMethodId" value="">
        <button type="button" id="payButton" class="btn btn-primary">Pay</button>
    </form>

    <?php include 'footer.php'; ?>

    <script>
        var stripe = Stripe('pk_test_51OdDwPEKHkfLvKZ82d07UlaG3aUGH6Ooct7aSpsveedoJtl2btOMOSwgjNSHbKzEPNEfTdlJfe3dPgH6eiyd801g00lV1WKP9j');
        var elements = stripe.elements();

        var options = {
            style: {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a',
                },
            },
        };

        var idealBank = elements.create('idealBank', options);
        idealBank.mount('#ideal-bank-element');

        var form = document.getElementById('payment-form');
        var payButton = document.getElementById('payButton');

        payButton.addEventListener('click', function () {
            stripe.createPaymentMethod({
                type: 'ideal',
                ideal: idealBank,
                billing_details: {
                    name: document.getElementById('name-on-card').value,
                },
            }).then(function (result) {
                if (result.error) {
                    console.error(result.error);
                } else {
                    document.getElementById('paymentMethodId').value = result.paymentMethod.id;
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
