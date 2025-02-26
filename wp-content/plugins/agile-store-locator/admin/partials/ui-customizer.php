
<div class="asl-p-cont asl-new-bg">
   <div class="container">
      <div class="row asl-setting-cont">
         <div class="col-md-12">
            <div class="asl-tabs asl-ui-customizer-cont p-0 mb-4 mt-4">
               <div class="asl-tabs-title">
                 <h3><?php echo esc_attr__('Agile Store Locator UI Customizer','asl_locator') ?></h3>
                 <a href="<?php echo admin_url().'admin.php?page=asl-settings'; ?>" class="asl-back-btn"><?php echo esc_attr__('Back to Settings','asl_locator') ?><i class="dashicons dashicons-undo mt-1 ml-2"></i></a>
               </div>
               <div class="asl-tabs-body">
                 <div class="form-inline">
                    <div class="form-group">
                       <div class="input-group">
                          <div class="input-group-prepend">
                             <label class="input-group-text" for="asl-ui-template"><?php echo esc_attr__('Template','asl_locator') ?></label>
                          </div>
                          <select id="asl-ui-template" class="custom-select col-md-12" name="ui-template">
                             <option value="template-0"><?php echo esc_attr__('Template','asl_locator') ?> 0</option>
                             <option value="template-1"><?php echo esc_attr__('Template','asl_locator') ?> 1</option>
                             <option value="template-2"><?php echo esc_attr__('Template','asl_locator') ?> 2</option>
                             <option value="template-3"><?php echo esc_attr__('Template','asl_locator') ?> 3</option>
                             <option value="template-4"><?php echo esc_attr__('Template','asl_locator') ?> 4</option>
                             <option value="template-list"><?php echo esc_attr__('Template List','asl_locator') ?></option>
                             <?php if(defined ( 'ASL_WC_VERSION' )):?>
                              <option value="template-wc"><?php echo esc_attr__('WC Addon','asl_locator') ?></option>
                             <?php endif; ?>
                          </select>
                       </div>
                    </div>
                    <div class="form-group">
                       <button type="button" class="btn btn-primary" data-loading-text="<?php echo esc_attr__('Loading...','asl_locator') ?>" data-completed-text="Loaded" id="btn-asl-load_uitemp"><?php echo esc_attr__('Load Template','asl_locator') ?></button>
                    </div>
                    <div class="form-group asl-save-btn">
                       <button type="button" class="btn btn-success disabled" disabled data-loading-text="<?php echo esc_attr__('Saving...','asl_locator') ?>" data-completed-text="Template Updated" id="btn-asl-save_uitemp"><?php echo esc_attr__('Save Settings','asl_locator') ?></button>
                    </div>
                    <div class="form-group asl-reset-btn hide">
                       <button type="button" class="btn btn-danger disabled" disabled data-loading-text="<?php echo esc_attr__('Reseting...','asl_locator') ?>" data-completed-text="Template Updated" id="btn-asl-reset_uitemp"><?php echo esc_attr__('Reset Template','asl_locator') ?></button>
                    </div>
                 </div>
                 <form id="frm-asl-ui-customizer">
                    <div class="mt-4" id="asl-fields-section" style="display: none">
                    </div>
                 </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- SCRIPTS -->
<script type="text/javascript">
  var ASL_Instance = {
    url: '<?php echo ASL_UPLOAD_URL ?>',
    plugin_url: '<?php echo ASL_URL_PATH ?>'
  },
  asl_configs =  <?php echo json_encode($all_configs); ?>;
  window.addEventListener("load", function() {
  asl_engine.pages.ui_template(asl_configs);
  });
</script>