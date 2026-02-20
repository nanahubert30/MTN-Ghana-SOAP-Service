<?php
/**
 * MTN Ghana SOAP Web Service Server
 * Provides methods for customer management and transactions
 * Access WSDL at: http://localhost/soap_service/server.php?wsdl
 */

// Include database connection
require_once 'database.php';

// Define the web service class with all methods
class MTNGhanaService {
    
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Get customer details by ID
     * @param int $customerId
     * @return array|string Customer data or error message
     */
    public function getCustomer($customerId) {
        $query = "SELECT id, name, phone, email, balance, subscription_date, status 
                  FROM customers WHERE id = " . intval($customerId);
        
        $result = $this->conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return [
                'id' => $row['id'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'balance' => floatval($row['balance']),
                'subscription_date' => $row['subscription_date'],
                'status' => $row['status']
            ];
        } else {
            return "Customer not found";
        }
    }
    
    /**
     * Get all customers
     * @return array Array of all customers
     */
    public function getAllCustomers() {
        $query = "SELECT id, name, phone, email, balance, subscription_date, status 
                  FROM customers ORDER BY name ASC";
        
        $result = $this->conn->query($query);
        $customers = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'balance' => floatval($row['balance']),
                    'subscription_date' => $row['subscription_date'],
                    'status' => $row['status']
                ];
            }
        }
        
        return $customers;
    }
    
    /**
     * Get customers by status
     * @param string $status (active, inactive, suspended)
     * @return array Array of customers with specified status
     */
    public function getCustomersByStatus($status) {
        $status = $this->conn->real_escape_string($status);
        $query = "SELECT id, name, phone, email, balance, subscription_date, status 
                  FROM customers WHERE status = '$status' ORDER BY name ASC";
        
        $result = $this->conn->query($query);
        $customers = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'balance' => floatval($row['balance']),
                    'subscription_date' => $row['subscription_date'],
                    'status' => $row['status']
                ];
            }
        }
        
        return $customers;
    }
    
    /**
     * Add new customer
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param float $balance
     * @return string Success or error message
     */
    public function addCustomer($name, $phone, $email, $balance) {
        $name = $this->conn->real_escape_string($name);
        $phone = $this->conn->real_escape_string($phone);
        $email = $this->conn->real_escape_string($email);
        $balance = floatval($balance);
        
        $query = "INSERT INTO customers (name, phone, email, balance, status) 
                  VALUES ('$name', '$phone', '$email', $balance, 'active')";
        
        if ($this->conn->query($query) === TRUE) {
            return "Customer added successfully. ID: " . $this->conn->insert_id;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
    
    /**
     * Update customer balance
     * @param int $customerId
     * @param float $newBalance
     * @return string Success or error message
     */
    public function updateBalance($customerId, $newBalance) {
        $customerId = intval($customerId);
        $newBalance = floatval($newBalance);
        
        $query = "UPDATE customers SET balance = $newBalance WHERE id = $customerId";
        
        if ($this->conn->query($query) === TRUE) {
            return "Balance updated successfully";
        } else {
            return "Error: " . $this->conn->error;
        }
    }
    
    /**
     * Recharge customer account
     * @param int $customerId
     * @param float $amount
     * @return array New balance or error message
     */
    public function rechargeAccount($customerId, $amount) {
        $customerId = intval($customerId);
        $amount = floatval($amount);
        
        // Get current balance
        $query = "SELECT balance FROM customers WHERE id = $customerId";
        $result = $this->conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newBalance = $row['balance'] + $amount;
            
            // Update balance
            $updateQuery = "UPDATE customers SET balance = $newBalance WHERE id = $customerId";
            
            if ($this->conn->query($updateQuery) === TRUE) {
                // Log transaction
                $descr = $this->conn->real_escape_string("Recharge GHS $amount");
                $transQuery = "INSERT INTO transactions (customer_id, amount, transaction_type, description) 
                               VALUES ($customerId, $amount, 'recharge', '$descr')";
                $this->conn->query($transQuery);
                
                return [
                    'success' => true,
                    'message' => "Recharge successful",
                    'previous_balance' => floatval($row['balance']),
                    'amount_recharged' => $amount,
                    'new_balance' => floatval($newBalance)
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "Error: " . $this->conn->error
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => "Customer not found"
            ];
        }
    }
    
    /**
     * Get customer transactions
     * @param int $customerId
     * @return array Array of transactions
     */
    public function getTransactions($customerId) {
        $customerId = intval($customerId);
        $query = "SELECT id, customer_id, amount, transaction_type, description, transaction_date 
                  FROM transactions WHERE customer_id = $customerId 
                  ORDER BY transaction_date DESC LIMIT 10";
        
        $result = $this->conn->query($query);
        $transactions = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $transactions[] = [
                    'id' => $row['id'],
                    'customer_id' => $row['customer_id'],
                    'amount' => floatval($row['amount']),
                    'transaction_type' => $row['transaction_type'],
                    'description' => $row['description'],
                    'transaction_date' => $row['transaction_date']
                ];
            }
        }
        
        return $transactions;
    }
    
    /**
     * Get service status (health check)
     * @return array Service information
     */
    public function getServiceStatus() {
        return [
            'service' => 'MTN Ghana SOAP Service',
            'version' => '1.0',
            'status' => 'operational',
            'database' => 'connected',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

// Create SOAP server
$soap = new SoapServer(null, [
    'uri' => 'http://localhost/WSS/',
    'soap_version' => SOAP_1_2
]);

// Register the service class
$soap->setClass('MTNGhanaService');

// Handle SOAP requests
if (isset($_GET['wsdl'])) {
    // Generate WSDL on demand
    header('Content-Type: application/xml');
    
    $wsdl = '<?xml version="1.0" encoding="UTF-8"?>
    <definitions xmlns="http://schemas.xmlsoap.org/wsdl/"
             xmlns:tns="http://localhost/WSS/"
             xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
             xmlns:xsd="http://www.w3.org/2001/XMLSchema"
             name="MTNGhanaService"
             targetNamespace="http://localhost/WSS/">

    <types>
            <xsd:schema targetNamespace="http://localhost/WSS/">
            <xsd:element name="Customer">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="id" type="xsd:int"/>
                        <xsd:element name="name" type="xsd:string"/>
                        <xsd:element name="phone" type="xsd:string"/>
                        <xsd:element name="email" type="xsd:string"/>
                        <xsd:element name="balance" type="xsd:float"/>
                        <xsd:element name="subscription_date" type="xsd:string"/>
                        <xsd:element name="status" type="xsd:string"/>
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
        </xsd:schema>
    </types>

    <message name="getCustomerRequest">
        <part name="customerId" type="xsd:int"/>
    </message>
    <message name="getCustomerResponse">
        <part name="return" type="xsd:anyType"/>
    </message>

    <message name="getAllCustomersRequest"/>
    <message name="getAllCustomersResponse">
        <part name="return" type="xsd:anyType"/>
    </message>

    <message name="rechargeAccountRequest">
        <part name="customerId" type="xsd:int"/>
        <part name="amount" type="xsd:float"/>
    </message>
    <message name="rechargeAccountResponse">
        <part name="return" type="xsd:anyType"/>
    </message>

    <portType name="MTNGhanaServicePortType">
        <operation name="getCustomer">
            <input message="tns:getCustomerRequest"/>
            <output message="tns:getCustomerResponse"/>
        </operation>
        <operation name="getAllCustomers">
            <input message="tns:getAllCustomersRequest"/>
            <output message="tns:getAllCustomersResponse"/>
        </operation>
        <operation name="rechargeAccount">
            <input message="tns:rechargeAccountRequest"/>
            <output message="tns:rechargeAccountResponse"/>
        </operation>
    </portType>

    <binding name="MTNGhanaServiceBinding" type="tns:MTNGhanaServicePortType">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
        <operation name="getCustomer">
            <soap:operation soapAction="getCustomer"/>
            <input><soap:body use="literal"/></input>
            <output><soap:body use="literal"/></output>
        </operation>
        <operation name="getAllCustomers">
            <soap:operation soapAction="getAllCustomers"/>
            <input><soap:body use="literal"/></input>
            <output><soap:body use="literal"/></output>
        </operation>
        <operation name="rechargeAccount">
            <soap:operation soapAction="rechargeAccount"/>
            <input><soap:body use="literal"/></input>
            <output><soap:body use="literal"/></output>
        </operation>
    </binding>

    <service name="MTNGhanaService">
        <port name="MTNGhanaServicePort" binding="tns:MTNGhanaServiceBinding">
            <soap:address location="http://localhost/WSS/server.php"/>
        </port>
    </service>

</definitions>';
    
    echo $wsdl;
} else {
    // Handle normal SOAP requests
    $soap->handle();
}

?>
