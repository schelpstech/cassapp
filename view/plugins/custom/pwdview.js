document.querySelectorAll(".togglePwd").forEach((btn) => {
  const changePwdForm = document.getElementById("changePasswordForm");
  // Stop execution if the form does not exist on the page
  if (!changePwdForm) return;
  btn.addEventListener("click", () => {
    const input = document.getElementById(btn.dataset.target);
    const icon = btn.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.replace("fa-eye-slash", "fa-eye");
    }
  });
});
