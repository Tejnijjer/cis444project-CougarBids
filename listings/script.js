window.onload = function () {
    document.getElementById('profileButton').addEventListener('click', goToProfile);
    document.getElementById('addListingButton').addEventListener('click', addListing);
    document.getElementById('logoButton').addEventListener('click', goToHome);



    $("#searchInput").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            console.log($(this).val());
        }
    });

    $('.delete-button').click(function () {
        $.ajax({
            type: "POST",
            url: "adminDelete.php",
            data: { id: $(this).parent().attr('id') },
        }).done(function () {
            window.location.reload();
        });
    });

    window.closeCreateListing = function () {
        document.getElementById('createListingModal').classList.remove('show');
    };

    window.closeEditListing = function () {
        document.getElementById('editListingModal').classList.remove('show');
    };

    $('#addListingButton').click(function () {
        document.getElementById('createListingModal').classList.add('show');
    });

    $(document).on('click', '.edit-button', function () {
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

    window.onclick = function (event) {
        const createModal = document.getElementById('createListingModal');
        const editModal = document.getElementById('editListingModal');

        if (event.target === createModal) closeCreateListing();
        if (event.target === editModal) closeEditListing();
    };

    setupImageUpload();
    setupEditImageUpload();
    setupFormSubmission();
    injectDynamicStyles();
};



function setupImageUpload() {
    const imageUploadBox = document.getElementById('imageUploadBox');
    const imageInput = document.getElementById('imageInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    if (!imageUploadBox || !imageInput || !imagePreviewContainer) return;

    const listingId = 'listing_' + Date.now();
    if (document.getElementById('listingId')) {
        document.getElementById('listingId').value = listingId;
    }

    imageUploadBox.addEventListener('click', () => imageInput.click());

    imageUploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadBox.classList.add('dragover');
    });

    imageUploadBox.addEventListener('dragleave', () => imageUploadBox.classList.remove('dragover'));

    imageUploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadBox.classList.remove('dragover');
        displayImagePreviews(e.dataTransfer.files);
    });

    imageInput.addEventListener('change', (e) => displayImagePreviews(e.target.files));
}

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


function displayImagePreviews(files) {
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    if (!files || files.length === 0 || !imagePreviewContainer) return;

    const file = files[0];
    if (!file.type.startsWith('image/')) return;

    imagePreviewContainer.innerHTML = '';
    const reader = new FileReader();

    reader.onload = function (e) {
        const previewDiv = document.createElement('div');
        previewDiv.className = 'image-preview';

        const img = document.createElement('img');
        img.src = e.target.result;

        const caption = document.createElement('input');
        caption.type = 'text';
        caption.name = `imageCaption_0`;
        caption.placeholder = 'Image description...';
        caption.className = 'caption-input';

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '×';
        removeBtn.onclick = function (e) {
            e.preventDefault();
            previewDiv.remove();
            document.getElementById('imageInput').value = '';
        };

        previewDiv.appendChild(img);
        previewDiv.appendChild(caption);
        previewDiv.appendChild(removeBtn);
        imagePreviewContainer.appendChild(previewDiv);
    };
    reader.readAsDataURL(file);
}

function handleEditImagePreview(file) {
    const imagePreviewContainer = document.getElementById('editImagePreviewContainer');
    if (!file || !file.type.startsWith('image/') || !imagePreviewContainer) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        imagePreviewContainer.innerHTML = '';

        const previewDiv = document.createElement('div');
        previewDiv.className = 'image-preview';

        const img = document.createElement('img');
        img.src = e.target.result;

        const caption = document.createElement('input');
        caption.className = 'caption-input';
        caption.name = 'imageCaption';
        caption.placeholder = 'Image description...';

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '×';
        removeBtn.onclick = function (e) {
            e.preventDefault();
            imagePreviewContainer.innerHTML = '';
            document.getElementById('imageInput').value = '';
        };

        previewDiv.appendChild(img);
        previewDiv.appendChild(caption);
        previewDiv.appendChild(removeBtn);
        imagePreviewContainer.appendChild(previewDiv);
    };
    reader.readAsDataURL(file);
}

function setupFormSubmission() {
    const createForm = document.getElementById('createForm');
    if (!createForm) return;

    createForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const imageInput = document.getElementById('imageInput');
        if (imageInput && imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        const preview = document.querySelector('.image-preview');
        if (preview) {
            const caption = preview.querySelector('.caption-input').value;
            formData.append('imageMeta_0', caption);
        }

        $.ajax({
            url: 'addListing.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        closeCreateListing();
                        window.location.reload();
                    } else {
                        alert('Error creating listing: ' + data.message);
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    alert('Unexpected error. Please try again.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while creating the listing.');
            }
        });
    });
}

function injectDynamicStyles() {
    if (!document.querySelector('.image-preview-container')) {
        const style = document.createElement('style');
        style.textContent = `
            .image-preview-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 10px;
            }
            .image-preview {
                position: relative;
                width: 120px;
                margin-bottom: 10px;
            }
            .image-preview img {
                width: 100%;
                height: 90px;
                object-fit: cover;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .image-preview .caption-input {
                width: 100%;
                margin-top: 5px;
                padding: 3px;
                font-size: 12px;
                border: 1px solid #ddd;
            }
            .image-preview .remove-btn {
                position: absolute;
                top: -8px;
                right: -8px;
                background: red;
                color: white;
                border: none;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                font-size: 12px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .image-upload {
                border: 2px dashed #ccc;
                padding: 20px;
                text-align: center;
                cursor: pointer;
                margin-bottom: 15px;
                transition: all 0.3s ease;
            }
            .image-upload:hover,
            .image-upload.dragover {
                background-color: #f5f5f5;
                border-color: #999;
            }
        `;
        document.head.appendChild(style);
    }
}

function goToProfile() {
    goToPage('/cis444project-CougarBids/profile-page/index.html');
}

function goToHome() {
    document.getElementById('listings').scrollTo(0, 0);
    window.location.reload();
}

function goToPage(page) {
    window.location.href = page;
}

function addListing() {
    document.getElementById('createListingModal').classList.add('show');
}

function fetchListingDetails(listingId) {
    $.ajax({
        url: 'get_listing_details.php',
        type: 'GET',
        data: { id: listingId },
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
                    img.src = image.image_path;
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
