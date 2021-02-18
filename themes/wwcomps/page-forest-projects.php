<?php
/*
  Template Name: Forest Projects
*/
get_header(); 
$thisID = get_the_ID();
?>
<section class="fl-angle-hdr-cntlr">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><span>FOREST</span> PROJECTS</h2>  
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>

<section class="page-banner-section forest-proj-sec">
  <?php  
    $slides = get_field('slidessec', $thisID);
    if($slides):
  ?>
  <div class="forest-proj-slider-cntlr">
    <?php if( $slides['gallery'] ): ?>
    <div class="forest-proj-slider frstProjSlider">
      <?php foreach( $slides['gallery'] as $gllID ): ?>
       <div class="forest-proj-slide-item">
          <div class="banner-bg">
            <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/hit-bnr-page-lft-img.jpg')"></div>
            <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo cbv_get_image_src($gllID); ?>')"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>

  <div class="forest-proj-slider-content">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="lucky-winner-desc-cntlr">
            <div class="lw-slide-item-desc forest-proj-slide-item-des">
              <i>
                <img class="desktop" src="<?php echo THEME_URI; ?>/assets/images/forest-proj-angle.png" alt="">
                <img class="mobile" src="<?php echo THEME_URI; ?>/assets/images/forest-project-res.png" alt="">
              </i>
              <?php 
              if( !empty($slides['title'])) printf('<h2 class="lw-slide-item-title">%s</h2>', $slides['title']); 
              if( !empty($slides['description'])) echo wpautop( $slides['description'] );
              ?>
              <div class="counter-up-cntlr">
                <span><img src="<?php echo THEME_URI; ?>/assets/images/fp-angel-img.png" alt=""></span>
                <ul class="reset-list">
                  <?php  
                  $money = $slides['money_raised'];
                  if($money): 
                  ?>
                  <li>
                    <div class="counter-item">
                      <div class="counter-item-img"><img src="<?php echo THEME_URI; ?>/assets/images/frst-proj-img1.png" alt=""></div>
                      <?php 
                        if( !empty($money['value'])) printf('<strong class="number-of-counter">£%s</strong>', $money['value']);
                        if( !empty($money['title'])) printf('<h6 class="counter-item-sub-title">%s</h6>', $money['title']);
                      ?>
                    </div>
                  </li>
                  <?php endif; ?>
                  <?php 
                  $planted = $slides['trees_planted'];
                  if($planted): 
                  ?>
                  <li>
                    <div class="counter-item">
                      <div class="counter-item-img"><img src="<?php echo THEME_URI; ?>/assets/images/frst-proj-img2.png" alt=""></div>
                      <?php 
                        if( !empty($planted['value'])) printf('<strong class="number-of-counter"><span>%s+</span></strong>', $planted['value']);
                        if( !empty($planted['title'])) printf('<h6 class="counter-item-sub-title">%s</h6>', $planted['title']);
                      ?>
                    </div>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</section>
<?php
  $showhideprojects = get_field('showhideprojects', $thisID);
  if( $showhideprojects ):
    $projects = get_field('ourprojects', $thisID);
    $projectsIDs = $projects['select_projects'];
    $projectTitle =  !empty($projects['title'])? $projects['title']: '<span>OUR</span> PROJECTS';
?>
<section class="our-project-sec inline-bg" style="background: url(<?php echo THEME_URI; ?>/assets/images/forest-project-bg.jpg)">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><?php echo $projectTitle; ?></h2> 
          </div>
        </div>
      </div>
    </div> 
  </div>
  <?php 
    if( !empty($projectsIDs) ){
      $projectscount = count($projectsIDs);
      $query = new WP_Query(array(
        'post_type' => 'projects',
        'posts_per_page'=> $projectscount,
        'post__in' => $projectsIDs,
        'orderby' => 'rand'

      ));
          
    }else{
      $query = new WP_Query(array(
        'post_type' => 'projects',
        'posts_per_page'=> -1,
        'orderby' => 'date',
        'order'=> 'asc',

      ));
    }
  if( $query->have_posts() ): 
  ?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="our-project-sec-cntlr">
          <div class="our-project-grid-items">
            <ul class="ulc reset-list our-proj-grid">
            <?php 
                while($query->have_posts()): $query->the_post();
                $location = get_field('location', get_the_ID());
                $imageID = get_field('image', get_the_ID());
                $describ = get_field('description', get_the_ID());
                $imagesrc = !empty($imageID)? cbv_get_image_src($imageID):'';
                $imagetag = !empty($imageID)? cbv_get_image_tag($imageID, 'projectgrid'):'';
              ?> 
               <li class="our-proj-grid-item">
                <div class="our-project-item">
                  <div class="project-img">
                    <?php echo $imagetag; ?>
                    <div class="proj-btn">
                      <a data-fancybox="gallery" data-caption="<?php echo get_the_title(); ?>" href="<?php echo $imagesrc; ?>" class="fl-btn" href="#">VIEW GALLERY</a>
                      <a class="fl-btn" href="#">PLAY 2 PLANT</a>
                    </div>
                  </div>
                  <div class="pur-proj-dec">
                    <h4 class="our-proj-title fl-h4"><a data-fancybox="gallery" data-caption="<?php echo get_the_title(); ?>" href="<?php echo $imagesrc; ?>" href="#"><?php the_title(); ?></a></h4>
                    <div class="proj-location">
                      <?php if( !empty($location)) printf('<span>%s</span>', $location); ?>
                    </div>
                    <?php if( !empty($describ) ) echo wpautop($describ); ?>
                  </div>
                </div>
              </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="forest-bg-div"></div>
  <?php endif; wp_reset_postdata();?>
</section>
<?php endif; ?>
<?php
  $showhidepartners = get_field('showhidepartners', $thisID);
  if( $showhidepartners ):
    $partners = get_field('ourpartners', $thisID);
    $logos = $partners['logos'];
    $partnerTitle =  !empty($partners['title'])? $partners['title']: '<span>OUR</span> PARTNERS';
?>
<section class="partners-sec">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><?php echo $partnerTitle; ?></h2> 
          </div>
        </div>
      </div>
    </div> 
  </div>
  <?php if( $logos ): ?>
  <div class="container">
    <div class="row">
      <div class="col sm-12">
        <div class="partners-sec-cntlr">
          <ul class="ulc reset-list">
            <?php foreach( $logos as $logoID ): ?>
            <li>
              <div class="partners-logo">
                <?php echo cbv_get_image_tag($logoID); ?>
              </div>
            </li>
          <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</section>
<?php endif; ?>
<?php get_footer(); ?>