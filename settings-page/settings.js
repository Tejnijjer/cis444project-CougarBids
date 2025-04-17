
// NAVIGATION 
function goToPage(page) {
    window.location.href = page;
}


document.addEventListener("DOMContentLoaded", function () {
    
    // DELETE FORM
    const delete_form = document.querySelector('form[action="delete_account.php"]');
    const delete_button = document.getElementById("delete-button");

    if (delete_button && delete_form) {
        delete_form.addEventListener("submit", function (e) {
        const confirmed = confirm("Are you sure you want to permanently delete your account");
        if (!confirmed) {
            e.preventDefault();
        }
    });
}
    
    // SAVE FORM
    const settings_form = document.querySelector('form[action="update_profile.php"]');
    if (settings_form) {
        settings_form.addEventListener("submit", function (e) {
            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const credit_card = document.getElementById("credit_card").value.trim();
            const address = document.getElementById("address").value.trim();
            const card_name = document.getElementById("card_name").value.trim();

            if (!name || !email || !password || !credit_card || !address || !card_name) {
                alert("Please fill in all required fields before saving");
                e.preventDefault();                
            }

        });
    }
});