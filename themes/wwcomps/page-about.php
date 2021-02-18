<?php
/*
  Template Name: About Us
*/
get_header(); 
$thisID = get_the_ID();
$intro = get_field('introsec', $thisID);
$bgimg = !empty($intro['image'])?cbv_get_image_src($intro['image']):THEME_URI.'/<?php echo THEME_URI; ?>/assets/images/ab-bnr-page-rgt-img.jpg';
?>
<section class="fl-angle-hdr-cntlr">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>ABOUT</span> US</h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>

<section class="page-banner-section how-it-works-sec">
  <div class="banner-bg">
    <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-lft-img.jpg')"></div>
    <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo $bgimg; ?>')"></div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-bnr-desc about-pg-bnr-desc">
        <?php 
          if( !empty($intro['title']) ) printf('<h2>%s</h2>', $intro['title'] ); 
          if( !empty($intro['subtitle']) ) printf('<h3 class="fl-h3">%s</h3>', $intro['subtitle'] ); 
        ?>
          <div>
          <?php if(!empty($intro['description'])) echo wpautop( $intro['description']); ?>
          </div>
          <?php 
          $link = $intro['link'];
          if( is_array( $link ) &&  !empty( $link['url'] ) ){
              printf('<a class="fl-btn" href="%s" target="%s">%s</a>', $link['url'], $link['target'], $link['title']); 
          }
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