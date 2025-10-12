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
                /**
                 * Display product gallery (images)
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>

                <div class="sponsored-badge">
                    <p class="sponsored-text">Sponsored</p>
                </div>

                <div class="custom-product-back-button">
                    <button class="back-button" onclick="history.back()">Back</button>
                    <button class="wishlist-button">Wishlist</button>
                </div>
            </div>
        </div>

        <!-- 🧾 Right Column: Product Info -->
        <div class="rifat-product-right">
            <div class="summary entry-summary">

                <!-- ✅ Product Title -->
                <h1 class="rifat-single-product-title"><?php the_title(); ?></h1>

                <!-- ✅ Product Short Description -->
                <div class="rifat-single-product-location">
                    <?php echo wpautop( $product->get_short_description() ); ?>
                </div>
                <!-- Location -->
                <p class="rifat-single-product-location">Alhambra, CA (20 mi away)</p>

                <!-- ✅ Product Price -->
                <div class="rifat-single-product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>

                <!-- ✅ Deal Info & Dealer Rating Row -->
                <div class="rifat-dealership-rating">
                    <div class="rifat-deal-section">
                        <div class="rifat-fair-deal-badge">
                            <span class="rifat-badge-dot">●</span> Fair Deal
                        </div>
                        <p class="rifat-market-info">
                            <?php echo esc_html__( '$18 above market', 'astra-child' ); ?>
                            <span class="rifat-info-icon">ⓘ</span>
                        </p>
                    </div>

                    <div class="rifat-rating-section">
                        <p class="rifat-dealer-label"><?php esc_html_e( 'Dealer rating', 'astra-child' ); ?></p>
                        <div class="rifat-stars-reviews">
                            <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                            <a href="#reviews" class="rifat-reviews-link">
                                (<?php echo $product->get_review_count(); ?> reviews)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ✅ Request Information Box -->
                <div class="rifat-request-information">
                    <h2 class="rifat-request-title"><?php esc_html_e( 'Request information', 'astra-child' ); ?></h2>

                    <button class="rifat-availability-button">
                        <?php esc_html_e( 'Check availability', 'astra-child' ); ?>
                    </button>

                    <button class="rifat-finance-button">
                        <?php esc_html_e( 'See finance & trade-in options', 'astra-child' ); ?>
                    </button>

                    <div class="rifat-contact-options">
                        <a href="tel:8001234567" class="rifat-contact-link">
                            <span class="rifat-phone-icon">📞</span>
                            <?php esc_html_e( 'Call (800) 123-4567', 'astra-child' ); ?>
                        </a>
                        <a href="#" class="rifat-contact-link">
                            <span class="rifat-chat-icon">💬</span>
                            <?php esc_html_e( 'Chat', 'astra-child' ); ?>
                        </a>
                    </div>
                </div>

                <!-- ✅ Pre-qualify Text -->
                <p class="rifat-prequalify-text">
                    <?php esc_html_e( 'Pre-qualify for financing with no impact to your credit score.', 'astra-child' ); ?>
                </p>

                <!-- ✅ Partner Logos (static placeholders — can make dynamic later) -->
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
         * Default WooCommerce tabs and related products
         */
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>