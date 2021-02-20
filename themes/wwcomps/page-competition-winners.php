<?php 
/*
Template Name: Competition Winners
*/
get_header();
?>
<section class="winners-sec">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>competitions</span> winners</h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
  <div class="winners-sec-bg inline-bg" style="background-image: url('<?php echo THEME_URI; ?>/assets/images/winr-bg.jpg')">
    <div class="container">
      <div class="row">
		<?php 
		$terms = get_terms( array(
		    'taxonomy' => 'winners_cat',
		    'hide_empty' => true,
		    'parent' => 0
		) );
		if( $terms ):
		?>
        <div class="col-md-12">
          <div class="lates-winners-slider-filter">
            <div class="filter-tabbar winr-tabbar">
              <ul class="filter reset-list">
                <li class="active"><a class="all" href="<?php echo get_link_by_page_template( 'page-competition-winners.php' ); ?>">ALL</a></li>
                <?php foreach( $terms as $term ): ?>
                <li><a data-filter="<?php echo $term->slug; ?>" class="<?php echo $term->name; ?>" href="<?php echo esc_url(get_term_link( $term )); ?>"><?php echo $term->slug; ?></a></li>
            	<?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
    	<?php endif; ?>
        <div class="col-md-12">
		<?php 
		  $sort = 'desc';
		  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		  $query = new WP_Query(array( 
		    'post_type'=> 'competition_winners',
		    'post_status' => 'publish',
		    'posts_per_page' => 4,
		    'paged' => $paged,
		    'orderby' => 'date',
		    'order'=> $sort
		    ) 
		  );
		?>
		<?php 
		if($query->have_posts()): 
		?>
          <div class=" winr-grid-cntrl product-grds-cntlr">
            <ul class="reset-list">
            <?php 
              $i = 1;
              while($query->have_posts()): $query->the_post();
                $attach_id = get_post_thumbnail_id(get_the_ID());
                $date = get_field('date', get_the_ID());
            ?>
              <li>
                <div class="winr-grid-item product-grd-item">
                  <div class="winr-fea-img-cntlr pro-fea-img-cntlr">
                    <a class="overlay-link" href="#" data-toggle="modal" data-target="#Modal-<?php echo $i; ?>"></a>
                    <div class="inline-bg" style="background: url(<?php echo !empty($attach_id)?cbv_get_image_src($attach_id, 'winnergrid'):''; ?>);"></div>
                  </div>
                  <div class="winr-grd-item-des product-grd-item-des mHc">
                    <div class="wgid-title-bar">
                      <h3 class="fl-h6 pgid-title wgid-title"><a href="#" data-toggle="modal" data-target="#Modal-<?php echo $i; ?>"><?php the_title(); ?></a></h3>
                    </div>
                    <div class="winr-grd-date">
                      <?php if( !empty($date) ) printf('<span>%s</span>', $date); ?>
                    </div>
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
                    	<?php if( !empty($attach_id) ){ ?>
                          <?php echo cbv_get_image_tag($attach_id, 'winners_popup'); ?>
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
              </li>
              <?php $i++; endwhile; ?>
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
<?php get_footer(); ?>