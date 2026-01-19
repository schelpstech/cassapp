
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("recordCandidatesForm");

    // Stop execution if this is not the target form
    if (!form) {
        return;
    }

    const numCandidatesField = form.querySelector("#numCandidatesCaptured");
    const schoolTypeField    = form.querySelector("#schoolType");
    const remittanceField    = form.querySelector("#remittanceDue");
    const schoolNameField    = form.querySelector("#schoolName");

    // Safety check
    if (!numCandidatesField || !schoolTypeField || !remittanceField || !schoolNameField) {
        console.error("Required fields are missing in recordCandidatesForm.");
        return;
    }

    // Calculate remittance
    function calculateRemittance() {
        const numCandidates = parseInt(numCandidatesField.value, 10) || 0;
        const schoolType = schoolTypeField.value.trim();

        let ratePerCandidate = 0;

        if (schoolType === "Public") {
            ratePerCandidate = 280;
        } else if (schoolType === "Private") {
            ratePerCandidate = 130;
        } else {
            remittanceField.value = "₦0.00";
            return;
        }

        const remittanceDue = numCandidates * ratePerCandidate;

        remittanceField.value = remittanceDue.toLocaleString("en-NG", {
            style: "currency",
            currency: "NGN",
            minimumFractionDigits: 2
        });
    }

    // Update school type from selected school
    function updateSchoolType() {
        const selectedOption = schoolNameField.options[schoolNameField.selectedIndex];
        const schoolType = selectedOption?.dataset?.schoolType || "";

        schoolTypeField.value = schoolType;
        numCandidatesField.value = 0;

        calculateRemittance();
    }

    // Event bindings (scoped to this form only)
    numCandidatesField.addEventListener("input", calculateRemittance);
    schoolTypeField.addEventListener("input", calculateRemittance);
    schoolNameField.addEventListener("change", updateSchoolType);

});
