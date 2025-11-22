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
/*
|--------------------------------------------------------------------------
| FULL CSS FOR archive-product.php
|--------------------------------------------------------------------------
| This code combines the base layout and the refined WOOF filter styles
| to match the provided image.
*/

/* ========== Base Layout (Keep Existing) ========== */
.shop-page-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px auto;
    max-width: 1200px;
    padding: 0 15px;
    box-sizing: border-box;
}

/* Sidebar (Keep Existing) */
.shop-sidebar {
    flex: 0 0 25%;
    width: 250px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

/* Product Grid (Keep Existing) */
.shop-products {
    flex: 1;
    min-width: 300px;
}


/* ------------------------------------------------ */
/* --- Filter Specific Styling (Refined for Match) -- */
/* ------------------------------------------------ */

/* Overall Filter Structure and Section Spacing */
.shop-sidebar .woof {
    padding: 0; /* Remove default padding from WOOF container */
}

/* Style for individual filter sections to match image dividers */
.shop-sidebar .woof_container {
    margin-bottom: 0;
    padding: 15px 0; /* Consistent vertical padding for all sections */
    border-bottom: 1px solid #eee; /* The divider line */
}
.shop-sidebar .woof_container:first-child {
    padding-top: 0;
}
.shop-sidebar .woof_container:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

/* Filter Main Title and Reset Button (Top Section: "Filters" and Refresh Icon) */
.shop-sidebar .woof_redraw_whole_container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.shop-sidebar h3.woof_container_title {
    font-size: 18px !important; /* Slightly smaller for the main title */
    font-weight: 600 !important;
    color: #333 !important;
    margin: 0 !important;
    display: inline-block;
}

/* Style the reset button (refresh icon) */
.shop-sidebar .woof_reset_search {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 20px; /* Smaller icon size */
    color: #888;
    transition: color 0.2s;
    line-height: 1;
    margin-right: 0;
}
.shop-sidebar .woof_reset_search:hover {
    color: #0077ff;
}

/* Section titles (Search, Price Range, Model, Make, etc.) */
/* This is for "Search by Text", "Price Range", "Product Model", etc. */
.shop-sidebar .woof_container .woof_container_title {
    font-size: 14px !important; /* Matches the text size in the image */
    font-weight: 600 !important;
    color: #333 !important;
    margin: 0 0 10px 0 !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 0;
    border: none;
}

/* Add the 'up' arrow icon for collapsible section headers */
.shop-sidebar .woof_container .woof_container_title::after {
    content: '↑'; /* Up arrow icon */
    font-size: 14px;
    font-weight: 600;
    color: #333;
    transition: transform 0.3s ease;
}
/* If the filter is closed (assuming WOOF adds a class), uncomment this: */
/* .shop-sidebar .woof_container.woof_closed .woof_container_title::after { content: '↓'; } */


/* Search Input Field */
.shop-sidebar input[type="search"],
.shop-sidebar .woof_text_input {
    width: 100%;
    padding: 8px 12px; /* Tighter padding */
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    margin-bottom: 0 !important;
}


/* Price Range Styling (ion.rangeSlider classes used by WOOF) */
.shop-sidebar .woof_price_filter {
    padding-top: 0;
    margin-top: 5px;
}

/* Price range text display */
.shop-sidebar .woof_price_filter_txt {
    font-size: 14px;
    color: #333;
    font-weight: 500;
    display: block;
    text-align: center;
    margin: 5px 0 10px 0; /* Vertical spacing */
}

/* Range Slider Bar and Knobs */
.irs-grid { display: none !important; } /* Hide the grid numbers */
.irs-min, .irs-max { display: none !important; } /* Hide the min/max labels */

.irs--flat .irs-line {
    height: 6px; /* Thinner line */
    background-color: #eee;
    border-radius: 3px;
    top: 30px;
}
.irs--flat .irs-bar {
    background-color: #0077ff !important;
    height: 6px; /* Match line height */
    top: 30px;
}
.irs--flat .irs-handle {
    top: 28px; /* Align handles with the bar */
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #0077ff !important; /* Accent border color */
    background: #fff !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}

/* Hiding the Min/Max input fields below the slider */
.shop-sidebar .woof_price_filter_inputs {
    display: none;
}


/* Select Dropdowns (Chosen/Select2 elements often used by WOOF) */
.shop-sidebar select,
.shop-sidebar .chosen-container-single .chosen-single {
    width: 100%;
    padding: 8px 12px;
    margin-top: 0 !important;
    border-radius: 6px;
    font-size: 14px;
    border: 1px solid #ddd;
    box-shadow: none !important;
    height: 38px !important; /* Set a specific height */
    line-height: 20px !important;
    background-color: #fff;
    /* Custom down arrow using inline SVG for a clean look */
    background: #fff url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23333333%22%20d%3D%22M287%2069.9H5.4c-7.4%200-11.7%209.1-6.8%2015.3l138.8%20163c2.4%202.8%206%204.3%209.6%204.3s7.2-1.5%209.6-4.3l138.8-163c4.9-6.2.6-15.3-6.8-15.3z%22%2F%3E%3C%2Fsvg%3E') no-repeat right 12px center;
    background-size: 10px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.shop-sidebar .chosen-container-single .chosen-single div b {
    display: none !important; /* Hides the default Chosen arrow */
}

/* Hiding any submit button to rely on AJAX auto-submit */
.shop-sidebar button,
.shop-sidebar .woof_submit_search_form {
    display: none !important;
}

/* Removing redundant spacing (if any) */
.shop-sidebar .woof_container_inner {
    padding-top: 0 !important;
}


/* ========== Responsive Styles (Keep Existing) ========== */

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