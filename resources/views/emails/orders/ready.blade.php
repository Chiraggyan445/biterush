<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Ready – BiteRush</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f9f9f9; font-family: 'Segoe UI', sans-serif;">
    <table width="100%" cellspacing="0" cellpadding="0" style="background-color: #f9f9f9;">
        <tr>
            <td align="center">
                <table width="600" cellspacing="0" cellpadding="20" style="background-color: #ffffff; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="padding: 30px 0;">
                            <img src="https://i.imgur.com/eI5M9UT.png" alt="BiteRush Logo" width="120">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 style="color: #333333;">Hi {{ $order->user->name }} 👋,</h2>
                            <p style="font-size: 16px; color: #555555; line-height: 1.6;">
                                Your order <strong>#{{ $order->id }}</strong> is now <span style="color: #28a745;"><strong>ready for delivery!</strong> 🍽️</span>
                            </p>

                            <table width="100%" style="margin-top: 20px;">
                                <tr>
                                    <td style="padding: 10px; background-color: #f1f1f1; border-radius: 8px;">
                                        <strong>Total:</strong> ₹{{ $order->total_amount }}<br>
                                        <strong>Restaurant:</strong> {{ $order->restaurant->name ?? 'N/A' }}<br>
                                        <strong>Order Time:</strong> {{ $order->created_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 15px; color: #666; margin-top: 30px;">
                                Sit tight! Our delivery partner is on the way. We’ll notify you when your order is out for delivery. 🛵
                            </p>

                            <div style="margin-top: 30px; text-align: center;">
                                <a href="{{ url('/order/track/' . $order->id) }}" style="background-color: #ff5722; color: white; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold;">Track Your Order</a>
                            </div>

                            <p style="font-size: 14px; color: #aaa; margin-top: 40px; text-align: center;">
                                Thank you for choosing <strong>BiteRush</strong> 🚀<br>
                                <em>Deliciousness delivered.</em>
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="font-size: 12px; color: #999999; text-align: center; margin-top: 10px;">
                    &copy; {{ date('Y') }} BiteRush. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
