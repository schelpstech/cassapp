 <?php
    if ($consultantDetails['workStatus'] == 200) {
        $utility->redirectWithNotification(
            'success',
            'Hello! Your job completion report has been activated.',
            'consultantClearedSchools'
        );
        exit;
    }

    if ($consultantDetails['workStatus'] == 300) {
        $utility->redirectWithNotification(
            'danger',
            'Error! Your job completion submission is pending verification.',
            'consultantDashboard'
        );
        exit;
    }
    $lgas = array_column($clearedSchoolRecords, 'lga');

    $uniqueLgas = array_map(
        'ucwords',
        array_unique(
            array_map(
                'strtolower',
                array_map(
                    'trim',
                    explode(',', implode(',', $lgas))
                )
            )
        )
    );

    // Final output
    $lga = implode(' / ', $uniqueLgas);

    // Final output
    $zone = $lga;
    $zoneCode = strtoupper(substr($zone, 0, 3));
    $consultantCode = substr(md5($consultantDetails['companyName']), 0, 4);
    $code = $_SESSION['active'];
    $verificationCode = strtoupper("WASSCE-$zoneCode-$consultantCode-$code");

    ?>
 <section class="content mt-4">
     <div class="container-fluid">
         <div class="row justify-content-center">
             <div class="col-xl-9">

                 <div class="card shadow border-0 position-relative">

                     <!-- WATERMARK -->
                     <div class="watermark"></div>

                     <div class="card-body">

                         <!-- OFFICIAL HEADER -->
                         <div class="text-center letter-header">

                             <img src="../../storage/app/moest.jpg" width="90">

                             <h5 class="mt-2 fw-bold text-uppercase">
                                 Ministry of Education Science & Technology
                             </h5>

                             <h6 class="fw-bold text-uppercase">
                                 WASSCE 2026 Biometrics & e-Registration Exercise
                             </h6>

                             <p class="mb-0 small">
                                 Official ANCOPPS Zone Candidate Passport Photograph Delivery Confirmation
                             </p>

                             <hr class="header-line">

                         </div>


                         <!-- VERIFICATION BLOCK -->

                         <div class="row mb-4">

                             <div class="col-md-8">

                                 <table class="table table-sm table-borderless">

                                     <tr>
                                         <th width="35%">Consultant Name:</th>
                                         <td><strong><?php echo htmlspecialchars($consultantDetails['companyName']); ?></strong></td>
                                     </tr>

                                     <tr>
                                         <th>Assigned Zone:</th>
                                         <td><strong><?php echo htmlspecialchars($zone); ?></strong></td>
                                     </tr>

                                     <tr>
                                         <th>Date Generated:</th>
                                         <td><?php echo date("d F Y"); ?></td>
                                     </tr>

                                     <tr>
                                         <th>Verification Code:</th>
                                         <td>
                                             <strong><?php echo $verificationCode; ?></strong>
                                         </td>
                                     </tr>

                                 </table>

                             </div>

                             <div class="col-md-4 text-end">
                                 <div id="qrVerify"></div>
                                 <p class="small text-muted mt-2">
                                     Scan to verify authenticity
                                 </p>
                             </div>

                         </div>


                         <!-- LETTER BODY -->

                         <div class="letter-body">

                             <p>
                                 The Chairman<br>
                                 <strong>ANCOPPS – <?php echo htmlspecialchars($zone); ?> Zone</strong>
                             </p>

                             <p class="mt-4">
                                 Dear Sir/Madam,
                             </p>

                             <p class="mt-3 text-justify">

                                 This letter serves as an official confirmation request regarding the distribution of
                                 passport photographs printed for candidates participating in the
                                 <strong>WASSCE 2026 Biometrics & e-Registration Exercise</strong>.

                                 The consultant listed above has reported that all passport photographs were successfully
                                 printed and delivered to the secondary schools within the
                                 <strong><?php echo htmlspecialchars($zone); ?> Zone</strong>.

                                 You are kindly requested to verify this claim by completing the endorsement section below.
                                 Your signature, official designation, and ANCOPPS stamp will serve as confirmation that
                                 the consultant has fulfilled the required delivery obligations within your zone.

                             </p>


                         </div>


                         <!-- CONFIRMATION TABLE -->

                         <div class="mt-5">

                             <table class="table table-bordered">

                                 <tr>
                                     <th width="35%">Chairman's Full Name</th>
                                     <td height="50"></td>
                                 </tr>

                                 <tr>
                                     <th>School Name</th>
                                     <td height="50"></td>
                                 </tr>

                                 <tr>
                                     <th>Official Position</th>
                                     <td height="50"></td>
                                 </tr>

                                 <tr>
                                     <th>Phone Number</th>
                                     <td height="50"></td>
                                 </tr>

                                 <tr>
                                     <th>ANCOPPS Zone</th>
                                     <td><?php echo htmlspecialchars($zone); ?></td>
                                 </tr>

                             </table>

                         </div>


                         <!-- SIGNATURE SECTION -->

                         <div class="row mt-5 text-center">

                             <div class="col-md-4">
                                 <p><strong>Signature</strong></p>
                                 <div class="signature-line"></div>
                             </div>

                             <div class="col-md-4">
                                 <p><strong>Date</strong></p>
                                 <div class="signature-line"></div>
                             </div>

                             <div class="col-md-4">
                                 <p><strong>Official ANCOPPS / School Stamp</strong></p>
                                 <div class="stamp-box"></div>
                             </div>

                         </div>


                         <!-- FOOTER -->

                         <div class="letter-footer mt-5">

                             <hr>

                             <p class="small text-muted text-center">

                                 This document forms part of the official clearance documentation for the
                                 WASSCE 2026 Biometrics & e-Registration Exercise.
                                 Any falsification or unauthorized alteration of this document is prohibited.

                                 Verification Code: <strong><?php echo $verificationCode; ?></strong>

                             </p>

                         </div>


                         <!-- PRINT BUTTON -->

                         <div class="text-end mt-4 no-print">
                             <button onclick="window.print()" class="btn btn-dark">
                                 Print Official Letter
                             </button>

                             <button class="btn btn-success" data-toggle="modal" data-target="#uploadModal" role="button">
                                 Upload Signed Copy
                             </button>
                         </div>


                     </div>
                 </div>
             </div>
         </div>
     </div>

 </section>
 <div class="modal fade" id="uploadModal">
     <div class="modal-dialog">
         <div class="modal-content">

             <form method="POST" action="../../app/jobcompletion.php" enctype="multipart/form-data">

                 <div class="modal-header">
                     <h5>Upload Signed & Stamped Letter</h5>
                 </div>

                 <div class="modal-body">

                     <input type="file" name="signedLetter" class="form-control" required>

                     <input type="hidden" name="verificationCode"
                         value="<?php echo $verificationCode; ?>">

                 </div>

                 <div class="modal-footer">
                     <button class="btn btn-primary">Upload</button>
                 </div>

             </form>

         </div>
     </div>
 </div>
 <style>
     .watermark {
         position: absolute;
         top: 50%;
         left: 50%;
         width: 450px;
         height: 450px;
         background: url('../../storage/app/moest.jpg') no-repeat center;
         background-size: contain;
         opacity: 0.05;
         transform: translate(-50%, -50%);
         z-index: 0;
     }

     .card-body {
         position: relative;
         z-index: 2;
     }

     .letter-header {
         text-align: center;
     }

     .header-line {
         border-top: 2px solid #000;
         width: 80%;
         margin: auto;
     }

     .letter-body {
         font-size: 16px;
         line-height: 1.8;
         text-align: justify;
     }

     .signature-line {
         border-bottom: 1px solid #000;
         height: 50px;
     }

     .stamp-box {
         border: 2px dashed #555;
         height: 70px;
     }

     .letter-footer {
         font-size: 12px;
     }

     @media print {

         body {
             -webkit-print-color-adjust: exact;
         }

         .no-print {
             display: none !important;
         }

         @page {
             size: A4 portrait;
             margin: 0mm;
         }

     }
 </style>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

 <script>
     var verifyData = `
Verification Code: <?php echo $verificationCode; ?>

Consultant:
<?php echo $consultantDetails['companyName']; ?>

Zone:
<?php echo $zone; ?>

Generated:
<?php echo date("d F Y"); ?>

`;

     new QRCode(document.getElementById("qrVerify"), {
         text: verifyData,
         width: 110,
         height: 110
     });
 </script>