<?php 
 defined( 'ABSPATH' ) || exit; global $product; // Required before 

$stock_quantity = $product->get_stock_quantity(); // get product stock

if ( $stock_quantity ) {
    $stock_text = $stock_quantity . ' in stock';
} else {
    $stock_text = 'Out of stock';
}

$location = get_post_meta( $product->get_id(), '_product_location', true );

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'custom-single-product', $product ); ?>>
    <div class="custom-product-container">
        <!-- 🖼️ Left Column: Product Images -->
        <div class="custom-product-left">
            <div class="custom-woocommerce-product-gallery">
                <?php do_action('woocommerce_before_single_product_summary'); ?>

                <div class="sponsored-badge">
                    <p class="sponsored-text">Sponsored</p>
                </div>

                <div class="custom-product-back-button">
                    <button class="back-button" id="shareButton">Share</button>
                    <button class="wishlist-button" id="wishlistButton">Wishlist</button>
                </div>
            </div>

        </div>

        <!-- Right Column: Product Info -->
        <div class="rifat-product-right">
            <div class="summary entry-summary">
                <!-- Product Title -->
                <h1 class="rifat-single-product-title">
                    <?php echo esc_html( $product->get_name() ); ?>
                </h1>

                <!-- Location -->
                <p class="rifat-single-product-location">
                    <?php echo esc_html( $location ? $location : 'Location not set' ); ?>
                </p>

                <!-- Price -->
                <div class="rifat-single-product-price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </div>

                <!-- Deal Info & Dealer Rating Row -->
                <div class="rifat-dealership-rating">
                    <!-- Left: Deal Info -->
                    <div class="rifat-deal-section">
                        <div class="rifat-fair-deal-badge"> <span class="rifat-badge-dot">●</span> Fair Deal </div>
                        <p class="rifat-market-info">
                            <?php echo esc_html( $stock_text ); ?> <span class="rifat-info-icon">ⓘ</span>
                        </p>
                    </div> <!-- Right: Dealer Rating -->
                    <div class="rifat-rating-section">
                        <p class="rifat-dealer-label">Dealer rating</p>
                        <div class="rifat-stars-reviews">
                            <?php 
    // Display average rating stars
    echo wc_get_rating_html( $product->get_average_rating() ); 
    ?>
                            <a href="#reviews" class="rifat-reviews-link">(<?php echo $product->get_review_count(); ?>
                                reviews)</a>
                        </div>

                    </div>

                </div>


                <!-- Request Information Box -->
                <div class="rifat-request-information">
                    <h2 class="rifat-request-title">Request information</h2>
                    <button class="rifat-availability-button" id="emailSellerBtn">Email Seller</button>

                    <div class="rifat-contact-options">
                        <a href="tel:8001234567" class="rifat-contact-link">
                            <span class="rifat-phone-icon">📞</span> Call (800) 123-4567
                        </a>
                        <a href="#" class="rifat-contact-link chat-link">
                            <span class="rifat-chat-icon">💬</span> Chat
                        </a>

                    </div>
                </div>


                <!-- Email Seller Modal -->
                <dialog id="emailSellerModal" class="modal">
                    <div class="modal-box">
                        <h3 class="text-lg font-bold">
                            Contact Seller about "<?php echo esc_html( $product->get_name() ); ?>"
                        </h3>

                        <p class="py-4">Fill in your details and message to contact the seller.</p>

                        <?php 
        $current_user = wp_get_current_user();
        $first_name = $current_user->first_name ?? '';
        $last_name = $current_user->last_name ?? '';
        $user_email = $current_user->user_email ?? '';
        ?>

                        <form id="rifat-email-form" method="post" class="rifat-email-form">
                            <label>
                                <span>First Name</span>
                                <input type="text" name="first_name" class="input" placeholder="Enter your first name"
                                    value="<?php echo esc_attr($first_name); ?>" required>
                            </label>
                            <label>
                                <span>Last Name</span>
                                <input type="text" name="last_name" class="input" placeholder="Enter your last name"
                                    value="<?php echo esc_attr($last_name); ?>" required>
                            </label>
                            <label>
                                <span>Your Email</span>
                                <input type="email" name="email" class="input" placeholder="Enter your email"
                                    value="<?php echo esc_attr($user_email); ?>" required>
                            </label>
                            <label>
                                <span>Your Phone</span>
                                <input type="tel" name="phone" class="input" placeholder="Enter your phone number"
                                    required>
                            </label>
                            <label>
                                <span>Postal Code</span>
                                <input type="text" name="postal_code" class="input" placeholder="Enter your postal code"
                                    required>
                            </label>
                            <label>
                                <span>Your Message</span>
                                <textarea name="message" class="textarea" placeholder="Write your message..."
                                    required></textarea>
                            </label>

                            <div class="modal-action">
                                <button type="submit" class="btn">Send Message</button>
                                <button class="btn" type="button" id="closeModalBtn">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>

            </div>
        </div>
    </div>
    <!-- 📑 Tabs (Description, Additional Info, Reviews) -->
    <div class="custom-product-tabs"> <?php do_action( 'woocommerce_after_single_product_summary' ); ?> </div>
</div> <?php do_action( 'woocommerce_after_single_product' ); ?>




<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("rifat-email-form");

    form.addEventListener("submit", function(e) {
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
            })
            .catch(err => console.log(err));
    });
});
</script>