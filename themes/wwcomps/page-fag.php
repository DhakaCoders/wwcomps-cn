<?php
/*
  Template Name: FAQ
*/
get_header(); 
$thisID = get_the_ID();
$intro = get_field('introsec', $thisID);
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

<section class="page-banner-section how-it-works-sec faqs-bnr-pg-sec">
  <div class="banner-bg">
    <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-lft-img.jpg')"></div>
    <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/faqs-bnr-page-rgt-img.jpg')"></div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-bnr-desc faqs-bnr-pg-desc">
          <?php if( !empty($intro['title']) ) printf('<h2 class="fl-h1">%s</h2>', $intro['title'] ); ?>
         <?php 
            $srooms_query = new WP_Query(array( 
              'post_type'=> 'faq',
              'post_status' => 'publish',
              'posts_per_page' => -1,
              'orderby' => 'date',
              'order'=> 'desc'
              ) 
            );
          ?>
          <?php if($srooms_query->have_posts()): ?>
          <div class="hh-accordion-tab-cntlr">
            <?php while($srooms_query->have_posts()): $srooms_query->the_post(); ?>
            <div class="hh-accordion-tab-row">
                <h3 class="hh-accordion-title"><span></span><?php the_title(); ?></h3>
                <div class="hh-accordion-des">
                  <div>
                    <?php the_content(); ?>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
          <?php 
            endif;  
            wp_reset_postdata();
          ?>
          <span>
            <img class="desktop" src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white.png" alt="">
            <img class="mobile" src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white-res.png" alt="">
          </span>
        </div>
      </div>
    </div>
  </div>    
</section>

<?php get_footer(); ?>