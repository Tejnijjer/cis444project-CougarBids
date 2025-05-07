
window.onload = function() {
    window.closeEditListing = function () {
        document.getElementById('editListingModal').classList.remove('show');
    };
    $('.delete_button').click(function (e) {
        e.stopPropagation();
        $.ajax({
            type: "POST",
            url: "delete.php",
            data: { id: $(this).parent().attr('id') },
        }).done(function () {
            window.location.reload();
        });
    });
    $('.edit_button').click(function (e) {
        e.stopPropagation();
        const listingId = $(this).parent().attr('id');
        fetchListingDetails(listingId);
    });
    $('#editForm').submit(function (e) {
        e.preventDefault();

        const formElement = document.getElementById('editForm');
        const formData = new FormData(formElement);

        const imageInput = document.getElementById('editImageInput');
        if (imageInput && imageInput.files.length > 0) {
            formData.append('newImage', imageInput.files[0]);
        }

        const captionInput = document.querySelector('#editImagePreviewContainer .caption-input');
        if (captionInput) {
            formData.append('imageCaption', captionInput.value);
        }

        $.ajax({
            url: 'update_listing.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    closeEditListing();
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('Error connecting to server. Please try again.');
            }
        });
    });
    //setupEditImageUpload();
};

function setupEditImageUpload() {
    const imageUploadBox = document.getElementById('editImageUploadBox');
    const imageInput = document.getElementById('editImageInput');
    const imagePreviewContainer = document.getElementById('editImagePreviewContainer');

    if (!imageUploadBox || !imageInput || !imagePreviewContainer) return;

    imageUploadBox.addEventListener('click', () => imageInput.click());

    imageUploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadBox.classList.add('dragover');
    });

    imageUploadBox.addEventListener('dragleave', () => {
        imageUploadBox.classList.remove('dragover');
    });

    imageUploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadBox.classList.remove('dragover');
        handleEditImagePreview(e.dataTransfer.files[0]);
    });

    imageInput.addEventListener('change', (e) => handleEditImagePreview(e.target.files[0]));
}

function showTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabId).classList.add('active');
    
    // Update button styles
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    event.target.classList.add('active');
}
function fetchListingDetails(listingId) {
    $.ajax({
        url: 'get_listing_details.php',
        type: 'GET',
        data: {id: listingId},
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#editListingId').val(response.listing.id);
                $('#editTitle').val(response.listing.name);
                $('#editDescription').val(response.listing.description);
                $('#editPrice').val(response.listing.price);
                $('#editCategory').val(response.listing.category || '');

                const editImageContainer = document.getElementById('editImagePreviewContainer');
                editImageContainer.innerHTML = '';

                const image = response.listing.image;
                if (image) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';
                    previewDiv.dataset.imageId = image.id;

                    const img = document.createElement('img');
                    img.src = "../listings/"+image.image_path;
                    img.alt = 'Listing Image';

                    const caption = document.createElement('input');
                    caption.type = 'text';
                    caption.className = 'caption-input';
                    caption.name = 'existingImageCaption';
                    caption.value = image.caption || '';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '×';
                    removeBtn.onclick = function (e) {
                        e.preventDefault();
                        previewDiv.remove();
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'deleteImage';
                        hiddenInput.value = '1';
                        editImageContainer.appendChild(hiddenInput);
                    };

                    previewDiv.appendChild(img);
                    previewDiv.appendChild(caption);
                    previewDiv.appendChild(removeBtn);
                    editImageContainer.appendChild(previewDiv);
                }

                document.getElementById('editListingModal').classList.add('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function () {
            alert('Error connecting to server. Please try again.');
        }
    });
}


document.querySelector('.profile-toggle').addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
        e.preventDefault();
        const menu = this.nextElementSibling;
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.profile-dropdown') && window.innerWidth <= 768) {
        document.querySelector('.dropdown-menu').style.display = 'none';
    }
});
