<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Change Password</strong></h3>
                    </div>

                    <form action="../../app/companyModule.php" method="post" id="changePasswordForm">
                        <div class="card-body">

                            <!-- Consultant User Code -->
                            <div class="form-group">
                                <label>Consultant User Code</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($consultantDetails['user_name'] ?? '') ?>" disabled>
                            </div>

                            <!-- Consultant Company -->
                            <div class="form-group">
                                <label>Consultant Company</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($consultantDetails['companyName'] ?? '') ?>" disabled>
                            </div>

                            <!-- Registered Email (NEW) -->
                            <div class="form-group">
                                <label>Registered Email Address</label>
                                <input type="email" id="userEmail" class="form-control"
                                    value="<?= htmlspecialchars($consultantDetails['contactEmail'] ?? '') ?>" readonly>
                            </div>

                            <!-- Request OTP -->
                            <div class="form-group">
                                <button type="button" id="requestOtpBtn" class="btn btn-ogun btn-block">
                                    Request OTP
                                </button>
                            </div>

                            <!-- OTP Input -->
                            <div class="form-group d-none" id="otpSection">
                                <label>Enter OTP</label>
                                <input type="text" id="otpInput" class="form-control" maxlength="6">
                                <button type="button" class="btn btn-success btn-sm mt-2" id="verifyOtpBtn">
                                    Verify OTP
                                </button>
                            </div>

                            <!-- PASSWORD SECTION (LOCKED UNTIL OTP VERIFIED) -->
                            <div id="passwordSection" class="d-none">

                                <!-- Old Password -->
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <div class="input-group">
                                        <input type="password" id="oldPassword" name="oldPassword" class="form-control" required>
                                        <div class="input-group-append togglePwd" data-target="oldPassword">
                                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="form-group">
                                    <label>New Password</label>
                                    <div class="input-group">
                                        <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                                        <div class="input-group-append togglePwd" data-target="newPassword">
                                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <small id="passwordHelp" class="form-text text-muted">
                                        Minimum 8 chars, uppercase, lowercase, number & symbol
                                    </small>
                                    <div id="passwordStrength"></div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                                        <div class="input-group-append togglePwd" data-target="confirmPassword">
                                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div id="passwordMatch"></div>
                                </div>

                                <!-- Submit -->
                                <button type="submit"
                                    name="new_user_password_credential"
                                    value="<?= $utility->inputEncode('user_password_editor_form'); ?>"
                                    class="btn btn-ogun btn-block">
                                    Update Login Credential
                                </button>

                            </div>

                        </div>
                    </form>

                    <div class="card-footer">
                        OTP verification is required before password change.
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>