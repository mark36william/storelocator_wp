<div id="asl-contact-modal" class="agile-modal agile-modal-form fade">
  <div class="agile-modal-backdrop-in"></div>
  <div class="agile-modal-dialog in">
      <div class="agile-modal-content">
          <div class="sl-form-group d-flex justify-content-between">
              <h4 class="sl-title"><?php echo asl_esc_lbl('contact_form') ?></h4>
              <button type="button" class="close-directions sl-close" data-dismiss="agile-modal" aria-label="Close" aria-hidden="true">&times;</button>
          </div>
          <form id="asl-contact-frm">
            <input type="hidden" name="id" id="contact-sl-store">
            <fieldset class="sl-rating">
               <input type="radio" id="sl-star-5" name="rating" value="5" /><label class="sl-full-star" for="sl-star-5" title="5 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star4-5" name="rating" value="4.5" /><label class="sl-half-star" for="sl-star-4-5" title="4.5 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-4" name="rating" value="4" /><label class="sl-full-star" for="sl-star-4" title="4 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-3-5" name="rating" value="3.5" /><label class="sl-half-star" for="sl-star-3-5" title="3.5 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-3" name="rating" value="3" /><label class="sl-full-star" for="sl-star-3" title="3 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-2-5" name="rating" value="2.5" /><label class="sl-half-star" for="sl-star-2-5" title="2.5 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-2" name="rating" value="2" /><label class="sl-full-star" for="sl-star-2" title="2 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-1-5" name="rating" value="1.5" /><label class="sl-half-star" for="sl-star-1-5" title="1.5 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star-1" name="rating" value="1" /><label class="sl-full-star" for="sl-star1" title="1 <?php echo asl_esc_lbl('contact_start') ?>"></label>
               <input type="radio" id="sl-star--5" name="rating" value="0.5" /><label class="sl-half-star" for="sl-star--5" title="0.5 <?php echo asl_esc_lbl('contact_start') ?>"></label>
            </fieldset>
            <div class="sl-form-group">
                <input type="text" name="name"  class="form-control" required data-pristine-required-message="<?php echo asl_esc_lbl('contact_err_name') ?>"  placeholder="<?php echo asl_esc_lbl('contact_name') ?>">
            </div>
            <div class="sl-form-group">
                <input type="text" name="email"  class="form-control" required data-pristine-required-message="<?php echo asl_esc_lbl('contact_err_email') ?>" placeholder="<?php echo asl_esc_lbl('contact_email') ?>">
            </div>
            <div class="sl-form-group">
                <textarea class="form-control" name="message" required data-pristine-required-message="<?php echo asl_esc_lbl('contact_err_msg') ?>"  placeholder="<?php echo asl_esc_lbl('contact_msg') ?>"></textarea>
            </div>
            <div class="sl-form-group mb-0">
              <button type="button" data-loading-text="<?php echo asl_esc_lbl('contact_submit') ?>" id="sl-lead-save" class="btn btn-default btn-submit"><?php echo asl_esc_lbl('Submit') ?></button>
            </div>
          </form>
      </div>
  </div>
</div>