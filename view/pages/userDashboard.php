<?php
    $dueRemittance = isset($totalfigure) && is_numeric($totalfigure) ? htmlspecialchars($totalfigure) : 0;
    $paidRemittance = isset($totalRemittedfigure) && is_numeric($totalRemittedfigure) ? htmlspecialchars($totalRemittedfigure) : 0;
    $balanceRemittance =  intval($dueRemittance) - intval($paidRemittance);
?>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12">
                <div class="row g-4">

                    <!-- Remittance Due -->
                    <div class="col-lg-3 col-md-6">
                        <div class="ogun-card ogun-card-danger">
                            <div class="ogun-card-body">
                                <div class="ogun-card-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="ogun-card-content">
                                    <h3><?= $utility->money($dueRemittance); ?></h3>
                                    <p>Remittance Due</p>
                                </div>
                            </div>
                            <a href="../../app/paymentHandler.php?pageid=<?= $utility->inputEncode('bulkClearanceProcess'); ?>"
                               class="ogun-card-footer">
                                Pay Outstanding (<?= $utility->money($balanceRemittance); ?>)
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Remittance Paid -->
                    <div class="col-lg-3 col-md-6">
                        <div class="ogun-card ogun-card-success">
                            <div class="ogun-card-body">
                                <div class="ogun-card-icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <div class="ogun-card-content">
                                    <h3><?= $utility->money($paidRemittance); ?></h3>
                                    <p>Remittance Paid</p>
                                </div>
                            </div>
                            <a href="../../app/router.php?pageid=<?= $utility->inputEncode('transactionHistories'); ?>"
                               class="ogun-card-footer">
                                View Transactions
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Schools Allocated -->
                    <div class="col-lg-3 col-md-6">
                        <div class="ogun-card ogun-card-warning">
                            <div class="ogun-card-body">
                                <div class="ogun-card-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="ogun-card-content">
                                    <h3><?= intval($allocatedSchoolNum ?? 0); ?></h3>
                                    <p>Schools Allocated</p>
                                </div>
                            </div>
                            <a href="../../app/router.php?pageid=<?= $utility->inputEncode('schoolsAllocatedList'); ?>"
                               class="ogun-card-footer">
                                View Schools
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Schools Cleared -->
                    <div class="col-lg-3 col-md-6">
                        <div class="ogun-card ogun-card-info">
                            <div class="ogun-card-body">
                                <div class="ogun-card-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ogun-card-content">
                                    <h3><?= intval($clearedSchoolNum ?? 0); ?></h3>
                                    <p>Schools Cleared</p>
                                </div>
                            </div>
                            <a href="../../app/router.php?pageid=<?= $utility->inputEncode('consultantClearedSchools'); ?>"
                               class="ogun-card-footer">
                                Clearance Summary
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
