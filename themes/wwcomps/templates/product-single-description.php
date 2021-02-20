<?php
global $product;
$description = get_field('description', $product->get_id());
$details = get_field('details', $product->get_id());
?>
<section clsss="current-competition-entry-section">
  <div class="current-competition-entry-sec-ctlr">
    <?php if( !empty($description) ): ?>
    <div class="hh-accordion-tab-row">
      <div class="fl-angle-hdr-cntlr hh-accordion-title hh-accordion-active">
        <div class="fl-angle-hdr">
          <span class="fl-angle-hdr-join"></span>
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="fl-angle-sec-hdr">
                  <h2 class="fl-h5 flash-title"><span>COMPETITION </span>   DISCRIPTION</h2>
                  <span class="icon-rotate"><i class="fas fa-angle-up"></i></span> 
                </div>
              </div>
            </div>
          </div> 
        </div>
      </div>
      <div class="hh-accordion-des">
           <div class="container">
             <div class="row">
               <div class="col-md-12">
                <div class="hh-accordion-des-cntlr">
                    <div><span class="acordion-arrow"> <img src="<?php echo THEME_URI; ?>/assets/images/cce-accor-angle.png" alt=""></span></div>
                    <div>
                      <?php echo wpautop( $description ); ?>
                    </div>
                </div>
               </div>
             </div>
           </div>
      </div>
    </div>
    <?php endif; ?>
    <?php if( !empty($details) ): ?>
    <div class="hh-accordion-tab-row">
      <div class="fl-angle-hdr-cntlr hh-accordion-title">
        <div class="fl-angle-hdr">
          <span class="fl-angle-hdr-join"></span>
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="fl-angle-sec-hdr">
                  <h2 class="fl-h5 flash-title"><span>COMPETITION </span>   DETAILS</h2>
                  <span class="icon-rotate"><i class="fas fa-angle-up"></i></span> 
                </div>
              </div>
            </div>
          </div> 
        </div>
      </div>
      <div class="hh-accordion-des">
           <div class="container">
             <div class="row">
               <div class="col-md-12">
                <div class="hh-accordion-des-cntlr">
                    <div><span class="acordion-arrow"> <img src="<?php echo THEME_URI; ?>/assets/images/cce-accor-angle.png" alt=""></span></div>
                    <div>
                      <?php echo wpautop($details); ?>
                        </tr>
                      </table>
                    </div>
                </div>
               </div>
             </div>
           </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>