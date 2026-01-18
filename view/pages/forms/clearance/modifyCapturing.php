<?php
if (!empty($SelectedCapturingRecords['schType'])) {
    $schtypeValue = ($SelectedCapturingRecords['schType'] == 1) ? 'Public' : (($SelectedCapturingRecords['schType'] == 2) ? 'Private' : '');
} else {
    $schtypeValue = 'Unspecified';
} ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Modify Record of Captured Candidates for Selected School</strong></h3>
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
                            <!-- Selected School Name -->
                            <div class="form-group">
                                <label for="modifyschoolName">Selected School Name:</label>
                                <div class="input-group">
                                    <select id="modifyschoolName" class="form-control" name="schoolName" readonly required>
                                        <option value="<?php echo $SelectedCapturingRecords['centreNumber']; ?>">
                                            <?php echo $SelectedCapturingRecords['centreNumber'] . " - " . $SelectedCapturingRecords['SchoolName']; ?>
                                        </option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-synagogue"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected School Type -->
                            <div class="form-group">
                                <label for="modifyschoolType">Selected School Type:</label>
                                <div class="input-group">
                                    <select id="modifyschoolType" class="form-control" name="schoolType" readonly required>
                                        <option value="<?php echo $schtypeValue; ?>">
                                            <?php echo $schtypeValue; ?>
                                        </option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-synagogue"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Number of Candidates Captured -->
                            <div class="form-group">
                                <label for="modifynumCandidatesCaptured">Number of Candidates Captured:</label>
                                <div class="input-group">
                                    <input type="number" id="modifynumCandidatesCaptured" class="form-control" 
                                           name="numCandidatesCaptured" min="1" max="9999" required
                                           value="<?php echo $utility->inputDecode($SelectedCapturingRecords['numberCaptured']); ?>" 
                                           placeholder="Enter number of candidates captured" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Remittance Due -->
                            <div class="form-group">
                                <label for="modifyremittanceDue">Remittance Due:</label>
                                <div class="input-group">
                                    <input type="text" id="modifyremittanceDue" class="form-control" name="remittanceDue" 
                                           readonly 
                                           value="<?php echo $utility->inputDecode($SelectedCapturingRecords['amountdue']); ?>" 
                                           placeholder="Calculated remittance due" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="submit" name="updateCandidates" 
                                            value="<?php echo $utility->inputEncode('update_candidates'); ?>" 
                                            class="btn btn-info">Update Record</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer">
                        This form is used to modify the number of candidates captured by school.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for Remittance Calculation -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const numCandidatesField = document.getElementById("modifynumCandidatesCaptured");
        const schoolTypeField = document.getElementById("modifyschoolType");
        const remittanceField = document.getElementById("modifyremittanceDue");

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
