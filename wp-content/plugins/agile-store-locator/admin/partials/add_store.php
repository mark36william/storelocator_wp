<?php

$level_mode = \AgileStoreLocator\Helper::expertise_level();

//  simple level
if($level_mode == '1'): ?>
<style type="text/css">
  .sl-complx {display: none;}
</style>
<?php endif; ?>
<div class="asl-p-cont asl-new-bg">
	<div class="hide">
		<svg xmlns="http://www.w3.org/2000/svg">
		  <symbol id="i-trash" viewBox="0 0 32 32" width="16" height="16" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
		  		<title><?php echo esc_attr__('Trash','asl_locator') ?></title>
			    <path d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
			</symbol>
			<symbol id="i-clock" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
		    <circle cx="16" cy="16" r="14" />
		    <path d="M16 8 L16 16 20 20" />
			</symbol>
			<symbol id="i-plus" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
		  	<title><?php echo esc_attr__('Add','asl_locator') ?></title>
		    <path d="M16 2 L16 30 M2 16 L30 16" />
			</symbol>
      <symbol id="i-chevron-top" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <path d="M30 20 L16 8 2 20" />
      </symbol>
      <symbol id="i-chevron-bottom" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <path d="M30 12 L16 24 2 12" />
      </symbol>
		</svg>
	</div>
	<div class="container">
		<div class="row asl-inner-cont">
			<div class="col-md-12">
				<div class="card p-0 mb-4">
					<h3 class="card-title"><?php echo esc_attr__('Create Store','asl_locator') ?><?php echo \AgileStoreLocator\Helper::getLangControl(); ?></h3>
          <div class="card-body">
              <form id="frm-addstore">
                  <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="txt_title"><?php echo esc_attr__('Title','asl_locator') ?></label>
                        <input type="text" id="txt_title" name="data[title]" class="form-control validate[required]">
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="txt_website"><?php echo esc_attr__('Website','asl_locator') ?></label>
                        <input type="text" id="txt_website" name="data[website]" placeholder="http://example.com" class="form-control">
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="txt_description"><?php echo esc_attr__('Description','asl_locator') ?></label>
                        <textarea id="txt_description" name="data[description]" rows="3"  placeholder="Enter Description" class="input-medium form-control"></textarea>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="txt_description_2"><?php echo esc_attr__('Additional Description','asl_locator') ?></label>
                        <textarea id="txt_description_2" name="data[description_2]" rows="3"  placeholder="Additional Description" class="input-medium form-control"></textarea>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="txt_phone"><?php echo esc_attr__('Phone','asl_locator') ?></label>
                        <input type="text" id="txt_phone" name="data[phone]" class="form-control">
                        
                    </div>
                    
                    <div class="col-md-6 form-group mb-3">
                        <label for="txt_fax"><?php echo esc_attr__('Fax','asl_locator') ?></label>
                        <input type="text"  id="txt_fax" name="data[fax]" class="form-control">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 asl-tabs">
                      <div class="asl-tabs asl-store-tabs p-0 mb-4 mt-4">
                       <div class="asl-tabs-body">
                          <ul class="nav nav-pills justify-content-center">
                             <li class="active rounded"><a data-toggle="pill" href="#sl-store-address"><?php echo esc_attr__('Store Address','asl_locator') ?></a></li>
                             <li class="rounded"><a data-toggle="pill" href="#sl-other-details"><?php echo esc_attr__('Other Details','asl_locator') ?></a></li>
                             <li class="rounded"><a data-toggle="pill" href="#sl-stores-timings"><?php echo esc_attr__('Store Timing','asl_locator') ?></a></li>
                            <?php if(class_exists('ASL_WC_Instance')): ?>
                             <li class="rounded"><a data-toggle="pill" href="#sl-woocommerce"><?php echo esc_attr__('WooCommerce','asl_locator') ?></a></li>
                            <?php endif; ?>

                            <?php if(class_exists('ASL_GRR_Instance')): ?>
                             <li class="rounded"><a data-toggle="pill" href="#sl-grr"><?php echo esc_attr__('Google Place ID','asl_locator') ?></a></li>
                            <?php endif ?>

                          </ul>
                          <div class="tab-content">
                            <div id="sl-store-address" class="tab-pane in active p-0">
                              <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="txt_email"><?php echo esc_attr__('Email','asl_locator') ?></label>
                                    <input type="text" id="txt_email" name="data[email]" class="form-control validate[custom[email]]">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="txt_street"><?php echo esc_attr__('Street','asl_locator') ?></label>
                                    <input type="text" id="txt_street" name="data[street]" class="form-control">
                                </div>
                                
                                <div class="col-md-6 form-group mb-3">
                                  <label for="txt_city"><?php echo esc_attr__('City','asl_locator') ?></label>
                                  <input type="text" id="txt_city" name="data[city]" class="form-control validate[required]">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                  <label for="txt_state"><?php echo esc_attr__('State','asl_locator') ?></label>
                                  <input type="text" id="txt_state" name="data[state]" class="form-control">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                  <label for="txt_postal_code"><?php echo esc_attr__('Postal Code','asl_locator') ?></label>
                                  <input type="text" id="txt_postal_code" name="data[postal_code]" class="form-control">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                  <label for="txt_country"><?php echo esc_attr__('Country','asl_locator') ?></label>
                                  <select id="txt_country" style="width:100%" name="data[country]" class="custom-select validate[required]">
                                    <option value=""><?php echo esc_attr__('Select Country','asl_locator') ?></option>  
                                    <?php foreach($countries as $country): ?>
                                      <option value="<?php echo esc_attr($country->id) ?>"><?php echo esc_attr($country->country) ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                                <div class="col-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div id="map_canvas" class="map_canvas"></div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group mb-3">
                                        <label for="asl_txt_lat"><?php echo esc_attr__('Latitude','asl_locator') ?></label>
                                        <input type="text" id="asl_txt_lat" name="data[lat]" value="0.0" readonly="true" class="form-control">
                                      </div>
                                      <div class="form-group mb-3">
                                        <label for="asl_txt_lng"><?php echo esc_attr__('Longitude','asl_locator') ?></label>
                                        <input type="text" id="asl_txt_lng" name="data[lng]" value="0.0" readonly="true" class="form-control">
                                      </div>
                                      <div class="form-group">
                                          <a id="lnk-edit-coord" class="btn float-right btn-warning"><?php echo esc_attr__('Change Coordinates','asl_locator') ?></a>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="dump-message"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div id="sl-other-details" class="tab-pane p-0">
                              <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                  <div class="form-group">
                                    <label for="ddl-asl-markers"><?php echo esc_attr__('Marker','asl_locator') ?></label>
                                      <div class="input-group">
                                      <select id="ddl-asl-markers">
                                        <?php foreach($markers as $m):?>
                                        <option value="<?php echo esc_attr($m->id) ?>" data-imagesrc="<?php echo ASL_UPLOAD_URL.'icon/'.$m->icon;?>" data-description="&nbsp;"><?php echo esc_attr($m->marker_name);?></option>
                                        <?php endforeach; ?>
                                      </select>
                                      <button type="button" class="btn btn-success" data-toggle="smodal" data-target="#addmarkermodel"><?php echo esc_attr__('New Marker','asl_locator') ?></button>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                  <label for="ddl-asl-logos"><?php echo esc_attr__('Logo','asl_locator') ?></label>
                                  <div class="input-group">
                                    <div id="ddl-asl-logos"></div>
                                    <button type="button" class="btn btn-success" data-toggle="smodal" data-target="#addimagemodel"><?php echo esc_attr__('New Logo','asl_locator') ?></button>
                                  </div>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                  <label for="ddl_categories"><?php echo esc_attr__('Category','asl_locator') ?></label>
                                  <select name="ddl_categories"  id="ddl_categories" multiple class="chosen-select-width form-control">                     
                                    <?php foreach($category as $catego): ?>
                                      <option value="<?php echo esc_attr($catego->id) ?>"><?php echo esc_attr($catego->category_name) ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                                <?php

                                  //  Get all control
                                  $ddl_controls = \AgileStoreLocator\Model\Attribute::get_controls();

                                  foreach($ddl_controls as $control_key => $control) {

                                    //  Get control values
                                    $ddl_values = \AgileStoreLocator\Model\Attribute::get_list($control_key, $lang);
                                ?>
                                <div class="col-md-6 sl-complx form-group mb-3">
                                  <div class="form-group sl-chosen">
                                    <label for="ddl_<?php echo esc_attr($control_key) ?>"><?php echo esc_attr($control['label'], 'asl_locator') ?></label>
                                    <select data-ph="<?php echo esc_attr($control['label'], 'asl_locator') ?>" name="data[<?php echo esc_attr($control['field']) ?>]"  id="ddl_<?php echo esc_attr($control_key) ?>" multiple class="asl-chosen chosen-select-width form-control">                      
                                      <?php foreach($ddl_values as $ddl_item): ?>
                                        <option value="<?php echo esc_attr($ddl_item->id) ?>"><?php echo esc_attr($ddl_item->name) ?></option>
                                      <?php endforeach ?>
                                    </select>
                                  </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="col-md-6 sl-complx form-group mb-3">
                                  <label for="txt-ordering"><?php echo esc_attr__('Priority Order','asl_locator') ?></label>
                                  <input type="number" id="txt-ordering" name="data[ordr]" placeholder="0" class="form-control validate[integer]">
                                  <small class="form-text text-muted"><?php echo esc_attr__('Descending Order for the list, higher number on top.','asl_locator') ?></small>
                                </div>
                                <?php foreach($fields as $field): 

                                  $field_name  = $field['name'];
                                  $field_label = $field['label'];
                                  ?>
                                  <div class="col-md-6 form-group mb-3">
                                    <label for="custom-f-<?php echo esc_attr($field_name); ?>"><?php echo esc_attr($field_label); ?></label>
                                    <input type="text" id="custom-f-<?php echo esc_attr($field_name); ?>" name="asl-custom[<?php echo esc_attr($field_name); ?>]"  class="form-control">
                                  </div>
                                <?php endforeach; ?>
                                <div class="col-md-6 form-group mb-3 align-items-center">
                                  <label for="sl-disabled"><?php echo esc_attr__('Disabled','asl_locator') ?></label>
                                  <div class="a-swith a-swith-alone">
                                    <input id="sl-disabled" name="data[is_disabled]" class="cmn-toggle cmn-toggle-round" type="checkbox">
                                    <label for="sl-disabled"></label>
                                    <span><?php echo esc_attr__('No','asl_locator') ?></span>
                                    <span><?php echo esc_attr__('Yes','asl_locator') ?></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div id="sl-stores-timings" class="tab-pane p-0">
                              <div class="row">
                                <div class="col-12">
                                  <div class="float-right">
                                    <a id="asl-time-cp" class="btn btn-info btn-sm mb-3" title="<?php echo esc_attr__('Copy/Paste Monday Timing','asl_locator') ?>"><?php echo esc_attr__('Same Everyday','asl_locator') ?></a>
                                  </div>
                                </div>
                                <div class="col-12">
                                  <div class="table-responsive">
                                    <table class="table table-bordered table-stripped asl-time-details">
                                      <tbody>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Monday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="mon">

                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                                </div>
                                                <div class="asl-closed-lbl">
                                                  <div class="a-swith">
                                                    <input id="cmn-toggle-0" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                    <label for="cmn-toggle-0"></label>
                                                    <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                    <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                  </div>
                                                </div>
                                              </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                          
                                        </tr>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Tuesday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="tue">
                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                              </div>
                                              <div class="asl-closed-lbl">
                                                <div class="a-swith">
                                                  <input id="cmn-toggle-1" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                  <label for="cmn-toggle-1"></label>
                                                  <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                  <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                </div>
                                              </div>
                                            </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Wednesday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="wed">
                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                              </div>
                                              <div class="asl-closed-lbl">
                                                <div class="a-swith">
                                                    <input id="cmn-toggle-2" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                    <label for="cmn-toggle-2"></label>
                                                    <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                    <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                </div>
                                              </div>
                                            </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Thursday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="thu">
                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                                </div>
                                                <div class="asl-closed-lbl">
                                                  <div class="a-swith">
                                                    <input id="cmn-toggle-3" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                    <label for="cmn-toggle-3"></label>
                                                    <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                    <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                  </div>
                                                </div>
                                              </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                          
                                        </tr>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Friday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="fri">
                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                              </div>
                                              <div class="asl-closed-lbl">
                                                <div class="a-swith">
                                                      <input id="cmn-toggle-4" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                      <label for="cmn-toggle-4"></label>
                                                      <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                      <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                  </div>
                                              </div>
                                            </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Saturday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="sat">
                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                              </div>
                                              <div class="asl-closed-lbl">
                                                  <div class="a-swith">
                                                    <input id="cmn-toggle-5" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                    <label for="cmn-toggle-5"></label>
                                                    <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                    <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                  </div>
                                              </div>
                                            </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                          
                                        </tr>
                                        <tr>
                                          <td colspan="1"><span class="lbl-day"><?php echo esc_attr__('Sunday','asl_locator') ?></span></td>
                                          <td colspan="3">
                                            <div class="asl-all-day-times" data-day="sun">
                                              <div class="form-group">
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="9:30 AM" class="form-control asl-start-time asltimepicker validate[required,funcCall[ASLmatchTime]]" placeholder="<?php echo esc_attr__('Start Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <div class="input-group bootstrap-asltimepicker">
                                                  <input type="text" value="6:30 PM" class="form-control asl-end-time asltimepicker validate[required]" placeholder="<?php echo esc_attr__('End Time','asl_locator') ?>">
                                                  <span class="input-group-append add-on"><span class="input-group-text"><svg width="16" height="16"><use xlink:href="#i-clock"></use></svg></span></span>
                                                </div>
                                                <span class="add-k-delete glyp-trash">
                                                  <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
                                                </span>
                                                </div>
                                                <div class="asl-closed-lbl">
                                                  <div class="a-swith">
                                                        <input id="cmn-toggle-6" class="cmn-toggle cmn-toggle-round" type="checkbox" checked="checked">
                                                        <label for="cmn-toggle-6"></label>
                                                        <span><?php echo esc_attr__('Closed','asl_locator') ?></span>
                                                        <span><?php echo esc_attr__('Open 24 Hour','asl_locator') ?></span>
                                                    </div>
                                                </div>
                                              </div>
                                          </td>
                                          <td>
                                            <span class="add-k-add glyp-add">
                                              <svg width="16" height="16"><use xlink:href="#i-plus"></use></svg>
                                            </span>
                                          </td>
                                          
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php if( class_exists('ASL_WC_Instance') && defined('ASL_WC_PLUGIN') ): ?>
                            <div id="sl-woocommerce" class="tab-pane p-0">
                              <?php ASLWC\Admin\StoreSetting::storeEditForm(null); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(class_exists('ASL_GRR_Instance')): ?>
                            <div id="sl-grr" class="tab-pane p-0">
                              <div class="col-md-6 form-group mb-3">
                                  <label for="txt_placed_id"><?php echo esc_attr__('Google Placed ID','asl_locator') ?></label>
                                  <input type="text"  id="txt_placed_id" name="grr[placed_id]" class="form-control">
                              </div>
                            </div>
                            <?php endif ?>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                  	<div class="col-12 mt-3">
                  		<button type="button" class="float-right btn btn-primary mrg-r-10" data-loading-text="<?php echo esc_attr__('Saving Store...','asl_locator') ?>" data-completed-text="<?php echo esc_attr__('Store Saved','asl_locator') ?>" id="btn-asl-add"><?php echo esc_attr__('Add Store','asl_locator') ?></button>
                  	</div>
                  </div>
              </form>
          </div>
        </div>
			</div>
		</div>
	</div>


	<!-- Modals	-->
  <div class="smodal fade" tabindex="-1" id="addimagemodel" role="dialog">
    <div class="smodal-dialog" role="document">
      <div class="smodal-content">
        <form id="frm-upload-logo" name="frm-upload-logo">
        <div class="smodal-header">
          <h5 class="smodal-title"><?php echo esc_attr__('Upload Logo','asl_locator') ?></h5>
          <button type="button" class="close" data-dismiss="smodal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="smodal-body">
          <div class="row">
            <div class="col-md-12 form-group mb-3">
                <label for="txt_logo-name"><?php echo esc_attr__('Name','asl_locator') ?></label>
                <input type="text" id="txt_logo-name" name="data[logo_name]" placeholder="<?php echo esc_attr__('Logo Name','asl_locator') ?>" class="form-control">
            </div>
            <div class="col-md-12 form-group mb-3">
              <div class="input-group">
                <div class="custom-file">
                  <?php 
                    
                    $logo_meta = 'add_img';
                    echo $this->asl_logo_uploader( $logo_meta,'' ); ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="progress hideelement progress_bar_" style="display:none">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%;">
                  <span style="position:relative" class="sr-only">0% Complete</span>
                </div>
              </div>
            </div>
            <ul></ul>
            <div class="col-12"><p id="message_upload" class="alert alert-warning hide"></p></div>
          </div>
        </div>

        <div class="smodal-footer">
          <button type="button" data-loading-text="<?php echo esc_attr__('Submitting ...','asl_locator') ?>" class="btn new_upload_logo btn-success"><?php echo esc_attr__('Upload','asl_locator') ?></button>
          <button type="button" class="btn btn-secondary" data-dismiss="smodal"><?php echo esc_attr__('Close','asl_locator') ?></button>
        </div>

        </form>
      </div>
    </div>
  </div>


	<!-- Add Marker -->
	<div class="smodal fade" tabindex="-1" id="addmarkermodel" role="dialog">
    <div class="smodal-dialog" role="document">
      <div class="smodal-content">
        <form id="frm-upload-marker" name="frm-upload-marker">
        <div class="smodal-header">
          <h5 class="smodal-title"><?php echo esc_attr__('Upload Marker','asl_locator') ?></h5>
          <button type="button" class="close" data-dismiss="smodal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="smodal-body">
          <div class="row">
  	        <div class="col-md-12 form-group mb-3">
                <label for="txt_marker-name"><?php echo esc_attr__('Marker Name','asl_locator') ?></label>
                <input type="text" id="txt_marker-name" name="data[marker_name]" class="form-control">
            </div>
            <div class="col-md-12 form-group mb-3" id="drop-zone-2">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo esc_attr__('Icon','asl_locator') ?></span>
                </div>
                <div class="custom-file">
                  <input name="files" type="file" class="custom-file-input" accept=".jpg,.png,.jpeg,.gif,.JPG" id="file-logo-2">
                  <label  class="custom-file-label" for="file-logo-2"><?php echo esc_attr__('File Path...','asl_locator') ?></label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="progress hideelement progress_bar_" style="display:none">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%;">
                  <span style="position:relative" class="sr-only">0% Complete</span>
                </div>
              </div>
            </div>
            <ul></ul>
            <div class="col-12"><p id="message_upload_1" class="alert alert-warning hide"></p></div>
          </div>
	      </div>
	      <div class="smodal-footer">
          <button type="button" data-loading-text="<?php echo esc_attr__('Submitting ...','asl_locator') ?>" class="btn btn-start btn-primary"><?php echo esc_attr__('Upload','asl_locator') ?></button>
	        <button type="button" class="btn btn-default" data-dismiss="smodal"><?php echo esc_attr__('Close','asl_locator') ?></button>
	      </div>
        </form>
	    </div>
	  </div>
	</div>
</div>

<!-- SCRIPTS -->
<script type="text/javascript">

	var asl_configs =  <?php echo json_encode($all_configs); ?>;
	var ASL_Instance = {
		url: '<?php echo ASL_UPLOAD_URL ?>',
    plugin_url: '<?php echo ASL_URL_PATH; ?>'
	};
  var asl_logos   = <?php echo json_encode($logos); ?>;

  window.addEventListener("load", function() {
	asl_engine.pages.add_store();
  });
</script>
