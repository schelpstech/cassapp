<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-2">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong> School Profile Create </strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="../../appadmin/schoolModule.php" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Centre Number :</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" class="form-control" name="schoolCode" minlength="7" maxlength="7" required="yes" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>School Name :</label>
                                <div class="input-group date" id="schoolName" data-target-input="nearest">
                                    <input type="text" class="form-control" name="schoolName" required="yes" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-synagogue"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Select Zone:</label>
                                <div class="input-group date" id="schoolZone" data-target-input="nearest">
                                    <select type="text" class="form-control" name="schoolZone" required="yes">
                                        <option> Select School Zone</option>
                                        <?php
                                        $count = 1;
                                        foreach ($zonelist as $data) {
                                        ?>
                                            <option value="<?php echo $data['waecCode'] ?>"> <?php echo $data['waecCode'] . " - " . $data['lga']  ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Select School Type</label>
                                <div class="input-group date" id="schoolType" data-target-input="nearest">
                                    <select type="text" class="form-control" name="schoolType" required="yes">
                                        <option> Select School Type</option>
                                        <option value="2"> Privately Owned School</option>
                                        <option value="1"> Public School </option>
                                    </select>
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
                                            name="profile_school_details"
                                            value="<?php echo $utility->inputEncode('school_profile_creator_form'); ?>"
                                            class="btn btn-info btn-block"
                                            id="updateProfileButton">
                                            Create School Profile
                                        </button>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer">
                        This form is used to Create School Profile
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>