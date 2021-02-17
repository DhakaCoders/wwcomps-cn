<?php get_header(); ?>
<section class="entry-lists">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join" style="width: 140.5px;"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title">
              <?php if( is_cart()){?>
              <span>My</span> Cart
            <?php }else { ?>
              <?php the_title(); ?>
            <?php } ?>
            </h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
  
  <div class="entry-list-bg inline-bg" style="background-image: url('<?php echo THEME_URI; ?>/assets/images/entry-list.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="entry-grid-list-items">
            <?php the_content(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>    
</section>
<?php get_footer(); ?>