<?php

if (file_exists('../../../app/phpqrcode/vendor/autoload.php')) {
    require_once '../../../app/phpqrcode/vendor/autoload.php';
} elseif (file_exists('../../app/phpqrcode/vendor/autoload.php')) {
    require_once '../../app/phpqrcode/vendor/autoload.php';
} elseif (file_exists('../app/phpqrcode/vendor/autoload.php')) {
    require_once '../app/phpqrcode/vendor/autoload.php';
} else {
    require_once './app/phpqrcode/vendor/autoload.php';
}

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeGenerator
{
    public function generateQRCode($text)
    {
        $qr_code = QrCode::create($text)
            ->setSize(300)
            ->setMargin(20)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);

        $writer = new PngWriter;
        $result = $writer->write($qr_code);
        $writer = new PngWriter;
        $result = $writer->write($qr_code);
        $dataUri = $result->getDataUri();
        echo $dataUri;
    }


    public function generateQRCoder($data)
    {
        $writer = new \Endroid\QrCode\Writer\PngWriter();

        $qrCode = \Endroid\QrCode\Builder\Builder::create()
            ->writer($writer)
            ->data($data)
            ->size(300)
            ->margin(20)
            ->build();

        $filePath = __DIR__ . '/../storage/qrcodes/' . md5($data) . '.png';

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $qrCode->saveToFile($filePath);

        return $filePath; // IMPORTANT: return file path only
    }

}