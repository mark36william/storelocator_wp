<!-- sModal -->
<div class="smodal asl-p-cont fade sl-cont sl-main-shortcode-popup" id="insert-sl-shortcode" tabindex="-1" role="dialog" aria-labelledby="insert-sl-shortcodeLabel" aria-hidden="true">
  <div class="smodal-dialog smodal-dialog-centered" role="document">
    <div class="smodal-content">
      <div class="smodal-header">
        <h5 class="smodal-title" id="insert-sl-shortcodeLabel"><?php echo esc_attr__('Store Locator Shortcode','asl_locator'); ?></h5>
        <button type="button" class="close" data-dismiss="smodal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="smodal-body">
        <form id="sl-shortcode-popup">
          <!-- Card body -->
          <div class="sl-inner-settings sl-inner-tab">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label" for="asl-template"><?php echo esc_attr__('Select Template','asl_locator'); ?></label>
                  <div class="field-group-inner">
                    <select class="custom-select custom-nice-select input" id="asl-template" name="template">
                      <option value="0"><?php echo esc_attr__('Template 0','asl_locator'); ?></option>
                      <option disabled="disabled" value="1"><?php echo esc_attr__('Template 1','asl_locator'); ?></option>
                      <option disabled="disabled" value="2"><?php echo esc_attr__('Template 2','asl_locator'); ?></option>
                      <option disabled="disabled" value="3"><?php echo esc_attr__('Template 3','asl_locator'); ?></option>
                      <option disabled="disabled" value="list"><?php echo esc_attr__('Template list','asl_locator'); ?></option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label" for="asl-search_type"><?php echo esc_attr__('Search Type','asl_locator'); ?></label>
                  <div class="field-group-inner">
                   <select name="search_type" id="asl-search_type" class="custom-select">
                      <option value="0"><?php echo esc_attr__('Search By Address (Google)','asl_locator'); ?></option>
                      <option disabled="disabled" value="1"><?php echo esc_attr__('Search By Store Name (Database)','asl_locator'); ?></option>
                      <option disabled="disabled" value="2"><?php echo esc_attr__('Search By Stores Cities, States (Database)','asl_locator'); ?></option>
                      <option value="3"><?php echo esc_attr__('Geocoding on Enter key (Google Geocoding API)','asl_locator'); ?></option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label" for="asl-prompt_location"><?php echo esc_attr__('Geo-Location Dialog','asl_locator'); ?></label>
                  <div class="field-group-inner">
                    <select id="asl-prompt_location" class="custom-select" name="prompt_location">
                        <option value="0"><?php echo esc_attr__('Disable','asl_locator') ?></option>
                        <option value="1"><?php echo esc_attr__('Geo-location Modal','asl_locator') ?></option>
                        <option value="2"><?php echo esc_attr__('Type your Location Modal','asl_locator') ?></option>
                        <option value="3"><?php echo esc_attr__('Geolocation On Load','asl_locator') ?></option>
                        <option value="4"><?php echo esc_attr__('GeoJS IP Service (Free API)','asl_locator') ?></option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                    <label class="custom-control-label" for="asl-distance_unit"><?php echo esc_attr__('Distance Unit','asl_locator') ?></label>
                    <div>
                       <div class="asl-wc-radio">
                          <label for="asl-distance_unit-KM"><input type="radio" name="distance_unit" value="KM"  id="asl-distance_unit-KM"><?php echo esc_attr__('KM','asl_locator') ?></label>
                       </div>
                       <div class="asl-wc-radio">
                          <label for="asl-distance_unit-Miles"><input checked="checked" type="radio" name="distance_unit" value="Miles" id="asl-distance_unit-Miles"><?php echo esc_attr__('Miles','asl_locator') ?></label>
                       </div>
                    </div>
                </div>
                <div class="form-group">
                  <label class="custom-control-label" for="asl-time_format"><?php echo esc_attr__('Time Format','asl_locator') ?></label>
                  <div>
                     <div class="asl-wc-radio">
                        <label for="asl-time_format-0"><input type="radio" checked="checked" name="time_format" value="0"  id="asl-time_format-0"><?php echo esc_attr__('12 Hours','asl_locator') ?></label>
                     </div>
                     <div class="asl-wc-radio">
                        <label for="asl-time_format-1"><input type="radio" name="time_format" value="1" id="asl-time_format-1"><?php echo esc_attr__('24 Hours','asl_locator') ?></label>
                     </div>
                     <p class="help-p"><?php echo esc_attr__('Select either 12 or 24 hours time format','asl_locator') ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="smodal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="smodal"><?php echo esc_attr__('Close','asl_locator'); ?></button>
        <button type="button" id="sl-add-shortcode" class="btn btn-primary"><?php echo esc_attr__('Insert Shortcode','asl_locator'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script type="text/javascript">
  var ASL_Instance = {
    url: '<?php echo ASL_UPLOAD_URL ?>'
  };
  window.addEventListener("load", function() {
    asl_engine.shortcode_generator();
  });
</script>