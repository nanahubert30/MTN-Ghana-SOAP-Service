<?php
/**
 * MTN Ghana SOAP Web Service Client
 * Tests and demonstrates the SOAP service functionality
 * Access at: http://localhost/soap_service/client.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$wsdl = 'http://localhost/WSS/server.php?wsdl';
$serviceUrl = 'http://localhost/WSS/server.php';

// Test results holder
$results = [];
$error = null;

try {
    // Create SOAP client
    $client = new SoapClient(null, [
        'location' => $serviceUrl,
        'uri'      => 'http://localhost/soap_service/',
        'soap_version' => SOAP_1_2
    ]);
    
    // Perform tests based on requested action
    $action = isset($_GET['action']) ? $_GET['action'] : 'all';
    
    switch($action) {
        case 'debug':
            // Debug: show raw SOAP request/response
            $client = new SoapClient(null, [
                'location' => $serviceUrl,
                'uri'      => 'http://localhost/WSS/',
                'soap_version' => SOAP_1_2,
                'trace' => 1,
                'exceptions' => 1
            ]);
            try {
                $resp = $client->getCustomer(1);
                header('Content-Type: text/plain');
                echo "=== RESPONSE (PHP) ===\n";
                var_export($resp);
                echo "\n\n=== LAST RESPONSE (raw XML) ===\n";
                echo $client->__getLastResponse();
                echo "\n\n=== LAST REQUEST (raw XML) ===\n";
                echo $client->__getLastRequest();
                exit;
            } catch (SoapFault $e) {
                header('Content-Type: text/plain');
                echo "SOAP Fault: " . $e->getMessage() . "\n\n";
                echo "LAST RESPONSE:\n" . $client->__getLastResponse();
                exit;
            }
            break;
        case 'status':
            $results['Service Status'] = $client->getServiceStatus();
            break;
            
        case 'customer':
            $customerId = isset($_GET['id']) ? intval($_GET['id']) : 1;
            $results['Get Customer (ID: ' . $customerId . ')'] = $client->getCustomer($customerId);
            break;
            
        case 'all_customers':
            $results['All Customers'] = $client->getAllCustomers();
            break;
            
        case 'active_customers':
            $results['Active Customers'] = $client->getCustomersByStatus('active');
            break;
            
        case 'transactions':
            $customerId = isset($_GET['id']) ? intval($_GET['id']) : 1;
            $results['Transactions (Customer ID: ' . $customerId . ')'] = $client->getTransactions($customerId);
            break;
            
        case 'recharge':
            $customerId = isset($_GET['id']) ? intval($_GET['id']) : 1;
            $amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 10.00;
            $results['Recharge Account (ID: ' . $customerId . ', Amount: GHS ' . $amount . ')'] = 
                $client->rechargeAccount($customerId, $amount);
            break;
            
        case 'add_customer':
            $name = isset($_GET['name']) ? $_GET['name'] : 'Test Customer';
            $phone = isset($_GET['phone']) ? $_GET['phone'] : '0241234567';
            $email = isset($_GET['email']) ? $_GET['email'] : 'test@example.com';
            $balance = isset($_GET['balance']) ? floatval($_GET['balance']) : 0;
            
            $results['Add Customer'] = $client->addCustomer($name, $phone, $email, $balance);
            break;
            
        case 'all':
        default:
            // Run all tests
            $results['Service Status'] = $client->getServiceStatus();
            $results['All Customers'] = $client->getAllCustomers();
            $results['Get Customer (ID: 1)'] = $client->getCustomer(1);
            $results['Active Customers'] = $client->getCustomersByStatus('active');
            $results['Transactions (Customer 1)'] = $client->getTransactions(1);
            break;
    }
    
} catch (SoapFault $e) {
    $error = "SOAP Fault: " . $e->faultstring;
    if (isset($e->detail)) {
        $error .= " - " . print_r($e->detail, true);
    }
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTN Ghana SOAP Service - Client Testing</title>
    <style>
        :root{
            --mtn-yellow: #FFCC00;
            --mtn-black: #222222;
            --mtn-gray: #f6f6f6;
            --mtn-muted: #6b6b6b;
            --card-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(180deg, #fff 0%, #fbfbfb 100%);
            color:var(--mtn-black);
            padding:24px;
        }
        .container{max-width:1200px;margin:0 auto;background:#fff;border-radius:8px;box-shadow:var(--card-shadow);overflow:hidden}
        .header{background:var(--mtn-yellow);color:var(--mtn-black);padding:28px 32px;text-align:left;display:flex;align-items:center;gap:18px}
        .header h1{font-size:1.8rem;margin:0;font-weight:700;letter-spacing:0.4px}
        .header p{margin:0;color:var(--mtn-black);opacity:0.95}
        .content{padding:28px 32px}
        .nav-buttons{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:24px}
        .nav-buttons a, .nav-buttons button{
            display:inline-block;padding:12px 16px;border-radius:6px;text-align:center;text-decoration:none;font-weight:600;border:2px solid transparent;cursor:pointer
        }
        .nav-buttons a.primary, .nav-buttons button.primary{background:var(--mtn-yellow);color:var(--mtn-black);border-color:rgba(0,0,0,0.06)}
        .nav-buttons a.ghost{background:transparent;color:var(--mtn-muted);border:1px solid #ececec}
        .nav-buttons a.primary:hover{filter:brightness(0.98);transform:translateY(-2px)}
        .error-box{background:#fff3f1;border-left:4px solid #d9534f;padding:16px;border-radius:6px;margin-bottom:20px;color:#8a1f11}
        .results{margin-top:8px}
        .result-item{background:#ffffff;border-radius:8px;border:1px solid #efefef;margin-bottom:18px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.03)}
        .result-title{padding:14px 18px;background:var(--mtn-gray);font-weight:700;color:var(--mtn-black);border-bottom:1px solid #eee}
        .result-content{padding:18px}
        .result-content pre{background:#fff;border:1px solid #efefef;padding:12px;border-radius:6px;overflow:auto}
        .result-content table{width:100%;border-collapse:collapse;margin-top:8px}
        .result-content table th, .result-content table td{padding:10px;text-align:left;border-bottom:1px solid #f0f0f0}
        .status-active{color:#198754;font-weight:700}
        .status-inactive{color:#dc3545;font-weight:700}
        .status-suspended{color:#f0ad4e;font-weight:700}
        .quick-action{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-top:22px}
        .action-form{background:#fff;border:1px solid #efefef;padding:14px;border-radius:8px}
        .action-form h3{margin-bottom:10px;color:var(--mtn-black)}
        .action-form input, .action-form select{width:100%;padding:10px;margin-bottom:8px;border:1px solid #e7e7e7;border-radius:6px}
        .action-form button{width:100%;padding:10px;background:var(--mtn-black);color:var(--mtn-yellow);border-radius:6px;border:none;font-weight:700;cursor:pointer}
        .info-box{background:linear-gradient(90deg, rgba(0,0,0,0.04), rgba(0,0,0,0.02));border-left:6px solid var(--mtn-yellow);padding:14px;margin-bottom:16px;border-radius:6px;color:var(--mtn-muted)}
        .footer{background:#fafafa;padding:14px;text-align:center;border-top:1px solid #eee;color:var(--mtn-muted);font-size:0.9rem}
        @media (max-width:600px){.header{padding:18px}.header h1{font-size:1.4rem}.content{padding:18px}}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="display:flex;flex-direction:column;width:100%">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                    <div>
                        <h1>MTN Ghana SOAP Service</h1>
                        <p>Web Service Client Testing Interface</p>
                    </div>
                </div>

                <div class="nav-buttons" style="margin-top:12px">
                    <a class="primary" href="?action=all">All Tests</a>
                    <a class="primary" href="?action=status">Service Status</a>
                    <a class="primary" href="?action=all_customers">All Customers</a>
                    <a class="primary" href="?action=active_customers">Active Customers</a>
                    <a class="primary" href="?action=customer&id=1">Customer #1</a>
                    <a class="primary" href="?action=transactions&id=1">Transactions #1</a>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div class="info-box">
                <strong>Service Information:</strong> This is a SOAP (Simple Object Access Protocol) web service 
                for managing MTN Ghana customer accounts. It communicates with a MySQL database to retrieve and manage customer data.
            </div>
            
            <?php if ($error): ?>
                <div class="error-box">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- nav moved into header; keep this area for results -->
            
            <?php if (!empty($results)): ?>
                <div class="results">
                    <h2 style="margin-bottom: 20px; color: #333;">Results</h2>
                    
                    <?php foreach ($results as $title => $data): ?>
                        <div class="result-item">
                            <div class="result-title"><?php echo htmlspecialchars($title); ?></div>
                            <div class="result-content">
                                <?php if (is_array($data)): ?>
                                    <?php if (!empty($data) && is_array(reset($data))): ?>
                                        <!-- Display table for array of objects -->
                                        <table>
                                            <thead>
                                                <tr>
                                                    <?php foreach (array_keys(reset($data)) as $key): ?>
                                                        <th><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?></th>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data as $row): ?>
                                                    <tr>
                                                        <?php foreach ($row as $cell): ?>
                                                            <td>
                                                                <?php
                                                                    if ($cell === 'active' || $cell === 'inactive' || $cell === 'suspended') {
                                                                        echo '<span class="status-' . htmlspecialchars($cell) . '">' . ucfirst(htmlspecialchars($cell)) . '</span>';
                                                                    } else {
                                                                        echo htmlspecialchars($cell);
                                                                    }
                                                                ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <pre><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <pre><?php echo htmlspecialchars($data); ?></pre>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="quick-action">
                <div class="action-form">
                    <h3>Get Specific Customer</h3>
                    <form method="GET">
                        <input type="hidden" name="action" value="customer">
                        <input type="number" name="id" placeholder="Customer ID" value="1" min="1" required>
                        <button type="submit">Get Customer</button>
                    </form>
                </div>
                
                <div class="action-form">
                    <h3>View Transactions</h3>
                    <form method="GET">
                        <input type="hidden" name="action" value="transactions">
                        <input type="number" name="id" placeholder="Customer ID" value="1" min="1" required>
                        <button type="submit">Get Transactions</button>
                    </form>
                </div>
                
                <div class="action-form">
                    <h3>Recharge Account</h3>
                    <form method="GET">
                        <input type="hidden" name="action" value="recharge">
                        <input type="number" name="id" placeholder="Customer ID" value="1" min="1" required>
                        <input type="number" name="amount" placeholder="Amount (GHS)" value="10" step="0.01" min="0.01" required>
                        <button type="submit">Recharge</button>
                    </form>
                </div>
                
                <div class="action-form">
                    <h3>Add New Customer</h3>
                    <form method="GET">
                        <input type="hidden" name="action" value="add_customer">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="tel" name="phone" placeholder="Phone Number" value="024" required>
                        <input type="email" name="email" placeholder="Email Address" required>
                        <input type="number" name="balance" placeholder="Initial Balance" value="0" step="0.01" min="0">
                        <button type="submit">Add Customer</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>MTN Ghana SOAP Web Service | Version 1.0 | Built with PHP & MySQL</p>
            <p>WSDL Available at: <code><?php echo htmlspecialchars($wsdl); ?></code></p>
        </div>
    </div>
</body>
</html>
