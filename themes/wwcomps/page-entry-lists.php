<?php 
/*Template Name: Entry Lists*/
get_header();
$thisID = get_the_ID();
?>
<section class="entry-lists">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>ENTRY</span> LISTS</h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
  
  <div class="entry-list-bg inline-bg" style="background-image: url('<?php echo THEME_URI; ?>/assets/images/entry-list.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <?php 
            $sort = 'desc';
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $query = new WP_Query(array( 
              'post_type'=> 'entrylist',
              'post_status' => 'publish',
              'posts_per_page' => 3,
              'paged' => $paged,
              'orderby' => 'date',
              'order'=> $sort
              ) 
            );
          ?>
          <?php 
          if($query->have_posts()): 
          ?>
          <div class="entry-grid-list-items">
            <ul class="clear-fix reset-list">
              <?php 
                while($query->have_posts()): $query->the_post();
                  $intro = get_field('intro', get_the_ID());
              ?>
              <li>
                <div class="entry-list-item mHc">
                  <div class="title-bar mHc1">
                    <h5 class="fl-h5 entty-item-title"><a href="#"><?php the_title(); ?></a></h5>
                  </div>
                  <?php if( !empty($intro['published_date']) ) printf('<div class="publish-date"><p>Publish on:<span> %s</span></p></div>', $intro['published_date']); ?>
                  <?php if( !empty($intro['draw_date']) ) printf('<div class="drwan-date"><p>Drawn on:<span> %s</span></p></div>', $intro['draw_date']); ?>
                  <?php if( !empty($intro['download_file']) ): ?>
                    <a class="fl-btn entry-lst-btn" href="<?php echo $intro['download_file']; ?>" target="_blank">view</a>
                  <?php endif; ?>
                </div>
              </li>
              <?php endwhile; ?>
            </ul>
          </div>
          <?php if( $query->max_num_pages > 1 ): ?>  
          <div class="fl-pagi-cntlr">
            <?php 
              $big = 999999999; // need an unlikely integer
              echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $query->max_num_pages,
                'type'  => 'list',
                'show_all' => false,
                'prev_text' => '',
                'next_text' => '',
                'end_size'  => 3,
                'mid_size'  => 3,
              ) ); 
            ?>
          </div>
          <?php endif; ?>
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
</section>
<?php
get_footer();
?>