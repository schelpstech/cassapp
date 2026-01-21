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
        sprintf(
            'User ID: %d requested password change OTP sent to email address: %s.',
            $_SESSION['active'],
            $consultantDetails['contactEmail']
        )
    );

    echo json_encode(['status' => 'success', 'message' => 'OTP sent']);
}

if ($data['action'] === 'verifyOtp') {

    if (time() > $_SESSION['pwd_otp_exp']) {

        // Record log: OTP expired
        $user->recordLog(
            $_SESSION['active'],
            'Password Change OTP Expired',
            sprintf(
                'User ID: %d attempted to verify an expired OTP.',
                $_SESSION['active']
            )
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
            sprintf(
                'User ID: %d successfully verified password change OTP.',
                $_SESSION['active']
            )
        );

        echo json_encode(['status' => 'success', 'message' => 'OTP verified']);
    } else {

        // Record log: Invalid OTP attempt
        $user->recordLog(
            $_SESSION['active'],
            'Invalid Password OTP Attempt',
            sprintf(
                'User ID: %d attempted password OTP verification with an invalid OTP.',
                $_SESSION['active']
            )
        );

        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    }
}
