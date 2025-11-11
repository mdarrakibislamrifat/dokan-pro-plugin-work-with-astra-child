
document.addEventListener("DOMContentLoaded", function () {

    // SHARE BUTTON
    const shareBtn = document.getElementById("shareButton");
    shareBtn.addEventListener("click", async () => {
        const productUrl = window.location.href;
        const productTitle = document.title;

        if (navigator.share) {
            // Use Web Share API if available
            try {
                await navigator.share({
                    title: productTitle,
                    url: productUrl
                });
            } catch (err) {
                console.log("Share canceled", err);
            }
        } else {
            // Fallback: copy link
            navigator.clipboard.writeText(productUrl);
            alert("Product link copied to clipboard!");
        }
    });

    // WISHLIST BUTTON
    const wishlistBtn = document.getElementById("wishlistButton");
    wishlistBtn.addEventListener("click", () => {
        // Here you can integrate with a real wishlist plugin (YITH or custom)
        wishlistBtn.classList.toggle("active");
        const isAdded = wishlistBtn.classList.contains("active");
        wishlistBtn.textContent = isAdded ? "Wishlisted" : "Wishlist";

        // Example feedback
        alert(isAdded ? "Product added to wishlist!" : "Product removed from wishlist!");
    });

});





// modal js

document.addEventListener("DOMContentLoaded", function () {
    const emailBtn = document.getElementById("emailSellerBtn");
    const chatLink = document.querySelector(".chat-link");
    const modal = document.getElementById("emailSellerModal");
    const closeModal = document.getElementById("closeModalBtn");

    // Function to open modal
    const openModal = (e) => {
        e.preventDefault();
        modal.showModal();
    };

    if (emailBtn) emailBtn.addEventListener("click", openModal);
    if (chatLink) chatLink.addEventListener("click", openModal);

    // Close modal
    if (closeModal) closeModal.addEventListener("click", () => {
        modal.close();
    });

    // Close on ESC
    window.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && modal.open) modal.close();
    });
});




