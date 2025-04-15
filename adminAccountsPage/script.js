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

};
function goToHome(){
    goToPage('/cis444project-CougarBids/listings/listings.php');
}
function goToPage(page) {
    window.location.href = page;
}