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
                    <form action="../../appadmin/allocationModule.php" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Select Zone:</label>
                                <div class="input-group date"  data-target-input="nearest">
                                    <select type="text" class="form-control" name="schoolZone" id="schoolZone" onchange="fetchUnallocatedSchools()" required="yes">
                                        <option value="">Select School Zone</option>
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
                                <label>Select Unallocated School</label>
                                <div class="input-group date"  data-target-input="nearest">
                                    <select type="text" class="form-control" name="schoolCode" id="schoolCode" required="yes">
                                        <option value="">Select  School</option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-map-signs"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Select Consultant in charge</label>
                                <div class="input-group date" id="consultantType" data-target-input="nearest">
                                    <select type="text" class="form-control" name="consultantId" required="yes">
                                        <option value="">Select Consultant</option>
                                        <?php
                                        $count = 1;
                                        foreach ($consultantList as $data) {
                                        ?>
                                            <option value="<?php echo $data['userId'] ?>"> <?php echo $data['user_name'] . " - " . $data['companyName']  ?></option>
                                        <?php
                                        }
                                        ?>
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
                                            name="schoolAllocator"
                                            value="<?php echo $utility->inputEncode('school_profile_allocator_form'); ?>"
                                            class="btn btn-info btn-block"
                                            id="updateProfileButton">
                                            Allocate School to Consultant
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


