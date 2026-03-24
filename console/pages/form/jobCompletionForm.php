<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-1">

                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Consultant Clearance Verification</strong></h3>
                    </div>

                    <?php if (!empty($consultantData))
                        $filePath = '../../storage/ancopps_letters/' . $consultantData['signedLetter'];
                    ?>

                    <div class="card-body">

                        <!-- Consultant Info -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>User Code:</strong><br>
                                <?php echo $consultantData['user_name']; ?>
                            </div>

                            <div class="col-md-6">
                                <strong>Company Name:</strong><br>
                                <?php echo $consultantData['companyName']; ?>
                            </div>

                            <div class="col-md-6 mt-2">
                                <strong>Phone:</strong><br>
                                <?php echo $consultantData['contactPhone']; ?>
                            </div>

                            <div class="col-md-6 mt-2">
                                <strong>Email:</strong><br>
                                <?php echo $consultantData['contactEmail']; ?>
                            </div>
                        </div>

                        <!-- Document Viewer -->
                        <div class="form-group">
                            <label><strong>Uploaded Signed Letter</strong></label>
                            <iframe src="<?php echo $filePath; ?>"
                                width="100%"
                                height="600px"
                                style="border:1px solid #ccc; border-radius:5px;">
                            </iframe>
                        </div>

                        <!-- Approval Form -->
                        <form action="../../appadmin/companyModule.php" method="POST">

                            <input type="hidden" name="consultant"
                                value="<?php echo $consultantData['userId']; ?>">

                            <div class="form-group">
                                <label>Admin Remark</label>
                                <textarea name="remark" class="form-control" rows="3"
                                    placeholder="Enter approval or rejection note..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit"
                                        name="approve_clearance"
                                        value="<?php echo $utility->inputEncode('approve_clearance_form'); ?>"
                                        class="btn btn-success btn-block">
                                        ✅ Approve (Set to 200)
                                    </button>
                                </div>

                                <div class="col-md-6">
                                    <button type="submit"
                                        name="reject_clearance"
                                        value="<?php echo $utility->inputEncode('reject_clearance_form'); ?>"
                                        class="btn btn-danger btn-block">
                                        ❌ Reject (Set to 100)
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>

                    <div class="card-footer text-muted">
                        Review the uploaded document carefully before approving or rejecting.
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>