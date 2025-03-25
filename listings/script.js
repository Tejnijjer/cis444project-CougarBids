
window.onload = function(){
    //add database functionality here
    for(let i=0; i<1000; i++){
        var listings = document.getElementById('listings');
        var itemBox = document.createElement('div');
        itemBox.className = 'item_box';

        var itemImage = document.createElement('div');
        itemImage.className = 'item-image';
        itemImage.textContent = 'Item Image';

        var itemDescription = document.createElement('div');
        itemDescription.className = 'item-description';
        itemDescription.textContent = 'Item Description';

        var itemPrice = document.createElement('div');
        itemPrice.className = 'item-price';
        itemPrice.textContent = 'Price';

        var listingButton = document.createElement('button'+i);
        listingButton.className = 'listing-button';

        itemBox.appendChild(itemImage);
        itemBox.appendChild(itemDescription);
        itemBox.appendChild(itemPrice);
        itemBox.appendChild(listingButton);

        listings.appendChild(itemBox);
    }
}

