<?php
$dueRemittance = isset($totalfigure) && is_numeric($totalfigure) ? htmlspecialchars($totalfigure) : 0;
$paidRemittance = isset($totalRemittedfigure) && is_numeric($totalRemittedfigure) ? htmlspecialchars($totalRemittedfigure) : 0;
$balanceRemittance =  intval($dueRemittance) - intval($paidRemittance);
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-1">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary" role="region" aria-label="Remittance Due">
                            <div class="inner">
                                <span>
                                    <h3><?php
                                        echo  $utility->number($profileSchools)
                                        ?></h3>
                                </span>
                                <p>Profiled Schools</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-synagogue"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info" role="region" aria-label="Remittance Paid">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($allocatedSchoolNum) && is_numeric($allocatedSchoolNum) ? intval($allocatedSchoolNum) : 0); ?></h3>
                                </span>
                                <p>Allocated Schools</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-synagogue"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success" role="region" aria-label="Schools Allocated">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($clearedSchoolNum) && is_numeric($clearedSchoolNum) ? intval($clearedSchoolNum) : 0); ?></h3>
                                    <p>Cleared Allocated</p>
                                </span>
                            </div>
                            <div class="icon">
                                <i class="fa fa-synagogue"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger" role="region" aria-label="Wallet Balance">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($unclearedSchoolNum) && is_numeric($unclearedSchoolNum) ? htmlspecialchars($unclearedSchoolNum) : 0); ?></h3>
                                </span>
                                <p>Pending Schools </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-synagogue"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary" role="region" aria-label="Remittance Due">
                            <div class="inner">
                                <h3><?php
                                    echo $utility->money($dueRemittance)
                                    ?></h3>
                                <p>Remittance Due</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success" role="region" aria-label="Remittance Paid">
                            <div class="inner">
                                <span>
                                    <h3><?php
                                        echo $utility->money($paidRemittance)
                                        ?></h3>
                                </span>
                                <p>Remittance Paid</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary" role="region" aria-label="Schools Allocated">
                            <div class="inner">
                                <span>
                                    <h3><?php
                                        echo $utility->money($totalamountPublic)
                                        ?></h3>
                                </span>
                                <p>Public Schools Remitted</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info" role="region" aria-label="Wallet Balance">
                            <div class="inner">
                                <span>
                                    <h3><?php
                                        echo $utility->money($totalamountPrivate)
                                        ?></h3>
                                </span>
                                <p> Private Schools Remitted</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-synagogue"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary" role="region" aria-label="Remittance Due">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($totalCandidate) && is_numeric($totalCandidate) ? htmlspecialchars($totalCandidate) : 0); ?></h3>
                                </span>
                                <p>Number of Candidates</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <a href="#" class="small-box-footer">

                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success" role="region" aria-label="Remittance Paid">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($totalRemittedNumber) && is_numeric($totalRemittedNumber) ? htmlspecialchars($totalRemittedNumber) : 0); ?></h3>
                                </span>
                                <p>Cleared Candidates</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary" role="region" aria-label="Schools Allocated">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($totalNumberPublic) && is_numeric($totalNumberPublic) ? htmlspecialchars($totalNumberPublic) : 0); ?></h3>
                                </span>
                                <p>Public School Candidates</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info" role="region" aria-label="Wallet Balance">
                            <div class="inner">
                                <span>
                                    <h3><?php echo $utility->number(isset($totalNumberPrivate) && is_numeric($totalNumberPrivate) ? htmlspecialchars($totalNumberPrivate) : 0); ?></h3>
                                </span>
                                <p>Private School Candidates</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-synagogue"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>