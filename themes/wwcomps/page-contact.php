<?php
/*
  Template Name: Contact Us
*/
get_header(); 
$thisID = get_the_ID();
$forms = get_field('formshortcode', $thisID);
$continfo = get_field('contactsec', $thisID);
$bgimg = !empty($continfo['image'])?cbv_get_image_src($continfo['image']):THEME_URI.'/assets/images/cu-bnr-rgt-img.jpg';

$custom_page_title = get_post_meta( $thisID, '_custom_page_title', true );
$page_title = (isset( $custom_page_title ) && !empty($custom_page_title)) ? $custom_page_title : get_the_title();
?>

<section class="fl-angle-hdr-cntlr">
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
</section>

<section class="page-banner-section contact-us-sec">
  <div class="banner-bg contact-bg">
    <div class="bnr-bg-lft inline-bg" style="background-image:url('<?php echo THEME_URI; ?>/assets/images/cu-bnr-lft-img.jpg')"></div>
    <div class="bnr-bg-rgt inline-bg" style="background-image:url('<?php echo $bgimg; ?>')"></div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="page-bnr-desc cnt-page-des">
          <?php 
            if(!empty($forms['title'])) printf('<h2 class="fl-h1">%s</h2>', $forms['title']);
            if(!empty($forms['description'])) echo wpautop( $forms['description']); 
          ?>
          <div class="cnt-form">
          <?php if(!empty($forms['form_shortcode'])) echo do_shortcode( $forms['form_shortcode'] ); ?>
          </div>
          <a class="" href="#"></a>
          <span>
            <img class="desktop angle-desk" src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white-2.png" alt="">
            <img class="mobile angle-mobile" src="<?php echo THEME_URI; ?>/assets/images/angle-rgt-white-res-3.png" alt="">
          </span>
        </div>
      </div>
    </div>
  </div>    
</section>
<?php 
$conttitle = !empty($continfo['title'])?$continfo['title']:'<span>WAYS TO</span> CONTACT US';
$gmaplink = !empty($continfo['googlemap_url'])?$continfo['googlemap_url']: 'javascript:void()';
?>
<section class="fl-angle-hdr-cntlr">
  <div class="fl-angle-hdr">
    <span class="fl-angle-hdr-join"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="fl-angle-sec-hdr">
            <h2 class="fl-h5 flash-title"><?php echo $conttitle; ?></h2>
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>

<section class="quick-cnt-links">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="qck-lnk-cntrl">
          <?php if($continfo): ?>
          <ul class="clearfix reset-list">
            
            <li>
              <div class="qck-lnk-item mHc">
                <div class="qck-lnk-img">
                 <img src="<?php echo THEME_URI; ?>/assets/images/location-ico.png" alt="">
                </div>
                <div class="qck-lnk-item-des inline-bg mHc1">
                  <h5 class="fl-h5 qck-lnk-title">address</h5>
                  <?php if(!empty($continfo['address'])): ?>
                  <address>
                    <a href="<?php echo $gmaplink; ?>"><?php echo $continfo['address']; ?></a>
                  </address>
                  <?php endif; ?>
                </div>  
              </div>    
            </li>
            <li>
              <div class="qck-lnk-item mHc">
                <div class="qck-lnk-img">
                 <img src="<?php echo THEME_URI; ?>/assets/images/phone-icon.png" alt="">
                </div>
                <div class="qck-lnk-item-des inline-bg mHc1">
                  <h5 class="fl-h5 qck-lnk-title">telephone</h5>
                  <?php 
                    if(!empty($continfo['telephone1'])) printf('<a href="tel:%s">%s</a>', phone_preg($continfo['telephone1']), $continfo['telephone1']); 
                    if(!empty($continfo['telephone2'])) printf('<a href="tel:%s">%s</a>', phone_preg($continfo['telephone2']), $continfo['telephone2']); 
                    if(!empty($continfo['telephone3'])) printf('<a href="tel:%s">%s</a>', phone_preg($continfo['telephone3']), $continfo['telephone3']); 
                  ?>
                </div>  
              </div>    
            </li>
            <li>
              <div class="qck-lnk-item mHc">
                <div class="qck-lnk-img">
                 <img src="<?php echo THEME_URI; ?>/assets/images/mail-icon.png" alt="">
                </div>
                <div class="qck-lnk-item-des inline-bg mHc1">
                  <h5 class="fl-h5 qck-lnk-title">email</h5>
                  <?php 
                    if(!empty($continfo['email_address1'])) printf('<a href="mailto::%s">%s</a>', $continfo['email_address1'], $continfo['email_address1']); 
                    if(!empty($continfo['email_address2'])) printf('<a href="mailto::%s">%s</a>', $continfo['email_address2'], $continfo['email_address2']); 
                    if(!empty($continfo['email_address3'])) printf('<a href="mailto::%s">%s</a>', $continfo['email_address3'], $continfo['email_address3']); 
                  ?>
                </div>  
              </div>    
            </li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>