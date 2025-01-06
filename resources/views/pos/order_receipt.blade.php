@php 
    $cart = $order->cart;
    $vat = $order->shop->vat;
@endphp 
<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 80mm; /* Set width to match the thermal printer paper size */
        }
        h2, p, div {
            text-align: center;
            margin: 0;
            padding: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            font-weight: bold;
        }
        .item-row td {
            border-bottom: 1px dashed #000;
        }
        .footer-row td {
            padding-top: 8px;
        }

        /* Header Section */
        h2 {
            font-size: 16px;
            font-weight: bold;
        }
        .address {
            font-size: 10px;
        }

        /* Subtotals and Totals */
        tfoot tr td {
            font-weight: bold;
        }
        .total {
            font-size: 14px;
        }

        /* Payment Info */
        .payment-method {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Shop Information -->
    <h2>{{ $order->shop->name }}</h2>
    <div class="address">{{ $order->shop->address }}</div>
    <p>Date: {{ $order->created_at->isoFormat('lll') }}</p>
    <p>Staff: {{ $order->staff }}</p>
    <p>Order No: {{ $order->orderID }}</p>

    <!-- Items Table -->
    <table>
        <tbody>
            @foreach ($cart as $item)
                <tr class="item-row">
                    <td>
                        {{ $item['name'] }}<br>
                        <span>₦{{ number_format($item['price'], 2) }} x {{ $item['qty'] }}</span>
                    </td>
                    <td style="text-align: right;">₦{{ number_format($item['price'] * $item['qty'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="footer-row">
                <td>Subtotal</td>
                <td style="text-align: right;">₦{{ number_format($order->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td>VAT ({{ $vat }}%)</td>
                <td style="text-align: right;">₦{{ number_format(calculate_pct($order->sub_total, $vat), 2) }}</td>
            </tr>
            <tr>
                <td>Service Charge</td>
                <td style="text-align: right;">₦{{ number_format($order->shop->service_fee, 2) }}</td>
            </tr>
            <tr>
                <td class="total">Total</td>
                <td style="text-align: right;" class="total">₦{{ number_format($order->total, 2) }}</td>
            </tr>
            <tr>
                <td>Paid Amount</td>
                <td style="text-align: right;">₦{{ number_format($order->total, 2) }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td style="text-align: right;">{{ $order->pay_method }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 10px;">
        <p>Thank you for your purchase!</p>
        <p>Visit us again!</p>
    </div>
</body>
</html>
