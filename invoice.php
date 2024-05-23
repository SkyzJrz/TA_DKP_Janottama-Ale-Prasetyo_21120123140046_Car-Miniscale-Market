<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - CarMiniscaleMarket</title>
    <link rel="stylesheet" href="dashboardstyle.css">
</head>

<body>

    <section class="invoice" id="invoice">
        <h1 class="heading">Invoice</h1>
        <div class="invoice-container">
            <h2>Customer Information</h2>
            <p id="customer-name"></p>
            <p id="customer-email"></p>
            <p id="customer-address"></p>

            <h2>Order Details</h2>
            <div id="order-details"></div>
            <h3>Total Price: Rp <span id="total-price"></span></h3>

            <button id="back-to-store" class="back-to-store">Back to Store</button>
            <p class="thank-you-message">Thanks for purchasing!</p>
        </div>
    </section>

    <script>
        function getQueryParams() {
            const params = {};
            const queryString = window.location.search.substring(1);
            const regex = /([^&=]+)=([^&]*)/g;
            let m;
            while (m = regex.exec(queryString)) {
                params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
            }
            return params;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const params = getQueryParams();

            document.getElementById('customer-name').innerText = `Name: ${params.name}`;
            document.getElementById('customer-email').innerText = `Email: ${params.email}`;
            document.getElementById('customer-address').innerText = `Address: ${params.address}`;

            const orderDetailsContainer = document.getElementById('order-details');
            const cart = JSON.parse(params.cart);
            let totalPrice = 0;

            cart.forEach(item => {
                const orderItem = document.createElement('div');
                orderItem.classList.add('order-item');
                orderItem.innerHTML = `
                    <h3>${item.name}</h3>
                    <p>Price: Rp${item.price.toLocaleString()}</p>
                    <p>Quantity: ${item.quantity}</p>
                `;
                orderDetailsContainer.appendChild(orderItem);

                totalPrice += item.price * item.quantity;
            });

            document.getElementById('total-price').innerText = totalPrice.toLocaleString();

            document.getElementById('back-to-store').addEventListener('click', () => {
                window.location.href = 'dashboard.php';  
            });
        });
    </script>
</body>

</html>
