<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-1">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong> Profile Consultant Company  </strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="../../appadmin/companyModule.php" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Consultant UserCode :</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" class="form-control" name="usercode" required="yes" value="<?php echo 'assoec'.substr(date("Y"),-2). ($cntUsers+2)?>" readonly/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Name :</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="companyName" required="yes" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Address - Full Address:</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="companyAddress" required="yes" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Contact -Phone Number:</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="contactPhone" required="yes"  />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Contact - Email Address:</label>
                                <div class="input-group date" id="commandAddress" data-target-input="nearest">
                                    <input type="text" class="form-control" name="contactEmail" required="yes"  />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <div>
                                        <button
                                            type="submit"
                                            name="profile_company_details"
                                            value="<?php echo $utility->inputEncode('company_profile_creator_form'); ?>"
                                            class="btn btn-info btn-block"
                                            id="updateProfileButton"
                                            >
                                            Create Consultant Company Profile 
                                        </button>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer">
                        This form is used to Create Consultant Company Profile
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
