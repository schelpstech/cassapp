<?php
if (!empty($SelectedCapturingRecords['schType'])) {
    $schtypeValue = ($SelectedCapturingRecords['schType'] == 1) ? 'Public' : (($SelectedCapturingRecords['schType'] == 2) ? 'Private' : '');
} else {
    $schtypeValue = 'Unspecified';
} ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-1">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Add Record of additional Candidates by in selected school</strong></h3>
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
                    <form action="../../app/paymentHandler.php" method="post" id="recordCandidatesForm">
                        <div class="card-body">
                            <!-- Select School Name -->
                            <div class="form-group">
                                <label for="addschoolName">Selected School Name:</label>
                                <div class="input-group">
                                    <select id="addschoolName" class="form-control" name="schoolName" readonly required="yes">
                                        <option value="<?php echo $SelectedCapturingRecords['centreNumber']; ?>">
                                            <?php echo $SelectedCapturingRecords['centreNumber'] . " - " . $SelectedCapturingRecords['SchoolName']; ?>
                                        </option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Selected School Type -->
                            <div class="form-group">
                                <label for="addschoolType">Selected School Type:</label>
                                <div class="input-group">
                                    <select id="addschoolType" class="form-control" name="schoolType" readonly required>
                                        <option value="<?php echo $schtypeValue; ?>">
                                            <?php echo $schtypeValue; ?>
                                        </option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-synagogue"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="addrecordedcaptured">Current Number of Cleared Candidates:</label>
                                <div class="input-group">
                                    <select id="addrecordedcaptured" class="form-control" readonly required="yes">
                                        <option value="<?php echo $SelectedCapturingRecords['numberCaptured']; ?>">
                                            <?php echo $utility->inputDecode($SelectedCapturingRecords['numberCaptured']) ?>
                                        </option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Number of Candidates Captured -->
                            <div class="form-group">
                                <label for="addnumCandidatesCaptured"> Number of Additional Candidates Captured:</label>
                                <div class="input-group">
                                    <input type="number" id="addnumCandidatesCaptured" class="form-control" name="numCandidatesCaptured"
                                        min="1" max="9999" required
                                        title="Please enter a number between 1 and 9999"
                                        placeholder="Enter number of candidates captured" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-users"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remittance Due -->
                            <div class="form-group">
                                <label for="addremittanceDue">Remittance Due @ &#8358;280 per Candidate:</label>
                                <div class="input-group">
                                    <input type="text" id="addremittanceDue" class="form-control" name="remittanceDue"
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
                                    <button type="submit" name="additionalCandidates" value=<?php echo $utility->inputEncode("clear_candidates"); ?>
                                        class="btn btn-info btn-block">Generate Clearance for Additional Candidates</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer">
                        This form is used to add to the number of candidates captured by school.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for Remittance Calculation -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const numCandidatesField = document.getElementById("addnumCandidatesCaptured");
        const schoolTypeField = document.getElementById("addschoolType");
        const remittanceField = document.getElementById("addremittanceDue");

        // Function to calculate remittance
        function calculateRemittance() {
            const numCandidates = parseInt(numCandidatesField.value) || 0; // Default to 0 if invalid
            const schoolType = schoolTypeField.value.trim(); // Get the school type

            // Determine the rate based on the school type
            let ratePerCandidate = 0;
            if (schoolType === "Public") {
                ratePerCandidate = 280; // Rate for Public schools
            } else if (schoolType === "Private") {
                ratePerCandidate = 130; // Rate for Private schools
            } else {
                remittanceField.value = "Invalid School Type"; // Handle unexpected school types
                return; // Exit if school type is invalid
            }

            // Calculate the remittance due
            const remittanceDue = numCandidates * ratePerCandidate;

            // Update the remittance field
            remittanceField.value = remittanceDue.toLocaleString("en-NG", {
                style: "currency",
                currency: "NGN",
                minimumFractionDigits: 2,
            });
        }

        // Add event listener to the number of candidates field
        if (numCandidatesField) {
            numCandidatesField.addEventListener("input", calculateRemittance);
        }
    });
</script>