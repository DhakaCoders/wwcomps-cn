<?php 
get_header(); ?>
<?php  
  $slides = get_field('slidessec', HOMEID);
  if($slides):
?>
<section class="lucky-winner-banner page-banner-section">
  <?php if( $slides['slides'] ): ?>
  <div class="lw-bnr-slider lwBnrSlider">
    <?php 
    foreach( $slides['slides'] as $slide ): 
      $slideImg = !empty($slide['image'])? cbv_get_image_src($slide['image'], 'hmslide'):'';
      $currency_symbol = get_woocommerce_currency_symbol();
    ?>
    <div class="lw-bnr-item-cntlr">
      <div class="lw-bnr-item">
        <div class="banner-bg lucky-winner-banner-bg">
          <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-lft-img.jpg')"></div>
          <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo $slideImg; ?>')"></div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="lucky-winner-desc-cntlr">
                <div class="lw-slide-item-desc">
                  <i><img src="assets/images/white-angle-right.png" alt=""></i>
                  <span class="lw-slide-item-desc-top-arrow"><img src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white-res.png" alt=""></span>
                    <?php 
                      if( !empty($slide['title'])) printf('<h2 class="lw-slide-item-title">%s</h2>', $slide['title']); 
                      if( !empty($slide['title'])) printf('<h3 class="lw-slide-item-sub-title fl-h2">%s</h3>', $slide['title']); 
                      if( !empty($slide['description'])) echo wpautop( $slide['description'] );
                    ?>
                    <div class="lw-si-prize-cntlr">
                      <strong>TICKET PRICE: </strong>
                      
                      <span class="price">
                        <?php if( !empty($slide['regular_price']) && !empty($slide['sale_price']) ): ?>
                        <span class="woocommerce-Price-amount amount">
                          <bdi>
                            <span class="woocommerce-Price-currencySymbol"><?php if(!empty($currency_symbol)) printf('%s', $currency_symbol); ?></span><?php echo $slide['sale_price']; ?>
                          </bdi>
                        </span>
                        <del><span class="woocommerce-Price-currencySymbol"> <?php if(!empty($currency_symbol)) printf('%s', $currency_symbol); ?></span><?php echo $slide['regular_price']; ?></del>
                        <?php else: ?>
                          <span class="woocommerce-Price-amount amount">
                          <bdi>
                            <span class="woocommerce-Price-currencySymbol"> <?php if(!empty($currency_symbol)) printf('%s', $currency_symbol); ?></span>
                            <?php echo $slide['regular_price']; ?>
                          </bdi>
                        </span>
                      <?php endif; ?>
                      </span>

                    </div>
                    <?php if( !empty($slide['link']) ): ?>
                    <div class="lw-si-btn">
                      <a class="fl-btn" target="_blank" href="<?php echo $slide['link']; ?>">ENTER NOW</a>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
<?php endif; ?>
<?php
  $showhidecounter = get_field('showhidecounter', HOMEID);
  if( $showhidecounter ):
    $counterups = get_field('counterups', HOMEID);
?>
<section class="counter-up-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="counter-up-cntlr">
          <?php if( $counterups ): ?>
          <ul class="reset-list">
            <?php 
            foreach( $counterups as  $counter ): 
            $symbol = !empty($counter['symbol'])?$counter['symbol']:'';
            ?>
            <li>
              <div class="counter-item">
                <div class="counter-item-img">
                <?php if(!empty($counter['icon'])) echo cbv_get_image_tag($counter['icon']); ?>
                </div>
                <?php 
                if( !empty($counter['value'])) printf('<strong class="number-of-counter"><span class="counter">%s</span>%s</strong>', $counter['value'], $symbol); 

                if( !empty($counter['title'])) printf('<h6 class="counter-item-sub-title">%s</h6>', $counter['title']); 
                ?>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<?php
  $showhidencomp = get_field('showhidencomp', HOMEID);
  if( $showhidencomp ):
    $newcomp = get_field('newcompetitions', HOMEID);
    $competitions = $newcomp['competitions'];
    $newcompTitle =  !empty($newcomp['title'])? $newcomp['title']: '<span>LATEST</span> COMPETITIONS';
?>
<section class="lates-compititions-section">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><?php echo $newcompTitle; ?></h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
  <?php if( $competitions ): ?>
  <div class="lates-compititions-sec-con inline-bg" style="background: url(<?php echo THEME_URI; ?>/assets/images/lates-winners-sec-con-bg.jpg);">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
             <div class="lates-winners-slider-filter">
                 <div class="filter-tabbar">
                  <ul class="filter reset-list">
                    <li class="active"><a data-filter="all"  class="all" href="#">ALL</a></li>
                    <?php 
                      foreach( $competitions as $compcat ): 
                      $comp_term = $compcat['select_category'];
                    ?>
                      <li><a data-filter="<?php echo $comp_term->slug; ?>" class="<?php echo $comp_term->slug; ?>" href="#"><?php echo $comp_term->name; ?></a></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
             </div>
          </div>
          <?php 
            global $woocommerce;
            $meta_query = $woocommerce->query->get_meta_query();
          ?>
          <div class="col-md-12">
            <div class="filter-slider-cntlr">
              <div class="latesCompititionsSlider">
                <?php 
                  $allargs = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'desc',
                    'meta_query' => $meta_query,
                    'tax_query' => array(
                      array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'lottery')
                    ),
                    'is_lottery_archive' => TRUE
                  ); 
                $allquery = new WP_Query($allargs);
                if( $allquery->have_posts() ): 
                while($allquery->have_posts()): $allquery->the_post();
                global $product;
                $thumID = get_post_thumbnail_id(get_the_ID());
                $thumurl = !empty($thumID)? cbv_get_image_src($thumID):'';
                $pp_max_ticket = get_post_meta(get_the_ID(), '_max_tickets_per_user', true);
                ?> 
                <div class="latesCompititionsSlideItem all">
                  <div class="product-grd-item">
                    <div class="pro-fea-img-cntlr">
                      <a class="overlay-link" href="<?php echo get_permalink(); ?>"></a>
                      <div class="inline-bg" style="background: url(<?php echo $thumurl; ?>);"></div>
                      <div class="pro-absolute-text">
                        <span>NEW PRICE!</span>
                      </div>
                    </div>
                    <div class="product-grd-item-des mHc">
                      <h3 class="fl-h6 pgid-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                      <div class="pro-grd-price">
                        <?php echo $product->get_price_html(); ?>
                      </div>
                    </div>
                    <div class="pro-grd-ftr-bar">
                      <div class="pro-grd-date">
                        <i><img src="<?php echo THEME_URI; ?>/assets/images/days-icon.svg"></i>
                        <span>14 days left</span>
                      </div>
                      <?php if( !empty($pp_max_ticket) ): ?>
                      <div class="pro-grd-time">
                        <i><img src="<?php echo THEME_URI; ?>/assets/images/avater-icon.svg"></i>
                        <span><?php echo $pp_max_ticket; ?> tickets pp</span>
                      </div>
                    <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
                <?php endif; wp_reset_postdata(); ?>
                <?php 
                  foreach( $competitions as $compcat ): 
                  $comp_term = $compcat['select_category'];

                  $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'desc',
                    'meta_query' => $meta_query,
                    'tax_query' => array(
                      'relation' => 'AND',
                      array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $comp_term->term_id,
                      ),
                      array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'lottery')
                    ),
                    'is_lottery_archive' => TRUE
                  ); 
                $query = new WP_Query($args);
                if( $query->have_posts() ): 
                while($query->have_posts()): $query->the_post();
                global $product;
                $thumID = get_post_thumbnail_id(get_the_ID());
                $thumurl = !empty($thumID)? cbv_get_image_src($thumID):'';
                $pp_max_ticket = get_post_meta(get_the_ID(), '_max_tickets_per_user', true);
                ?> 
                <div class="latesCompititionsSlideItem <?php echo $comp_term->slug; ?>">
                  <div class="product-grd-item">
                    <div class="pro-fea-img-cntlr">
                      <a class="overlay-link" href="<?php echo get_permalink(); ?>"></a>
                      <div class="inline-bg" style="background: url(<?php echo $thumurl; ?>);"></div>
                    </div>
                    <div class="product-grd-item-des mHc">
                      <h3 class="fl-h6 pgid-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                      <div class="pro-grd-price">
                        <?php echo $product->get_price_html(); ?>
                      </div>
                    </div>
                    <div class="pro-grd-ftr-bar">
                      <div class="pro-grd-date">
                        <i><img src="<?php echo THEME_URI; ?>/assets/images/days-icon.svg"></i>
                        <span>14 days left</span>
                      </div>
                      <?php if( !empty($pp_max_ticket) ): ?>
                      <div class="pro-grd-time">
                        <i><img src="<?php echo THEME_URI; ?>/assets/images/avater-icon.svg"></i>
                        <span><?php echo $pp_max_ticket; ?> tickets pp</span>
                      </div>
                    <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
                <?php endif; wp_reset_postdata(); ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="lwsc-btn">
              <a class="fl-btn" href="<?php echo get_permalink(get_option( 'woocommerce_shop_page_id' )); ?>">SEE ALL COMPETITIONS</a>
            </div>
          </div>
        </div>
    </div> 
  </div> 
  <?php endif; ?> 
</section>
<?php endif; ?>

<section class="left-right-desc">
<?php
  $showhideintro = get_field('showhideintro', HOMEID);
  if( $showhideintro ):
    $intro = get_field('introsec', HOMEID);
    $bgimg = !empty($intro['image'])?cbv_get_image_src($intro['image']):THEME_URI.'/assets/images/htp-banner-lft-img.jpg';
?>
  <div class="how-to-play-wrap page-banner-section left-right-desc-item right-desc-item">
    <div class="banner-bg htp-banner-bg">
      <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo $bgimg; ?>')"></div>
      <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/htp-banner-rt-img.jpg')"></div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="htp-sec-desc-cntlr">
            <div class="htp-desc-hedding">
              <?php if( !empty($intro['title']) ) printf('<h3 class="htp-desc-title fl-h4">%s</h3>', $intro['title'] ); ?>
              <i><img src="<?php echo THEME_URI; ?>/assets/images/blue-arrow.png" alt=""></i>
            </div>
            <div class="htp-sec-desc">
              <i><img src="<?php echo THEME_URI; ?>/assets/images/white-angle-left.png" alt=""></i>
              <?php 
                if( !empty($intro['subtitle']) ) printf('<h6 class="htp-desc-sub-title fl-h5">%s</h6>', $intro['subtitle'] ); 
                if(!empty($intro['description'])) echo wpautop( $intro['description']);
                $link = $intro['link'];
                if( is_array( $link ) &&  !empty( $link['url'] ) ){
                    printf('<div class="htp-desc-button"><a class="fl-btn" href="%s" target="%s">%s</a></div>', $link['url'], $link['target'], $link['title']); 
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<?php
  $showhideintro_2 = get_field('showhideintro_2', HOMEID);
  if( $showhideintro_2 ):
    $intro2 = get_field('introsec_2', HOMEID);
    $bgimg2 = !empty($intro2['image'])?cbv_get_image_src($intro2['image']):THEME_URI.'/assets/images/rstsn-projects-bnr-rt-img.jpg';
?>
  <div class="how-to-play-wrap page-banner-section rstsn-projects-sec left-right-desc-item">
    <div class="banner-bg rstsn-projects-bg">
      <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-lft-img.jpg')"></div>
      <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo $bgimg2; ?>')"></div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="htp-sec-desc-cntlr rstsn-projects-desc-cntlr">
            <div class="htp-desc-hedding">
              <?php if( !empty($intro2['title']) ) printf('<h3 class="htp-desc-title">%s</h3>', $intro2['title'] ); ?>
              <i><img src="<?php echo THEME_URI; ?>/assets/images/blue-arrow.png" alt=""></i>
            </div>
            <div class="htp-sec-desc rstsn-projects-desc">
              <i><img src="<?php echo THEME_URI; ?>/assets/images/white-angle-right.png" alt=""></i>
              <?php 
                if( !empty($intro['subtitle']) ) printf('<h6 class="htp-desc-sub-title">%s</h6>', $intro['subtitle'] ); 
                if(!empty($intro['description'])) echo wpautop( $intro['description']);
                $link = $intro['link'];
                if( is_array( $link ) &&  !empty( $link['url'] ) ){
                    printf('<div class="htp-desc-button"><a class="fl-btn" href="%s" target="%s">%s</a></div>', $link['url'], $link['target'], $link['title']); 
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
</section>
<?php
  $showhidewinners = get_field('showhidewinners', HOMEID);
  if( $showhidewinners ):
    $winners = get_field('latestwinners', HOMEID);
    $winnercats = !empty($winners['winners'])?$winners['winners']:'';
    $winnersTitle =  !empty($winners['title'])? $winners['title']: '<span>LATEST</span> WINNERS';
?>
<section class="lates-winners-section">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><?php echo $winnersTitle; ?></h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
  <?php if( $winnercats ): ?>
  <div class="lates-winners-sec-con inline-bg" style="background: url(<?php echo THEME_URI; ?>/assets/images/lates-winners-sec-con-bg.jpg);">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
             <div class="lates-winners-slider-filter">
                 <div class="filter-tabbar">
                  <ul class="filter reset-list">
                    <li class="active"><a data-filter="all"  class="all" href="#">ALL</a></li>
                    <?php 
                      foreach( $winnercats as $wincat ): 
                      $win_term = $wincat['select_category'];
                    ?>
                      <li><a data-filter="<?php echo $win_term->slug; ?>" class="<?php echo $win_term->slug; ?>" href="#"><?php echo $win_term->name; ?></a></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
             </div>
          </div>
          <div class="col-md-12">
            <div class="filter-slider-cntlr">
              <div class="winnersSlider">
                <?php 
                  $allwinargs = array(
                    'post_type' => 'competition_winners',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'desc',
                  ); 
                $allwinquery = new WP_Query($allwinargs);
                if( $allwinquery->have_posts() ): 
                  $i = 1;
                while($allwinquery->have_posts()): $allwinquery->the_post();
                  $winthumID = get_post_thumbnail_id(get_the_ID());
                  $winthumurl = !empty($winthumID)? cbv_get_image_src($winthumID):'';
                  $date = get_field('date', get_the_ID());
                ?> 
                <div class="winnersSlideItem winall">
                  <div class="winners-grd-item">
                    <div class="winners-grd-item-fea-img-cntlr">
                      <a class="overlay-link" href="#" data-toggle="modal" data-target="#Modal-<?php echo $i; ?>"></a>
                      <div class="inline-bg" style="background: url(<?php echo $winthumurl; ?>);"></div>
                    </div>
                    <div class="winners-grd-item-des mHc">
                      <h6 class="fl-h6 mHc1 hwgid-title"><a href="#" data-toggle="modal" data-target="#Modal-<?php echo $i; ?>"><?php the_title(); ?></a></h6>
                      <?php if( !empty($date) ) printf('<strong class="winners-grd-date">%s</strong>', $date); ?>
                    </div>
                  </div>
                </div>
                <?php $i++; endwhile; ?>
                <?php endif; wp_reset_postdata(); ?>
                <?php 
                  foreach( $winnercats as $wincat ): 
                  $win_term = $wincat['select_category'];

                  $catwinargs = array(
                    'post_type' => 'competition_winners',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'desc',
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'winners_cat',
                        'field' => 'term_id',
                        'terms' => $win_term->term_id,
                      )
                    )
                  ); 
                $catwinquery = new WP_Query($catwinargs);
                if( $catwinquery->have_posts() ): 
                while($catwinquery->have_posts()): $catwinquery->the_post();
                $winthumID = get_post_thumbnail_id(get_the_ID());
                $winthumurl = !empty($winthumID)? cbv_get_image_src($winthumID):'';
                $date = get_field('date', get_the_ID());
                ?> 
                <div class="winnersSlideItem <?php echo $win_term->slug; ?>">
                  <div class="winners-grd-item">
                    <div class="winners-grd-item-fea-img-cntlr">
                      <a class="overlay-link" href="#"></a>
                      <div class="inline-bg" style="background: url(<?php echo $winthumurl; ?>);"></div>
                    </div>
                    <div class="winners-grd-item-des mHc">
                      <h6 class="fl-h6 mHc1 hwgid-title"><a href="#"><?php the_title(); ?></a></h6>
                      <?php if( !empty($date) ) printf('<strong class="winners-grd-date">%s</strong>', $date); ?>
                    </div>
                  </div>
                  <!-- Modal -->
                  <div class="modal fade" id="Modal-<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby=" Modal-<?php echo $i; ?>Label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title wgid-title-popup" id="Modal-<?php echo $i; ?>Label"><?php the_title(); ?></h5>
                        </div>
                        <div class="modal-body">
                          <i>
                          <?php if( !empty($winthumID) ){ ?>
                              <?php echo cbv_get_image_tag($winthumID); ?>
                            <?php }?>
                          </i>
                          <div class="body-cont">
                            <?php the_content(); ?>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-success pop-btn" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
                <?php endif; wp_reset_postdata(); ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="lwsc-btn">
              <a class="fl-btn" href="<?php echo get_link_by_page_template('page-competition-winners.php'); ?>">SEE ALL WINNERS</a>
            </div>
          </div>
        </div>
    </div> 
  </div> 
  <?php endif; ?>  
</section>
<?php endif; ?>
<?php get_footer(); ?>