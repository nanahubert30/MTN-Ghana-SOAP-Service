<?php
/**
 * Database Configuration File for MTN Ghana SOAP Service
 * Handles connection to MySQL database
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // XAMPP default is empty
define('DB_NAME', 'mtn_ghana');

// Avoid throwing mysqli exceptions so missing tables produce controllable errors
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Set charset to utf8
$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Suppress warnings for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
