<!-- Container -->
<div class="asl-p-cont asl-new-bg">
<div class="hide">
  <svg xmlns="http://www.w3.org/2000/svg">
    <symbol id="i-plus" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title><?php echo esc_attr__('Add','asl_locator') ?></title>
        <path d="M16 2 L16 30 M2 16 L30 16" />
    </symbol>
    <symbol id="i-trash" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title><?php echo esc_attr__('Trash','asl_locator') ?></title>
        <path d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
    </symbol>
    <symbol id="i-edit" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title><?php echo esc_attr__('Edit','asl_locator') ?></title>
        <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
    </symbol>
    <symbol id="i-export" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
      <path d="M28 22 L28 30 4 30 4 22 M16 4 L16 24 M8 12 L16 4 24 12" />
    </symbol>
    <svg id="i-alert" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <title><?php echo esc_attr__('Warning','asl_locator') ?></title>
        <path d="M16 3 L30 29 2 29 Z M16 11 L16 19 M16 23 L16 25" />
    </svg>
  </svg>
</div>
  <div class="container">
    <div class="row asl-inner-cont">
      <div class="col-md-12">
        <div class="card p-0 mb-4">
          <h3 class="card-title"><?php echo esc_attr__('Manage Leads','asl_locator') ?></h3>
          <div class="card-body">
            <div class="row mb-4 d-flex justify-content-between">
              <div class="col-md-7">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="sl-datetimepicker"><?php echo esc_attr__('Duration Selection','asl_locator') ?></label>
                      <div class="input-group mb-3">
                        <input type="text" id="sl-datetimepicker" class="form-control">
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark mrg-r-10" id="sl-btn-export-leads"><i><svg width="13" height="13"><use xlink:href="#i-export"></use></svg></i><?php echo esc_attr__('Export Leads','asl_locator') ?></button>
                        </div>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark mrg-r-10" id="sl-btn-export-dealers"><i><svg width="13" height="13"><use xlink:href="#i-export"></use></svg></i><?php echo esc_attr__('Export Dealers','asl_locator') ?></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 align-middle">
                <div class="float-right mt-3">
                  <button type="button" id="btn-asl-delete-all" class="btn btn-danger mrg-r-10"><i><svg width="13" height="13"><use xlink:href="#i-trash"></use></svg></i><?php echo esc_attr__('Delete Selected','asl_locator') ?></button>
                </div>
              </div>
            </div>
          	<table id="tbl_leads" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th align="center">&nbsp;</th>
                    <th align="center"><input type="text" class="form-control" data-id="l.id"  placeholder="<?php echo esc_attr__('Search ID','asl_locator') ?>"  /></th>
                    <th align="center"><input type="text" class="form-control" data-id="l.name"  placeholder="<?php echo esc_attr__('Search Lead Name','asl_locator') ?>"  /></th>
                    <th align="center"><input type="text" class="form-control" data-id="l.phone"  placeholder="<?php echo esc_attr__('Search Lead Phone','asl_locator') ?>"  /></th>
                    <th align="center"><input type="text" class="form-control" data-id="l.email"  placeholder="<?php echo esc_attr__('Search Lead Email','asl_locator') ?>"  /></th>
                    <th align="center"><input type="text" class="form-control" data-id="l.postal_code"  placeholder="<?php echo esc_attr__('Search Postal Code','asl_locator') ?>"  /></th>
                    <th align="center"><input type="text" class="form-control" data-id="s.title"  placeholder="<?php echo esc_attr__('Search Store Title','asl_locator') ?>"  /></th>
                    <th align="center">&nbsp;</th>
                  </tr>
                  <tr>
                    <th align="center"><a class="select-all"><?php echo esc_attr__('Select All','asl_locator') ?></a></th>
                    <th align="center"><?php echo esc_attr__('ID','asl_locator') ?></th>
                    <th align="center"><?php echo esc_attr__('Lead Name','asl_locator') ?></th>
                    <th align="center"><?php echo esc_attr__('Lead Phone','asl_locator') ?></th>
                    <th align="center"><?php echo esc_attr__('Lead Email','asl_locator') ?></th>
                    <th align="center"><?php echo esc_attr__('Postal Code','asl_locator') ?></th>
                    <th align="center"><?php echo esc_attr__('Store','asl_locator') ?></th>
                    <th align="center"><?php echo esc_attr__('Dated','asl_locator') ?>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>    	
<!-- asl-cont end-->

<!-- SCRIPTS -->
<script type="text/javascript">
var ASL_Instance = {
	url: '<?php echo ASL_UPLOAD_URL ?>'
};

window.addEventListener("load", function() {
  asl_engine.pages.lead_manager(ASL_Instance);
});
</script>