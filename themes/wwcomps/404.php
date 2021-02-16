<?php
get_header();
?>
<section class="main-content page-404-wrp">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-404 text-center">
                    <span style="transform: rotate(180deg); display: inline-block;">
                      <i><img src="<?php echo THEME_URI; ?>/assets/images/team-border-btm.svg"></i>
                    </span>
                    <h1>404</h1>
                    <p><?php _e( 'It looks like nothing was found at this location.', THEME_NAME ); ?></p>
                    <span>
                      <i><img src="<?php echo THEME_URI; ?>/assets/images/team-border-btm.svg"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section><!-- end of main content -->
<?php 
  get_footer();
?>