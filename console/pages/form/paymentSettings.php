<?php
// Fetch existing settings
$settings = $model->getRows('tbl_payment_settings', ['return_type' => 'single']);
if (!$settings) {
  // Should have been created by setup script, but fallback
  $settings = ['public_key' => '', 'secret_key' => '', 'environment' => 'live', 'is_active' => 1];
}

// Determine Webhook URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$webhookUrl = "$protocol://$host/app/webhook.php";
?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Payment Gateway Settings (iNPAY)</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="../../appadmin/paymentSettingsModule.php" method="post">
          <input type="hidden" name="action" value="update_settings">

          <div class="form-group">
            <label for="public_key">Public Key</label>
            <input type="text" class="form-control" id="public_key" name="public_key" placeholder="pk_live_..."
              value="<?php echo htmlspecialchars($settings['public_key']); ?>" required>
          </div>

          <div class="form-group">
            <label for="secret_key">Secret Key</label>
            <input type="password" class="form-control" id="secret_key" name="secret_key" placeholder="sk_live_..."
              value="<?php echo htmlspecialchars($settings['secret_key']); ?>" required>
          </div>

          <div class="form-group">
            <label for="environment">Environment</label>
            <select class="form-control" id="environment" name="environment">
              <option value="live" <?php echo ($settings['environment'] == 'live') ? 'selected' : ''; ?>>Live</option>
              <option value="test" <?php echo ($settings['environment'] == 'test') ? 'selected' : ''; ?>>Test</option>
            </select>
          </div>

          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?php echo ($settings['is_active'] == 1) ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="is_active">Enable Payment Gateway</label>
            </div>
          </div>

          <div class="form-group">
            <label>Webhook URL</label>
            <div class="input-group">
              <input type="text" class="form-control" value="<?php echo $webhookUrl; ?>" readonly id="webhook-url">
              <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat" onclick="copyWebhook()">Copy</button>
              </span>
            </div>
            <small class="form-text text-muted">Use this URL in your iNPAY Dashboard Webhook settings.</small>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Settings</button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

<script>
  function copyWebhook() {
    var copyText = document.getElementById("webhook-url");
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    document.execCommand("copy");
    alert("Webhook URL copied to clipboard: " + copyText.value);
  }
</script>