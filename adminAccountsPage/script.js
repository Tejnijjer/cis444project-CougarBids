window.onload =function (){
    $('.delete_account_button').click(function() {
        $.ajax({
            type: "POST",
            url: "deleteAccount.php",
            data: {id: $(this).attr('id')},
        }).done(function() {
            window.location.reload();
        });
    });
    $(document).ready(function () {
        $('.edit_account_button').click(function () {
            const button = $(this);
            $('#editUserId').val(button.data('id'));
            $('#editUsername').val(button.data('username'));
            $('#editIsAdmin').val(button.data('admin'));
            $('#editAccountModal').addClass('show');
        });

        $('#editAccountForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "updateAccount.php",
                data: $(this).serialize(),
                success: function () {
                    location.reload();
                }
            });
        });
    });


};
function closeEditAccount() {
    document.getElementById("editAccountModal").style.display = "none";
}
function goToHome(){
    goToPage('/cis444project-CougarBids/listings/listings.php');
}
function goToPage(page) {
    window.location.href = page;
}


