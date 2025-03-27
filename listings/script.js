
window.onload = function(){
    //add database functionality here
    for(let i=0; i<15; i++){
        var listings = document.getElementById('listings');
        var itemBox = document.createElement('div');
        itemBox.className = 'item_box';

        var itemImage = document.createElement('div');
        itemImage.className = 'item-image';
        itemImage.innerHTML='<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3vrTUU3CKbUDThpm8aZzFXdTmai6PodNfXA&s" alt="Item Image">';
        var itemDescription = document.createElement('div');
        itemDescription.className = 'item-description';
        itemDescription.textContent = 'Placeholder Item '+i;

        var itemPrice = document.createElement('div');
        itemPrice.className = 'item-price';
        itemPrice.textContent = 'Price';

        var listingButton = document.createElement('item'+i);

        listingButton.className = 'listing-button';
        listingButton.addEventListener('click', listingClicked);

        var deleteButton = document.createElement('button');
        deleteButton.className = 'delete-button';

        //deleteButton.addEventListener('click', deleteListing);

        var editButton = document.createElement('button');
        editButton.className = 'edit-button';

        var editIcon = document.createElement('img');
        editIcon.src = 'edit.png';
        editIcon.className = 'admin-edit-icon';
        var deleteIcon = document.createElement('img');
        deleteIcon.src = 'delete.png';
        deleteIcon.className = 'admin-delete-icon';

        itemBox.appendChild(itemImage);
        itemBox.appendChild(itemDescription);
        itemBox.appendChild(itemPrice);
        itemBox.appendChild(listingButton);

        editButton.appendChild(editIcon);
        deleteButton.appendChild(deleteIcon);


        itemBox.appendChild(deleteButton);
        itemBox.appendChild(editButton);
        listings.appendChild(itemBox);
    }
    document.getElementById('profileButton').addEventListener('click', goToProfile);
    document.getElementById('addListingButton').addEventListener('click', addListing);
    document.getElementById('logoButton').addEventListener('click', goToHome);


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
    alert(this.tagName);
}
function goToProfile(){
    alert('Go to profile');
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




