<?php
include '../include/header.php';
include '../include/nav.php';
include '../include/aside.php';

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h6>Page :: <?php echo $_SESSION['page_name'] ?> </h6>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><strong>Active Module</strong></li>
                        <li class="breadcrumb-item active"><?php echo $_SESSION['module'] ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php
            // Check if pageid exists in the GET request
            $pageId = isset($_GET['pageid']) ? $utility->inputDecode($_GET['pageid']) : '';

            switch ($pageId) {
                case 'consoleDashboard':
                    include './adminDashboard.php';
                    break;

                case 'consultantRecord':
                    include './form/consultantCreate.php';
                    include './report/consultantMgr.php';
                    break;

                case 'reportConsultantClearance':
                    include './report/reportConsultantClearance.php';
                    break;

                case 'modifyCapturing':
                    include './forms/clearance/modifyCapturing.php';
                    break;

                case 'manageschoollist':
                    include './form/schoolCreate.php';
                    include './report/listofschools.php';
                    break;

                case 'manageschoolallocation':
                    include './form/schoolAllocate.php';
                    include './report/allocatedSchoolList.php';
                    break;

                case 'addCandidates':
                    include './forms/clearance/addCandidates.php';
                    break;

                case 'reportTransactionHistory':
                    include './report/transactionRecords.php';
                    break;

                    case 'consultantClearedSchools':
                        include './report/clearanceSummary.php';
                        break;

                    case 'reportSchoolClearance':
                        include './report/clearanceSummary.php';
                        break;

                        case 'consultantpwdMgr':
                            include './forms/consultant/passwordmgr.php';
                            break;
                default:
                    include './adminDashboard.php';
                    break;
            }
            ?>
        </div>
    </section>
</div>
<?php
include '../include/footer.php';
?>