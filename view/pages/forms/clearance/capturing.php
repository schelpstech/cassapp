<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-1">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Record Captured Candidates by School</strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" aria-label="Collapse Form">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" aria-label="Remove Form">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Form Starts -->
                    <form action="../../app/clearanceModule.php" method="post" id="recordCandidatesForm">
                        <div class="card-body">
                            <!-- Select School Name -->
                            <div class="form-group">
                                <label for="schoolName">Select School Name:</label>
                                <div class="input-group">
                                    <select id="schoolName" class="form-control" name="schoolName" required="yes">
                                        <option value="">Select School</option>
                                        <?php if (!empty($allocatedSchools)) : ?>
                                            <?php foreach ($allocatedSchools as $data) : ?>
                                                <option value="<?php echo htmlspecialchars($data['schoolCode'], ENT_QUOTES); ?>">
                                                    <?php echo htmlspecialchars($data['schoolCode'] . " - " . $data['SchoolName'], ENT_QUOTES); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="">No Schools Allocated</option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Number of Candidates Captured -->
                            <div class="form-group">
                                <label for="numCandidatesCaptured">School Type</label>
                                <div class="input-group">
                                    <input type="text" id="schoolType" readonly class="form-control" name="schoolType"
                                        required="yes" placeholder="" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Number of Candidates Captured -->
                            <div class="form-group">
                                <label for="numCandidatesCaptured">Number of Candidates Captured:</label>
                                <div class="input-group">
                                    <input type="number" id="numCandidatesCaptured" class="form-control" name="numCandidatesCaptured"
                                        min="1" max="9999" required="yes"
                                        title="Please enter a number between 1 and 9999"
                                        placeholder="Enter number of candidates captured" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-users"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remittance Due -->
                            <div class="form-group">
                                <label for="remittanceDue">Remittance Due @ &#8358;280 per Candidate for Public & &#8358;130 per Candidate for Private :</label>
                                <div class="input-group">
                                    <input type="text" id="remittanceDue" class="form-control" name="remittanceDue"
                                        readonly
                                        placeholder="Calculated remittance due" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-money-bill-wave"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <?php
                                    // Check the user's active status
                                    $isActive = isset($consultantDetails['activeStatus']) && $consultantDetails['activeStatus'] == 1;
                                    $redirectUrl = '../../app/router.php?pageid=' . $utility->inputEncode('consultantpwdMgr');
                                    ?>
                                    <div style="position: relative;">
                                        <button
                                            type="submit"
                                            name="recordCandidates"
                                            value="<?php echo $utility->inputEncode('record_candidates'); ?>"
                                            class="btn btn-info btn-block"
                                            id="submitRecordButton"
                                            <?php if (!$isActive) echo 'disabled'; ?>>
                                            Submit Record
                                        </button>

                                        <!-- Hidden clickable div for handling disabled button redirection -->
                                        <?php if (!$isActive): ?>
                                            <div
                                                id="redirectDivSubmitRecord"
                                                style="cursor: pointer; color: transparent; height: 100%; width: 100%; position: absolute; top: 0; left: 0;">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer">
                        This form is used to record the number of candidates captured by school.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const isActive = <?php echo $isActive ? 'true' : 'false'; ?>;
        const redirectUrl = "<?php echo $redirectUrl; ?>";

        if (!isActive) {
            const redirectDivSubmitRecord = document.getElementById("redirectDivSubmitRecord");
            redirectDivSubmitRecord.addEventListener("click", function () {
                alert("You need to change your password first.");
                window.location.href = redirectUrl;
            });
        }
    });
</script>