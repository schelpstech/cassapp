<?php

// begin or resume session
session_start();

// Include necessary file
include_once 'user.class.php';
include_once 'model.class.php';
include_once 'utility.class.php';
include_once 'qrcode.class.php';
include_once 'paystack.class.php';
include_once 'mail.class.php';

// database access parameters
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'c3clearancemoest_repo';

// Initialize $db_conn to avoid undefined variable warning if connection fails
$db_conn = null;

// connect to database
try {
    $db_conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle the error
    $errors = [];
    array_push($errors, $e->getMessage());
    // Optionally, you can log the error or display it to the user
    echo "Error: " . $e->getMessage();
}

// Only proceed if connection was successful
if ($db_conn !== null) {
    // make use of database with users
    $user = new User($db_conn);
    $model = new Model($db_conn);
    $utility = new Utility();
    $generator = new QRCodeGenerator();
    $paystack = new PaystackPayment();
    $mail = new MailService();
} else {
    // Handle the case when the connection fails (e.g., show an error message or stop further processing)
    echo "Database connection failed.";
}
