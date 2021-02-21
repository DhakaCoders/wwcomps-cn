<?php 
get_header(); 
while( have_posts() ): the_post();
  $thisID = get_the_ID();
  $custom_page_title = get_post_meta( $thisID, '_custom_page_title', true );
  $page_title = (isset( $custom_page_title ) && !empty($custom_page_title)) ? $custom_page_title : get_the_title();
?>
<section class="entry-lists">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join" style="width: 140.5px;"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title">
              <?php echo $page_title; ?>
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
<?php endwhile; ?>
<?php get_footer(); ?>