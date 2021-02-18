<?php
/*
  Template Name: How It Works
*/
get_header(); 
$thisID = get_the_ID();
$intro = get_field('introsec', $thisID);
$shopID = get_option( 'woocommerce_shop_page_id' );
?>
<section class="fl-angle-hdr-cntlr">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>HOW IT</span> WORKS</h2> 
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>

<section class="page-banner-section how-it-works-sec">
  <div class="banner-bg">
    <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-lft-img.jpg')"></div>
    <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-rgt-img.jpg')"></div>
  </div>
  <div class="page-banner-sec-cntlr">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-bnr-desc">
        <?php 
          if( !empty($intro['title']) ) printf('<h2 class="fl-h1">%s</h2>', $intro['title'] ); 
          if( !empty($intro['subtitle1']) ) printf('<h3 class="fl-h3">%s</h3>', $intro['subtitle1'] ); 
          if( !empty($intro['subtitle2']) ) printf('<strong>%s</strong>', $intro['subtitle2'] ); 
        ?>
          <div>
            <?php if(!empty($intro['description'])) echo wpautop( $intro['description'], true ); ?>
          </div>
          <a class="fl-btn" href="<?php echo get_permalink($shopID); ?>">SEE COMPETITIONS</a>
          <span>
            <img class="desktop" src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white.png" alt="">
            <img class="mobile" src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white-res.png" alt="">
          </span>
        </div>
      </div>
    </div>
  </div>    
  </div>    
</section>
<?php get_footer(); ?>