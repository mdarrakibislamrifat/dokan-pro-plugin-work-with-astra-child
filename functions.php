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


// Shortcode for Custom Search Form
function custom_search_form_shortcode() {
    ob_start();
    ?>
<div class="custom-search-wrapper">
    <h1 style="color: #fff;">By Make & Model</h1>
    <form role="search" method="get" class="custom-search-form" action="<?php echo esc_url(home_url('/')); ?>">

        <select name="make" class="custom-search-select">
            <option value="">All makes</option>
            <?php
                $makes = get_terms(array(
                    'taxonomy' => 'make',
                    'hide_empty' => false,
                ));
                if (!empty($makes) && !is_wp_error($makes)) {
                    foreach ($makes as $make) {
                        echo '<option value="' . esc_attr($make->slug) . '">' . esc_html($make->name) . '</option>';
                    }
                }
                ?>
        </select>

        <select name="model" class="custom-search-select">
            <option value="">All models</option>
            <?php
                $models = get_terms(array(
                    'taxonomy' => 'model',
                    'hide_empty' => false,
                ));
                if (!empty($models) && !is_wp_error($models)) {
                    foreach ($models as $model) {
                        echo '<option value="' . esc_attr($model->slug) . '">' . esc_html($model->name) . '</option>';
                    }
                }
                ?>
        </select>

        <div class="price-range">
            <input type="text" name="min_price" class="price-input" placeholder="Min price    $">
            <span class="price-separator">to</span>
            <input type="text" name="max_price" class="price-input" placeholder="Max price    $">
        </div>

        <div class="location-radius">
            <input type="text" name="zipcode" class="location-input" placeholder="90049" value="90049">
            <select name="radius" class="radius-select">
                <option value="50">50 mi</option>
                <option value="25">25 mi</option>
                <option value="50" selected>50 mi</option>
                <option value="100">100 mi</option>
                <option value="200">200 mi</option>
            </select>

        </div>

        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<style>
.custom-search-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.custom-search-wrapper h1 {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 30px;
    color: #000;
}

.custom-search-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.custom-search-select,
.price-input,
.location-input,
.radius-select {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    background-color: #fff;
    color: #666;
}

.price-range {
    display: flex;
    align-items: center;
    gap: 15px;
}

.price-range .price-input {
    flex: 1;
}

.price-separator {
    color: #000000ff;
    font-size: 16px;
}

.location-radius {
    display: flex;
    gap: 15px;
}

.location-input {
    flex: 1;
}

.radius-select {
    flex: 1;
}

.search-button {
    width: 100%;
    padding: 18px;
    background-color: #008000;
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.search-button:hover {
    background-color: #006600;
}
</style>
<?php
    return ob_get_clean();
}
add_shortcode('custom_search_form', 'custom_search_form_shortcode');





// Shortcode for displaying products in Flexbox grid (initial 6 + search filter)
function custom_search_results_shortcode() {
    ob_start();

    // Query args
    $args = array(
        'post_type'      => 'product', // replace with your CPT if different
        'posts_per_page' => 6,         // show 6 products initially
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $tax_query  = array('relation' => 'AND');
    $meta_query = array('relation' => 'AND');
    $has_filter = false;

    // Apply filters if form submitted
    if (!empty($_GET['make'])) {
        $tax_query[] = array(
            'taxonomy' => 'make',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['make']),
        );
        $has_filter = true;
    }
    if (!empty($_GET['model'])) {
        $tax_query[] = array(
            'taxonomy' => 'model',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['model']),
        );
        $has_filter = true;
    }
    if (!empty($_GET['min_price'])) {
        $meta_query[] = array(
            'key'     => 'price',
            'value'   => floatval($_GET['min_price']),
            'compare' => '>=',
            'type'    => 'NUMERIC'
        );
        $has_filter = true;
    }
    if (!empty($_GET['max_price'])) {
        $meta_query[] = array(
            'key'     => 'price',
            'value'   => floatval($_GET['max_price']),
            'compare' => '<=',
            'type'    => 'NUMERIC'
        );
        $has_filter = true;
    }

    if ($has_filter) {
        if (count($tax_query) > 1) $args['tax_query'] = $tax_query;
        if (count($meta_query) > 1) $args['meta_query'] = $meta_query;
    }

    // Run query
    $query = new WP_Query($args);
    ?>

<div class="custom-product-grid">
    <?php
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $price = get_post_meta(get_the_ID(), 'price', true);
                $brand = wp_get_post_terms(get_the_ID(), 'make', array('fields' => 'names'));
                ?>
    <div class="custom-product-item">
        <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium'); ?>
        </a>
        <?php else: ?>
        <img src="https://via.placeholder.com/200x150" alt="<?php the_title(); ?>">
        <?php endif; ?>
        <div class="product-name">
            <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
        </div>

        <?php if (!is_wp_error($brand) && !empty($brand)) : ?>
        <div class="product-brand"><?php echo esc_html($brand[0]); ?></div>
        <?php endif; ?>
        <?php if ($price) : ?>
        <div class="product-price">$<?php echo esc_html($price); ?></div>
        <?php endif; ?>
    </div>
    <?php
            }
        } else {
            echo '<p class="no-results">No products found.</p>';
        }
        wp_reset_postdata();
        ?>
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
    /* 3 columns */
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

.product-name a {
    font-weight: bold;
    color: #ffffffff;
    text-decoration: underline;
    cursor: pointer;
}

/* Responsive for tablets and phones */
@media (max-width: 768px) {
    .custom-product-item {
        flex: 1 1 calc(50% - 20px);
        /* 2 per row */
    }
}

@media (max-width: 480px) {
    .custom-product-item {
        flex: 1 1 100%;
        /* 1 per row */
    }
}
</style>

<?php
    return ob_get_clean();
}
add_shortcode('custom_search_results', 'custom_search_results_shortcode');