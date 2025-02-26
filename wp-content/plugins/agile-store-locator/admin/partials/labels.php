<?php 

$all_labels     = \AgileStoreLocator\Model\Label::load_labels();
$default_labels = \AgileStoreLocator\Model\Label::asl_default_labels(); 

?>
<div class="row mt-2 mb-4 asl-lbl-tabs-hdr">
   <div class="col-md-6 col-sm-6 col-12 mb-5">
      <div class="form-group d-lg-flex d-md-block">
        <label class="custom-control-label" for="asl-head_title"><?php echo esc_attr__('Header Title','asl_locator') ?></label>
        <div class="form-group-inner">
          <input  type="text" class="form-control validate[required]" name="data[head_title]" id="asl-head_title" placeholder="<?php echo esc_attr__('Head title','asl_locator') ?>">
          <p class="help-p"><a target="_blank" href="https://agilestorelocator.com/wiki/change-header-title-text-store-locator/"><?php echo esc_attr__('Change it for Translation','asl_locator') ?></a></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-12 mb-5">
      <div class="form-group d-lg-flex d-md-block">
        <label class="custom-control-label" for="asl-category_title"><?php echo esc_attr__('Category Title','asl_locator') ?></label>
        <div class="form-group-inner">
          <input  type="text" class="form-control validate[required]" name="data[category_title]" id="asl-category_title" placeholder="<?php echo esc_attr__('Category title','asl_locator') ?>">
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-12 mb-5">
      <div class="form-group d-lg-flex d-md-block">
        <label class="custom-control-label" for="asl-no_item_text"><?php echo esc_attr__('No Item Text','asl_locator') ?></label>
        <div class="form-group-inner">
          <input  type="text" class="form-control validate[required]" name="data[no_item_text]" id="asl-no_item_text" placeholder="<?php echo esc_attr__('No Item Text','asl_locator') ?>">
        </div>
      </div>
   </div>
   <div class="col-12 mb-5 sl-complx">
      <div class="form-group d-lg-flex d-md-block">
        <label class="custom-control-label" for="asl-tran_lbl"><?php echo esc_attr__('Labels','asl_locator') ?></label>
         <div class="form-group-inner">
         <label class="switch" for="asl-tran_lbl"><input type="checkbox" value="1" class="custom-control-input" name="data[tran_lbl]" id="asl-tran_lbl"><span class="slider round"></span></label>
         <p class="help-p"><?php echo __('To enable the label changing feature please enable the switch,<br> For multi-language translation, please disable it and follow the ','asl_locator') ?> <a target="_blank" href="https://agilestorelocator.com/wiki/language-translation-store-locator/"><?php echo esc_attr__('multi-language translation guide','asl_locator') ?></a></p>
         </div>
      </div>
   </div>
   <div class="col-12">
      <div class="row">
         <div class="col-md-12">
            <button type="button" class="btn btn-success float-right btn-asl-user_setting" data-loading-text="<?php echo esc_attr__('Saving...','asl_locator') ?>" data-completed-text="Settings Updated"><?php echo esc_attr__('Save Settings','asl_locator') ?></button>
         </div>
      </div>
      <hr>
      <p class="alert alert-info"><?php echo esc_attr__('Labels section can be used to change the labels of the frontend of the store locator widgets, enable the switch above to activate. (since ver. 4.8.28, Beta version)','asl_locator') ?></p>
   </div>
   <div class="col-md-6">
      <div class="form-group d-md-block">
         <label class="custom-control-label" for="asl-notify_email"><?php echo esc_attr__('Search the keyword and change the text','asl_locator') ?></label>
         <div class="form-group-inner">
            <input  type="search" class="form-control" name="search" id="label-search" placeholder="<?php echo esc_attr__('Search Labels','asl_locator') ?>">
            <button class="asl_searh_icon">
               <svg viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M29.6538 27.3463L24.1288 21.8375C25.9114 19.5665 26.8786 16.7621 26.875 13.875C26.875 11.3038 26.1126 8.79043 24.6841 6.65259C23.2557 4.51475 21.2253 2.84851 18.8499 1.86457C16.4745 0.880633 13.8606 0.623189 11.3388 1.1248C8.81708 1.62641 6.5007 2.86454 4.68262 4.68262C2.86454 6.5007 1.62641 8.81708 1.1248 11.3388C0.623189 13.8606 0.880633 16.4745 1.86457 18.8499C2.84851 21.2253 4.51475 23.2557 6.65259 24.6841C8.79043 26.1126 11.3038 26.875 13.875 26.875C16.7621 26.8786 19.5665 25.9114 21.8375 24.1288L27.3463 29.6538C27.4973 29.8061 27.677 29.927 27.8751 30.0095C28.0731 30.092 28.2855 30.1344 28.5 30.1344C28.7145 30.1344 28.9269 30.092 29.1249 30.0095C29.323 29.927 29.5027 29.8061 29.6538 29.6538C29.8061 29.5027 29.927 29.323 30.0095 29.1249C30.092 28.9269 30.1344 28.7145 30.1344 28.5C30.1344 28.2855 30.092 28.0731 30.0095 27.8751C29.927 27.677 29.8061 27.4973 29.6538 27.3463ZM4.12501 13.875C4.12501 11.9466 4.69683 10.0616 5.76818 8.4582C6.83952 6.85482 8.36226 5.60513 10.1438 4.86718C11.9254 4.12923 13.8858 3.93614 15.7771 4.31235C17.6685 4.68856 19.4057 5.61715 20.7693 6.98071C22.1329 8.34428 23.0615 10.0816 23.4377 11.9729C23.8139 13.8642 23.6208 15.8246 22.8828 17.6062C22.1449 19.3877 20.8952 20.9105 19.2918 21.9818C17.6884 23.0532 15.8034 23.625 13.875 23.625C11.2891 23.625 8.80919 22.5978 6.98071 20.7693C5.15224 18.9408 4.12501 16.4609 4.12501 13.875Z" fill="grey"/>
               </svg>   
            </button>
         </div>
      </div>
   </div>
</div>
<div class="row asl-label-section mt-2">
  <?php foreach ($all_labels as $key => $label) { ?>
   <div class="col-12 mb-3 asl-label">
      <div class="form-group d-md-block">
        <label class="custom-control-label"><?php echo esc_attr($default_labels[$key]); ?></label>
         <div class="form-group-inner">
            <input type="text" class="form-control" data-name="<?php echo esc_attr($key) ; ?>"  value="<?php echo \AgileStoreLocator\Model\Label::get_label($key) ;?>" placeholder="<?php echo esc_attr($default_labels[$key]); ?>">
         </div>
      </div>
   </div>
  <?php } ?>
   <div class="col-12 mb-5 no_result">
      <div class="form-group d-lg-flex d-md-block">
          <p class="w-100 fw-bold text-center"><?php echo esc_attr__('No result found','asl_locator') ?></p>
      </div>
   </div>   
</div>
<!-- SCRIPTS -->
<script type="text/javascript">

  window.addEventListener("load", function() {
  asl_engine.pages.labels();
  });
</script>