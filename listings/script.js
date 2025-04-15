



window.onload = function(){
    //add database functionality here




    document.getElementById('profileButton').addEventListener('click', goToProfile);
    document.getElementById('addListingButton').addEventListener('click', addListing);
    document.getElementById('logoButton').addEventListener('click', goToHome);



    var checkbox = document.querySelector('input[type="checkbox"]');
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
    }
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
    $("#searchInput").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            console.log($(this).val());
        }
    });
    $("#createForm").submit(function(event) {
        event.preventDefault();
        var data = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            console.log(item.name);
            return obj;
        }, {});

        $.ajax({
            type: "POST",
            url: "addListing.php",
            data: { title: data['title'], desc: data['desc'], price: data['price'], cat: data['cat']},
        }).done(function( msg ) {
            document.getElementById('createListingModal').classList.remove('show');
            window.location.reload();
        });




    });
    $('.delete-button').click(function() {

        $.ajax({
            type: "POST",
            url: "adminDelete.php",
            data: { id: $(this).parent().attr('id') },
        }).done(function( msg ) {
            window.location.reload();
            console.log("Deleted listing with id: " + $(this).parent().attr('id'));
        });
        //alert($(this).parent().attr('id'));
    });
    window.closeCreateListing = function() {
        document.getElementById('createListingModal').classList.remove('show');
    };

    window.closeEditListing = function() {
        document.getElementById('editListingModal').classList.remove('show');
    };

    // Event listener for "Create Listing" button
    $('#addListingButton').click(function() {
        document.getElementById('createListingModal').classList.add('show');
    });

    // Event delegation for edit buttons
    $(document).on('click', '.edit-button', function() {
        const listingId = $(this).parent().attr('id');
        fetchListingDetails(listingId);
    });

    // Function to fetch listing details
    function fetchListingDetails(listingId) {
        $.ajax({
            url: 'get_listing_details.php',
            type: 'GET',
            data: { id: listingId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Populate form fields with listing data
                    $('#editListingId').val(response.listing.id);
                    $('#editTitle').val(response.listing.name);
                    $('#editDescription').val(response.listing.description);
                    $('#editPrice').val(response.listing.price);

                    // Set category if available
                    if (response.listing.category) {
                        $('#editCategory').val(response.listing.category);
                    } else {
                        $('#editCategory').val(''); // Default to empty if no category
                    }

                    // Show the edit modal
                    document.getElementById('editListingModal').classList.add('show');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error connecting to server. Please try again.');
            }
        });
    }

    // Handle edit form submission
    $('#editForm').submit(function(e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: 'update_listing.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Listing updated successfully!');
                    closeEditListing();
                    // Reload the page to see the updated listing
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error connecting to server. Please try again.');
            }
        });
    });

}
function listingClicked(){
    goToPage('/cis444project-CougarBids/expanded-listing/buying.html');

}
function goToProfile(){
    goToPage('/cis444project-CougarBids/profile-page/index.html');

}
function addListing(){
    document.getElementById('createListingModal').classList.add('show');
}
window.onclick = function(event) {
    const createModal = document.getElementById('createListingModal');
    const editModal = document.getElementById('editListingModal');

    if (event.target === createModal) {
        closeCreateListing();
    }

    if (event.target === editModal) {
        closeEditListing();
    }
};
function goToHome(){
    document.getElementById('listings').scrollTo(0,0)
window.location.reload();
}
function goToPage(page) {
        window.location.href = page;
}




