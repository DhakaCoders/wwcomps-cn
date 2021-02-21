<?php 
/*Template Name: Ended Competitions*/
get_header(); 
$thisID = get_the_ID();
$custom_page_title = get_post_meta( $thisID, '_custom_page_title', true );
$page_title = (isset( $custom_page_title ) && !empty($custom_page_title)) ? $custom_page_title : get_the_title();
?>
<section class="fl-angle-hdr-cntlr">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><?php echo $page_title; ?></h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>
  <?php 
    global $woocommerce;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $meta_query = $woocommerce->query->get_meta_query();
     $meta_query []= array(
      'key'     => '_lottery_closed',
      'compare' => 'EXISTS',
    );

    $meta_query []=  array( 
      'key' => '_lottery_started',
      'compare' => 'NOT EXISTS',
    );
    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'ignore_sticky_posts' => 1,
      'posts_per_page' => 1,
      'paged' => $paged,
      'orderby' => 'meta_value',
      'order' => $order,
      'meta_query' => $meta_query,
      'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'lottery')),
      'meta_key' => '_lottery_dates_to',
      'is_lottery_archive' => TRUE,
      'show_past_lottery' => TRUE
    );
  $pQuery = new WP_Query( $args );
  ?>
<section class="products-grd-page-con">
  <div class="container">
      <div class="row">
        <?php if( $pQuery->have_posts() ): ?>
        <div class="col-md-12">
            <div class="product-grds-cntlr">
              <ul class="reset-list">
                <?php 
                  while($pQuery->have_posts()): $pQuery->the_post();
                  global $product, $woocommerce, $post; 
                  $thumID = get_post_thumbnail_id($product->get_id());
                  $thumurl = !empty($thumID)? cbv_get_image_src($thumID):'';
                ?>
                <li>
                  <div class="product-grd-item">
                    <div class="pro-fea-img-cntlr">
                      <a class="overlay-link" href="<?php the_permalink(); ?>"></a>
                      <div class="inline-bg" style="background: url(<?php echo $thumurl; ?>);"></div>
                      <div class="pro-absolute-text">
                        <span>ENDED!</span>
                      </div>
                    </div>
                    <div class="product-grd-item-des mHc">
                      <h3 class="fl-h6 pgid-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                      <div class="pro-grd-price">
                        <?php echo $product->get_price_html(); ?>
                      </div>
                    </div>
                  </div>
                </li>
                <?php endwhile; ?>
                
              </ul>
            </div>
        </div>
        <?php if( $pQuery->max_num_pages > 1 ): ?>
        <div class="col-sm-12">
          <div class="products-grd-page-pagi">
            <div class="fl-pagi-cntlr">
            <?php 
              $big = 999999999; // need an unlikely integer
              echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $pQuery->max_num_pages,
                'type'  => 'list',
                'show_all' => false,
                'prev_text' => '',
                'next_text' => '',
                'end_size'  => 3,
                'mid_size'  => 3,
              ) ); 
            ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
          <div class="col-sm-12">
            <div class="no-results">No Results.</div>
          </div>
        <?php endif; wp_reset_postdata(); ?>
      </div>
  </div>    
</section>
<?php get_footer(); ?>