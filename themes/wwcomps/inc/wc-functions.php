<?php

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_filter( 'woocommerce_show_page_title' , 'woo_hide_page_title' );
function woo_hide_page_title() {
    
    return false;
    
}

add_action('woocommerce_before_main_content', 'get_custom_wc_output_content_wrapper', 11);
add_action('woocommerce_after_main_content', 'get_custom_wc_output_content_wrapper_end', 9);

function get_custom_wc_output_content_wrapper(){

    if(is_shop() OR is_product_category()){ 
       echo '<section class="latest-compitions-page-con-cntlr inline-bg" style="background: url('.THEME_URI.'/assets/images/latest-compitions-page-con-bg.jpg);">
  <div class="fl-angle-hdr-cntlr">
    <div class="fl-angle-hdr">
      <span class="fl-angle-hdr-join"></span>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="fl-angle-sec-hdr">
              <h2 class="fl-h5 flash-title"><span>LATEST</span> COMPETITIONS</h2>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>';

  echo '  <div class="latest-compitions-page-tab-cntlr">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="filter-tabbar">
            <ul class="filter reset-list">
              <li class="active"><a class="all" href="#">ALL</a></li>
              <li><a class="cars" href="#">CARS</a></li>
              <li><a class="games" href="#">GAMES</a></li>
              <li><a class="holiday" href="#">HOLIDAY</a></li>
              <li><a class="tvs" href="#">TVS</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="products-grd-page-con">
    <div class="container">
        <div class="row">
          <div class="col-md-12">
              <div class="product-grds-cntlr">';
    }


}

function get_custom_wc_output_content_wrapper_end(){
	if(is_shop() OR is_product_category()){ 
    	echo '</div></div></div></div></div></section>';
	}

}

add_action('recent_ended_competition', 'get_recent_competition');
function get_recent_competition(){
    get_template_part('templates/wc-ended', 'templates');
}


add_filter('woocommerce_catalog_orderby', 'wc_customize_product_sorting');

function wc_customize_product_sorting($sorting_options){
    $sorting_options = array(
        'menu_order' => __( 'sort by', 'woocommerce' ),
        'popularity' => __( 'popularity', 'woocommerce' ),
        'rating'     => __( 'average rating', 'woocommerce' ),
        'date'       => __( 'newness', 'woocommerce' ),
        'price'      => __( 'low price', 'woocommerce' ),
        'price-desc' => __( 'high price', 'woocommerce' ),
    );

    return $sorting_options;
}

/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

/*Loop Hooks*/


/**
 * Add loop inner details are below
 */

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('woocommerce_shop_loop_item_title', 'add_shorttext_below_title_loop', 5);
if (!function_exists('add_shorttext_below_title_loop')) {
    function add_shorttext_below_title_loop() {
        global $product, $woocommerce, $post;

        echo '<div class="fl-pro-grd-item">';
        echo '<div class="fl-pro-grd-item-fea-img">';
        if ( $product->is_on_sale() ) :
        echo '<div class="fl-pro-sale-text">SALE</div>';
        endif;
        echo '<a href="'.get_permalink( $product->get_id() ).'">';
        echo wp_get_attachment_image( get_post_thumbnail_id($product->get_id()), 'phgrid' );
        echo '</a>';
        echo '</div>';
        echo '<div class="fl-pro-grd-item-prices">';
        echo '<p><strong>';
        echo $product->get_price_html();
        echo '</strong></p>';
        echo '</div>';
        echo '<div class="fl-pro-grd-item-title mHc">';
        echo '<span class="title-angle"></span>';
        echo '<strong><a href="'.get_permalink( $product->get_id() ).'">';
        echo get_the_title(); 
        echo '</a></strong>';
        echo '</div>';
        echo '</div>';
        
    }
}



/*Remove Single page Woocommerce Hooks & Filters are below*/



remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action('lottery_price', 'get_lottery_price');

function get_lottery_price(){
    global $product;
    echo '<div class="fl-pro-summary-prices"><div>';
    echo '<label>ENTER FOR:</label>';
    echo $product->get_price_html();
    echo '</div></div>';
}

add_action('wc_product_subtitle', 'get_product_subtitle');
function get_product_subtitle(){
    global $product;
    $product_text = get_field( 'product_text',  $product->get_id());
    if( !empty($product_text) ):
        echo '<h3 class="fl-product-summary-title-1">'.$product_text.'</h3>';
    else:
        echo '<h3 class="fl-product-summary-title-1">ENTER NOW FOR A CHANCE TO WIN THE KEYS TO A</h3>';
    endif;
}

add_action('wc_product_stats', 'get_product_stats');
function get_product_stats(){
    get_template_part('templates/wc/product', 'stats');
}

add_action('wc_product_tab', 'get_single_product_tab');
function get_single_product_tab(){
    get_template_part('templates/wc/product-single', 'tabs');
}

add_action('wc_lottery_countdown', 'get_product_lottery_countdown');
function get_product_lottery_countdown(){
    get_template_part('templates/wc/product-single', 'countdown');
}

add_action('wc_short_description', 'get_product_short_description');
function get_product_short_description(){
    global $product;
    $custom_text = get_field('custom_text', $product->get_id());
    echo '<div class="fl-product-summary-txt">'.wpautop($custom_text).'</div>';
}

// change a number of the breadcrumb defaults.
add_filter( 'woocommerce_breadcrumb_defaults', 'cbv_woocommerce_breadcrumbs' );
if( !function_exists('cbv_woocommerce_breadcrumbs')):
function cbv_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => '',
            'wrap_before' => '<div class="fl-breadcrumbs"><ul class="reset-list">',
            'wrap_after'  => '</ul></div>',
            'before'      => '<li>',
            'after'       => '</li>',
            'home'        => _x( 'home', 'breadcrumb', 'woocommerce' ),
        );
}
endif;



// Front: Calculate new item price and add it as custom cart item data
add_filter('woocommerce_add_cart_item_data', 'add_custom_product_data', 10, 3);
function add_custom_product_data( $cart_item_data, $product_id, $variation_id ) {

    $lottery_tickets_ques = filter_input( INPUT_POST, 'lottery_question' );

    if ( empty($lottery_tickets_ques)) {
        return $cart_item_data;
    }

        $cart_item_data['lottery_question'] = $lottery_tickets_ques;

    
    return $cart_item_data;
}

// Front: Display option in cart item
add_filter('woocommerce_get_item_data', 'display_custom_item_data', 10, 2);

function display_custom_item_data($item_data, $cart_item) {
        if ( empty( $cart_item['lottery_question'] ) ) {
            return $item_data;
        }
        $question = maybe_unserialize( get_post_meta( $cart_item['product_id'], '_lottery_question', true ) );

        $item_data[] = array(
            'key'     => __( 'Question', 'wc-lottery-ques' ),
            'value'   => wc_clean( $cart_item['lottery_question'] ),
            'display' => isset( $question[ $cart_item['lottery_question'] ] ['text'] ) ? $question[ $cart_item['lottery_question'] ]['text']  : '',
        );


        return $item_data;
}


// Save and display custom fields in order item meta
add_action( 'woocommerce_add_order_item_meta', 'add_custom_fields_order_item_meta', 20, 3 );
function add_custom_fields_order_item_meta( $item_id, $cart_item, $cart_item_key ) {

    if ( isset($cart_item['lottery_question']) ) {
        wc_add_order_item_meta($item_id, 'Question', $cart_item['lottery_question']);
        
    }

}

//add_filter( 'woocommerce_sale_flash', 'lw_replace_sale_text' );
function lw_replace_sale_text( $html ) {
return str_replace( __( 'Sale!', 'woocommerce' ), __( 'Offer Discount', 'woocommerce' ), $html );
}