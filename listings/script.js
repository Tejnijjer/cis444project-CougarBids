
window.onload = function(){
    //add database functionality here




    document.getElementById('profileButton').addEventListener('click', goToProfile);
    document.getElementById('addListingButton').addEventListener('click', addListing);
    document.getElementById('logoButton').addEventListener('click', goToHome);
    $('.delete-button').click(function() {
        $.ajax({
            type: "POST",
            url: "some.php",
            data: { name: "John" }
        }).done(function( msg ) {
            alert( "Data Saved: " + msg );
        });
    });

    var checkbox = document.querySelector('input[type="checkbox"]');

    checkbox.addEventListener('change', function () {
        let a;
        let b;
        if (checkbox.checked) {
            // do this
            console.log('Checked');
            a = document.getElementsByClassName('delete-button');
            for (let i = 0; i < a.length; i++) {
                a[i].style.display = 'inline-block';
            }
            b = document.getElementsByClassName('edit-button');
            for (let i = 0; i < b.length; i++) {
                b[i].style.display = 'inline-block';
            }
        } else {
            // do that
            a = document.getElementsByClassName('delete-button');
            for (let i = 0; i < a.length; i++) {
                a[i].style.display = 'none';
            }
            b = document.getElementsByClassName('edit-button');
            for (let i = 0; i < b.length; i++) {
                b[i].style.display = 'none';
            }
            console.log('Unchecked');
        }
    });


}
function listingClicked(){
    goToPage('/cis444project-CougarBids/expanded-listing/buying.html');

}
function goToProfile(){
    goToPage('/cis444project-CougarBids/profile-page/index.html');

}
function addListing(){
    const modal = document.getElementById('createListingModal');
    modal.style.display = 'flex';
}
function closeCreateListing(){
    const modal = document.getElementById('createListingModal');
    modal.style.display = 'none';
}
window.onclick = function(event) {
    const modal = document.getElementById('createListingModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
function goToHome(){
    document.getElementById('listings').scrollTo(0,0)
window.location.reload();
}
function goToPage(page) {
        window.location.href = page;
}




