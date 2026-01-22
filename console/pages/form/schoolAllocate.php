<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card card-primary shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-school mr-2"></i>
                            <strong>School Allocation</strong>
                        </h3>
                    </div>

                    <form action="../../appadmin/allocationModule.php" method="post">
                        <div class="card-body">

                            <!-- Zone Selection -->
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-map-marker-alt mr-1 text-primary"></i>
                                    Select Zone
                                </label>
                                <select
                                    class="form-control"
                                    name="schoolZone"
                                    id="schoolZone"
                                    onchange="fetchUnallocatedSchools()"
                                    required
                                >
                                    <option value="">-- Select School Zone --</option>
                                    <?php foreach ($zonelist as $data) { ?>
                                        <option value="<?php echo $data['waecCode'] ?>">
                                            <?php echo $data['waecCode'] . ' - ' . $data['lga']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- School Selection -->
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-building mr-1 text-success"></i>
                                    Select Unallocated Schools
                                </label>

                                <select
                                    class="form-control"
                                    name="schoolCode[]"
                                    id="schoolCode"
                                    multiple
                                    required
                                    style="min-height: 220px;"
                                >
                                    <option value="" disabled>
                                        Select schools from the list
                                    </option>
                                </select>

                                <small class="form-text text-muted mt-1">
                                    <i class="fas fa-info-circle"></i>
                                    Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple schools.
                                </small>
                            </div>

                            <!-- Consultant Selection -->
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-user-tie mr-1 text-warning"></i>
                                    Consultant in Charge
                                </label>
                                <select
                                    class="form-control"
                                    name="consultantId"
                                    required
                                >
                                    <option value="">-- Select Consultant --</option>
                                    <?php foreach ($consultantList as $data) { ?>
                                        <option value="<?php echo $data['userId']; ?>">
                                            <?php echo $data['user_name'] . ' - ' . $data['companyName']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-md-6 text-muted pt-2">
                                    <i class="fas fa-info-circle"></i>
                                    Allocate one or more schools to a consultant
                                </div>
                                <div class="col-md-6 text-right">
                                    <button
                                        type="submit"
                                        name="schoolAllocator"
                                        value="<?php echo $utility->inputEncode('school_profile_allocator_form'); ?>"
                                        class="btn btn-primary px-4"
                                        id="updateProfileButton"
                                    >
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Allocate Schools
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
