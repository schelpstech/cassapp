
document.addEventListener("DOMContentLoaded", () => {
  const changePwdForm = document.getElementById("changePasswordForm");
  if (!changePwdForm) return;

  const requestOtpBtn = changePwdForm.querySelector("#requestOtpBtn");
  const verifyOtpBtn = changePwdForm.querySelector("#verifyOtpBtn");
  const otpInput = changePwdForm.querySelector("#otpInput");
  const otpSection = changePwdForm.querySelector("#otpSection");
  const passwordSection = changePwdForm.querySelector("#passwordSection");

  /* =========================
     REQUEST OTP
  ========================== */
  requestOtpBtn?.addEventListener("click", () => {
    // Disable button and update text
    requestOtpBtn.disabled = true;
    const originalText = requestOtpBtn.textContent;
    requestOtpBtn.textContent = "Sending OTP...";

    fetch("../../app/otpHandler.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "sendOtp" }),
    })
      .then(r => r.json())
      .then(d => {
        alert(d.message);

        if (d.status === "success") {
          otpSection.classList.remove("d-none");
        } else {
          // Re-enable if failed
          requestOtpBtn.disabled = false;
          requestOtpBtn.textContent = originalText;
        }
      })
      .catch(err => {
        console.error("OTP request error:", err);
        requestOtpBtn.disabled = false;
        requestOtpBtn.textContent = originalText;
        alert("Failed to send OTP. Please try again.");
      });
  });

  /* =========================
     VERIFY OTP
  ========================== */
  verifyOtpBtn?.addEventListener("click", () => {
    const otp = otpInput.value.trim();

    if (!otp) {
      alert("Please enter the OTP");
      return;
    }

    // Disable verify button
    verifyOtpBtn.disabled = true;
    const originalText = verifyOtpBtn.textContent;
    verifyOtpBtn.textContent = "Verifying...";

    fetch("../../app/otpHandler.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "verifyOtp", otp }),
    })
      .then(r => r.json())
      .then(d => {
        alert(d.message);

        if (d.status === "success") {
          passwordSection.classList.remove("d-none");
          // Keep verify button disabled after success
          verifyOtpBtn.textContent = "Verified";
        } else {
          // Re-enable if verification fails
          verifyOtpBtn.disabled = false;
          verifyOtpBtn.textContent = originalText;
        }
      })
      .catch(err => {
        console.error("OTP verification error:", err);
        verifyOtpBtn.disabled = false;
        verifyOtpBtn.textContent = originalText;
        alert("OTP verification failed. Please try again.");
      });
  });
});