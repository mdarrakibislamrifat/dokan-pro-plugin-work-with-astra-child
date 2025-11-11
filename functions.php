<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );



// Enque single page product custom css
function rifat_custom_single_product() {
    if (is_product()) {
        wp_enqueue_style('custom-single-product-css', get_stylesheet_directory_uri() . '/assets/css/custom-single-product.css', array(), '1.0.0', 'all');
    }
}

add_action( 'wp_enqueue_scripts', 'rifat_custom_single_product' );


// enque js file
// Enque single page product custom css
function rifat_custom_single_product_js() {
    if (is_product()) {
        wp_enqueue_script('custom-single-product-js', get_stylesheet_directory_uri() . '/assets/js/custom-single-product.js', array('jquery'), '1.0.0', true);
    }
}

add_action( 'wp_enqueue_scripts', 'rifat_custom_single_product_js' );






// Six Products Shortcode
function custom_six_products_shortcode() {
    ob_start();

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $query = new WP_Query($args);
    ?>
    
    <div class="custom-product-grid">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php
                $price = get_post_meta(get_the_ID(), '_price', true);
                $brand = wp_get_post_terms(get_the_ID(), 'product_brand', ['fields' => 'names']);
                ?>
                <div class="custom-product-item">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) {
                            the_post_thumbnail('medium');
                        } else {
                            echo '<img src="https://via.placeholder.com/200x150" alt="' . esc_attr(get_the_title()) . '">';
                        } ?>
                    </a>
                    <div class="product-name">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>

                    <?php if (!empty($brand)) : ?>
                        <div class="product-brand"><?php echo esc_html($brand[0]); ?></div>
                    <?php endif; ?>

                    <?php if ($price) : ?>
                        <div class="product-price">$<?php echo esc_html($price); ?></div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="no-results">No products found.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>

    <style>
    .custom-product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
    .custom-product-item {
        flex: 1 1 calc(33.333% - 20px);
        box-sizing: border-box;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
    }
    .custom-product-item img {
        max-width: 100%;
        max-height: 150px;
        height: auto;
        border-radius: 4px;
    }
    .product-price { display: none; }
    .product-name a {
        font-weight: bold;
        color: #fff;
        text-decoration: underline;
        cursor: pointer;
    }
    @media (max-width: 768px) {
        .custom-product-item { flex: 1 1 calc(50% - 20px); }
    }
    @media (max-width: 480px) {
        .custom-product-item { flex: 1 1 100%; }
    }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('six_products', 'custom_six_products_shortcode');




add_filter('woocommerce_get_shop_page_permalink', function($permalink) {
    return add_query_arg($_GET, $permalink);
});











// Mail function for contact seller form
add_action('wp_ajax_send_vendor_email', 'rifat_send_vendor_email');
add_action('wp_ajax_nopriv_send_vendor_email', 'rifat_send_vendor_email');

function rifat_send_vendor_email() {
    if (!isset($_POST['product_id'])) {
        wp_send_json(['success'=>false, 'message'=>'Product ID missing.']);
    }

    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
    if (!$product) wp_send_json(['success'=>false, 'message'=>'Invalid product.']);

    // Vendor info
    $vendor_id = get_post_field('post_author', $product_id);
    $vendor = get_userdata($vendor_id);
    if (!$vendor) wp_send_json(['success'=>false, 'message'=>'Vendor not found.']);
    $vendor_email = $vendor->user_email;

    // Current user (sender)
    $current_user = wp_get_current_user();
    if (!$current_user->exists()) wp_send_json(['success'=>false, 'message'=>'You must be logged in.']);
    $sender_email = sanitize_email($_POST['email']);
    $sender_name = sanitize_text_field($_POST['first_name'] . ' ' . $_POST['last_name']);

    // Message
    $phone = sanitize_text_field($_POST['phone']);
    $postal_code = sanitize_text_field($_POST['postal_code']);
    $message = sanitize_textarea_field($_POST['message']);

    $subject = "New Inquiry about: " . $product->get_name();
    $body = "You have a new inquiry from $sender_name ($sender_email)\n\n";
    $body .= "Phone: $phone\nPostal Code: $postal_code\n\nMessage:\n$message";

    $headers = ["From: $sender_name <$sender_email>"];

    $mail_sent = wp_mail($vendor_email, $subject, $body, $headers);

    if($mail_sent){
        wp_send_json(['success'=>true, 'message'=>'Message sent successfully!']);
    } else {
        wp_send_json(['success'=>false, 'message'=>'Failed to send message.']);
    }
}










// Add These Fields to the Dokan Product Form

// Show vehicle fields on both "Add New" and "Edit" product pages in Dokan
add_action('dokan_product_edit_after_main', 'add_vehicle_fields_to_dokan_form');
add_action('dokan_new_product_after_main',  'add_vehicle_fields_to_dokan_form');

function add_vehicle_fields_to_dokan_form($post) {
    // When adding a new product, $post may be null-ish — handle safely
    $post_id = isset($post->ID) ? $post->ID : 0;

    $make = get_post_meta($post_id, '_vehicle_make', true);
    $model = get_post_meta($post_id, '_vehicle_model', true);
    $year = get_post_meta($post_id, '_vehicle_year', true);
    $engine = get_post_meta($post_id, '_vehicle_engine', true);
    $transmission = get_post_meta($post_id, '_vehicle_transmission', true);
    $trim = get_post_meta($post_id, '_vehicle_trim', true);
    ?>
    <div class="dokan-form-group">
        <label for="vehicle_make"><?php _e('Make', 'your-textdomain'); ?></label>
        <input id="vehicle_make" type="text" name="vehicle_make" value="<?php echo esc_attr($make); ?>" class="dokan-form-control"/>
    </div>
    <div class="dokan-form-group">
        <label for="vehicle_model"><?php _e('Model', 'your-textdomain'); ?></label>
        <input id="vehicle_model" type="text" name="vehicle_model" value="<?php echo esc_attr($model); ?>" class="dokan-form-control"/>
    </div>
    <div class="dokan-form-group">
        <label for="vehicle_year"><?php _e('Year', 'your-textdomain'); ?></label>
        <input id="vehicle_year" type="text" name="vehicle_year" value="<?php echo esc_attr($year); ?>" class="dokan-form-control"/>
    </div>
    <div class="dokan-form-group">
        <label for="vehicle_engine"><?php _e('Engine', 'your-textdomain'); ?></label>
        <input id="vehicle_engine" type="text" name="vehicle_engine" value="<?php echo esc_attr($engine); ?>" class="dokan-form-control"/>
    </div>
    <div class="dokan-form-group">
        <label for="vehicle_transmission"><?php _e('Transmission', 'your-textdomain'); ?></label>
        <input id="vehicle_transmission" type="text" name="vehicle_transmission" value="<?php echo esc_attr($transmission); ?>" class="dokan-form-control"/>
    </div>
    <div class="dokan-form-group">
        <label for="vehicle_trim"><?php _e('Trim', 'your-textdomain'); ?></label>
        <input id="vehicle_trim" type="text" name="vehicle_trim" value="<?php echo esc_attr($trim); ?>" class="dokan-form-control"/>
    </div>
    <?php
}








// Save handler (robust for 1 or 2 args)
add_action('dokan_process_product_meta', 'save_vehicle_fields_dokan', 10, 2);

function save_vehicle_fields_dokan( $product_id, $postdata = array() ) {
    // defensive: make sure we have a product id
    if ( empty( $product_id ) ) {
        return;
    }

    // optional debug - logs POST keys when WP_DEBUG is on
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
        error_log( 'save_vehicle_fields_dokan called for product_id: ' . $product_id );
        error_log( '$_POST keys: ' . print_r( array_keys( $_POST ), true ) );
    }

    $fields = array( 'make', 'model', 'year', 'engine', 'transmission', 'trim' );

    foreach ( $fields as $field ) {
        $key = 'vehicle_' . $field;
        if ( isset( $_POST[ $key ] ) ) {
            $value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
            update_post_meta( $product_id, '_vehicle_' . $field, $value );
        }
    }
}












// Frontend Filter (Select Your Truck)
add_shortcode('vehicle_filter', function () {
    global $wpdb;
    ob_start();

    ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .vehicle-filter-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }

        .vehicle-filter-container h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: left;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .filter-group select {
            padding: 0px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #f5f5f5;
            font-size: 14px;
            color: #666;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 40px;
        }

        .filter-group select:hover {
            background-color: #efefef;
            border-color: #d0d0d0;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #ff4d26;
            background-color: white;
        }

        .search-btn {
            width: 100%;
            padding: 14px;
            background-color: #ff4d26;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-btn:hover {
            background-color: #e63d15;
        }

        .search-btn:active {
            background-color: #cc3410;
        }

        @media (max-width: 600px) {
            .vehicle-filter-container {
                padding: 25px;
            }

            .vehicle-filter-container h2 {
                font-size: 24px;
                margin-bottom: 25px;
            }

            .filter-grid {
                gap: 15px;
                margin-bottom: 20px;
            }

            .filter-group select {
                padding: 11px 12px;
                font-size: 13px;
            }

            .search-btn {
                padding: 12px;
                font-size: 15px;
            }
        }
    </style>

    <div class="vehicle-filter-container">
        <h2>Select Your Truck</h2>
       <?php
$shop_id  = wc_get_page_id( 'shop' );
$shop_url = $shop_id ? get_permalink( $shop_id ) : site_url( '/shop/' );
?>
<form id="vehicle-filter" action="<?php echo esc_url( $shop_url ); ?>" method="GET">

            <div class="filter-grid">
                <!-- Make -->
                <div class="filter-group">
                    <select name="make" required>
                        <option value="">Make</option>
                        <?php
                            // Get unique makes from all products
                            $makes = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_vehicle_make' AND meta_value != ''");

                            if ($makes) {
                                foreach ($makes as $make) {
                                    echo "<option value='" . esc_attr($make) . "'>" . esc_html($make) . "</option>";
                                }
                            }

                        ?>
                    </select>
                </div>

                <!-- Model -->
                <div class="filter-group">
                    <select name="model" required disabled>
                        <option value="">Model</option>
                        <?php
                        $models = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_vehicle_model' AND meta_value != ''");
                        if ($models) {
                            foreach ($models as $model) {
                                echo "<option value='" . esc_attr($model) . "'>" . esc_html($model) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Year -->
                <div class="filter-group">
                     <select name="vf_year" required disabled>
        <option value="">Year</option>
        <?php
        $years = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_vehicle_year' AND meta_value != ''");
        if ($years) {
            foreach ($years as $year) {
                echo "<option value='" . esc_attr($year) . "'>" . esc_html($year) . "</option>";
            }
        }
        ?>
    </select>
                </div>

                <!-- Engine -->
                <div class="filter-group">
                    <select name="engine" required disabled>
                        <option value="">Engine</option>
                        <?php
                        $engines = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_vehicle_engine' AND meta_value != ''");
                        if ($engines) {
                            foreach ($engines as $engine) {
                                echo "<option value='" . esc_attr($engine) . "'>" . esc_html($engine) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Transmission -->
                <div class="filter-group">
                    <select name="transmission" required disabled>
                        <option value="">Transmission</option>
                        <?php
                        $transmissions = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_vehicle_transmission' AND meta_value != ''");

                        if ($transmissions) {
                            foreach ($transmissions as $transmission) {
                                echo "<option value='" . esc_attr($transmission) . "'>" . esc_html($transmission) . "</option>";
                            }
                        }
                        ?>
                        
                    </select>
                </div>

                <!-- Trim -->
                <div class="filter-group">
                    <select name="trim" required disabled>
                        <option value="">Trim</option>
                        <?php
                        $trims = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_vehicle_trim' AND meta_value != ''");

                       if ($trims) {
                            foreach ($trims as $trim) {
                                echo "<option value='" . esc_attr($trim) . "'>" . esc_html($trim) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

<script>
jQuery(document).ready(function($) {
    // Enable next dropdown when previous one has a value
    $('#vehicle-filter select').each(function(index) {
        $(this).on('change', function() {
            // Get next select
            var nextSelect = $('#vehicle-filter select').eq(index + 1);
            if ($(this).val() !== '') {
                nextSelect.prop('disabled', false);
            } else {
                // If user clears selection, disable all following selects
                $('#vehicle-filter select').slice(index + 1).prop('disabled', true).val('');
            }
        });
    });
});
</script>

    <?php
    
    return ob_get_clean();
});






add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || $query->is_post_type_archive('product'))) {

        $fields = ['make', 'model', 'vf_year', 'engine', 'transmission', 'trim'];
        $meta_query = ['relation' => 'AND'];
        $has_filter = false;

        foreach ($fields as $field) {
            if (!empty($_GET[$field])) {
                $meta_query[] = [
                    'key' => '_vehicle_' . ($field === 'vf_year' ? 'year' : $field), // map back to meta key
                    'value' => sanitize_text_field($_GET[$field]),
                    'compare' => '='
                ];
                $has_filter = true;
            }
        }

        if ($has_filter) {
            $query->set('meta_query', $meta_query);
            $query->set('post_type', 'product');
        }
    }
});





