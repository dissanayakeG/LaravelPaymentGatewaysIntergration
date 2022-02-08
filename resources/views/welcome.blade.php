<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .content {
            text-align: center;
            margin: 200px auto;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Ensures optimal rendering on mobile devices. -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/> <!-- Optimal Internet Explorer compatibility -->

</head>
<body>
<div class="content flex-center">
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <div id="paypal-button"></div>
    <script>
        paypal.Button.render({
            env: 'sandbox', // Or 'production'
            // Set up the payment:
            // 1. Add a payment callback
            payment: function (data, actions) {
                // 2. Make a request to your server
                return actions.request.post('/api/create-payment').then(function (res) {
                    // 3. Return res.id from the response
                    // console.log('res:::',res);
                    return res.id;
                });
            },
            // Execute the payment:
            // 1. Add an onAuthorize callback
            onAuthorize: function (data, actions) {
                // 2. Make a request to your server
                return actions.request.post('/api/execute-payment', {
                    paymentID: data.paymentID,
                    payerID: data.payerID
                }).then(function (res) {
                    console.log('exc res:::',res);
                    // 3. Show the buyer a confirmation message.
                });
            }

        }, '#paypal-button');

    </script>
</div>
</body>
</html>
