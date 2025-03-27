document.addEventListener("DOMContentLoaded", function () {
    const usernameInput = document.querySelector("input[type='text']");
    const passwordInput = document.querySelector("input[type='password']");
    
    // Set max character lengths
    const maxUsernameLength = 15;
    const maxPasswordLength = 20;

    // Restrict username input length
    usernameInput.addEventListener("input", function () {
        if (this.value.length > maxUsernameLength) {
            this.value = this.value.substring(0, maxUsernameLength);
        }

        if (this.value.length < 8){
            alert("Password must be at least 8 characters long.");
        }
    });

    // Restrict password input length
    passwordInput.addEventListener("input", function () {
        if (this.value.length > maxPasswordLength) {
            this.value = this.value.substring(0, maxPasswordLength);
        }

        if (this.value.length < 8){
            alert("Password must be at least 8 characters long.");
        }
    });

    submit.addEventListener("click", );
});
