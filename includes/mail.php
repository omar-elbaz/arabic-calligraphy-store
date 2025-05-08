<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mailgun\Mailgun;

function sendOrderReceipt($orderId, $userEmail) {
    global $pdo;
    
    try {
        // Get order details
        $stmt = $pdo->prepare("
            SELECT o.*, u.email, u.first_name, u.last_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get order items
        $stmt = $pdo->prepare("
            SELECT oi.*, p.name, p.price
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create email content
        $subject = "Order Confirmation - Order #" . $orderId;
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #eee; }
                .order-details { margin: 20px 0; }
                .item { padding: 10px 0; border-bottom: 1px solid #eee; }
                .total { text-align: right; font-weight: bold; margin-top: 20px; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Thank You for Your Order!</h1>
                    <p>Order #" . $orderId . "</p>
                </div>
                
                <div class='order-details'>
                    <h2>Order Details</h2>
                    <p><strong>Order Date:</strong> " . date('F j, Y', strtotime($order['created_at'])) . "</p>
                    <p><strong>Shipping Address:</strong><br>" . nl2br(htmlspecialchars($order['shipping_address'])) . "</p>
                </div>

                <div class='items'>
                    <h2>Items Ordered</h2>";

        foreach ($items as $item) {
            $message .= "
                    <div class='item'>
                        <p><strong>" . htmlspecialchars($item['name']) . "</strong></p>
                        <p>Quantity: " . $item['quantity'] . "</p>
                        <p>Price: $" . number_format($item['price'], 2) . "</p>
                        <p>Subtotal: $" . number_format($item['price'] * $item['quantity'], 2) . "</p>
                    </div>";
        }

        $message .= "
                    <div class='total'>
                        <p>Total: $" . number_format($total, 2) . "</p>
                    </div>
                </div>

                <div class='footer'>
                    <p>Thank you for shopping with us!</p>
                    <p>If you have any questions about your order, please contact us.</p>
                </div>
            </div>
        </body>
        </html>";

        // Initialize Mailgun
        $mg = Mailgun::create($_ENV['MAILGUN_API_KEY']);
        
        // Send email using Mailgun
        $result = $mg->messages()->send($_ENV['MAILGUN_DOMAIN'], [
            'from'    => 'Arabic Calligraphy Store <noreply@' . $_ENV['MAILGUN_DOMAIN'] . '>',
            'to'      => $userEmail,
            'subject' => $subject,
            'html'    => $message
        ]);

        return true;
    } catch (Exception $e) {
        error_log("Error sending order receipt: " . $e->getMessage());
        return false;
    }
} 