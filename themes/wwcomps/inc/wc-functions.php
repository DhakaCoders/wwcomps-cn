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

        echo '<div class="latest-compitions-page-tab-cntlr">';
        echo '<div class="container"><div class="row"><div class="col-md-12">';
        echo '<div class="filter-tabbar">';
        $terms = get_terms( array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0
        ) );
        if( $terms ):
        $curt_term = get_queried_object();
        echo '<ul class="filter reset-list">';
        $shopID = get_option( 'woocommerce_shop_page_id' );
            if(is_product_category()){
                echo '<li><a class="all" href="'.get_permalink($shopID).'">ALL</a></li>';  
            }else{
                echo '<li class="active"><a class="all" href="'.get_permalink($shopID).'">ALL</a></li>';
            }
        
            foreach( $terms as $term ):
                $active = (isset($curt_term->term_id) && ($curt_term->term_id == $term->term_id))?' class="active"':'';
                echo '<li'.$active.'><a class="'.$term->slug.'" href="'.esc_url(get_term_link( $term )).'">'.$term->name.'</a></li>';
            endforeach;
        echo '</ul>';
        endif; 
        echo '</div></div></div></div></div>';
        echo '<div class="products-grd-page-con">';
        echo '<div class="container"><div class="row"><div class="col-md-12">';
        echo '<div class="product-grds-cntlr">';
    }elseif( is_single() ){
        echo '<section class="latest-compitions-page-con-cntlr inline-bg" style="background: url('.THEME_URI.'/assets/images/latest-compitions-page-con-bg.jpg);">';
        echo '<div class="products-grd-page-con">
                <div class="container">
                <div class="row">
                  <div class="col-md-12">
                      <div class="product-grds-cntlr">';
    }


}

function get_custom_wc_output_content_wrapper_end(){
	if(is_shop() OR is_product_category()){ 
    	echo '</div></div></div></div></div></section>';
        get_template_part('templates/recent', 'ended');
	}elseif(is_single()){
        echo '</div></div></div></div></div></section>';
        get_template_part('templates/product-single', 'description');
    }

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

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = 3;
  return $cols;
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
        $thumID = get_post_thumbnail_id($product->get_id());
        $thumurl = !empty($thumID)? cbv_get_image_src($thumID):'';

        echo '<div class="product-grd-item">';
        echo '<div class="pro-fea-img-cntlr">';
        echo '<a class="overlay-link" href="'.get_permalink( $product->get_id() ).'"></a>';
        echo '<div class="inline-bg" style="background: url('.$thumurl.');"></div>';
        echo '<div class="pro-absolute-text">';
        echo '<span>NEW PRICE!</span>';
        echo '</div>';
        echo '</div>';
        echo '<div class="product-grd-item-des mHc" style="height: 117px;">';
        echo '<h3 class="fl-h6 pgid-title"><a href="'.get_permalink( $product->get_id() ).'">'.get_the_title().'</a></h3>';
        echo '<div class="pro-grd-price">';
        echo $product->get_price_html();
        echo '</div>';
        echo '</div>';
        echo '<div class="pro-grd-ftr-bar">';
        echo '<div class="pro-grd-date">';
        echo '<i><img src="'.THEME_URI.'/assets/images/days-icon.svg"></i>';
        echo '<span>14 days left</span>';
        echo '</div>';
        echo '<div class="pro-grd-time">';
        echo '<i><img src="'.THEME_URI.'/assets/images/avater-icon.svg"></i>';
        echo '<span>20 tickets pp</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}


remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action('wc_top_title_text', 'add_custom_text_box_top_title', 5, 0);
function add_custom_text_box_top_title() {
    echo '<p>PLAY AND PLANT FOR A <strong>CHANCE TO WIN</strong></p>';
}

add_action('lottery_price', 'get_lottery_price');

function get_lottery_price(){
    global $product;
    echo '<div class="fl-pro-summary-prices"><div>';
    echo '<label>TICKET PRICE:</label>';
    echo $product->get_price_html();
    echo '</div></div>';
}

add_action('wc_short_description', 'get_product_short_description');
function get_product_short_description(){
    global $product;
    $custom_text = get_the_excerpt();
    echo '<div class="fl-product-summary-txt">'.wpautop($custom_text).'</div>';
}
add_filter( 'woocommerce_get_stock_html', '__return_empty_string', 10, 2 );


function projectnamespace_woocommerce_text( $translated, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        $translated = str_replace(
            array( 'Cart totals' ),
            array( 'Basket Totals'),
            $translated
        );
    }

    return $translated;
}

add_filter( 'gettext', 'projectnamespace_woocommerce_text', 30, 3 );

// Hook in
//add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    unset( $fields['billing']['billing_company'] ); // remove company field
    unset($fields['order']['order_comments']);

     return $fields;
}
