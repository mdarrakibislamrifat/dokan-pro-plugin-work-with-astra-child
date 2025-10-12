<?php
/**
 * Custom Single Product Layout for Astra Child Theme
 */
defined( 'ABSPATH' ) || exit;

global $product;

// Required before the product content starts
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'custom-single-product', $product ); ?>>

    <div class="custom-product-container">


        <!-- 🖼️ Left Column: Product Images -->
        <div class="custom-product-left">

            <div class="custom-woocommerce-product-gallery">
                <?php
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
                <div class="sponsored-badge">
                    <p class="sponsored-text">Sponsored</p>
                </div>
                <div class="custom-product-back-button">
                    <button class="back-button">Back</button>
                    <button class="wishlist-button">Wishlist</button>




                </div>
            </div>
        </div>

        <!--  Right Column: Product Info -->
        <div class="rifat-product-right">
            <div class="summary entry-summary">

                <!-- Product Title -->
                <h1 class="rifat-single-product-title">2024 Honda Accord EX FWD</h1>

                <!-- Location -->
                <p class="rifat-single-product-location">Alhambra, CA (20 mi away)</p>

                <!-- Price -->
                <div class="rifat-single-product-price">$25,999</div>

                <!-- Deal Info & Dealer Rating Row -->
                <div class="rifat-dealership-rating">
                    <!-- Left: Deal Info -->
                    <div class="rifat-deal-section">
                        <div class="rifat-fair-deal-badge">
                            <span class="rifat-badge-dot">●</span> Fair Deal
                        </div>
                        <p class="rifat-market-info">$18 above market <span class="rifat-info-icon">ⓘ</span></p>
                    </div>

                    <!-- Right: Dealer Rating -->
                    <div class="rifat-rating-section">
                        <p class="rifat-dealer-label">Dealer rating</p>
                        <div class="rifat-stars-reviews">
                            <span class="rifat-stars">★★★<span class="rifat-half-star">★</span><span
                                    class="rifat-empty-star">★</span></span>
                            <a href="#" class="rifat-reviews-link">(7 reviews)</a>
                        </div>
                    </div>
                </div>

                <!-- Request Information Box -->
                <div class="rifat-request-information">
                    <h2 class="rifat-request-title">Request information</h2>

                    <button class="rifat-availability-button">Check availability</button>

                    <button class="rifat-finance-button">See finance & trade-in options</button>

                    <div class="rifat-contact-options">
                        <a href="tel:8001234567" class="rifat-contact-link">
                            <span class="rifat-phone-icon">📞</span> Call (800) 123-4567
                        </a>
                        <a href="#" class="rifat-contact-link">
                            <span class="rifat-chat-icon">💬</span> Chat
                        </a>
                    </div>
                </div>

                <!-- Pre-qualify Text -->
                <p class="rifat-prequalify-text">
                    Pre-qualify for financing with no impact to your credit score.
                </p>

                <!-- Partner Logos -->
                <div class="rifat-partner-logos">
                    <div class="rifat-logo-placeholder">Capital One</div>
                    <div class="rifat-logo-placeholder">CHASE</div>
                </div>


            </div>
        </div>
    </div>

    <!-- 📑 Tabs (Description, Additional Info, Reviews) -->
    <div class="custom-product-tabs">
        <?php
        /**
         * Hook: woocommerce_after_single_product_summary
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>