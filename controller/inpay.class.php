<?php

class InpayPayment
{
  private $secretKey;
  private $publicKey;

  public function __construct($secretKey, $publicKey)
  {
    $this->secretKey = $secretKey;
    $this->publicKey = $publicKey;
  }

  public function getPublicKey()
  {
    return $this->publicKey;
  }

  /**
   * Verify transaction with iNPAY API
   * Checks both GET and POST endpoints as per documentation
   */
  public function verifyTransaction($reference)
  {
    $headers = [
      'Authorization: Bearer ' . $this->secretKey,
      'Accept: application/json',
    ];

    // 1. Try GET status
    $url = 'https://api.inpaycheckout.com/api/v1/developer/transaction/status?reference=' . rawurlencode($reference);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode === 200) {
      $decoded = json_decode($response, true);
      if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['success'])) {
        if ($this->isTransactionSuccessful($decoded['data'])) {
          return $decoded['data'];
        }
      }
    }

    // 2. Try POST verify if GET failed or wasn't conclusive (though docs say if GET fails)
    $url = 'https://api.inpaycheckout.com/api/v1/developer/transaction/verify';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode(['reference' => $reference]),
      CURLOPT_HTTPHEADER => array_merge($headers, ['Content-Type: application/json']),
      CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode === 200) {
      $decoded = json_decode($response, true);
      if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['success'])) {
        if ($this->isTransactionSuccessful($decoded['data'])) {
          return $decoded['data'];
        }
      }
    }

    throw new Exception("Transaction verification failed or inconclusive.");
  }

  private function isTransactionSuccessful($data)
  {
    // Treat the payment as successful only when:
    // success is true (checked by caller)
    // data.status === 'completed'
    // data.verified is truthy
    return (
      isset($data['status']) &&
      strtolower($data['status']) === 'completed' &&
      !empty($data['verified'])
    );
  }
}
?>