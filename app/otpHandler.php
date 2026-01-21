<?php
include './query.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data['action'] === 'sendOtp') {
    $otp = random_int(100000, 999999);
    $_SESSION['pwd_otp'] = $otp;
    $_SESSION['pwd_otp_exp'] = time() + 300;

    $mail->sendEmail(
        $consultantDetails['contactEmail'],
        "Password Change OTP",
        "Your OTP is $otp"
    );

    // Record log: OTP sent
    $user->recordLog(
        $_SESSION['active'],
        'Password Change OTP Sent',
        "User ID: {$_SESSION['active']} requested password change OTP sent to email address: {$consultantDetails['contactEmail']}"
    );

    echo json_encode(['status' => 'success', 'message' => 'OTP sent']);
}

if ($data['action'] === 'verifyOtp') {

    if (time() > $_SESSION['pwd_otp_exp']) {

        // Record log: OTP expired
        $user->recordLog(
            $_SESSION['active'],
            'Password Change OTP Expired',
            "User ID: {$_SESSION['active']}  attempted to verify an expired OTP "
        );

        echo json_encode(['status' => 'error', 'message' => 'OTP expired']);
        exit;
    }

    if ($data['otp'] == $_SESSION['pwd_otp']) {
        $_SESSION['otp_verified'] = true;

        // Record log: OTP verified
        $user->recordLog(
            $_SESSION['active'],
            'Password Change OTP Verified',
            "User ID: {$_SESSION['active']} successfully verified password change OTP "
        );

        echo json_encode(['status' => 'success', 'message' => 'OTP verified']);
    } else {

        // Record log: Invalid OTP attempt
        $user->recordLog(
            $_SESSION['active'],
            'Invalid Password OTP Attempt',
            "User ID: {$_SESSION['active']} attempted password OTP verification with an invalid OTP."
        );

        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    }
}
