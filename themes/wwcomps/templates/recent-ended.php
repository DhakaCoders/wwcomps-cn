<section class="recently-ended-sec-cntlr">
  <div class="fl-angle-hdr-cntlr">
    <div class="fl-angle-hdr">
      <span class="fl-angle-hdr-join" style="width: 140.5px;"></span>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="fl-angle-sec-hdr">
              <h2 class="fl-h5 flash-title"><span>RECENTLY</span> ENDED</h2> 
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
  <?php 
    global $woocommerce;
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
      'posts_per_page' => 6,
      'orderby' => 'meta_value',
      'order' => $order,
      'meta_query' => $meta_query,
      'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'lottery')),
      'meta_key' => '_lottery_dates_to',
      'is_lottery_archive' => TRUE,
      'show_past_lottery' => TRUE
    );
  $pQuery = new WP_Query( $args );
  if( $pQuery->have_posts() ):
  ?>
  <div class="products-grd-page-con">
    <div class="container">
        <div class="row">
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
                      <div class="product-grd-item-des mHc" style="height: 115px;">
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
          <div class="col-sm-12">
            <div class="recently-ended-view-more-btn">
              <a class="fl-btn" href="<?php echo get_link_by_page_template( 'page-ended-competitions.php' ); ?>">SEE ALL ENDED </a>
            </div>
          </div>
        </div>
    </div>    
  </div>
  <?php endif; wp_reset_postdata(); ?>
</section>