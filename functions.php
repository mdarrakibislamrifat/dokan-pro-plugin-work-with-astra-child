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








// Frontend Filter (Select Your Truck)
add_shortcode('vehicle_filter', function () {
    ob_start();
    ?>
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box;}
        .vehicle-filter-container {
            background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 40px; max-width: 500px; width: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }
        .vehicle-filter-container h2 {
            font-size: 28px; font-weight: 700; color: #1a1a1a;
            margin-bottom: 30px; text-align: left;
        }
        .filter-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;
        }
        .filter-group select {
            padding: 10px 15px; border: 1px solid #e0e0e0; border-radius: 6px;
            background-color: #f5f5f5; font-size: 14px; color: #666; cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 10px center;
            background-size: 20px; padding-right: 40px;
        }
        .filter-group select:hover {background-color: #efefef; border-color: #d0d0d0;}
        .filter-group select:focus {outline: none; border-color: #ff4d26; background-color: white;}
        .search-btn {
            width: 100%; padding: 14px; background-color: #ff4d26; color: white;
            border: none; border-radius: 6px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: background-color 0.3s ease;
        }
        .search-btn:hover {background-color: #e63d15;}
        .search-btn:active {background-color: #cc3410;}
    </style>

    <div class="vehicle-filter-container">
        <h2>Equipment for Sale</h2>
        <?php
        $shop_id  = wc_get_page_id('shop');
        $shop_url = $shop_id ? get_permalink($shop_id) : site_url('/shop/');
        ?>
        <form id="vehicle-filter" action="<?php echo esc_url($shop_url); ?>" method="get">
            <div class="filter-grid">
                <?php
                // Attribute slugs as per your screenshot
               $attributes = ['truck-category', 'make', 'model', 'years'];


foreach ($attributes as $i => $attr_slug) {
    $taxonomy = 'pa_' . $attr_slug; // WooCommerce attribute prefix
    $label = ucwords(str_replace('-', ' ', $attr_slug)); // Friendly label

    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    ?>
    <div class="filter-group">
    <select name="<?php echo esc_attr($attr_slug); ?>" <?php echo $i === 0 ? '' : 'disabled'; ?> required>
        <?php
        // Change first option label for Truck Category only
        if ($attr_slug === 'truck-category') {
            echo '<option value="">Category</option>';
        } else {
            echo '<option value="">' . esc_html($label) . '</option>';
        }
        ?>

        <?php
        if (!is_wp_error($terms) && $terms) {
            foreach ($terms as $term) {
                echo "<option value='" . esc_attr($term->slug) . "'>" . esc_html($term->name) . "</option>";
            }
        }
        ?>
    </select>
</div>

    <?php
}


                ?>
            </div>

            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // ajax URL
        var ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';

        // Utility: populate a select with returned terms
        function populateSelect($select, terms) {
            $select.empty().append('<option value="">'+ $select.attr('name').charAt(0).toUpperCase() + $select.attr('name').slice(1) +'</option>');
            if (!terms || !terms.length) return;
            terms.forEach(function(t){
                $select.append('<option value="'+ t.slug +'">'+ t.name +'</option>');
            });
        }

        // When any select changes
        $('#vehicle-filter select').on('change', function() {
            var $this = $(this);
            var index = $('#vehicle-filter select').index(this);

            // Reset following selects
            $('#vehicle-filter select').slice(index + 1).prop('disabled', true).val('').find('option:not(:first)').remove();

            // Collect filters up to this select
            var filters = {};
            $('#vehicle-filter select').each(function(i){
                if (i <= index) {
                    var name = $(this).attr('name');
                    var val = $(this).val();
                    if (val) filters[name] = val;
                }
            });

            // Prepare next select
            var $next = $('#vehicle-filter select').eq(index + 1);
            if ($next.length === 0) return;

            var nextTax = 'pa_' + $next.attr('name');

            // AJAX request to get valid terms for the next select
            $.post(ajaxUrl, {
                action: 'get_vehicle_terms',
                taxonomy: nextTax,
                filters: filters
            }, function(response) {
                if (response && response.success) {
                    populateSelect($next, response.data);
                    // enable only if there are options
                    if (response.data && response.data.length) {
                        $next.prop('disabled', false);
                    }
                }
            });
        });

        // Optional: if page loaded with query params, pre-fill selects and trigger change to load dependents
        (function prefillFromQuery(){
            var urlParams = new URLSearchParams(window.location.search);
            var changed = false;
            $('#vehicle-filter select').each(function(i){
                var name = $(this).attr('name');
                if (urlParams.has(name) && urlParams.get(name)) {
                    $(this).val(urlParams.get(name));
                    changed = true;
                }
            });
            if (changed) {
                // trigger change on first populated select to kick off cascading loads
                $('#vehicle-filter select').filter(function(){ return $(this).val() !== ''; }).last().trigger('change');
            }
        })();
    });
    </script>
    <?php
    return ob_get_clean();
});





// Handle the filtering on shop page
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && is_shop()) {
        $tax_query = ['relation' => 'AND'];

        $attributes = ['truck-category', 'make', 'model', 'years'];

        foreach ($attributes as $attr) {
            if (!empty($_GET[$attr])) {
                $tax_query[] = [
                    'taxonomy' => 'pa_' . sanitize_text_field($attr),
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET[$attr]),
                ];
            }
        }

        if (count($tax_query) > 1) {
            $query->set('tax_query', $tax_query);
        }
    }
});








// AJAX handlers (public + logged in)
add_action('wp_ajax_get_vehicle_terms', 'get_vehicle_terms');
add_action('wp_ajax_nopriv_get_vehicle_terms', 'get_vehicle_terms');

function get_vehicle_terms() {
    // Security & input
    $taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
    $filters  = isset($_POST['filters']) && is_array($_POST['filters']) ? array_map('sanitize_text_field', $_POST['filters']) : [];

    if (empty($taxonomy)) {
        wp_send_json_error('Missing taxonomy');
    }

    // Build tax_query from selected filters (only include non-empty)
    $tax_query = ['relation' => 'AND'];
    foreach ($filters as $key => $value) {
        if (! $value ) continue;
        $tax_query[] = [
            'taxonomy' => 'pa_' . sanitize_key($key),
            'field'    => 'slug',
            'terms'    => $value,
        ];
    }

    // Query product IDs that match selected filters
    $q = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => $tax_query,
    ]);

    $product_ids = $q->posts ?: [];

    if (empty($product_ids)) {
        wp_send_json_success([]); // no products -> no terms
    }

    // Get terms used by those products for requested taxonomy
    $requested_tax = sanitize_text_field($taxonomy); // this will be like 'pa_categories', 'pa_make', etc.

    $terms = wp_get_object_terms($product_ids, $requested_tax, ['fields' => 'all']);

    if (is_wp_error($terms) || empty($terms)) {
        wp_send_json_success([]);
    }

    // Make unique and prepare response (slug + name)
    $map = [];
    foreach ($terms as $t) {
        if (!isset($map[$t->term_id])) {
            $map[$t->term_id] = ['slug' => $t->slug, 'name' => $t->name];
        }
    }

    $result = array_values($map);
    wp_send_json_success($result);
}




