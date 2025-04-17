document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("auth_form");
    const usernameInput = form.querySelector("input[name='username']");
    const passwordInput = form.querySelector("input[name='password']");
    const createButton = document.getElementById("create_button");
  
    const maxUsernameLength = 15;
    const maxPasswordLength = 20;
  
    function validatelength(event) {
      const action = event.submitter?.value;
  
      if (usernameInput.value.length > maxUsernameLength) {
        usernameInput.value = usernameInput.value.substring(0, maxUsernameLength);
      }
  
      if (passwordInput.value.length > maxPasswordLength) {
        passwordInput.value = passwordInput.value.substring(0, maxPasswordLength);
      }
  
      if (action === "register") {
        if (usernameInput.value.length < 5) {
          alert("Username must be at least 5 characters long.");
          event.preventDefault();
          return;
        }
  
        if (passwordInput.value.length < 8) {
          alert("Password must be at least 8 characters long.");
          event.preventDefault();
        }
      }
    }
  
    form.addEventListener("submit", validatelength);
  });
  