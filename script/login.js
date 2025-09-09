document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registrationForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");

  // Email regex for standard structure
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Common disposable email domains
  const blockedDomains = [
    "tempmail.com", "10minutemail.com", "guerrillamail.com", "mailinator.com",
    "yopmail.com", "throwawaymail.com", "fakeinbox.com"
  ];

  // Password regex (secure)
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

  form.addEventListener("submit", (e) => {
    let valid = true;
    emailError.textContent = "";
    passwordError.textContent = "";

    // ✅ Email Validation
    const emailValue = emailInput.value.trim();
    if (!emailRegex.test(emailValue)) {
      emailError.textContent = "Invalid email format.";
      valid = false;
    } else {
      const domain = emailValue.split("@")[1].toLowerCase();
      if (blockedDomains.includes(domain)) {
        emailError.textContent = "Disposable emails are not allowed.";
        valid = false;
      }
    }

    // ✅ Password Validation
    if (!passwordRegex.test(passwordInput.value)) {
      passwordError.textContent = "Password must be 8+ chars with upper, lower, number & symbol.";
      valid = false;
    }

    if (!valid) e.preventDefault();
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const togglePasswordIcons = document.querySelectorAll(".toggle-password");

  togglePasswordIcons.forEach(icon => {
    icon.addEventListener("click", () => {
      const targetId = icon.getAttribute("data-target");
      const passwordField = document.getElementById(targetId);
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    });
  });
});