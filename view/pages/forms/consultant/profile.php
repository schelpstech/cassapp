<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-1">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong> Consultant Company Profile </strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="../../app/companyModule.php" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Consultant UserCode :</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" disabled class="form-control" value="<?php if (!empty($consultantDetails['user_name'])) {
                                                                                                echo $consultantDetails['user_name'];
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?>" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Name :</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="companyName" required="yes" value="<?php if (!empty($consultantDetails['companyName'])) {
                                                                                                                            echo $consultantDetails['companyName'];
                                                                                                                        } else {
                                                                                                                            echo "";
                                                                                                                        } ?>" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Address - Full Address:</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="companyAddress" required="yes" value="<?php if (!empty($consultantDetails['companyAddress'])) {
                                                                                                                            echo $consultantDetails['companyAddress'];
                                                                                                                        } else {
                                                                                                                            echo "";
                                                                                                                        } ?>" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Contact -Phone Number:</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="contactPhone" required="yes" value="<?php if (!empty($consultantDetails['contactPhone'])) {
                                                                                                                            echo $consultantDetails['contactPhone'];
                                                                                                                        } else {
                                                                                                                            echo "";
                                                                                                                        } ?>" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Contact - Email Address:</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="contactEmail" required="yes" value="<?php if (!empty($consultantDetails['contactEmail'])) {
                                                                                                                            echo $consultantDetails['contactEmail'];
                                                                                                                        } else {
                                                                                                                            echo "";
                                                                                                                        } ?>" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <?php
                                    // Assuming $userExists['activeStatus'] is already fetched

                                    $isActive = isset($consultantDetails['activeStatus']) && $consultantDetails['activeStatus'] == 1;
                                    $redirectUrl = '../../app/router.php?pageid=' . $utility->inputEncode('consultantpwdMgr');
                                    ?>
                                    <div>
                                        <button
                                            type="submit"
                                            name="edit_company_details"
                                            value="<?php echo $utility->inputEncode('company_profile_editor_form'); ?>"
                                            class="btn btn-ogun btn-block"
                                            id="updateProfileButton"
                                            <?php if (!$isActive) echo 'disabled'; ?>>
                                            Update Consultant Company Profile Details
                                        </button>


                                        <!-- Hidden clickable div for handling disabled button redirection -->

                                        <?php if (!$isActive): ?>
                                            <div id="redirectDiv" class="form-disabled-overlay"></div>
                                        <?php endif; ?>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer">
                        This form is used to Edit Consultant Company Profile
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const isActive = <?php echo $isActive ? 'true' : 'false'; ?>;
        const redirectUrl = "<?php echo $redirectUrl; ?>";

        if (!isActive) {
            const redirectDiv = document.getElementById("redirectDiv");
            redirectDiv.addEventListener("click", function() {
                alert("You need to change your password first.");
                window.location.href = redirectUrl;
            });
        }
    });
</script>