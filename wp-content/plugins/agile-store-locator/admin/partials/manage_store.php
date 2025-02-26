<!-- Container -->
<div class="asl-p-cont asl-new-bg">
<div class="hide">
  <svg xmlns="http://www.w3.org/2000/svg">
    <symbol id="i-plus" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title><?php echo esc_attr__('Add','asl_locator') ?></title>
        <path d="M16 2 L16 30 M2 16 L30 16" />
    </symbol>
    <symbol id="i-clipboard" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title>Duplicate</title>
      <path d="M12 2 L12 6 20 6 20 2 12 2 Z M11 4 L6 4 6 30 26 30 26 4 21 4" />
    </symbol>
    <symbol id="i-trash" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title>Trash</title>
        <path d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
    </symbol>
    <symbol id="i-edit" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title>Edit</title>
        <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
    </symbol>
    <symbol id="i-info" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <path d="M16 14 L16 23 M16 8 L16 10" />
        <circle cx="16" cy="16" r="14" />
    </symbol>

    <!-- schedule Store icon -->
     <symbol id="i-clock" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
         <title><?php echo esc_attr__('Timer','asl_locator') ?></title>
          <circle cx="16" cy="16" r="14" />
             <path d="M16 8 L16 16 20 20" />
     </symbol>
       <!-- schedule Store icon -->
  </svg>
</div>
  <div class="container">
    <div class="row asl-inner-cont">
      <div class="col-md-12">
        <div class="card p-0 mb-4">
          <h3 class="card-title">
            <span><?php echo esc_attr__('Manage Stores','asl_locator');

              //  Add the name
              if(isset($_GET['categories']))  {
                echo ' ('.\AgileStoreLocator\Helper::get_category_name($_GET['categories']).')';
              }
             ?></span>
            <?php echo \AgileStoreLocator\Helper::getLangControl(); ?>
          </h3>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 form-inline">
                <div class="form-group">
                  <div class="input-group input-group-sm">
                    <select class="col-2 sl-custom custom-select" id="asl-ddl-status">
                      <option value="1"><?php echo esc_attr__('Status Enable','asl_locator') ?></option>
                      <option value="0"><?php echo esc_attr__('Status Disable','asl_locator') ?></option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info"  id="btn-change-status" type="button"><?php echo esc_attr__('Change','asl_locator') ?></button>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-info ml-md-2" data-toggle="smodal" data-target="#sl-fields-sh" type="button"><?php echo esc_attr__('Show/Hide Columns','asl_locator') ?></button>
                </div>
              </div>
              <div class="col-md-6">
                <button type="button" id="btn-asl-delete-all" class="btn btn-danger float-md-right"><i class="mr-1"><svg width="12" height="12"><use xlink:href="#i-trash"></use></svg></i><?php echo esc_attr__('Delete Selected','asl_locator') ?></button>
                <button type="button" id="btn-validate-coords"  data-loading-text="<?php echo esc_attr__('Validating...','asl_locator') ?>" class="btn mr-md-2 btn-dark float-md-right"><?php echo esc_attr__('Validate Coordinates','asl_locator') ?></button>
                <a href="<?php echo admin_url().'admin.php?page=create-agile-store' ?>" class="btn btn-success text-white float-md-right mr-md-2"><i><svg width="13" height="13"><use xlink:href="#i-plus"></use></svg></i><?php echo esc_attr__('New Store','asl_locator') ?></a>
              </div>

            </div>
            <div class="alert alert-primary mt-3 mb-3" role="alert">
              <i><svg width="14" height="14"><use xlink:href="#i-info"></use></svg></i><?php echo esc_attr__('Store Locator Listing columns can easily be updated by simply add/remove from the template, Please visit the link for more','asl_locator') ?> <a href="https://agilestorelocator.com/blog/customize-google-marker-infowindow-sidebar-store-locator/" target="_blank">"Customize Store Locator"</a>.
            </div>
            <?php if($pending_stores > 0): ?>
              <div id="alert-pending-stores" class="alert alert-warning mt-3 mb-3" role="alert"><?php echo esc_attr__('You have pending stores to approve them.','asl_locator') ?> <a id="btn-pending-stores" class="btn ml-md-2 btn-warning btn-sm" data-pending="<?php echo esc_attr__('Hide Pending Stores','asl_locator') ?>" data-all="<?php echo esc_attr__('Show Pending Stores','asl_locator') ?>" data-loading-text="<?php echo esc_attr__('Loading Stores','asl_locator') ?>"><span><?php echo esc_attr__('Show Pending Stores','asl_locator') ?></span> <i class="badge badge-light"><?php echo $pending_stores ?></i></a></div>
            <?php endif; ?>  
            <div class="table-responsive">
              <table id="tbl_stores" class="table table-bordered table-striped">
                <thead>
                  <tr>

                    <th><input type="text" class="form-control sml" data-id="id"  disabled="disabled" style="opacity: 0" placeholder="<?php echo esc_attr__('Search ID','asl_locator') ?>"  />
                    </th>
                    <th><input type="text" class="form-control sml" data-id="id"  disabled="disabled" style="opacity: 0" placeholder="<?php echo esc_attr__('Search ID','asl_locator') ?>"  />
                    </th>
                    
                   <!-- Schedule Store -->
                     <th>
                      <select class="form-control" data-id="scheduled">
                        <option value=""><?php echo esc_attr('All','asl_locator') ?></option>
                        <option value="1"><?php echo esc_attr('Scheduled','asl_locator') ?></option>
                        <option value="2"><?php echo esc_attr('Running','asl_locator') ?></option>
                        <option value="3"><?php echo esc_attr('Expired','asl_locator') ?></option>
                      </select>
                    </th>

                    <th><input type="text" class="form-control" data-id="id"  placeholder="<?php echo esc_attr__('Search ID','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="title"  placeholder="<?php echo esc_attr__('Search Title','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="lat"  placeholder="<?php echo esc_attr__('Search Lat','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="lng"  placeholder="<?php echo esc_attr__('Search Lng','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="street"  placeholder="<?php echo esc_attr__('Search Street','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="state"  placeholder="<?php echo esc_attr__('Search State','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="city"  placeholder="<?php echo esc_attr__('Search City','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="country"  placeholder="<?php echo esc_attr__('Search Country','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="phone"  placeholder="<?php echo esc_attr__('Search Phone','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="email"  placeholder="<?php echo esc_attr__('Search Email','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="website"  placeholder="<?php echo esc_attr__('Search URL','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="postal_code"  placeholder="<?php echo esc_attr__('Search Zip','asl_locator') ?>"  /></th>
                   <th>
                     <!-- <input type="text" class="form-control" data-id="is_disabled"  placeholder="<?php echo esc_attr__('Disabled','asl_locator') ?>"  /> -->
                     <select data-id="is_disabled" class="form-control">
                       <option value=""><?php echo esc_attr('All','asl_locator') ?></option>
                       <option value="1"><?php echo esc_attr('Disabled','asl_locator') ?></option>
                     </select>
                   </th>
                    <th><input type="text" class="form-control" data-id="category" disabled="disabled" style="opacity:0"  placeholder="<?php echo esc_attr__('Categories','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="marker_id"  placeholder="<?php echo esc_attr__('Marker ID','asl_locator') ?>"  /></th>
                    <th><input type="text" class="form-control" data-id="logo_id"  placeholder="<?php echo esc_attr__('Logo ID','asl_locator') ?>" /></th>
                    <th><input type="text" class="form-control" data-id="created_on"  placeholder="<?php echo esc_attr__('Created On','asl_locator') ?>"  /></th>
                  </tr>
                  <tr>
                    <th><a class="select-all"><?php echo esc_attr__('Select All','asl_locator') ?></a></th>
                    <th><?php echo esc_attr__('Action','asl_locator') ?>&nbsp;</th>
                    <th><?php echo esc_attr__('Schedule','asl_locator') ?>&nbsp;</th>
                    <th><?php echo esc_attr__('Store ID','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Title','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Lat','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Lng','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Street','asl_locator') ?></th>
                    <th><?php echo esc_attr__('State','asl_locator') ?></th>
                    <th><?php echo esc_attr__('City','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Country','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Phone','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Email','asl_locator') ?></th>
                    <th><?php echo esc_attr__('URL','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Postal Code','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Disabled','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Categories','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Marker ID','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Logo ID','asl_locator') ?></th>
                    <th><?php echo esc_attr__('Created On','asl_locator') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="dump-message asl-dumper"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Modals -->
  <div class="smodal fade" tabindex="-1" id="sl-fields-sh" role="dialog">
    <div class="smodal-dialog" role="document">
      <div class="smodal-content">
        <form id="frm-fields-sh" name="frm-fields-sh">
        <div class="smodal-header">
          <h5 class="smodal-title"><?php echo esc_attr__('Columns Visiblity','asl_locator') ?></h5>
          <button type="button" class="close" data-dismiss="smodal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="smodal-body">
          <div class="row">
            <div class="col-md-12 form-group mb-3">
              <label for="ddl-fs-cntrl"><?php echo esc_attr__('Hidden Columns','asl_locator') ?></label>
              <select id="ddl-fs-cntrl" multiple class="chosen-select-width form-control">                     
                <?php foreach($field_columns as $col_key => $col_val): ?>
                  <option value="<?php echo esc_attr($col_key) ?>"><?php echo esc_attr($col_val) ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>

        <div class="smodal-footer">
          <button type="button" id="sl-btn-sh" data-loading-text="<?php echo esc_attr__('Submitting ...','asl_locator') ?>" class="btn btn-start btn-primary"><?php echo esc_attr__('Save','asl_locator') ?></button>
          <button type="button" class="btn btn-secondary" data-dismiss="smodal"><?php echo esc_attr__('Close','asl_locator') ?></button>
        </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Store Schedule Modal -->
  <div class="smodal fade" tabindex="-1" id="sl-schedule-store" role="dialog">
    <div class="smodal-dialog" role="document">
      <div class="smodal-content">
        <form id="frm-schedule-store" name="frm-schedule-store" class="sl-schedule-store-frm">
        <div class="smodal-header">
          <h5 class="smodal-title"><?php echo esc_attr('Schedule Store','asl_locator') ?></h5>
          <button type="button" class="close" data-dismiss="smodal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="smodal-body">
          <div class="row">

            <div class="col-md-12 mb-2">
              <div class="form-group d-lg-flex d-md-block align-items-center">
                <label class="custom-control-label" for="ddl-fs-edate"><?php echo esc_attr('Start Date ','asl_locator') ?></label>
                <div class="form-group-inner">
                  <input id="asl-sched-start-date" type="text" name="asl-sched-start-date" required="required" class="form-control">
                </div>
              </div>
            </div>
            
            <div class="col-md-12 mb-2">
              <div class="form-group d-lg-flex d-md-block align-items-center">
                <label class="custom-control-label" for="ddl-fs-edate"><?php echo esc_attr('End Date','asl_locator') ?></label>
                <div class="form-group-inner">
                  <input id="asl-sched-end-date" type="text" name="asl-sched-end-date" required="required" class="form-control">
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group d-lg-flex d-md-block disable_now">
                  <label class="custom-control-label" for="ddl-fs-date-switch"><?php echo esc_attr('Disable','asl_locator') ?></label>
                  <div class="form-group-inner">
                    <label class="switch" for="ddl-fs-date-switch"><input type="checkbox" value="" class="custom-control-input" name="ddl-fs-date-switch" id="ddl-fs-date-switch"><span class="slider round"></span></label>
                  </div>
              </div>
            </div>

            <!-- Store ID -->
             <input type="text" name="store_id" id="store_id" value="" hidden="hidden" />

          </div>
        </div>

        <div class="smodal-footer">
          <button type="button" id="btn-schedule" data-loading-text="<?php echo esc_attr('Submitting ...','asl_locator') ?>" class="btn btn-start btn-primary btn-schedule"><?php echo esc_attr('Save','asl_locator') ?></button>
          <button type="button" class="btn btn-secondary" data-dismiss="smodal"><?php echo esc_attr('Close','asl_locator') ?></button>
        </div>

        </form>
      </div>
    </div>
  </div>
  <!-- Store Schedule Modal -->

</div>


<!-- SCRIPTS -->
<script type="text/javascript">

// All config data
var asl_configs  =  <?php echo json_encode($all_configs); ?>;

var ASL_Instance = {
  manage_stores_url: '<?php echo admin_url().'admin.php?page=edit-agile-store&store_id=' ?>',
  url: '<?php echo ASL_UPLOAD_URL ?>'
};

var asl_hidden_columns = <?php echo (empty($hidden_fields))? '[]': $hidden_fields; ?>;

window.addEventListener("load", function() {
  asl_engine.pages.manage_stores();
  
  //  When schedule is enabled
  if(asl_configs.store_schedule == '1') {
    asl_engine.pages.schedule_stores();
  }
});
</script>
