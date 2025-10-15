
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
    const form = document.getElementById("rifat-email-form");

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

    // AJAX form submission
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'send_vendor_email');
        formData.append('product_id', "<?php echo $product->get_id(); ?>");

        fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) modal.close();
            })
            .catch(err => console.log(err));
    });
});