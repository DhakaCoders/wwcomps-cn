<?php
/*
  Template Name: Gallery
*/
get_header(); 
$thisID = get_the_ID();
$intro = get_field('introsec', $thisID);
?>
<section class="gallery-sec">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>FOREST</span> GALLERY</h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
  
  <div class="gallery-sec-bg inline-bg" style="background-image: url('<?php echo THEME_URI; ?>/assets/images/galler-sec-bg.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="gallery-sec-ctlr">
            <?php 
              $sort = 'desc';
              $query = new WP_Query(array( 
                'post_type'=> 'gallery',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order'=> $sort
                ) 
              );
            ?>
            <?php 
            if($query->have_posts()): 
            ?>
            <ul class="reset-list clearfix">
            <?php 
              while($query->have_posts()): $query->the_post();
                $location = get_field('location', get_the_ID());
                $imageID = get_field('image', get_the_ID());
                $imagesrc = !empty($imageID)? cbv_get_image_src($imageID, 'gallerygrid'):'';
            ?>
              <li>
                <div class="gallery-grd-item">
                  <div class="gallery-triangle"></div>
                  <div class="gallery-grd-item-icon">
                    <img src="<?php echo THEME_URI; ?>/assets/images/gallery-grd-item-icon-01.png">
                  </div>
                  <div class="gallery-grd-item-img-ctlr">
                    <div class="gallery-grd-item-img inline-bg" style="background: url('<?php echo $imagesrc; ?>');">
                      
                    </div>
                    <div class="gallery-grd-item-overlay-img">
                      <div class="gallery-grd-item-overlay-btn">
                        <a class="fl-btn" data-fancybox="gallery" data-caption="<?php echo get_the_title(); ?>" href="<?php echo $imagesrc; ?>" href="#">view gallery</a>
                      </div>
                    </div>
                  </div>
                  <div class="gallery-grd-item-des mHc">
                    <div class="gallery-grd-item-des-hdr">
                      <h5 class="gallery-gid-title mHc1"><a data-fancybox="gallery" data-caption="<?php echo get_the_title(); ?>" href="<?php echo $imagesrc; ?>" href="#"><?php the_title(); ?></a></h5>
                    </div>
                    <div class="gallery-place-date">
                      <div class="gallery-place">
                        <span><i class="fas fa-map-marker-alt"></i><?php if( !empty($location)) printf('%s', $location); ?></span>
                      </div>
                      <div class="galley-date">
                        <span><i class="fas fa-calendar-alt"></i> 14.01.2021</span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <?php endwhile; ?>
            </ul>
            <?php 
              else:
                echo '<div class="no-results">No Results.</div>';
              endif;  
              wp_reset_postdata();
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>    
</section>
<?php get_footer(); ?>