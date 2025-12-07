<?php
session_start();

// Simulated bank account
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 1000;
    $_SESSION['transactions'] = [];
}

// Vulnerable transfer function
function vulnerableTransfer($amount) {
    // Simulate delay
    usleep(rand(100000, 500000)); // 0.1-0.5 seconds
    
    $old_balance = $_SESSION['balance'];
    $new_balance = $old_balance - $amount;
    
    // VULNERABLE: No transaction locking
    $_SESSION['balance'] = $new_balance;
    
    return [
        'old' => $old_balance,
        'new' => $new_balance,
        'time' => microtime(true)
    ];
}

if (isset($_POST['amount'])) {
    $amount = (int)$_POST['amount'];
    
    echo "<h3>Transfer Result:</h3>";
    
    if ($amount > 0 && $amount <= $_SESSION['balance']) {
        $result = vulnerableTransfer($amount);
        $_SESSION['transactions'][] = $result;
        
        echo "<div style='background:#d4edda;padding:15px;margin:10px 0;'>";
        echo "✅ Transfer successful!<br>";
        echo "Amount: \${$amount}<br>";
        echo "Old Balance: \${$result['old']}<br>";
        echo "New Balance: \${$result['new']}";
        echo "</div>";
    } else {
        echo "<div style='background:#f8d7da;padding:15px;margin:10px 0;'>";
        echo "❌ Invalid amount or insufficient funds!";
        echo "</div>";
    }
}

// Simulate concurrent attacks
if (isset($_GET['race'])) {
    echo "<h3>🚀 Race Condition Simulation:</h3>";
    
    $initial_balance = $_SESSION['balance'];
    $transfers = 5;
    $amount_per_transfer = 300; // Total would be $1500
    
    echo "Initial Balance: \${$initial_balance}<br>";
    echo "Attempting {$transfers} transfers of \${$amount_per_transfer} each<br>";
    echo "Total attempted withdrawal: \$" . ($transfers * $amount_per_transfer) . "<br><br>";
    
    // Simulate concurrent requests
    $results = [];
    for ($i = 0; $i < $transfers; $i++) {
        if ($_SESSION['balance'] >= $amount_per_transfer) {
            $results[] = vulnerableTransfer($amount_per_transfer);
        }
    }
    
    echo "<strong>Final Balance: \$" . $_SESSION['balance'] . "</strong><br>";
    
    if ($_SESSION['balance'] < 0) {
        echo "<div style='background:#dc3545;color:white;padding:15px;margin:10px 0;'>";
        echo "💥 RACE CONDITION EXPLOITED! Negative balance achieved!";
        echo "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Race Condition Test</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        .account { background: #007bff; color: white; padding: 20px; border-radius: 10px; margin: 20px 0; }
        form { background: #f8f9fa; padding: 20px; border-radius: 10px; }
        input { padding: 10px; margin: 10px; width: 200px; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        .race-btn { background: #dc3545; }
        .transactions { background: #e9ecef; padding: 15px; margin: 20px 0; }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
    </style>
</head>
<body>
    <h2>🏦 Race Condition Vulnerability (Bank Transfer)</h2>
    
    <div class="account">
        <h3>Your Account</h3>
        <p><strong>Current Balance:</strong> $<?php echo $_SESSION['balance']; ?></p>
        <p><strong>Account ID:</strong> ACC-<?php echo session_id(); ?></p>
    </div>
    
    <form method="POST">
        <h3>Make a Transfer</h3>
        <p>Transfer Amount: $<input type="number" name="amount" value="100" min="1" max="1000"></p>
        <button type="submit">Transfer Now</button>
    </form>
    
    <div style="margin: 20px 0;">
        <a href="?race=1" class="race-btn" style="padding: 15px 30px; background: #dc3545; color: white; text-decoration: none;">
            🚀 Simulate Race Condition Attack (5 concurrent transfers)
        </a>
    </div>
    
    <?php if (!empty($_SESSION['transactions'])): ?>
    <div class="transactions">
        <h3>Recent Transactions:</h3>
        <table border="1" cellpadding="10">
            <tr><th>Time</th><th>Old Balance</th><th>Amount</th><th>New Balance</th></tr>
            <?php foreach (array_reverse($_SESSION['transactions']) as $tx): ?>
            <tr>
                <td><?php echo date('H:i:s', (int)$tx['time']); ?></td>
                <td>$<?php echo $tx['old']; ?></td>
                <td>$<?php echo $tx['old'] - $tx['new']; ?></td>
                <td>$<?php echo $tx['new']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
    
    <div style="background: #fff3cd; padding: 20px; margin: 20px 0; border: 1px solid #ffeaa7;">
        <h3>🎯 Race Condition Explanation:</h3>
        <p><strong>Vulnerability:</strong> Lack of transaction locking allows concurrent operations to read stale balance.</p>
        <p><strong>Attack:</strong> Send multiple transfer requests simultaneously before balance updates.</p>
        <p><strong>Impact:</strong> Negative balances, double spending, inventory manipulation.</p>
        <p><strong>Real Examples:</strong> Cryptocurrency exchanges, e-commerce carts, ticket booking systems.</p>
        
        <h4>🛡️ Prevention:</h4>
        <ul>
            <li>Database transactions with locking (SELECT FOR UPDATE)</li>
            <li>Optimistic/pessimistic locking</li>
            <li>Atomic operations</li>
            <li>Rate limiting</li>
        </ul>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="?reset=1" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none;">
            Reset Account Balance
        </a>
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
    
    <?php if (isset($_GET['reset'])) { session_destroy(); header('Location: race_condition.php'); } ?>
</body>
</html>
