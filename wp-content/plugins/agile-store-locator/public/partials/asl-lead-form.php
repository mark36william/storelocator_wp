<?php

?>
<section class="asl-cont asl-lead-cont">
  <form id="asl-lead-form" class="asl-lead-form" novalidate method="post" onsubmit="return false">
  <div class="asl-wrapper mt-3">
    <div class="sl-container">
      <article class="sl-form-box">
        <div class="sl-top-title">
          <h3><?php echo asl_esc_lbl('find_dealer') ?></h3>
          <p><?php echo asl_esc_lbl('lead_agree') ?></p>
        </div>
        <div class="sl-row justify-content-center">
          <div class="pol-md-7 pol-sm-12">
            <div class="sl-row">
              <div class="pol-md-6 pol-sm-6 sl-name-field">
                <div class="sl-form-group">
                  <input type="text" name="name"  required data-pristine-required-message="<?php echo asl_esc_lbl('lead_enter_name') ?>" placeholder="<?php echo asl_esc_lbl('lead_ful_name') ?>" class="form-control sl-form-fields">
                </div>
              </div>
              <div class="pol-md-6 pol-sm-6 sl-email-field">
                <div class="sl-form-group">
                  <input type="email" name="email" required data-pristine-required-message="<?php echo asl_esc_lbl('lead_valid_email') ?>" placeholder="<?php echo asl_esc_lbl('lead_email') ?>" class="form-control sl-form-fields">
                </div>
              </div>
              <div class="pol-md-6 pol-sm-6 sl-phone-field">
                <div class="sl-form-group">
                  <input type="tel" name="phone" required data-pristine-required-message="<?php echo asl_esc_lbl('lead_enter_phone') ?>" placeholder="<?php echo asl_esc_lbl('lead_phone') ?>" class="form-control sl-form-fields">
                </div>
              </div>
              <div class="pol-md-6 pol-sm-6 sl-zip-field">
                <div class="sl-form-group">
                  <input type="text" name="postal_code" required data-pristine-required-message="<?php echo asl_esc_lbl('lead_enter_zip') ?>" placeholder="<?php echo asl_esc_lbl('lead_zip') ?>" class="form-control sl-form-fields">
                </div>
              </div>
              <div class="pol-12 sl-message-field">
                <div class="sl-form-group">
                  <textarea name="message" placeholder="<?php echo asl_esc_lbl('lead_message') ?>" class="form-control sl-form-fields"></textarea>
                </div>
              </div>
              <div class="pol-12 text-center">
                <a data-loading-text="<?php echo asl_esc_lbl('lead_submitting') ?>" class="sl-submit-btn" id="sl-lead-save"><?php echo asl_esc_lbl('lead_submit') ?></a>
              </div>
            </div>
          </div>
        </div>
      </article>
    </div>
  </div>
</form>
</section>