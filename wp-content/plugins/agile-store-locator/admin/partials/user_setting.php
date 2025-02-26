<?php

//$store = \AgileStoreLocator\Helper::get_store(2);



$level_mode = \AgileStoreLocator\Helper::expertise_level();

//	simple level
?>
<!-- <style type="text/css">
	.sl-complx {display: none;}
</style> -->
<?php
if($level_mode == '1'): ?>
<style type="text/css">
	.sl-complx {display: none;}
</style>
<?php endif; ?>
<div class="asl-p-cont asl-new-bg">
	<div class="hide">
		<svg xmlns="http://www.w3.org/2000/svg">
		  <symbol id="i-trash" viewBox="0 0 32 32" width="13" height="13" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
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
		<div class="row asl-setting-cont">
			<div class="col-md-12">
			  <div class="asl-tabs p-0 mb-4 mt-4">
			     <h3 class="asl-tabs-title"><?php echo esc_attr__('ASL Settings (Full Version - ','asl_locator').ASL_CVERSION ?>)</h3>
			     <div class="asl-tabs-body">
			     		<div class="col-12">
								<?php 
							if($level_mode == '1'): ?>
							<p class="alert alert-warning mb-4" role="alert"><?php echo esc_attr__('Expert mode is disabled, simple options are visible only, to view all options, enable from the dashboard.','asl_locator') ?></p>
						<?php endif; ?>
							</div>
			        <ul class="nav nav-pills justify-content-center">
			           <li class="active rounded"><a data-toggle="pill" href="#sl-gen-tab"><?php echo esc_attr__('General','asl_locator') ?></a></li>
			           <li class="rounded"><a data-toggle="pill" href="#maps-tab"><?php echo esc_attr__('Maps','asl_locator') ?></a></li>
			           <li class="rounded"><a data-toggle="pill" href="#sl-ui-tab"><?php echo esc_attr__('UI Settings','asl_locator') ?></a></li>
			           <li class="rounded sl-complx"><a data-toggle="pill" href="#sl-detail"><?php echo esc_attr__('Detail Page','asl_locator') ?></a></li>
			           <li class="rounded sl-complx"><a data-toggle="pill" href="#sl-register"><?php echo esc_attr__('Notifications','asl_locator') ?></a></li>
			           <li class="rounded sl-complx"><a  data-toggle="pill" href="#sl-customizer"><?php echo esc_attr__('Customizer','asl_locator') ?></a></li>
			           <li class="rounded sl-complx"><a  data-toggle="pill" href="#sl-labels"><?php echo esc_attr__('Labels','asl_locator') ?></a></li>
			           <?php if(!defined ( 'ASL_WC_VERSION' )):?>
			           <li class="rounded sl-complx"><a  data-toggle="pill" href="#sl-wc"><?php echo esc_attr__('WooCommerce','asl_locator') ?></a></li>
			           <?php endif; ?>
			        </ul>
			        <form id="frm-usersetting">
			        	<div class="tab-content">
		              <div id="sl-gen-tab" class="tab-pane in active">
		              	<div class="row mt-2">
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
				                <div class="form-group d-lg-flex d-md-block">
				                  <label class="custom-control-label" for="asl-api_key"><?php echo esc_attr__('Google API Key','asl_locator') ?></label>
				                  <div class="form-group-inner">
				                  <input  type="text" class="form-control" name="data[api_key]" id="asl-api_key" placeholder="<?php echo esc_attr__('API KEY','asl_locator') ?>">
				                  <p class="help-p"><a target="_blank" class="text-muted" href="https://agilestorelocator.com/blog/enable-google-maps-api-agile-store-locator-plugin/"><?php echo esc_attr__('How to generate Google API?','asl_locator') ?></a></p>
				                  </div>
				                </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-server_key"><?php echo esc_attr__('Google Server API Key','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                    <input  type="text" class="form-control" name="data[server_key]" id="asl-server_key" placeholder="<?php echo esc_attr__('Google API KEY (Geocoding)','asl_locator') ?>">
			                    <p class="help-p"><a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/what-is-google-server-key/"><?php echo esc_attr__('What is Google Server Key?','asl_locator') ?></a> | <a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/google-server-api-key-troubleshooting/"><?php echo esc_attr__('Troubleshoot','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-default_lat"><?php echo esc_attr__('Default Coordinates','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <div class="input-group">
			                      	<input  type="number" class="form-control validate[required]" name="data[default_lat]" id="asl-default_lat" placeholder="<?php echo esc_attr__('Latitude','asl_locator') ?>">
			                      	<input  type="number" class="form-control validate[required]" name="data[default_lng]"  id="asl-default_lng" placeholder="<?php echo esc_attr__('Longitude','asl_locator') ?>">
			                      	<button data-toggle="smodal" data-target="#asl-map-modal" id="asl-setting-search-button" class="btn btn-dark no-shade-focus rounded-0" type="button"><?php echo esc_attr__('Change','asl_locator') ?></button>
			                      </div>
			                      <p class="help-p"><a target="_blank" class="text-muted" href="https://www.google.com/maps"><?php echo esc_attr__('Get your coordinates by right click on the map','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-distance_control"><?php echo esc_attr__('Distance Control','asl_locator') ?></label>
			                    <div>
                             	<div class="asl-wc-radio">
                                <label for="asl-distance_control-0"><input type="radio" name="data[distance_control]" value="0"  id="asl-distance_control-0"><?php echo esc_attr__('Slider','asl_locator') ?></label>
                             	</div>
                             	<div class="asl-wc-radio">
                                <label for="asl-distance_control-1"><input type="radio" name="data[distance_control]" value="1" id="asl-distance_control-1"><?php echo esc_attr__('Dropdown','asl_locator') ?></label>
                             	</div>
                             	<div class="asl-wc-radio">
                                <label for="asl-distance_control-2"><input type="radio" name="data[distance_control]" value="2" id="asl-distance_control-2"><?php echo esc_attr__('Boundary Box','asl_locator') ?></label>
                             	</div>
                             	<p class="help-p"><a class="text-muted" target="_blank" href="https://agilestorelocator.com/wiki/set-radius-value-distance-range-slider/"><?php echo esc_attr__('Select the distance filter control','asl_locator') ?></a></p>
                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-direction_redirect"><?php echo esc_attr__('Store Direction','asl_locator') ?></label>
			                    <div class="form-group-inner">
		                        <select  name="data[direction_redirect]" id="asl-direction_redirect" class="custom-select">
		                          <option value="0"><?php echo esc_attr__('Show Direction in the Panel via Google Direction API','asl_locator') ?></option>
		                          <option value="1"><?php echo esc_attr__('Open in Google Maps (Mobile)','asl_locator') ?></option>
		                          <option value="2"><?php echo esc_attr__('Open in Google Maps (All Devices)','asl_locator') ?></option>
		                        </select>
			                      <p class="help-p"><?php echo esc_attr__('Select how you want the direction to work.','asl_locator') ?> | <a href="https://agilestorelocator.com/wiki/load-google-maps-app-mobile-direction/" target="_blank"><?php echo esc_attr__('Guide Help','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-dropdown_range"><?php echo esc_attr__('Distance Options','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                    <input type="text" class="form-control" name="data[dropdown_range]" id="asl-dropdown_range" placeholder="Example: 10,20,30">
			                    <p class="help-p"><?php echo esc_attr__('Enter the search dropdown options values, comma separated. Add default value with * symbol.','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="search_type"><?php echo esc_attr__('Search Type','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  name="data[search_type]" id="asl-search_type" class="custom-select">
			                        <option value="0"><?php echo esc_attr__('Search By Address (Google)','asl_locator') ?></option>
			                        <option value="1"><?php echo esc_attr__('Search By Store Name (Database)','asl_locator') ?></option>
			                        <option value="2"><?php echo esc_attr__('Search By Stores Cities, States (Database)','asl_locator') ?></option>
			                        <option value="3"><?php echo esc_attr__('Geocoding on Enter key (Google Geocoding API)','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><?php echo esc_attr__('Select the Address search type, it can be database search or Google Place API/Geocoding.','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="prompt_location"><?php echo esc_attr__('Geolocation','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-prompt_location" name="data[prompt_location]" class="custom-select">
			                        <option value="0"><?php echo esc_attr__('Disable','asl_locator') ?></option>
			                        <option value="1"><?php echo esc_attr__('Geo-location Modal','asl_locator') ?></option>
			                        <option value="2"><?php echo esc_attr__('Type your Location Modal','asl_locator') ?></option>
			                        <option value="3"><?php echo esc_attr__('Geolocation On Load','asl_locator') ?></option>
			                        <option value="4"><?php echo esc_attr__('GeoJS IP Service (Free API)','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/prompt-geo-location-dialog/"><?php echo esc_attr__('How Geolocation works?','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="search_destin"><?php echo esc_attr__('Search Result','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-search_destin" name="data[search_destin]" class="custom-select">
			                        <option value="0"><?php echo esc_attr__('Default','asl_locator') ?></option>
			                        <option value="1"><?php echo esc_attr__('Show My Nearest Location From Search','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><span class="red"><?php echo esc_attr__('Warning! search will pinpoint the nearest available store, and will NOT center to actual location','asl_locator') ?></span></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="sort_by"><?php echo esc_attr__('Sort List','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-sort_by" name="data[sort_by]" class="custom-select">
			                        <option value=""><?php echo esc_attr__('Default (Distance)','asl_locator') ?></option>
			                        <option value="id"><?php echo esc_attr__('Store ID','asl_locator') ?></option>
			                        <option value="title"><?php echo esc_attr__('Title','asl_locator') ?></option>
			                        <option value="city"><?php echo esc_attr__('City','asl_locator') ?></option>
			                        <option value="state"><?php echo esc_attr__('State','asl_locator') ?></option>
			                        <option value="logo_id"><?php echo esc_attr__('Logo ID','asl_locator') ?></option>
			                        <option value="cat"><?php echo esc_attr__('Categories','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/sort-store-attribute/"><?php echo esc_attr__('Sort your listing based on fields, default is Distance','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="stores_limit"><?php echo esc_attr__('Stores Limit','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <input  type="number" class="form-control validate[integer]" name="data[stores_limit]" id="asl-stores_limit">
			                      <p class="help-p"><a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/show-limited-stores-sort-by-distance/"><?php echo esc_attr__('To show a limited number of stores.','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-distance_unit"><?php echo esc_attr__('Distance Unit','asl_locator') ?></label>
			                    <div>
                             <div class="asl-wc-radio">
                                <label for="asl-distance_unit-KM"><input type="radio" name="data[distance_unit]" value="KM"  id="asl-distance_unit-KM"><?php echo esc_attr__('KM','asl_locator') ?></label>
                             </div>
                             <div class="asl-wc-radio">
                                <label for="asl-distance_unit-Miles"><input type="radio" name="data[distance_unit]" value="Miles" id="asl-distance_unit-Miles"><?php echo esc_attr__('Miles','asl_locator') ?></label>
                             </div>
                             <p class="help-p"><?php echo esc_attr__('Select the distance unit to use on Store Locator','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="geo_button"><?php echo esc_attr__('Search Button Type','asl_locator') ?></label>
			                    <div>
                             	<div class="asl-wc-radio">
                                <label for="asl-geo_button-0"><input type="radio" name="data[geo_button]" value="0"  id="asl-geo_button-0"><?php echo esc_attr__('Search Location','asl_locator') ?></label>
                             	</div>
                             	<div class="asl-wc-radio">
                                <label for="asl-geo_button-1"><input type="radio" name="data[geo_button]" value="1" id="asl-geo_button-1"><?php echo esc_attr__('Geo-Location','asl_locator') ?></label>
                             	</div>
                             	<p class="help-p"><?php echo esc_attr__('Select either to display the geolocation button or the search button next to address search','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="load_all"><?php echo esc_attr__('Marker Load','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  name="data[load_all]" id="asl-load_all" class="custom-select">
			                        <option value="1"><?php echo esc_attr__('Load All','asl_locator') ?></option>
			                        <option value="0"><?php echo esc_attr__('Load on Bound','asl_locator') ?></option>
			                        <option value="2"><?php echo esc_attr__('Load via Button','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><?php echo esc_attr__('Use Load on Bound in case of 1K+ markers','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="single_cat_select"><?php echo esc_attr__('Category Select','asl_locator') ?></label>
			                    <div>
                             <div class="asl-wc-radio">
                                <label for="asl-single_cat_select-0"><input type="radio" name="data[single_cat_select]" value="0"  id="asl-single_cat_select-0"><?php echo esc_attr__('Multiple Category Selection','asl_locator') ?></label>
                             </div>
                             <div class="asl-wc-radio">
                                <label for="asl-single_cat_select-1"><input type="radio" name="data[single_cat_select]" value="1" id="asl-single_cat_select-1"><?php echo esc_attr__('Single Category Selection','asl_locator') ?></label>
                             </div>
                             <p class="help-p"><?php echo esc_attr__('To make the category selection mode','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" class="custom-control-label" for="asl-google_search_type"><?php echo esc_attr__('Search Field','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  name="data[google_search_type]" id="asl-google_search_type" class="custom-select">
			                        <option value=""><?php echo esc_attr__('All','asl_locator') ?></option>
			                        <option value="cities"><?php echo esc_attr__('Cities (Cities)','asl_locator') ?></option>
			                        <option value="regions"><?php echo esc_attr__('Regions (Locality, City, State)','asl_locator') ?></option>
			                        <option value="geocode"><?php echo esc_attr__('Geocode','asl_locator') ?></option>
			                        <option value="address"><?php echo esc_attr__('Address','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><?php echo esc_attr__('To restrict the Google Place API search scope','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
			                
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-country_restrict"><?php echo esc_attr__('Restrict Search','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <input  type="text" class="form-control validate[minSize[2]]" name="data[country_restrict]" id="asl-country_restrict" placeholder="Example: US">
			                      <p class="help-p"><?php echo esc_attr__('Enter 2 alphabet country, for multiple countries comma separated','asl_locator') ?> | <a href="https://agilestorelocator.com/wiki/restrict-search-to-specific-countries/" target="_blank"><?php echo esc_attr__('Guide Doc','asl_locator') ?></a> | <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank" rel="nofollow">Code</a></p>
			                    </div>
			                  </div>
			                </div>

			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-first_load"><?php echo esc_attr__('List Load','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                    <select  name="data[first_load]" id="asl-first_load" class="custom-select">
			                      <option value="1"><?php echo esc_attr__('Default','asl_locator') ?></option>
			                      <option value="2"><?php echo esc_attr__('No List and Markers','asl_locator') ?></option>
			                      <option value="3"><?php echo esc_attr__('No List and Markers with Full Map','asl_locator') ?></option>
			                      <option value="4"><?php echo esc_attr__('No List with Markers','asl_locator') ?></option>
			                      <option value="5"><?php echo esc_attr__('No List & Map at Load','asl_locator') ?></option>
			                      <option value="6"><?php echo esc_attr__('Only Show Stores on Search or Filter Selection','asl_locator') ?></option>
			                      <option value="7"><?php echo esc_attr__('Only Show Selected Store on List','asl_locator') ?></option>
			                    </select>
			                    <p class="help-p"><?php echo esc_attr__('Show no stores on the page load','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>

			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
							  <div class="form-group d-lg-flex d-md-block">
							    <label class="custom-control-label" for="filter_ddl"><?php echo esc_attr__('Dropdown Filters','asl_locator') ?></label>
							    <div class="form-group-inner">
							    	<?php

							    	$ddl_controls = AgileStoreLocator\Model\Attribute::get_controls();
							    	?>
							      <select  multiple id="asl-filter_ddl" class="custom-select asl-chosen">
							      	<?php foreach($ddl_controls as $ddl_control): ?>
							        <option value="<?php echo esc_attr__($ddl_control['field']) ?>"><?php echo esc_attr__($ddl_control['label'],'asl_locator') ?></option>
							      	<?php endforeach; ?>
							      </select>
							      <p class="help-p"><?php echo esc_attr__('Additional Dropdowns filters based on Brand and Specialities data.','asl_locator') ?></p>
							    </div>
							  </div>
							</div>

			                <div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-advance_filter"><?php echo esc_attr__('Advance Filter','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-advance_filter"><input type="checkbox" value="1" class="custom-control-input" name="data[advance_filter]" id="asl-advance_filter"><span class="slider round"></span></label>
	                          	<p class="help-p"><a href="https://agilestorelocator.com/wiki/enable-disable-advance-features/" target="_blank"><?php echo esc_attr__('Disabling it will remove all the filters','asl_locator') ?></a></p>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-address_ddl"><?php echo esc_attr__('Address Dropdowns','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-address_ddl"><input type="checkbox" value="1" class="custom-control-input" name="data[address_ddl]" id="asl-address_ddl"><span class="slider round"></span></label>
	                          	<p class="help-p"><a href="https://agilestorelocator.com/wiki/drop-down-menus-address/" target="_blank"><?php echo esc_attr__('Dropdown controls for Country, State and City.','asl_locator') ?></a></p>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-time_switch"><?php echo esc_attr__('Time Switch','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-time_switch"><input type="checkbox" value="1" class="custom-control-input" name="data[time_switch]" id="asl-time_switch"><span class="slider round"></span></label>
	                          	<p class="help-p"><?php echo esc_attr__('Control will show a switch to see opened stores at the current time','asl_locator') ?></p>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-distance_slider"><?php echo esc_attr__('Distance Control','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-distance_slider"><input type="checkbox" value="1" class="custom-control-input" name="data[distance_slider]" id="asl-distance_slider"><span class="slider round"></span></label>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-analytics"><?php echo esc_attr__('Analytics','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-analytics"><input type="checkbox" value="1" class="custom-control-input" name="data[analytics]" id="asl-analytics"><span class="slider round"></span></label>

		                          <p class="help-p"><a href="https://agilestorelocator.com/wiki/intro-store-locator-analytics/" target="_blank"><?php echo esc_attr__('Enable the Analytics','asl_locator') ?></a></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-sort_by_bound"><?php echo esc_attr__('Sort By Bound','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-sort_by_bound"><input type="checkbox" value="1" class="custom-control-input" name="data[sort_by_bound]" id="asl-sort_by_bound"><span class="slider round"></span></label>
	                          	<p class="help-p"><?php echo esc_attr__('Refresh list to show nearest stores in the view.','asl_locator') ?></p>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-target_blank"><?php echo esc_attr__('Open Link New Tab','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-target_blank"><input type="checkbox" value="1" class="custom-control-input" name="data[target_blank]" id="asl-target_blank"><span class="slider round"></span></label>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                        <label class="custom-control-label" for="asl-user_center"><?php echo esc_attr__('Default Location Center','asl_locator') ?></label>
	                        <div class="form-group-inner">
	                          <label class="switch" for="asl-user_center"><input type="checkbox" value="1" class="custom-control-input" name="data[user_center]" id="asl-user_center"><span class="slider round"></span></label>
	                        	<p class="help-p"><a href="https://agilestorelocator.com/wiki/why-the-google-map-zoom-in-on-the-page-load/" target="_blank"><?php echo esc_attr__('Store Locator will consider Default coordinates as the center point','asl_locator') ?></a></p>
	                        </div>
	                      </div>
                      </div>
                      <!-- Start Branches -->
											<div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
				                <div class="form-group d-lg-flex d-md-block">
				                    <label class="custom-control-label" for="asl-branches"><?php echo esc_attr__('Store Branches','asl_locator') ?></label>
				                    <div class="form-group-inner">
				                      <label class="switch" for="asl-branches"><input type="checkbox" value="1" class="custom-control-input" name="data[branches]" id="asl-branches"><span class="slider round"></span></label>
				                  		<p class="help-p"><?php echo esc_attr__('Ability to group stores in a single store. Supported with Template 1 only','asl_locator') ?> | <span class="red"><?php echo esc_attr__('Beta version','asl_locator') ?></span></p>
				                    </div>
				                </div>
					            </div>
											<!-- End Branches -->
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-geo_marker"><?php echo esc_attr__('Geo-Location Marker','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-geo_marker"><input type="checkbox" value="1" class="custom-control-input" name="data[geo_marker]" id="asl-geo_marker"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('To remove the user own location marker','asl_locator') ?></p>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-remove_maps_script"><?php echo esc_attr__('Remove Other Maps Scripts','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-remove_maps_script"><input type="checkbox" value="1" class="custom-control-input" name="data[remove_maps_script]" id="asl-remove_maps_script"><span class="slider round"></span></label>
		                          <p class="help-p"><?php echo esc_attr__('Remove other Google Maps scripts in case of malfunctioning','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-and_filter"><?php echo esc_attr__('AND Filter','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-and_filter"><input type="checkbox" value="1" class="custom-control-input" name="data[and_filter]" id="asl-and_filter"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('To change the category filter logic from OR to AND','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-category_marker"><?php echo esc_attr__('Category Marker','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-category_marker"><input type="checkbox" value="1" class="custom-control-input" name="data[category_marker]" id="asl-category_marker"><span class="slider round"></span></label>
	                        		<p class="help-p"><a href="https://agilestorelocator.com/wiki/enable-category-markers/" target="_blank"><?php echo esc_attr__('Manage Markers will be replaced by the Category Icons','asl_locator') ?></a></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-category_bound"><?php echo esc_attr__('Category Bound','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-category_bound"><input type="checkbox" value="1" class="custom-control-input" name="data[category_bound]" id="asl-category_bound"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('Fit bound to markers when a category is selected','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-sort_random"><?php echo esc_attr__('Sort Random','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-sort_random"><input type="checkbox" value="1" class="custom-control-input" name="data[sort_random]" id="asl-sort_random"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('Sort stores list randomly on the load of the Store Locator (Enabling it will disable Default Location Marker)','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-locale"><?php echo esc_attr__('Data WPML','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-locale"><input type="checkbox" value="1" class="custom-control-input" name="data[locale]" id="asl-locale"><span class="slider round"></span></label>
	                        		<p class="help-p text-danger">(<?php echo esc_attr__('Enabling it will hide all your stores data if data is not assigned for the correct language','asl_locator') ?>) | <a href="https://agilestorelocator.com/wiki/language-translation-store-locator/" target="_blank" rel="nofollow"><?php echo esc_attr__('Documentation','asl_locator') ?></a></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
						  <div class="form-group d-lg-flex d-md-block">
						    <label class="custom-control-label" for="asl-gdpr"><?php echo esc_attr__('GDPR','asl_locator') ?></label>
						    <div>
						        <div class="asl-wc-radio">
						          <label for="asl-gdpr-0"><input type="radio" name="data[gdpr]" value="0"  id="asl-gdpr-0"><?php echo esc_attr__('Disable','asl_locator') ?></label>
						        </div>
						        <div class="asl-wc-radio">
						          <label for="asl-gdpr-1"><input type="radio" name="data[gdpr]" value="1" id="asl-gdpr-1"><?php echo esc_attr__('Plugin GDPR','asl_locator') ?></label>
						        </div>
						        <div class="asl-wc-radio">
						          <label for="asl-gdpr-2"><input type="radio" name="data[gdpr]" value="2" id="asl-gdpr-2"><?php echo esc_attr__('Borlabs Cookies','asl_locator') ?></label>
						        </div>
						        <p class="help-p"><a class="text-muted" target="_blank" href="https://agilestorelocator.com/wiki/gdpr-consent-for-google-maps-library/"><?php echo esc_attr__('GDPR Consent for the Google Maps Library','asl_locator') ?></a></p>
						    </div>
						  </div>
						</div>


						<!-- Store schedule switch -->
						<div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
		                      <div class="form-group d-lg-flex d-md-block">
		                          <label class="custom-control-label" for="asl-store_schedule"><?php echo esc_attr__('Store Schedule','asl_locator') ?></label>
			                        <div class="form-group-inner">
			                          <label class="switch" for="asl-store_schedule"><input type="checkbox" value="1" class="custom-control-input" name="data[store_schedule]" id="asl-store_schedule"><span class="slider round"></span></label>
		                        		<p class="help-p"><?php echo esc_attr__('Schedule stores to display on specified duration','asl_locator') ?> | <span class="red"><?php echo esc_attr__('Beta version','asl_locator') ?></span></p>
			                        </div>
		                      </div>
		                  </div>

			              </div>
		              </div>
		              <div id="maps-tab" class="tab-pane">
		              	<div class="row">
		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-map_type"><?php echo esc_attr__('Default Map','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-map_type" name="data[map_type]" class="custom-select">
			                        <option value="hybrid"><?php echo esc_attr__('Hybrid','asl_locator') ?></option>
			                        <option value="roadmap"><?php echo esc_attr__('Road Map','asl_locator') ?></option>
			                        <option value="satellite"><?php echo esc_attr__('Satellite','asl_locator') ?></option>
			                        <option value="terrain"><?php echo esc_attr__('Terrain','asl_locator') ?></option>
			                      </select>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-zoom"><?php echo esc_attr__('Default Zoom','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-zoom" name="data[zoom]" class="custom-select">
			                        <?php for($index = 2;$index <= 20;$index++):?>
			                        <option value="<?php echo $index ?>"><?php echo $index ?></option>
			                        <?php endfor; ?>
			                      </select>
			                      <p class="help-p"><a target="_blank" href="https://agilestorelocator.com/wiki/why-the-google-map-zoom-in-on-the-page-load/"><?php echo esc_attr__('Why the Default Zoom is not working?','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-zoom_li"><?php echo esc_attr__('Clicked Zoom','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-zoom_li" name="data[zoom_li]" class="custom-select">
			                        <option value=""><?php echo esc_attr__('Default Zoom','asl_locator') ?></option>
			                        <?php for($index = 2;$index <= 20;$index++):?>
			                        <option value="<?php echo $index ?>"><?php echo $index ?></option>
			                        <?php endfor; ?>
			                      </select>
			                      <p class="help-p"><?php echo esc_attr__('Zoom value when store list item is clicked','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-search_zoom"><?php echo esc_attr__('Search Zoom','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-search_zoom" name="data[search_zoom]" class="custom-select">
			                        <?php for($index = 2;$index <= 20;$index++):?>
			                        <option value="<?php echo $index ?>"><?php echo $index ?></option>
			                        <?php endfor; ?>
			                      </select>
			                      <p class="help-p"><?php echo esc_attr__('Zoom value when a search is performed, it works only when radius circle is disabled.','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
		              		<div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label"  for="asl-map_region"><?php echo esc_attr__('Map Region','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  id="asl-map_region" name="data[map_region]" class="custom-select">
			                        <option value=""><?php echo esc_attr__('None','asl_locator') ?></option>
			                        <?php foreach($countries as $country): ?>
			                        <option value="<?php echo esc_attr__($country->code) ?>"><?php echo esc_attr__($country->country) ?></option>
			                        <?php endforeach ?>
			                      </select>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-map_language"><?php echo esc_attr__('Map Language','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <input type="text" class="form-control validate[minSize[2]]" maxlength="5" name="data[map_language]" id="asl-map_language" placeholder="Example: US">
			                      <p class="help-p"><?php echo esc_attr__('Enter the language code.','asl_locator') ?> <a href="https://agilestorelocator.com/wiki/display-maps-different-language/" target="_blank" rel="nofollow">Get Code</a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                	<div class="form-group d-lg-flex d-md-block">
								  <label class="custom-control-label" for="asl-cluster"><?php echo esc_attr__('Cluster','asl_locator') ?></label>
								  <div class="form-group-inner">
								    <select  id="asl-cluster" name="data[cluster]" class="custom-select">
								      <option value="0"><?php echo esc_attr__('Disable','asl_locator') ?></option>
								      <option value="1"><?php echo esc_attr__('Marker Clusterer Plus','asl_locator') ?></option>
								      <option value="2"><?php echo esc_attr__('Marker Clusterer (New)','asl_locator') ?></option>
								    </select>
								  	<p class="help-p"><?php echo esc_attr__('Count of markers will appear as clusters','asl_locator') ?> | <a href="https://agilestorelocator.com/wiki/store-locator-clusters/" target="_blank" rel="nofollow"><?php echo esc_attr__('Change Colors','asl_locator') ?></a></p>
								  </div>
								</div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-scroll_wheel"><?php echo esc_attr__('Mouse Scroll','asl_locator') ?></label>
	                        	<div class="form-group-inner">
	                          	<label class="switch" for="asl-scroll_wheel"><input type="checkbox" value="1" class="custom-control-input" name="data[scroll_wheel]" id="asl-scroll_wheel"><span class="slider round"></span></label>
	                        	</div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-radius_circle"><?php echo esc_attr__('Radius Circle','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-radius_circle"><input type="checkbox" value="1" class="custom-control-input" name="data[radius_circle]" id="asl-radius_circle"><span class="slider round"></span></label>
                              <p class="help-p"><?php echo esc_attr__('It only appear with dropdown control and overrides the search zoom value to fitbound','asl_locator') ?> | <a href="https://agilestorelocator.com/wiki/change-the-radius-color/" target="_blank" rel="nofollow"><?php echo esc_attr__('Radius Colors','asl_locator') ?></a></p>
		                        </div>
	                      </div>
                      </div>
                    </div>
                    <div class="row">
	          			<div class="col-md-12 form-group mb-3 map_layout">
						    <label class="custom-control-label" for="asl-map_layout"><?php echo esc_attr__('Map Layouts','asl_locator') ?></label>
						    <div class="row">
						    	<div class="col-md-6 a-radio-select">
							      <input type="radio" id="asl-map_layout-0" value="0" name="data[map_layout]"><label for="asl-map_layout-0"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/25-blue-water/25-blue-water.png" /></label>
							      <input type="radio" id="asl-map_layout-1" value="1" name="data[map_layout]"><label for="asl-map_layout-1"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/Flat Map/53-flat-map.png" /></label>
							      <input type="radio" id="asl-map_layout-2" value="2" name="data[map_layout]"><label for="asl-map_layout-2"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/Icy Blue/7-icy-blue.png" /></label>
							      <input type="radio" id="asl-map_layout-3" value="3" name="data[map_layout]"><label for="asl-map_layout-3"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/Pale Dawn/1-pale-dawn.png" /></label>
							      <input type="radio" id="asl-map_layout-4" value="4" name="data[map_layout]"><label for="asl-map_layout-4"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/cladme/6618-cladme.png" /></label>
							      <input type="radio" id="asl-map_layout-5" value="5" name="data[map_layout]"><label for="asl-map_layout-5"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/light monochrome/29-light-monochrome.png" /></label>
							      <input type="radio" id="asl-map_layout-6" value="6" name="data[map_layout]"><label for="asl-map_layout-6"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/mostly grayscale/4183-mostly-grayscale.png" /></label>
							      <input type="radio" id="asl-map_layout-7" value="7" name="data[map_layout]"><label for="asl-map_layout-7"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/turquoise water/8-turquoise-water.png" /></label>
							      <input type="radio" id="asl-map_layout-8" value="8" name="data[map_layout]"><label for="asl-map_layout-8"><span class="actv"></span><img src="<?php echo ASL_URL_PATH ?>admin/images/map/unsaturated browns/70-unsaturated-browns.png" /></label>
							      <input type="radio" id="asl-map_layout-9" value="9" name="data[map_layout]"><label for="asl-map_layout-9"><span class="actv"></span><span class="ml-custom"><b><?php echo esc_attr__('Custom','asl_locator') ?></b></span></label>
							    </div>
							    <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-map_layout_custom"><?php echo esc_attr__('Map Custom','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <textarea id="asl-map_layout_custom"  rows="6"  placeholder="<?php echo esc_attr__('Google Style','asl_locator') ?>"  class="input-medium form-control"><?php echo $custom_map_style ?></textarea>
                        <p class="help-p"><a target="_blank" href="https://agilestorelocator.com/wiki/disable-default-google-maps-locations/"><?php echo esc_attr__('How to create custom maps?','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
								    </div>
								  </div>
			          		</div>
		              </div>
		              <div id="sl-ui-tab" class="tab-pane">
		              	<div class="row mt-2">
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-map_top"><?php echo esc_attr__('Map & List Order','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                    <select  name="data[map_top]" id="asl-map_top" class="custom-select">
			                      <option value="0"><?php echo esc_attr__('List Top, Map Bottom','asl_locator') ?></option>
			                      <option value="2"><?php echo esc_attr__('Map Top, List Bottom','asl_locator') ?></option>
			                    </select>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-full_height"><?php echo esc_attr__('Full Height','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <select  name="data[full_height]" id="asl-full_height" class="custom-select">
			                        <option value=""><?php echo esc_attr__('None','asl_locator') ?></option>
			                        <option value="full-height"><?php echo esc_attr__('Full Height (Not Fixed)','asl_locator') ?></option>
			                        <option value="full-height asl-fixed"><?php echo esc_attr__('Full Height (Fixed)','asl_locator') ?></option>
			                      </select>
			                      <p class="help-p"><a target="_blank" href="https://agilestorelocator.com/wiki/can-we-adjust-the-height-of-the-store-locator-map/" href=""><?php echo esc_attr__('Change Height of the Locator','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-full_width"><?php echo esc_attr__('Full Width','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-full_width"><input type="checkbox" value="1" class="custom-control-input" name="data[full_width]" id="asl-full_width"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('Make the store locator full width 100% with respect to the parent container','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
											  <div class="form-group d-lg-flex d-md-block">
											    <label class="custom-control-label" for="asl-tabs_layout"><?php echo esc_attr__('Tabs Layout','asl_locator') ?></label>
											    <div>
											      <div class="asl-wc-radio">
											        <label for="asl-tabs_layout-0"><input type="radio" name="data[tabs_layout]" value="0"  id="asl-tabs_layout-0"><?php echo esc_attr__('Dropdowns','asl_locator') ?></label>
											      </div>
											      <div class="asl-wc-radio">
											        <label for="asl-tabs_layout-1"><input type="radio" name="data[tabs_layout]" value="1"  id="asl-tabs_layout-1"><?php echo esc_attr__('Clickable Tabs','asl_locator') ?></label>
											      </div>
											      <p class="help-p"><?php echo esc_attr__('To show categories dropdown in tab options.','asl_locator') ?><br><span class="red">(Supported in Template 0 & 1)</span></p>
											    </div>
											  </div>
											</div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-additional_info"><?php echo esc_attr__('Description','asl_locator') ?></label>
			                    <div>
			                    	<div class="asl-wc-radio">
															<label for="asl-additional_info-0"><input type="radio" name="data[additional_info]" value="0"  id="asl-additional_info-0"><?php echo esc_attr__('Hide','asl_locator') ?></label>
														</div>
														<div class="asl-wc-radio">
															<label for="asl-additional_info-1"><input type="radio" name="data[additional_info]" value="1"  id="asl-additional_info-1"><?php echo esc_attr__('In Store List','asl_locator') ?></label>
														</div>
														<div class="asl-wc-radio">
															<label for="asl-additional_info-2"><input type="radio" name="data[additional_info]" value="2" id="asl-additional_info-2"><?php echo esc_attr__('In Modal via Link','asl_locator') ?></label>
														</div>
														<p class="help-p"><?php echo esc_attr__('To show the description text either in listing or modal.','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="week_hours"><?php echo esc_attr__('Hours Format','asl_locator') ?></label>
			                    <div>
														<div class="asl-wc-radio">
															<label for="asl-week_hours-0"><input type="radio" name="data[week_hours]" value="0"  id="asl-week_hours-0"><?php echo esc_attr__('Today','asl_locator') ?></label>
														</div>
														<div class="asl-wc-radio">
															<label for="asl-week_hours-1"><input type="radio" name="data[week_hours]" value="1" id="asl-week_hours-1"><?php echo esc_attr__('7 Days','asl_locator') ?></label>
														</div>
														<div class="asl-wc-radio">
															<label for="asl-week_hours-2"><input type="radio" name="data[week_hours]" value="2" id="asl-week_hours-2"><?php echo esc_attr__('7 Days (Grouped)','asl_locator') ?></label>
														</div>
														<p class="help-p"><?php echo esc_attr__('To show only the current day hours or full week','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-time_format"><?php echo esc_attr__('Time Format','asl_locator') ?></label>
			                    <div>
                             <div class="asl-wc-radio">
                                <label for="asl-time_format-0"><input type="radio" name="data[time_format]" value="0"  id="asl-time_format-0"><?php echo esc_attr__('12 Hours','asl_locator') ?></label>
                             </div>
                             <div class="asl-wc-radio">
                                <label for="asl-time_format-1"><input type="radio" name="data[time_format]" value="1" id="asl-time_format-1"><?php echo esc_attr__('24 Hours','asl_locator') ?></label>
                             </div>
                             <p class="help-p"><?php echo esc_attr__('Select either 12 or 24 hours time format','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-zoom_btn"><?php echo esc_attr__('Zoom Button','asl_locator') ?></label>
			                    <div class="form-group-inner">
	                            <label class="switch" for="asl-zoom_btn"><input type="checkbox" value="1" class="custom-control-input" name="data[zoom_btn]" id="asl-zoom_btn"><span class="slider round"></span></label>
	                            <p class="help-p"><?php echo esc_attr__('Show/Hide Zoom button in the infobox.','asl_locator') ?></p>
	                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-direction_btn"><?php echo esc_attr__('Direction Button','asl_locator') ?></label>
			                    <div class="form-group-inner">
	                            <label class="switch" for="asl-direction_btn"><input type="checkbox" value="1" class="custom-control-input" name="data[direction_btn]" id="asl-direction_btn"><span class="slider round"></span></label>
	                            <p class="help-p"><?php echo esc_attr__('Show/Hide direction button in the listing and infobox.','asl_locator') ?></p>
	                          </div>
			                  </div>
			                </div>
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-slug_link"><?php echo esc_attr__('Website Link','asl_locator') ?></label>
	                          <div class="form-group-inner">
	                            <label class="switch" for="asl-slug_link"><input type="checkbox" value="1" class="custom-control-input" name="data[slug_link]" id="asl-slug_link"><span class="slider round"></span></label>
	                          </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-show_categories"><?php echo esc_attr__('Show Categories','asl_locator') ?></label>
	                          <div class="form-group-inner">
	                            <label class="switch" for="asl-show_categories"><input type="checkbox" value="1" class="custom-control-input" name="data[show_categories]" id="asl-show_categories"><span class="slider round"></span></label>
	                          </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-hide_hours"><?php echo esc_attr__('Hide Hours','asl_locator') ?></label>
	                          <div class="form-group-inner">
	                            <label class="switch" for="asl-hide_hours"><input type="checkbox" value="1" class="custom-control-input" name="data[hide_hours]" id="asl-hide_hours"><span class="slider round"></span></label>
	                          </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-hide_logo"><?php echo esc_attr__('Hide Logo','asl_locator') ?></label>
	                          <div class="form-group-inner">
	                            <label class="switch" for="asl-hide_logo"><input type="checkbox" value="1" class="custom-control-input" name="data[hide_logo]" id="asl-hide_logo"><span class="slider round"></span></label>
	                          </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-print_btn"><?php echo esc_attr__('Print Button','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-print_btn"><input type="checkbox" value="1" class="custom-control-input" name="data[print_btn]" id="asl-print_btn"><span class="slider round"></span></label>
	                        		<p class="help-p"><a target="_blank" href="https://agilestorelocator.com/wiki/custom-print-header-for-store-list/"><?php echo esc_attr__('Add Print Header or remove it','asl_locator') ?></a></p>
		                        </div>
	                      </div>
                      </div>
		              		<div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-display_list"><?php echo esc_attr__('Display List','asl_locator') ?></label>
			                    <div class="form-group-inner">
	                          <label class="switch" for="asl-display_list"><input type="checkbox" value="1" class="custom-control-input" name="data[display_list]" id="asl-display_list"><span class="slider round"></span></label>
	                        </div>
			                  </div>
			                </div>
		              	</div>
		              	<div class="row mb-4">
			          			<div class="col-md-4">
			          					<div class="form-group">
			          							<label class="custom-control-label" for="asl-template"><?php echo esc_attr__('UI Templates','asl_locator') ?></label>
			                        <div class="input-group mb-3">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text" for="asl-template"><?php echo esc_attr__('Template','asl_locator') ?></label>
			                            </div>
			                            <select id="asl-template" class="custom-select col-md-12" name="data[template]">
			                                <option value="0"><?php echo esc_attr__('Template','asl_locator') ?> 0</option>
			                                <option value="1"><?php echo esc_attr__('Template','asl_locator') ?> 1</option>
			                                <option value="2"><?php echo esc_attr__('Template','asl_locator') ?> 2</option>
			                                <option value="3"><?php echo esc_attr__('Template','asl_locator') ?> 3</option>
			                                <option value="4"><?php echo esc_attr__('Template','asl_locator') ?> 4 (<?php echo esc_attr__('Grid','asl_locator') ?>)</option>
			                                <option value="list"><?php echo esc_attr__('Template List','asl_locator') ?></option>
			                            </select>
			                        </div>
			                    </div>
			                    <div class="form-group layout-section">
			                      <div class="input-group mb-3">
			                          <div class="input-group-prepend">
			                            <label for="asl-layout" class="input-group-text"><?php echo esc_attr__('Layout','asl_locator') ?></label>
			                          </div>
			                          <select id="asl-layout" class="custom-select" name="data[layout]">
			                              <option value="0"><?php echo esc_attr__('List Format','asl_locator') ?></option>
			                              <option value="1"><?php echo esc_attr__('Accordion (States, Cities, Countries)','asl_locator') ?></option>
			                              <option value="2"><?php echo esc_attr__('Accordion (Categories)','asl_locator') ?></option>
			                          </select>
			                      </div>
			                    </div>
			                    <div class="form-group">
			                    	<div class="template-box box_layout_list box_layout_3 box_layout_0 hide">
													    <div class="form-group color_scheme">
													      <label class="custom-control-label" for="asl-color_scheme"><?php echo esc_attr__('Color Schema','asl_locator') ?></label>
													      <div class="a-radio-select">
													        <?php for($_ind = 0; $_ind <= 9; $_ind++): ?>
													        <span>
													          <input type="radio" id="asl-color_scheme-<?php echo $_ind ?>" value="<?php echo $_ind ?>" name="data[color_scheme]">
													          <label class="color-box color-<?php echo $_ind ?>" for="asl-color_scheme-<?php echo $_ind ?>"></label>
													        </span>
													        <?php endfor; ?>
													      </div>
													    </div>
													  </div>
													  <div class="template-box box_layout_1 hide">
													    <div class="form-group color_scheme layout_2">
													      <label class="custom-control-label" for="asl-color_scheme_1"><?php echo esc_attr__('Color Scheme','asl_locator') ?></label>
													      <div class="a-radio-select">
													        <?php for($_ind = 0; $_ind <= 9; $_ind++): ?>
													        <span>
													          <input type="radio" id="asl-color_scheme_1-<?php echo $_ind ?>" value="<?php echo $_ind ?>" name="data[color_scheme_1]">
													          <label class="color-box color-<?php echo $_ind ?>" for="asl-color_scheme_1-<?php echo $_ind ?>">
													          	<i class="actv"></i>
													            <span class="co_1"></span>
													            <span class="co_2"></span>
													          </label>
													        </span>
													        <?php endfor; ?>
													      </div>
													    </div>
													  </div>
													  <div class="template-box box_layout_2 hide">
													    <div class="form-group map_layout color_scheme layout_2">
													      <label class="custom-control-label" for="asl-color_scheme"><?php echo esc_attr__('Color Scheme','asl_locator') ?></label>
													      <div class="a-radio-select">
													        <?php 
													        $tmpl_2_colors = array(
													          '0' => array('#CC3333', '#542733'),
													          '1' => array('#008FED', '#2580C3'),
													          '2' => array('#93628F', '#4A2849'),
													          '3' => array('#FF9800', '#FFC107'),
													          '4' => array('#01524B', '#75C9D3'),
													          '5' => array('#ED468B', '#FDCC29'),
													          '6' => array('#D55121', '#FB9C6C'),
													          '7' => array('#D13D94', '#AD0066'),
													          '8' => array('#99BE3B', '#01735A'),
													          '9' => array('#3D5B99', '#EFF1F6')
													        );
													        foreach($tmpl_2_colors as $_ct => $ctv):
													        ?>
													        <span>
													          <input type="radio" id="asl-color_scheme_2-<?php echo $_ct ?>" value="<?php echo $_ct ?>" name="data[color_scheme_2]">
													          <label class="color-box color-<?php echo $_ct ?>" for="asl-color_scheme_2-<?php echo $_ct ?>" style="background-color:<?php echo $ctv[0] ?>">
													          	<i class="actv"></i>
													            <span class="co_1"></span>
													          </label>
													        </span>
													      <?php endforeach; ?>
													      </div>
													    </div>
													  </div>
													  <p class="help-p mb-3"><a  href="<?php echo admin_url().'admin.php?page=sl-ui-customizer' ?>"><?php echo esc_attr__('Colors & Fonts Customizer','asl_locator') ?></a></p>
													  <div class="box_layout_0 box_layout_3 box_layout_list hide">
													  	<div class="form-group mb-3 Font_color">
														    <label class="custom-control-label" for="asl-font_color_scheme"><?php echo esc_attr__('Font Colors','asl_locator') ?></label>
														    <div class="a-radio-select">
														      <?php for($_ind = 0; $_ind <= 4; $_ind++): ?>
														      <span>
														        <input type="radio" id="asl-font_color_scheme-<?php echo $_ind ?>" value="<?php echo $_ind ?>" name="data[font_color_scheme]">
														        <label class="font-color-box color-<?php echo $_ind ?>" for="asl-font_color_scheme-<?php echo $_ind ?>"></label>
														      </span>
														      <?php endfor; ?>
														    </div>
														  </div>
													  </div>
			                    </div>
			                </div>
			                <div class="col-md-8 justify-content-md-center text-center">
			                  <div class="row">
			                  	<div class="col-12">
			                  		<figure class="figure">
			                    		<img  id="asl-tmpl-img" src="<?php echo ASL_URL_PATH ?>admin/images/asl-tmpl-0-0.png" alt="Thumbnail" class="figure-img img-fluid rounded">
			                    		<figcaption class="figure-caption text-center"><?php echo esc_attr__('Selected Store Locator','asl_locator') ?></figcaption>
			                  		</figure>
			                  	</div>
			                  	<div class="col-12">
			                  		<a href="<?php echo admin_url().'admin.php?page=sl-ui-customizer' ?>" class="btn btn-primary"><?php echo esc_attr__('UI Customizer','asl_locator') ?></a>
			                  	</div>
			                  </div>	
			                </div>
			          		</div>
			          		<div class="row">
			          			<div class="col-md-12 form-group mb-3 infobox_layout">
			          				<label class="custom-control-label" for="asl-infobox_layout"><?php echo esc_attr__('Infobox Layout','asl_locator') ?></label>
										    <div class="a-radio-select">
										      <input type="radio" id="asl-infobox_layout-0" value="0" name="data[infobox_layout]"><label for="asl-infobox_layout-0"><img src="<?php echo ASL_URL_PATH ?>/admin/images/infobox_1.png" /></label>
										      <input type="radio" id="asl-infobox_layout-2" value="2" name="data[infobox_layout]"><label for="asl-infobox_layout-2"><img src="<?php echo ASL_URL_PATH ?>/admin/images/infobox_2.png" /></label>
										      <input type="radio" id="asl-infobox_layout-1" value="1" name="data[infobox_layout]"><label for="asl-infobox_layout-1"><img src="<?php echo ASL_URL_PATH ?>/admin/images/infobox_3.png" /></label>
										    </div>
										  </div>
			          		</div>
		              </div>
		              <div id="sl-detail" class="tab-pane">
		              	<div class="row mt-2">
		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-link_type"><?php echo esc_attr__('Website Link Type','asl_locator') ?></label>
			                    <div>
                             	<div class="asl-wc-radio">
                                <label for="asl-link_type-0"><input type="radio" name="data[link_type]" value="0" id="asl-link_type-0"><?php echo esc_attr__('Website Field','asl_locator') ?></label>
                             	</div>
                             	<div class="asl-wc-radio">
                                <label for="asl-link_type-1"><input type="radio" name="data[link_type]" value="1" id="asl-link_type-1"><?php echo esc_attr__('Page Slug','asl_locator') ?></label>
                             	</div>
                             	<p class="help-p"><?php echo esc_attr__('Select the URL type of website link, page slug will only work when page slug is provided','asl_locator') ?></p>
                          </div>
			                  </div>
			                </div>


			                <div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-store_schema"><?php echo esc_attr__('Store JSON-LD','asl_locator') ?></label>
			                    <div class="form-group-inner">
	                          <label class="switch" for="asl-store_schema"><input type="checkbox" value="1" class="custom-control-input" name="data[store_schema]" id="asl-store_schema"><span class="slider round"></span></label>
	                          <p class="help-p"><?php echo esc_attr__('JSON schema data for Google SEO','asl_locator') ?></p>
	                        </div>
			                  </div>
			                </div>

		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-rewrite_slug"><?php echo esc_attr__('Store Page Slug','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <div class="input-group">
			                      	<input  type="text" class="form-control" name="data[rewrite_slug]" id="asl-rewrite_slug" placeholder="<?php echo esc_attr__('stores','asl_locator') ?>">
			                      	<input  type="number" class="form-control" name="data[rewrite_id]"  id="asl-rewrite_id" placeholder="<?php echo esc_attr__('156','asl_locator') ?>">
			                      </div>
			                      <p class="help-p">
			                      	<?php echo esc_attr__('1- Provide the page relative URL and the WordPress Page id','asl_locator') ?><br>
															<?php echo esc_attr__('2- Add [ASL_STORE] on the same page','asl_locator') ?><br>
															3- <a href="/wp-admin/options-permalink.php"><?php echo esc_attr__('"Save Changes" Permalink','asl_locator') ?></a> | <a target="_blank" href="https://agilestorelocator.com/wiki/store-details-page/"><?php echo esc_attr__('Store Detail Guide','asl_locator') ?></a>
			                      </p>
			                    </div>
			                  </div>
			                </div>
			                <!-- Create store slug -->
			                <div class="col-md-6 col-sm-6 col-12 mb-5 sl-complx">
											  <div class="form-group d-lg-flex d-md-block">
											    <label class="custom-control-label" for="slug_attr_ddl"><?php echo esc_attr__('Store Slug Fields','asl_locator') ?></label>
											    <div class="form-group-inner">
											      <select  multiple id="asl-slug_attr_ddl" class="custom-select asl-chosen">
											      	<?php foreach ($slug_attr as $key => $value) { ?>
				 							        <option value="<?php echo $key ?>"><?php echo esc_attr__($value, 'asl_locator') ?></option>
											      	<?php } ?>
											      </select>
											      <p class="help-p"><?php echo esc_attr__('Title and City are default fields to create slug','asl_locator') ?></p>
											    </div>
											  </div>
							    			<!-- <button type="button" class="btn btn-primary float-right" data-loading-text="Saving..." data-completed-text="Settings Updated" id="btn-asl-slug_reset"> Reset Slug</button> -->
											</div>
			                <!-- end  -->
		              	</div>
		              </div>
		              <div id="sl-register" class="tab-pane">
		              	<div class="row mt-2 mb-4">
		              		<div class="col-md-12">
		              			<a title="<?php echo esc_attr__('Filter & Export Leads','asl_locator') ?>" class="btn btn-primary btn-md float-right" href="<?php echo admin_url().'admin.php?page=sl-lead-manager' ?>"><?php echo esc_attr__('Lead Manager','asl_locator') ?></a>
		              		</div>
		              	</div>
		              	<div class="row mt-2">
		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-notify_email"><?php echo esc_attr__('Notification Email','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <input  type="text" class="form-control" name="data[notify_email]" id="asl-notify_email" placeholder="<?php echo esc_attr__('Email address','asl_locator') ?>">
			                      <p class="help-p"><?php echo esc_attr__('Email address to recieve the email notification for stores registered through frontend form.','asl_locator') ?> | <a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/how-to-add-a-lead-form/"><?php echo esc_attr__('How to add lead form?','asl_locator') ?></a></p>
			                    </div>
			                  </div>
			                </div>
		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-admin_notify"><?php echo esc_attr__('Notification Status','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-admin_notify"><input type="checkbox" value="1" class="custom-control-input" name="data[admin_notify]" id="asl-admin_notify"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('Enable all kind of email alerts & notifications.','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-lead_follow_up"><?php echo esc_attr__('Lead Follow-up Email','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-lead_follow_up"><input type="checkbox" value="1" class="custom-control-input" name="data[lead_follow_up]" id="asl-lead_follow_up"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('Send a follow-up notification to the dealer lead after 48 hours.','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
		              	</div>
		              	<div class="row mt-2">
		              		<div class="col-12">
		              			<h5 class="card-title mb-3"><?php echo esc_attr__('Contact Form 7 Hook', 'asl_locator') ?></h5>
		              			<p><?php echo esc_attr__('Agile Store Locator provides compatibility to bind CF7 mail hook using that the email copy (cc) can be sent to the closest store email address, it calculates the closest store by the postal code field that you have to add in the CF 7.', 'asl_locator') ?></p>
		              		</div>
		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
			                  <div class="form-group d-lg-flex d-md-block">
			                    <label class="custom-control-label" for="asl-cf7_field"><?php echo esc_attr__('CF7 Postal Code Field','asl_locator') ?></label>
			                    <div class="form-group-inner">
			                      <input  type="text" class="form-control" name="data[cf7_field]" id="asl-cf7_field" placeholder="<?php echo esc_attr__('postal-code','asl_locator') ?>">
			                      <p class="help-p"><?php echo esc_attr__('Postal Code Field ID in CF7 Form.','asl_locator') ?></p>
			                    </div>
			                  </div>
			                </div>
		              		<div class="col-md-6 col-sm-6 col-12 mb-5">
	                      <div class="form-group d-lg-flex d-md-block">
	                          <label class="custom-control-label" for="asl-cf7_hook"><?php echo esc_attr__('Enable Hook','asl_locator') ?></label>
		                        <div class="form-group-inner">
		                          <label class="switch" for="asl-cf7_hook"><input type="checkbox" value="1" class="custom-control-input" name="data[cf7_hook]" id="asl-cf7_hook"><span class="slider round"></span></label>
	                        		<p class="help-p"><?php echo esc_attr__('Enable the CF7 integration hook.','asl_locator') ?></p>
		                        </div>
	                      </div>
                      </div>
		              	</div>
		              </div>
  		            
  		            <div id="sl-customizer" class="tab-pane">
		              	<div class="row mt-2">
		              		<div class="col-12">
		              			<p class="alert alert-info" role="alert"><a target="_blank" href="https://agilestorelocator.com/wiki/store-locator-template-customizer/"><?php echo esc_attr__('Using this Template Customizer, the store list or the marker infobox content can be managed, you can modify it via HTML editor. Changes applied through this customizer will be safe even after updates.','asl_locator') ?></a></p>
		              		</div>
		              		<div class="col-md-5">
		              			<div class="form-group">
		              				<div class="input-group mb-3">
		              					<div class="input-group-prepend">
		              						<label class="input-group-text" for="asl-customize-template"><?php echo esc_attr__('Template','asl_locator') ?></label>
		              					</div>
		              					<select id="asl-customize-template" class="custom-select col-md-12">
		              						<option value="template-0"><?php echo esc_attr__('Template','asl_locator') ?> 0</option>
		              						<option value="template-1"><?php echo esc_attr__('Template','asl_locator') ?> 1</option>
		              						<option value="template-2"><?php echo esc_attr__('Template','asl_locator') ?> 2</option>
		              						<option value="template-3"><?php echo esc_attr__('Template','asl_locator') ?> 3</option>
		              						<option value="template-4"><?php echo esc_attr__('Template','asl_locator') ?> 4</option>
		              						<option value="store-grid"><?php echo esc_attr__('Store Grid','asl_locator') ?></option>
		              						<option value="template-list"><?php echo esc_attr__('Template list','asl_locator') ?></option>
		              					</select>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-5">
		              			<div class="input-group mb-3">
	              					<div class="input-group-prepend">
	              						<label for="asl-customize-section" class="input-group-text"><?php echo esc_attr__('Section','asl_locator') ?></label>
	              					</div>
	              					<select id="asl-customize-section" class="custom-select">
	              						<option value="list"><?php echo esc_attr__('list','asl_locator') ?></option>
	              						<option value="infobox"><?php echo esc_attr__('infobox','asl_locator') ?></option>
	              					</select>
	              				</div>
		              		</div>
		              		<div class="col-md-12 mb-4">
		              			<div class="form-group">
		              				<button type="button" class="btn btn-primary mb-2 mr-3" data-loading-text="<?php echo esc_attr__('Loading...','asl_locator') ?>" data-completed-text="Loaded" id="btn-asl-load_ctemp"><?php echo esc_attr__('Load Template','asl_locator') ?></button>
		              				<button type="button" class="btn btn-success mb-2" data-loading-text="<?php echo esc_attr__('Saving...','asl_locator') ?>" data-completed-text="Template Updated" id="btn-asl-save_ctemp"><?php echo esc_attr__('Save Template','asl_locator') ?></button>
		              				<button type="button" class="btn btn-danger float-right" data-loading-text="<?php echo esc_attr__('Reseting...','asl_locator') ?>" data-completed-text="Reset Done" id="btn-asl-reset_ctemp"><?php echo esc_attr__('Reset Template','asl_locator') ?></button>
		              				<a href="<?php echo admin_url().'admin.php?page=sl-ui-customizer' ?>" class="btn btn-info float-right mr-3"><?php echo esc_attr__('Color & Fonts','asl_locator') ?></a>
		              				
		              			</div>
                      </div>
                      <div class="col-md-12 col-sm-12 col-12 mb-0">
		              			<div class="form-group layout-section">
		              				<label class="custom-control-label" for="sl-custom-template-textarea"><?php echo esc_attr__('Template Editor','asl_locator') ?></label>
		              				<div class="input-group-richtex sl-custom-tpl-text-section">
		              					<textarea id="sl-custom-template-textarea"></textarea>
		              				</div>
		              			</div>
                    	</div>
		              	</div>
		              </div>
		              <?php if(!defined ( 'ASL_WC_VERSION' )) {
		              	include ASL_PLUGIN_PATH.'admin/partials/asl-wc-ads.php';
		              } 
		              ?>
		              <!-- ASL Labels Stat-->
    	           	<div id="sl-labels" class="tab-pane">
        				 		<?php include ASL_PLUGIN_PATH.'admin/partials/labels.php'; ?>
                  </div>
    	        		<!-- ASL Labels End-->
			        </div>
	          		<div class="row">
	          			<div class="col-md-12">
	          				<button type="button" class="btn btn-success float-right btn-asl-user_setting asl-btn-setting-main" data-loading-text="<?php echo esc_attr__('Saving...','asl_locator') ?>" data-completed-text="Settings Updated"><?php echo esc_attr__('Save Settings','asl_locator') ?></button>
	          			</div>
	          		</div>
          	</form>
			     </div>
			  </div>
			</div>
    </div>
    <div class="row">
    	<div class="col-md-12">
      	<div class="card p-0 mb-4">
					<h3 class="card-title"><?php echo esc_attr__('Manage JSON Cache','asl_locator') ?></h3>
	          <div class="card-body">
	          		<h3 class="alert alert-warning" style="width:100%;font-size: 14px"><span style="margin-right: 10px"><?php echo esc_attr__('Warning! JSON cache loading preloads all the data to serve it with great speed, but everytime you make any changes you have to hit the "Refresh Cache" button, if your JSON file is cached by browser or CDN such as cloudflare change the Query Parameter value.','asl_locator') ?> </span></h3>
	            	<div class="row">
	            		<div class="col-12">
	            			<form id="frm-asl-cache">
		            			<table class="table table-striped">
											  <thead class="thead-primary">
											    <tr>
											      <th scope="col">Lang</th>
											      <th scope="col">Cache</th>
											      <th scope="col">Version</th>
											    </tr>
											  </thead>
											  <tbody>
											  	<?php
											  	foreach ($active_langs as $lang): ?>
											    <tr>
											      <td><?php echo $lang ?></td>
											      <td>
											      	<div class="a-swith">
						                    <input value="1" <?php if(isset($cache_settings[$lang]) && $cache_settings[$lang] == '1') echo 'checked' ?> name="<?php echo esc_attr__($lang) ?>" id="asl-fast-cache-<?php echo esc_attr__($lang) ?>" data-lang="<?php echo esc_attr__($lang) ?>" class="cmn-toggle cmn-toggle-round" type="checkbox">
						                    <label for="asl-fast-cache-<?php echo esc_attr__($lang) ?>"></label>
							              	</div>
											      </td>
											      <td>
											      	<div class="form-group mb-0">
			            					  	<div class="input-group">
															    <input name="<?php echo esc_attr__($lang) ?>-ver" value="<?php if(isset($cache_settings[$lang.'-ver'])) echo esc_attr__($cache_settings[$lang.'-ver']); else echo '1'; ?>" id="asl-cache-ver-<?php echo esc_attr__($lang) ?>" type="number" style="max-width: 200px;min-height: 34px;height: 34px;" class="form-control" placeholder="<?php echo esc_attr__('Query Parameter','asl_locator') ?>" aria-label="<?php echo esc_attr__('Query Parameter','asl_locator') ?>">
															    <div class="input-group-append">
															      <button  data-lang="<?php echo esc_attr__($lang) ?>" type="button" data-loading-text="<?php echo esc_attr__('Refreshing...','asl_locator') ?>" class="btn btn-primary sl-refresh-cache"><?php echo esc_attr__('Refresh Cache','asl_locator') ?></button>
															    </div>
														  	</div>
			            					  </div>
											      </td>
											    </tr>
											  <?php endforeach; ?>
											  </tbody>
											</table>
										</form>
	            		</div>
	            	</div>
	        </div>
	      </div>
    	</div>
    </div>
		<div class="row asl-inner-cont">
      <div class="col-md-12">
      	<div class="card p-0 mb-4">
					<h3 class="card-title"><?php echo esc_attr__('Manage Additional Fields','asl_locator') ?></h3>
	          <div class="card-body">
            	<div class="row">
            		<div class="col-md-12">
            			<p><?php echo esc_attr__('Additional fields for the store can be created through this section, new fields will appear in the store form and via CSV import.','asl_locator') ?> <?php echo esc_attr__('To show the additional fields on the template, please add the fields in the template as in this ','asl_locator') ?><a target="_blank" href="https://www.youtube.com/watch?v=WpPUMxlNX4M"><?php echo esc_attr__('Video Guide','asl_locator') ?></a></p>
									<p class="alert alert-info" role="alert"><?php echo __(' <b>Control Name</b> must be small-case and without spacing, please use underscore sign (_) as the space separator, example: <b>facebook_url</b></p>','asl_locator') ?> </p>
            			<form id="frm-asl-custom-fields">
										<div class="table-responsive">
				            	<table class="table table-bordered table-stripped asl-attr-manage">
				            		<thead>
				            			<tr>
				            				<th><?php echo esc_attr__('Label','asl_locator') ?></th>
				            				<th><?php echo esc_attr__('Control Name','asl_locator') ?></th>
				            				<th><?php echo esc_attr__('CSS Class','asl_locator') ?></th>
				            				<th><?php echo esc_attr__('Action','asl_locator') ?></th>
				            			</tr>
				            		</thead>
											  <tbody>
											  	<?php 
											  	foreach($fields as $field): 

											  		$field_name  = strip_tags($field['name']);
                    				$field_label = strip_tags($field['label']);
                    				$css_class 	 = isset($field['css_class'])? strip_tags($field['css_class']): '';
											  		?>
														<tr>
	                            <td colspan="1"><div class="form-group"><input value="<?php echo esc_attr__($field_label); ?>" type="text" class="asl-attr-label form-control validate[required,funcCall[ASLValidateLabel]]"></div></td>
	                            <td colspan="1"><div class="form-group"><input value="<?php echo esc_attr__($field_name); ?>" type="text" class="asl-attr-name form-control validate[required,funcCall[ASLValidateName]]"></div></td>
	                            <td colspan="1"><div class="form-group"><input maxlength="50" value="<?php echo esc_attr__($css_class); ?>" type="text" class="asl-attr-class form-control"></div></td>
	                            <td colspan="1">
	                              <span class="add-k-delete glyp-trash">
	                                <svg width="16" height="16"><use xlink:href="#i-trash"></use></svg>
	                              </span>
	                            </td>
	                          </tr>
											  	<?php endforeach; ?>
											  </tbody>
											</table>
										</div>
								</form>
								</div>
            	</div>
							<div class="row">
          			<div class="col-md-12">
          				<button type="button" class="btn btn-dark mrg-r-10 mt-3 float-left" id="btn-asl-add-field"><i><svg width="13" height="13"><use xlink:href="#i-plus"></use></svg></i><?php echo esc_attr__('New Field','asl_locator') ?></button>
          				<button type="button" class="btn btn-success mt-3 float-right" data-loading-text="<?php echo esc_attr__('Saving...','asl_locator') ?>" data-completed-text="Fields Updated" id="btn-asl-save-schema"><?php echo esc_attr__('Save Fields','asl_locator') ?></button>
          			</div>
        			</div>
	        	</div>
	      </div>
    	</div>
      <?php
      	include ASL_PLUGIN_PATH.'admin/partials/asl-faq.php';
      ?>
    </div>
	</div>

		<!-- Map Modal -->
	<div class="smodal fade" tabindex="-1" id="asl-map-modal" role="dialog">
    <div class="smodal-dialog" role="document">
      <div class="smodal-content">
        <div class="smodal-header">
          <h5 class="smodal-title"><?php echo esc_attr__('Set Coordinates & Zoom','asl_locator') ?></h5>
          <button type="button" class="close" data-dismiss="smodal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="smodal-body">
	        <p><b><?php echo esc_attr__('Set your store locator default coordinates and zoom level using the marker below, drag the marker to pinpoint.','asl_locator') ?></b></p>
	        <div class="row">
	        	<div class="col-12">
	        	</div>
	        	<div class="col-12 mb-2">
              <input id="asl-setting-search-box" type="text" class="form-control" placeholder="<?php echo esc_attr__('Search Location','asl_locator') ?>">
          	</div>      
	          <div class="col-12">
	              <div class="map_canvas" style="height:300px" id="map_canvas"></div>
	          </div>
	          <div class="col-12">
	              <button id="asl-setting-set-coordinates" class="btn btn-dark btn-block mt-2" type="button"><?php echo esc_attr__('Use Default Location & Zoom','asl_locator') ?></button>
	          </div>
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
	asl_engine.pages.user_setting(asl_configs);
	});
</script>