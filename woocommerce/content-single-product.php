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
            <h1>Hello</h1>
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
        </div>

        <!-- 📋 Right Column: Product Info -->
        <div class="custom-product-right">

            <div class="summary entry-summary">
                <h1>Hello</h1>
                <?php
                /**
                 * Hook: woocommerce_single_product_summary
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>
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