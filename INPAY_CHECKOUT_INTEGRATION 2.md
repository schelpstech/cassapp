# iNPAY Checkout Integration Guide

This guide captures the key details discovered while building the WHMCS gateway so you can reuse them in other platforms such as WordPress. It focuses on the iNPAY Checkout modal, the verification flow, and webhook processing.

## 1. Key Concepts

- **Amounts are submitted in kobo**: Multiply naira amounts by 100 (e.g. ₦109.65 → `10965`).
- **Supported currency**: The inline checkout currently accepts NGN.
- **Public vs secret keys**: The public key is used on the frontend when launching the modal; the secret key is required on the server for verification and webhook validation.
- **Idempotency**: Always guard against double-processing using the transaction reference supplied by iNPAY.

## 2. Loading the Checkout Modal

Include the inline SDK from `https://js.inpaycheckout.com/v2/inline.js`. Lazily loading the script keeps your pages fast and avoids duplicate requests.

```html
<button id="inpay-pay-button">Pay with iNPAY</button>
<script>
(function() {
  var config = {
    buttonId: 'inpay-pay-button',
    publicKey: 'pk_live_...',
    amountKobo: 10965,
    customer: {
      email: 'customer@example.com',
      firstName: 'Ada',
      lastName: 'Lovelace',
      phone: '+2348012345678'
    },
    metadata: {
      invoice_id: 12345,
      reference: '12345_1696000000_a1b2c3d4',
      gateway: 'custom-wordpress',
      callback_url: 'https://example.com/inpay/callback'
    },
    // V2 Update: Optional - Restrict payment methods
    // Available options: "card", "bank", "payid"
    // Leave undefined to use inpay settings from dashboard or set to "all" to show all available methods
    paymentMethods: ["card", "bank", "payid"]
  };

  var sdkPromise;

  function loadSdk() {
    if (window.iNPAY && typeof window.iNPAY.InpayCheckout === 'function') {
      return Promise.resolve(window.iNPAY.InpayCheckout);
    }

    if (!sdkPromise) {
      sdkPromise = new Promise(function(resolve, reject) {
        var script = document.createElement('script');
        script.src = 'https://js.inpaycheckout.com/v2/inline.js';
        script.onload = function() {
          if (window.iNPAY && typeof window.iNPAY.InpayCheckout === 'function') {
            resolve(window.iNPAY.InpayCheckout);
          } else {
            reject(new Error('iNPAY checkout initialisation failed.'));
          }
        };
        script.onerror = function() {
          reject(new Error('Unable to load iNPAY checkout script.'));
        };
        document.head.appendChild(script);
      });
    }

    return sdkPromise;
  }

  function launchCheckout() {
    loadSdk()
      .then(function(Checkout) {
        var checkout = new Checkout();
        checkout.checkout({
          apiKey: config.publicKey,
          amount: config.amountKobo,
          email: config.customer.email,
          firstName: config.customer.firstName,
          lastName: config.customer.lastName,
          metadata: JSON.stringify(config.metadata),
          paymentMethods: config.paymentMethods,
          onSuccess: function(reference) {
            // reference is either a string or object with a `reference` key
            var ref = typeof reference === 'object' ? reference.reference : reference;
            verifyPayment(ref, config.metadata.invoice_id);
          },
          onFailure: function(error) {
            alert('Payment failed: ' + (error && error.message ? error.message : 'Unknown error'));
          },
          onExpired: function() {
            alert('Payment session expired. Please try again.');
          },
          onError: function(error) {
            alert('Payment error: ' + (error && error.message ? error.message : 'Unknown error'));
          }
        });
      })
      .catch(function(error) {
        alert(error.message || 'Unable to start payment.');
      });
  }

  document.getElementById(config.buttonId).addEventListener('click', function() {
    if (!config.amountKobo || config.amountKobo <= 0) {
      alert('Invalid payment amount.');
      return;
    }

    if (typeof Promise === 'undefined') {
      // Provide a Promise polyfill for legacy browsers
      var polyfill = document.createElement('script');
      polyfill.src = 'https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js';
      polyfill.onload = launchCheckout;
      polyfill.onerror = launchCheckout;
      document.head.appendChild(polyfill);
    } else {
      launchCheckout();
    }
  });

  function verifyPayment(reference, invoiceId) {
    // Example: POST to your server to trigger verification
    fetch('https://example.com/inpay/verify', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ reference: reference, invoice_id: invoiceId })
    }).then(function(response) {
      return response.json();
    }).then(function(result) {
      if (result.success) {
        window.location.reload();
      }
    });
  }
})();
</script>
```

## 2.1 V2 Checkout Modal Updates

The V2 update introduces enhanced control over the payment experience, allowing you to specify which payment methods are available to the customer.

### Payment Methods
You can control the displayed payment methods using the `paymentMethods` configuration option. This defaults to showing all available methods if omitted.

**Available Options:**
- `"card"`: Credit and Debit card payments
- `"bank"`: Bank transfer
- `"payid"`: PayID and QR code payments

**Usage Examples:**

1. **Show all methods (Default):**
   ```javascript
   // omit paymentMethods or set to "all"
   // or
   paymentMethods: "all"
   ```

2. **Card Only:**
   ```javascript
   paymentMethods: ["card"]
   ```

3. **Bank Transfer & PayID Only:**
   ```javascript
   paymentMethods: ["bank", "payid"]
   ```

## 3. Performing Server-Side Verification

The server must confirm payment status using your secret key. The API exposes two endpoints:

1. `GET https://api.inpaycheckout.com/api/v1/developer/transaction/status?reference=REFERENCE`
2. `POST https://api.inpaycheckout.com/api/v1/developer/transaction/verify`

Always send the `Authorization: Bearer <secretKey>` header and `Accept: application/json`. If the GET call fails or returns a non-200 status code, retry with the POST endpoint.

The response shape used in the WHMCS module looked like:

```json
{
  "success": true,
  "data": {
    "status": "completed",
    "verified": true,
    "amount": 10965,
    "reference": "12345_1696000000_a1b2c3d4",
    "metadata": {
      "invoice_id": 12345,
      "reference": "12345_1696000000_a1b2c3d4"
    }
  }
}
```

Treat the payment as successful only when:

- `success` is `true`
- `data.status === 'completed'`
- `data.verified` is truthy (boolean true or string/integer equivalent)

### Sample PHP Verification Handler

```php
<?php
function verifyInpayTransaction(string $reference, string $secretKey): array
{
    $headers = [
        'Authorization: Bearer ' . $secretKey,
        'Accept: application/json',
    ];

    $attempts = [
        ['method' => 'GET', 'url' => 'https://api.inpaycheckout.com/api/v1/developer/transaction/status?reference=' . rawurlencode($reference)],
        ['method' => 'POST', 'url' => 'https://api.inpaycheckout.com/api/v1/developer/transaction/verify', 'payload' => json_encode(['reference' => $reference])],
    ];

    foreach ($attempts as $attempt) {
        $ch = curl_init($attempt['url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => $attempt['method'],
            CURLOPT_HTTPHEADER => array_merge($headers, isset($attempt['payload']) ? ['Content-Type: application/json'] : []),
        ]);

        if (isset($attempt['payload'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $attempt['payload']);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            continue; // try the next method
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode !== 200) {
            continue;
        }

        $decoded = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['success'])) {
            return $decoded['data'];
        }
    }

    return [];
}

$transaction = verifyInpayTransaction($_POST['reference'], $secretKey);

$isCompleted = !empty($transaction)
    && strtolower($transaction['status'] ?? '') === 'completed'
    && in_array($transaction['verified'] ?? false, [true, 'true', 1, '1'], true);

if ($isCompleted) {
    // Mark order as paid, deliver goods/services, etc.
}
```

## 4. Webhook Handling

iNPAY signs each webhook with an `X-Webhook-Signature` header. The value starts with `sha256=` followed by an HMAC SHA-256 hash of the raw request body using your secret key. You will also receive `X-Webhook-Timestamp` (milliseconds) and `X-Webhook-Event`.

### Validation Steps

1. Ensure the timestamp is within a 5-minute tolerance to mitigate replay attacks.
2. Strip the leading `sha256=` from the signature header and compute `hash_hmac('sha256', $payload, $secretKey)`.
3. Compare using `hash_equals` to avoid timing attacks.

### Sample PHP Webhook Endpoint

```php
<?php
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$timestamp = (int) ($_SERVER['HTTP_X_WEBHOOK_TIMESTAMP'] ?? 0);
$eventName = $_SERVER['HTTP_X_WEBHOOK_EVENT'] ?? '';

$now = (int) round(microtime(true) * 1000);
$allowedSkew = 5 * 60 * 1000; // 5 minutes in ms

if (abs($now - $timestamp) > $allowedSkew) {
    http_response_code(400);
    exit('Invalid timestamp');
}

$expectedSignature = hash_hmac('sha256', $payload, $secretKey);
$cleanSignature = preg_replace('/^sha256=/', '', $signature);

if (!hash_equals($expectedSignature, $cleanSignature)) {
    http_response_code(401);
    exit('Invalid signature');
}

$data = json_decode($payload, true);
if (json_last_error() !== JSON_ERROR_NONE || empty($data['event'])) {
    http_response_code(400);
    exit('Malformed event');
}

$completionEvents = [
    'payment.virtual_payid.completed',
    'payment.checkout_payid.completed',
    'payment.virtual_account.completed',
    'payment.checkout_virtual_account.completed',
];

if (in_array($data['event'], $completionEvents, true)) {
    $transaction = $data['data'] ?? [];
    $metadata = $transaction['metadata'] ?? [];

    // Metadata may be JSON encoded string or an object
    if (is_string($metadata)) {
        $decoded = json_decode($metadata, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $metadata = $decoded;
        }
    }

    $invoiceId = $metadata['invoice_id'] ?? null;
    $reference = $metadata['reference'] ?? $transaction['reference'] ?? null;

    if ($reference) {
        $verification = verifyInpayTransaction($transaction['reference'] ?? $reference, $secretKey);

        $isComplete = !empty($verification)
            && strtolower($verification['status'] ?? '') === 'completed'
            && in_array($verification['verified'] ?? false, [true, 'true', 1, '1'], true);

        if ($isComplete) {
            // Mark the invoice/order as paid here using $invoiceId & $reference
        }
    }
}

http_response_code(200);
exit('OK');
```

## 5. Recommended Metadata

Embedding metadata in the checkout request helps correlate webhook and verification responses with your application. The WHMCS integration used the following structure:

```json
{
  "invoice_id": 12345,
  "gateway": "your-platform-name",
  "reference": "12345_1696000000_a1b2c3d4",
  "phone": "+2348012345678",
  "callback_url": "https://example.com/inpay/callback"
}
```

Feel free to augment this with tenant IDs, cart IDs, or anything else you need to reconcile payments. Keep the payload small (under 1 KB) and avoid sensitive data.

## 6. Operational Tips

- **Retry logic**: Implement retry/backoff when polling your verification endpoint from the front end or when handling webhooks.
- **Duplicate protection**: Persist processed references to guard against double credits.
- **Logging**: Provide a configuration toggle to enable verbose logging during testing without overwhelming production logs.
- **Error messaging**: Surface user-friendly errors on the frontend but log the detailed messages on the backend.
- **Environment separation**: Store keys securely and consider staging vs production keys to avoid accidental live charges during development.

With these patterns, you should be able to build an iNPAY Checkout integration in WordPress (or any other framework) that matches the robustness of the WHMCS add-on.
