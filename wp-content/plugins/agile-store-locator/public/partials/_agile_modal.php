<div id="agile-modal-direction" class="agile-modal fade">
    <div class="agile-modal-backdrop-in"></div>
    <div class="agile-modal-dialog in">
        <div class="agile-modal-content">
            <div class="sl-form-group d-flex justify-content-between">
                <h4><?php echo asl_esc_lbl('modal_get_direc') ?></h4>
                <button type="button" class="close-directions sl-close" data-dismiss="agile-modal" aria-label="Close">&times;</button>
            </div>
            <div class="sl-form-group">
                <label for="frm-lbl"><?php echo asl_esc_lbl('modal_from') ?>:</label>
                <input type="text" class="form-control frm-place" id="frm-lbl" placeholder="<?php echo asl_esc_lbl('enter_loc') ?>">
            </div>
            <div class="sl-form-group">
                <label for="frm-lbl"><?php echo asl_esc_lbl('modal_to') ?>:</label>
                <input readonly="true" type="text"  class="directions-to form-control" id="to-lbl" placeholder="<?php echo asl_esc_lbl('modal_pre_des_add') ?>">
            </div>
            <div class="sl-form-group mb-0">
                <label class="checkbox-inline">
                    <input type="radio" name="dist-type"  id="rbtn-km" value="0"> <?php echo asl_esc_lbl('km') ?>
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="dist-type" checked id="rbtn-mile" value="1"> <?php echo asl_esc_lbl('miles') ?>
                </label>
            </div>
            <div class="sl-form-group mb-0">
                <button type="submit" class="btn btn-default btn-submit"><?php echo asl_esc_lbl('modal_get_direc') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="asl-geolocation-agile-modal" class="agile-modal fade">
  <div class="agile-modal-backdrop-in"></div>
  <div class="agile-modal-dialog in">
    <div class="agile-modal-content">
      <?php if($all_configs['prompt_location'] == '2'): ?>
      <div class="sl-form-group d-flex justify-content-between">
        <h4><?php echo asl_esc_lbl('modal_geo_pos') ?></h4>
        <button type="button" class="close-directions sl-close" data-dismiss="agile-modal" aria-label="Close" aria-hidden="true">&times;</button>
      </div>
      <div class="sl-form-group">
        <div class="sl-row">
        <div class="pol-lg-12 mb-2">
          <input type="text" class="form-control" id="asl-current-loc" placeholder="<?php echo asl_esc_lbl('modal_your_add') ?>">
        </div>
        <div class="pol-lg-12">
          <button type="button" id="asl-btn-locate" class="btn btn-block btn-default"><?php echo asl_esc_lbl('modal_locate') ?></button>
        </div>
        </div>
      </div>
      <?php else: ?>
      <div class="sl-form-group d-flex justify-content-between">
        <h5><?php echo asl_esc_lbl('modal_use_my_loc') ?></h5>
        <button type="button" class="close-directions sl-close" data-dismiss="agile-modal" aria-label="Close" aria-hidden="true">&times;</button>
      </div>
      <div class="sl-form-group text-center mb-0">
        <button type="button" id="asl-btn-geolocation" class="btn btn-block btn-default"><?php echo asl_esc_lbl('modal_use_loc') ?></button>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<div id="asl-desc-agile-modal" class="agile-modal fade">
  <div class="agile-modal-backdrop-in"></div>
  <div class="agile-modal-dialog in">
    <div class="agile-modal-content">
      <div class="sl-row">
        <div class="pol-md-12">
          <div class="sl-form-group d-flex justify-content-between">
            <h4 class="sl-title"></h4>
            <button type="button" class="close-directions sl-close" data-dismiss="agile-modal" aria-label="Close">&times;</button>
          </div>
          <p class="sl-desc"></p>
        </div>
      </div>
    </div>
  </div>
</div>