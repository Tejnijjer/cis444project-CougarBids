document.addEventListener("DOMContentLoaded", function () {
    const usernameInput = document.querySelector("#create_form input[name='username']");
    const passwordInput = document.querySelector("#create_form input[name='password']");
    const createButton = document.getElementById("create_button");

    // Set max character lengths
    const maxUsernameLength = 15;
    const maxPasswordLength = 20;

    function validatelength(event) {
        // Restrict username length
        if (usernameInput.value.length > maxUsernameLength) {
            usernameInput.value = usernameInput.value.substring(0, maxUsernameLength);
        }

        // Restrict password length
        if (passwordInput.value.length > maxPasswordLength) {
            passwordInput.value = passwordInput.value.substring(0, maxPasswordLength);
        }

        // Check username length
        if (usernameInput.value.length < 5) {
            alert("Username must be at least 5 characters long.");
            event.preventDefault(); // Prevent form submission
            return;
        }

        // Check password length
        if (passwordInput.value.length < 8) {
            alert("Password must be at least 8 characters long.");
            event.preventDefault(); // Prevent form submission
        }
    }

    createButton.addEventListener("click", validatelength);
});
