<?php
include './query.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data['action'] === 'sendOtp') {
    $otp = random_int(100000, 999999);
    $_SESSION['pwd_otp'] = $otp;
    $_SESSION['pwd_otp_exp'] = time() + 300;



     $mail->sendEmail($consultantDetails['contactEmail'], "Password Change OTP", "Your OTP is $otp");

    echo json_encode(['status'=>'success','message'=>'OTP sent']);
}

if ($data['action'] === 'verifyOtp') {
    if (time() > $_SESSION['pwd_otp_exp']) {
        echo json_encode(['status'=>'error','message'=>'OTP expired']);
        exit;
    }

    if ($data['otp'] == $_SESSION['pwd_otp']) {
        $_SESSION['otp_verified'] = true;
        echo json_encode(['status'=>'success','message'=>'OTP verified']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Invalid OTP']);
    }
}
