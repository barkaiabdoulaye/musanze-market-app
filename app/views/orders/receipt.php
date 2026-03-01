<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?> - Musanze Market</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 30px 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        
        .receipt {
            max-width: 400px;
            width: 100%;
            background: white;
            padding: 30px 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            border-radius: 12px;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .receipt-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #2E7D32;
        }
        
        .receipt-header p {
            color: #666;
            font-size: 12px;
            margin: 3px 0;
        }
        
        .receipt-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #2E7D32;
        }
        
        .receipt-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .receipt-items {
            margin: 20px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        
        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 15px;
        }
        
        .receipt-total {
            margin: 20px 0;
            padding: 15px 0;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            color: #2E7D32;
        }
        
        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #333;
            font-size: 12px;
            color: #666;
        }
        
        .qr-placeholder {
            text-align: center;
            margin: 20px 0;
            font-family: monospace;
            background: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
        }
        
        .print-button {
            display: block;
            width: 100%;
            padding: 15px;
            background: #2E7D32;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 20px 0 10px;
            transition: background 0.3s;
        }
        
        .print-button:hover {
            background: #1B5E20;
        }
        
        .back-button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #757575;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        
        .back-button:hover {
            background: #616161;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #F57C00; color: white; }
        .status-completed { background: #2E7D32; color: white; }
        .status-cancelled { background: #C62828; color: white; }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt {
                box-shadow: none;
                max-width: 100%;
                padding: 15px;
            }
            
            .print-button, .back-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>MUSANZE MARKET</h1>
            <p>Order Management System</p>
            <p>Tel: +250 788 123 456</p>
            <p>Kigali, Rwanda</p>
        </div>
        
        <div class="receipt-info">
            <p><strong>Receipt No:</strong> #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></p>
            <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Cashier:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-<?= $order['status'] ?>">
                    <?= ucfirst($order['status']) ?>
                </span>
            </p>
        </div>
        
        <div class="receipt-info">
            <p><strong>Farmer:</strong> <?= htmlspecialchars($order['farmer_name']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['farmer_phone']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($order['location'] ?: 'Not specified') ?></p>
        </div>
        
        <div class="receipt-items">
            <div class="receipt-item">
                <span>Product:</span>
                <span><strong>Irish Potatoes</strong></span>
            </div>
            <div class="receipt-item">
                <span>Quantity:</span>
                <span><?= number_format($order['quantity'], 2) ?> kg</span>
            </div>
            <div class="receipt-item">
                <span>Unit Price:</span>
                <span><?= number_format($order['unit_price']) ?> RWF/kg</span>
            </div>
            <div class="receipt-item">
                <span>Pickup Location:</span>
                <span><?= htmlspecialchars($order['pickup_location']) ?></span>
            </div>
        </div>
        
        <div class="receipt-total">
            <span>TOTAL AMOUNT</span>
            <span><?= number_format($order['total_amount']) ?> RWF</span>
        </div>
        
        <?php if (!empty($order['notes'])): ?>
            <div class="receipt-info">
                <p><strong>Notes:</strong></p>
                <p><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
            </div>
        <?php endif; ?>
        
        <div class="qr-placeholder">
            <div>████████████</div>
            <div>████████████</div>
            <div>████████████</div>
            <div>████████████</div>
            <p style="margin-top: 10px;">Scan to verify</p>
        </div>
        
        <div class="receipt-footer">
            <p>This receipt is valid for potato collection</p>
            <p>Thank you for your business!</p>
            <p>Payment: Cash on collection</p>
        </div>
        
        <button onclick="window.print()" class="print-button">
            🖨️ Print Receipt
        </button>
        
        <a href="javascript:window.close()" class="back-button">
            Close Window
        </a>
    </div>
</body>
</html>