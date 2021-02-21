<?php
/*Template Name: Live Draws*/
get_header();
$thisID = get_the_ID();
$custom_page_title = get_post_meta( $thisID, '_custom_page_title', true );
$page_title = (isset( $custom_page_title ) && !empty($custom_page_title)) ? $custom_page_title : get_the_title();
?>
<section class="live-draws-sec">
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

  <div class="live-draws inline-bg" style="background: url('<?php echo THEME_URI; ?>/assets/images/live-draws-bg-img.jpg');">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="live-draws-cntlr">
              <?php 
                $live_embed = get_field('live_embed', $thisID);
                if( !empty($live_embed) ):
              ?>
                <div class="fbvideo-live">
                  <div class="fb-video-embaded">
                    <?php echo $live_embed; ?>
                  </div>
                </div>
              <?php else: ?>
              <h1 class="live-draws-title fl-h2">NOT CURRENTLY LIVE</h1>
              <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<section class="previous-draws-sec">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>PREVIOUS</span> DRAWS</h2>
          </div>
        </div>
      </div>
    </div> 
  </div>

  <div class="previous-draws">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="previous-draws-grids-cntlr">
          <?php 
            $sort = 'desc';
            $query = new WP_Query(array( 
              'post_type'=> 'live_draws',
              'post_status' => 'publish',
              'posts_per_page' => 6,
              'orderby' => 'date',
              'order'=> $sort
              ) 
            );
          ?>
          <?php 
          if($query->have_posts()): 
          ?>
            <ul class="reset-list">
            <?php 
              while($query->have_posts()): $query->the_post();
                $embaded_v = get_field('video_embaded', get_the_ID());
                if( !empty($embaded_v) ):
            ?>
              <li>
                <div class="prev-grid-item">
                  <div class="fb-video-embaded">
                    <?php echo $embaded_v; ?>
                  </div>
                </div>
              </li>
              <?php endif; ?>
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
<?php
get_footer();
?>