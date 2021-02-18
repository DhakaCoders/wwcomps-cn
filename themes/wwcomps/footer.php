<?php 
  $logoObj = get_field('logo_footer', 'options');
  if( is_array($logoObj) ){
    $logo_tag = '<img src="'.$logoObj['url'].'" alt="'.$logoObj['alt'].'" title="'.$logoObj['title'].'">';
  }else{
    $logo_tag = '';
  }
  $address = get_field('address', 'options');
  $map_url = get_field('map_url', 'options');
  $gmaplink = !empty($map_url)?$map_url: 'javascript:void()';
  $telephone = get_field('telephone', 'options');
  $emailadres = get_field('email_address', 'options');
  $aboutus = get_field('aboutus', 'options');
  $copyright_text = get_field('copyright_text', 'options');
?>
<footer class="footer-wrp">
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="ftr-cols">
            <div class="ftr-col-1 ftr-col ">
              <div class="ftr-intro-col">
                <?php if( !empty( $logo_tag ) ) :?>
                <div class="ftr-logo">
                  <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo $logo_tag; ?>
                  </a>
                </div>
                <?php endif; ?>
                <?php if( !empty($aboutus['description']) ): ?>
                <div class="ftr-intro-col-des">
                  <?php echo wpautop($aboutus['description']); ?>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="ftr-col-2 ftr-col ">
              <div class="ftr-col-2-con">
                <h5 class="fl-h5 ftr-heading"><?php _e( 'SITE LINKS', THEME_NAME ); ?></h5>
                <div class="ftr-collapse-con">
                  <?php 
                    $mmenuOptions = array( 
                        'theme_location' => 'cbv_fta_menu1', 
                        'menu_class' => 'clearfix',
                        'container' => false,
                      );
                    wp_nav_menu( $mmenuOptions ); 
                  ?>
                </div>
              </div>
            </div>
            <div class="ftr-col-3 ftr-col ">
              <div class="ftr-col-3-con">
                  <h5 class="fl-h5 ftr-heading"><?php _e( 'WILD LINKS', THEME_NAME ); ?></h5>
                  <div class="ftr-collapse-con">
                  <?php 
                    $mmenuOptions = array( 
                        'theme_location' => 'cbv_fta_menu2', 
                        'menu_class' => 'clearfix',
                        'container' => false,
                      );
                    wp_nav_menu( $mmenuOptions ); 
                  ?>
                  </div>
                </div>
            </div>
            <div class="ftr-col-4 ftr-col ">
              <div class="ftr-col-4-con">
                  <h5 class="fl-h5 ftr-heading"><?php _e( 'CONTACT INFO', THEME_NAME ); ?></h5>
                  <div class="ftr-collapse-con">
                    <?php if( !empty($address) ): ?>
                    <div class="ftr-contact-info-row">
                      <strong class="hide-sm"><?php _e( 'ADDRESS', THEME_NAME ); ?>:</strong>
                      <div><?php printf('<a target="_blank" href="%s">%s</a>', $gmaplink, $address); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if( !empty($telephone) ): ?>
                    <div class="ftr-contact-info-row">
                      <strong><?php _e( 'PHONE', THEME_NAME ); ?>:</strong>
                      <div>
                        <?php printf('<a href="tel:%s">%s</a>', phone_preg($telephone), $telephone); ?>
                      </div>
                    </div>
                    <?php endif; ?>
                    <?php if( !empty($emailadres) ): ?>
                    <div class="ftr-contact-info-row">
                      <strong><?php _e( 'EMAIL', THEME_NAME ); ?>:</strong>
                      <div>
                        <?php printf('<a href="mailto:%s">%s</a>', $emailadres, $emailadres); ?>
                      </div>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-btm">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
            <div class="ftr-btm-con">
              <div class="copyright-text">
                <?php if( !empty($copyright_text) ) printf('<p>%s</p>', $copyright_text); ?>
              </div>
              <div class="copyright-menu">
                <?php 
                  $mmenuOptions = array( 
                      'theme_location' => 'cbv_copyright_menu', 
                      'menu_class' => 'reset-list',
                      'container' => false,
                    );
                  wp_nav_menu( $mmenuOptions ); 
                ?>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div> 
</footer>
<?php wp_footer(); ?>
</body>
</html>