
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

        itemBox.appendChild(itemImage);
        itemBox.appendChild(itemDescription);
        itemBox.appendChild(itemPrice);
        itemBox.appendChild(listingButton);
        listings.appendChild(itemBox);
    }
    document.getElementById('profileButton').addEventListener('click', goToProfile);
    document.getElementById('addListingButton').addEventListener('click', addListing);
    document.getElementById('logoButton').addEventListener('click', goToHome);
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



