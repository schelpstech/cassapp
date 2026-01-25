<?php

// begin or resume session
session_start();

/* PHP timezone */
date_default_timezone_set('Africa/Lagos');
// Include necessary file
include_once 'user.class.php';
include_once 'model.class.php';
include_once 'utility.class.php';
include_once 'qrcode.class.php';
include_once 'inpay.class.php';
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

     /* MySQL session settings */
    $db_conn->exec("SET time_zone = '+01:00'");
    $db_conn->exec("SET NAMES utf8mb4");

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
    $mail = new MailService();

    // Initialize iNPAY
    // Initialize iNPAY
    $inpaySettings = [];
    try {
        // Attempt to fetch settings
        $inpaySettings = $model->getRows('tbl_payment_settings', ['return_type' => 'single', 'where' => ['is_active' => 1]]);
    } catch (Exception $e) {
        // If fetch fails (likely table missing), create table and retry
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `tbl_payment_settings` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `provider` varchar(50) NOT NULL DEFAULT 'inpay',
                `public_key` varchar(255) NOT NULL,
                `secret_key` varchar(255) NOT NULL,
                `environment` varchar(50) NOT NULL DEFAULT 'live',
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $db_conn->exec($sql);

            // Check if empty and insert default
            $check = $db_conn->query("SELECT count(*) FROM tbl_payment_settings");
            if ($check->fetchColumn() == 0) {
                $db_conn->exec("INSERT INTO tbl_payment_settings (public_key, secret_key, environment) VALUES ('', '', 'live')");
            }

            // Retry fetch
            $inpaySettings = $model->getRows('tbl_payment_settings', ['return_type' => 'single', 'where' => ['is_active' => 1]]);
        } catch (Exception $ex) {
            // Silently fail or log if creation fails too
            error_log("Failed to create tbl_payment_settings: " . $ex->getMessage());
        }
    }

    $secretKey = $inpaySettings['secret_key'] ?? '';
    $publicKey = $inpaySettings['public_key'] ?? '';
    $inpay = new InpayPayment($secretKey, $publicKey);
} else {
    // Handle the case when the connection fails (e.g., show an error message or stop further processing)
    echo "Database connection failed.";
}
