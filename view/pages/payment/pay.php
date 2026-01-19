<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Process Payment - iNPAY</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f4f6f9;
      margin: 0;
    }

    .card {
      background: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
      width: 100%;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 1rem;
    }

    button:hover {
      background-color: #0056b3;
    }

    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #007bff;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="spinner"></div>
    <h3>Processing Payment...</h3>
    <p>Please wait while we initialize the secure checkout.</p>
    <button id="inpay-pay-button" style="display:none;">Pay Now</button>
  </div>

  <!-- iNPAY Inline SDK -->
  <script>
    (function () {
      // Configuration from PHP
      var config = {
        buttonId: 'inpay-pay-button',
        publicKey: '<?php echo $publicKey; ?>',
        amountKobo: <?php echo $amount; ?>,
        customer: {
          email: '<?php echo $email; ?>',
          firstName: '', // Optional/Not provided in current flow
          lastName: '',
          phone: ''
        },
        metadata: {
          reference: '<?php echo $transactionReference; ?>',
          callback_url: '<?php echo $callbackUrl; ?>'
        }
      // paymentMethods omitted to use backend configuration
    };

    var sdkPromise;

    function loadSdk() {
      if (window.iNPAY && typeof window.iNPAY.InpayCheckout === 'function') {
        return Promise.resolve(window.iNPAY.InpayCheckout);
      }

      if (!sdkPromise) {
        sdkPromise = new Promise(function (resolve, reject) {
          var script = document.createElement('script');
          script.src = 'https://js.inpaycheckout.com/v2/inline.js';
          script.onload = function () {
            if (window.iNPAY && typeof window.iNPAY.InpayCheckout === 'function') {
              resolve(window.iNPAY.InpayCheckout);
            } else {
              reject(new Error('iNPAY checkout initialisation failed.'));
            }
          };
          script.onerror = function () {
            reject(new Error('Unable to load iNPAY checkout script.'));
          };
          document.head.appendChild(script);
        });
      }

      return sdkPromise;
    }

    function launchCheckout() {
      loadSdk()
        .then(function (Checkout) {
          var checkout = new Checkout();
          checkout.checkout({
            apiKey: config.publicKey,
            amount: config.amountKobo,
            email: config.customer.email,
            metadata: JSON.stringify(config.metadata),
            // paymentMethods: config.paymentMethods, // Omitted
            onSuccess: function (reference) {
              // reference is either a string or object with a `reference` key
              var ref = typeof reference === 'object' ? reference.reference : reference;
              // Redirect to callback URL for server verification
              window.location.href = config.metadata.callback_url + '?reference=' + ref;
            },
            onFailure: function (error) {
              alert('Payment failed: ' + (error && error.message ? error.message : 'Unknown error'));
            },
            onExpired: function () {
              alert('Payment session expired. Please try again.');
            },
            onError: function (error) {
              alert('Payment error: ' + (error && error.message ? error.message : 'Unknown error'));
            }
          });
        })
        .catch(function (error) {
          alert(error.message || 'Unable to start payment.');
        });
    }

    // Auto-launch
    if (typeof Promise === 'undefined') {
      var polyfill = document.createElement('script');
      polyfill.src = 'https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js';
      polyfill.onload = launchCheckout;
      polyfill.onerror = launchCheckout;
      document.head.appendChild(polyfill);
    } else {
      launchCheckout();
    }

    }) ();
  </script>
</body>

</html>