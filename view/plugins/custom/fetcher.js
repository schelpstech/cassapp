document.addEventListener("DOMContentLoaded", function () {
    const schoolNameField = document.getElementById("schoolName");
    const schoolTypeField = document.getElementById("schoolType");

    schoolNameField.addEventListener("change", function () {
        const selectedSchoolCode = this.value;

        if (selectedSchoolCode) {
            // Make an AJAX request to fetch the school type
            fetch('../../app/ajax_query.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ schoolCode: selectedSchoolCode }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Update the schoolType field
                        schoolTypeField.value = data.schoolType || "Unknown";
                    } else {
                        alert(data.message || "Error fetching school type");
                        schoolTypeField.value = "";
                    }
                })
                .catch((error) => {
                    console.error("Error fetching school type:", error);
                    alert("An error occurred. Please try again.");
                    schoolTypeField.value = "";
                });
        } else {
            schoolTypeField.value = ""; // Clear field if no school selected
        }
    });
});

function fetchUnallocatedSchools() {
    // Get the selected zone code
    var schoolZone = $("#schoolZone").val(); // Fetch the selected zone code
    var action = 'fetchUnallocatedSchools'; // Define the action to send to the server
    
    // Check if a zone has been selected
    if (schoolZone != "") {
        // Make the AJAX request to fetch the unallocated schools
        $.ajax({
            url: '../../appadmin/ajax_query.php', // URL of the PHP file handling the AJAX request
            method: "POST", // The HTTP request method
            data: {
                selectedZoneCode: schoolZone, // Send the selected zone code
                action: action, // Send the action type (fetching unallocated schools)
            },
            success: function (data) {
                // On success, update the #schoolCode dropdown with the returned data
                $("#schoolCode").html(data);
            },
            error: function (xhr, status, error) {
                // Handle any errors that occur during the AJAX request
                console.error("Error fetching unallocated schools:", error);
                alert("An error occurred while fetching the unallocated schools.");
            },
            cache: false // Prevent caching of the response
        });
    } else {
        // If no zone is selected, display an alert message
        alert("Please select a valid School Zone.");
    }
}
