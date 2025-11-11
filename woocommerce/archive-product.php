<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
do_action( 'woocommerce_shop_loop_header' );
?>

<?php if ( woocommerce_product_loop() ) : ?>

<div class="shop-page-wrapper">

    <!-- Left Sidebar: Husky Filter -->
    <div class="shop-sidebar">
        <?php echo do_shortcode('[woof]'); ?>
    </div>

    <!-- Right Content: Products -->
    <div class="shop-products">
        <?php
        do_action( 'woocommerce_before_shop_loop' );

        woocommerce_product_loop_start();

        if ( wc_get_loop_prop( 'total' ) ) {
            while ( have_posts() ) {
                the_post();

                do_action( 'woocommerce_shop_loop' );

                wc_get_template_part( 'content', 'product' );
            }
        }

        woocommerce_product_loop_end();

        do_action( 'woocommerce_after_shop_loop' );
        ?>
    </div>

</div>

<style>
/* ========== Base Layout ========== */
.shop-page-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px auto;
    max-width: 1200px;
    padding: 0 15px;
    box-sizing: border-box;
}

/* Sidebar */
.shop-sidebar {
    flex: 0 0 25%;
    width: 250px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

/* Product Grid */
.shop-products {
    flex: 1;
    min-width: 300px;
}

/* ========== Responsive Styles ========== */

/* Tablet View (Below 992px) */
@media (max-width: 992px) {
    .shop-page-wrapper {
        flex-direction: column;
    }

    .shop-sidebar {
        flex: 100%;
        order: 1;
        min-width: auto;
    }

    .shop-products {
        flex: 100%;
        order: 2;
    }
}

/* Mobile View (Below 600px) */
@media (max-width: 600px) {
    .shop-page-wrapper {
        gap: 10px;
    }

    .shop-sidebar {
        padding: 10px;
        border: none;
        box-shadow: none;
        background: transparent;
    }

    .shop-products {
        min-width: auto;
    }
}
</style>

<?php else : ?>

<?php
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action( 'woocommerce_no_products_found' );
    ?>

<?php endif; ?>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
?>