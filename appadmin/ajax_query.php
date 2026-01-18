<?php
include 'adminquery.php';
try {

    // Check if selectedZoneCode is provided in the POST request
    if (isset($_POST['selectedZoneCode']) && !empty($_POST['selectedZoneCode'])) {
        $lgaCode = $_POST['selectedZoneCode'];
        
        // Select All Unallocated Schools in Zone
        $tblName = 'tbl_schoollist';
        $conditions = [
            'where' => [
                'lgaCode' =>  $lgaCode,
            ],
            'where_raw' => "centreNumber NOT IN (
                SELECT schoolCode FROM tbl_schoolallocation 
                WHERE examYear = " . $examYear['id'] . "
            )",
            'order_by' => 'SchoolName ASC',
        ];

        // Fetch the unallocated schools
        $unallocatedSchools = $model->getRows($tblName, $conditions);
        
        // Check if schools were found
        if (!empty($unallocatedSchools)) {
            // Output the schools as <option> elements for the dropdown
            echo '<option value="">Select Unallocated Schools</option>';
            foreach ($unallocatedSchools as $school) {
                echo '<option value="' . $school['centreNumber'] . '">' . $school['centreNumber']. " - ".$school['SchoolName'] . '</option>';
            }
        } else {
            // If no schools are found, return a message to the front-end
            echo '<option value="">No unallocated schools found for this zone.</option>';
        }
    } else {
        // If LGA code is missing, return an error message
        echo '<option value="">LGA code is required.</option>';
    }

} catch (Exception $e) {
    // Catch any exceptions and output the error
    echo '<option value="">An error occurred: ' . $e->getMessage() . '</option>';
}
?>
