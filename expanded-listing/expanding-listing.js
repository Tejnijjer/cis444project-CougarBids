// Function to navigate to different pages
function goToPage(page) {
    window.location.href = page;
}

// Function to change the main image when clicking thumbnails
document.addEventListener("DOMContentLoaded", function () {
    let thumbnails = document.querySelectorAll(".thumbnail-container img");
    let mainImage = document.querySelector(".main-image-container img");

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener("click", function () {
            mainImage.src = this.src;
        });
    });
});

// Function to toggle favorite (heart icon)
document.addEventListener("DOMContentLoaded", function () {
    let favoriteIcon = document.querySelector(".favorite-icon");

    if (favoriteIcon) {
        favoriteIcon.addEventListener("click", function () {
            if (this.textContent === "❤️") {
                this.textContent = "♡"; // Unfilled heart
            } else {
                this.textContent = "❤️"; // Filled heart
            }
        });
    }
});

// Event listeners for buttons (you can modify these as needed)
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".buy").addEventListener("click", function () {
        alert("Proceeding to checkout...");
    });

    document.querySelector(".offer").addEventListener("click", function () {
        alert("Make an offer for this item.");
    });

    document.querySelector(".add-to-cart").addEventListener("click", function () {
        alert("Item added to cart!");
    });
});
