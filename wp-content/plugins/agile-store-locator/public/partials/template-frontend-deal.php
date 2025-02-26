<link rel='stylesheet' id='asl-plugin-css'  href='<?php echo ASL_URL_PATH ?>public/css/asl.css' type='text/css' media='all' />
<style type="text/css">
#asl-storelocator.asl-p-cont .Status_filter .onoffswitch-inner::before{content: "<?php echo esc_attr__('OPEN', 'asl_locator') ?>" !important}
#asl-storelocator.asl-p-cont .Status_filter .onoffswitch-inner::after{content: "<?php echo esc_attr__('CLOSE', 'asl_locator') ?>" !important}
</style>
<?php

//dd($objects[0]->short_desc);
$all_configs['template'] = '0';


?>
<script type="text/javascript">
  //if(!((typeof google === 'object' && typeof google.maps === 'object')))
  var asl_configuration = <?php echo json_encode($all_configs); ?>,
    asl_categories      = <?php echo json_encode($all_categories); ?>,
    asl_markers         = <?php echo json_encode($all_markers); ?>,
    _asl_map_customize  = <?php echo ($map_customize)?$map_customize:'null'; ?>;
</script>

<?php
$class = '';

if($all_configs['display_list'] == '0')
  $class = ' map-full';

if($all_configs['full_width'])
  $class .= ' full-width';

$geo_btn_class = ($all_configs['geo_button'] == '1')?'asl-geo icon-direction-outline':'icon-search';

$ddl_class_grid = ($all_configs['search_2'])? 'col-xs-4': 'col-xs-5';
$tsw_class_grid = ($all_configs['search_2'])? 'col-xs-4 col-md-2': 'col-xs-4 col-md-2';


?>

<div id="asl-storelocator" class="container storelocator-main asl-p-cont asl-template-0 asl-layout-<?php echo $all_configs['layout']; ?> asl-bg-<?php echo $all_configs['color_scheme'].$class; ?> asl-text-<?php echo $all_configs['font_color_scheme'] ?> asl-deals">
  <?php if($all_configs['advance_filter']): ?>    
  <div class="row Filter_section">
      <div class="col-xs-12 col-sm-4 search_filter">
          <p><?php echo esc_attr__( 'Search Location', 'asl_locator') ?></p>
          <div class="sl-search-group">
            <input type="text" value="<?php echo $default_addr ?>" data-submit="disable"  tabindex="2" id="auto-complete-search" placeholder="<?php echo esc_attr__( 'Enter a Location', 'asl_locator') ?>"  class="<?php echo $search_type_class ?> form-control isp_ignore">
            <span><i class="<?php echo $geo_btn_class ?>" title="<?php echo ($all_configs['geo_button'] == '1')?__('Current Location','asl_locator'):__('Search Location','asl_locator') ?>"></i></span>
          </div>
      </div>
      <div class="col-xs-12 col-sm-8">
        <div class="<?php if($all_configs['search_2']) echo 'no-pad'; ?>">
          <div class="row">
            <?php if($all_configs['search_2']): ?>
            <div class="col-xs-12 col-sm-4 col-md-4 search_filter asl-name-search">
              <p><?php echo esc_attr__( 'Search Name', 'asl_locator') ?></p>
              <div class="sl-search-group"><input type="text" tabindex="2" id="auto-complete-search" placeholder="<?php echo esc_attr__( 'Search Name', 'asl_locator') ?>"  class="asl-search-name form-control isp_ignore"></div>
            </div>
            <?php endif ?>
            <div class="<?php echo $adv_class_grid ?> asl-advance-filters hide">
                <div class="row">
                  <div class="<?php echo $ddl_class_grid ?> drop_box_filter">
                      <div class="asl-filter-cntrl">
                        <label class="asl-cntrl-lbl"><?php echo $all_configs['category_title']  ?></label>
                        <div class="categories_filter" id="categories_filter">
                        </div>
                      </div>
                  </div>
                  <?php if($filter_ddl): ?>
                  <?php foreach ($filter_ddl as $a_filter):?>
                  <div class="<?php echo $ddl_class_grid ?> drop_box_filter">
                      <div class="asl-filter-cntrl">
                        <label class="asl-cntrl-lbl"><?php echo esc_attr__( $a_filter,'asl_locator') ?></label>
                        <div class="categories_filter" id="<?php echo $a_filter ?>_filter">
                        </div>
                      </div>
                  </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
                  <div class="<?php echo $ddl_class_grid ?> range_filter hide">
                      <div class="rangeFilter asl-filter-cntrl">
                        <label class="asl-cntrl-lbl"><?php echo esc_attr__( 'Distance Range','asl_locator') ?></label>
                        <input id="asl-radius-slide" type="text" class="span2" />
                        <span class="rad-unit"><?php echo esc_attr__( 'Radius','asl_locator') ?>: <span id="asl-radius-input"></span> <span id="asl-dist-unit"><?php echo esc_attr__( 'KM','asl_locator') ?></span></span>
                      </div>
                  </div>
                  <div class="<?php echo $tsw_class_grid ?> Status_filter">
                      <div class="asl-filter-cntrl">
                        <label class="asl-cntrl-lbl"><?php echo esc_attr__('Status', 'asl_locator') ?></label>
                        <div class="onoffswitch">
                          <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="asl-open-close" checked>
                          <label class="onoffswitch-label" for="asl-open-close">
                              <span class="onoffswitch-inner"></span>
                              <span class="onoffswitch-switch"></span>
                          </label>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  <?php endif; ?>
  <div class="row asl-loc-sec">
    <div class="col-sm-8 col-xs-12 asl-map">
      <div class="store-locator">
        <div id="asl-map-canv"></div>
        <!-- agile-modal -->
        <div id="agile-modal-direction" class="agile-modal fade">
          <div class="agile-modal-backdrop-in"></div>
          <div class="agile-modal-dialog in">
            <div class="agile-modal-content">
              <div class="agile-modal-header">
                <button type="button" class="close-directions close" data-dismiss="agile-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo esc_attr__('Get Your Directions', 'asl_locator') ?></h4>
              </div>
              <div class="form-group">
                <label for="frm-lbl"><?php echo esc_attr__('From', 'asl_locator') ?>:</label>
                <input type="text" class="form-control frm-place" id="frm-lbl" placeholder="<?php echo esc_attr__('Enter a Location', 'asl_locator') ?>">
              </div>
              <div class="form-group">
                <label for="frm-lbl"><?php echo esc_attr__('To', 'asl_locator') ?>:</label>
                <input readonly="true" type="text"  class="directions-to form-control" id="to-lbl" placeholder="<?php echo esc_attr__('Prepopulated Destination Address', 'asl_locator') ?>">
              </div>
              <div class="form-group">
                <span for="frm-lbl"><?php echo esc_attr__('Show Distance In', 'asl_locator') ?></span>
                <label class="checkbox-inline">
                  <input type="radio" name="dist-type"  id="rbtn-km" value="0"> <?php echo esc_attr__('KM', 'asl_locator') ?>
                </label>
                <label class="checkbox-inline">
                  <input type="radio" name="dist-type" checked id="rbtn-mile" value="1"> <?php echo esc_attr__('Mile', 'asl_locator') ?>
                </label>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default btn-submit"><?php echo esc_attr__('GET DIRECTIONS', 'asl_locator') ?></button>
              </div>
            </div>
          </div>
        </div>

        <div id="asl-geolocation-agile-modal" class="agile-modal fade">
          <div class="agile-modal-backdrop-in"></div>
          <div class="agile-modal-dialog in">
            <div class="agile-modal-content">
              <button type="button" class="close-directions close" data-dismiss="agile-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php if($all_configs['prompt_location'] == '2'): ?>
              <div class="form-group">
                <h4><?php echo esc_attr__('LOCATE YOUR GEOPOSITION', 'asl_locator') ?></h4>
              </div>
              <div class="form-group">
                <div class="">
                <div class="col-md-9 asl-p-0">
                  <input type="text" class="form-control" id="asl-current-loc" placeholder="<?php echo esc_attr__('Your Address', 'asl_locator') ?>">
                </div>
                <div class="col-md-3 asl-p-0">
                  <button type="button" id="asl-btn-locate" class="btn btn-block btn-default"><?php echo esc_attr__('LOCATE', 'asl_locator') ?></button>
                </div>
                </div>
              </div>
              <?php else: ?>
              <div class="form-group">
                <h4><?php echo esc_attr__('Use my location to find the closest Service Provider near me', 'asl_locator') ?></h4>
              </div>
              <div class="form-group text-center">
                <button type="button" id="asl-btn-geolocation" class="btn btn-block btn-default"><?php echo esc_attr__('USE LOCATION', 'asl_locator') ?></button>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <!-- agile-modal end -->
      </div>
    </div>
    <div class="col-sm-4 col-xs-12 asl-panel">
      <?php if(!$all_configs['advance_filter']): ?>    
      <div class="col-xs-12 inside search_filter">
        <p><?php echo esc_attr__( 'Search Location', 'asl_locator') ?></p>
        <div class="asl-store-search">
            <input type="text" value="<?php echo $default_addr ?>" id="auto-complete-search" class="<?php echo $search_type_class ?> form-control" placeholder="<?php echo esc_attr__( 'Enter a Location', 'asl_locator') ?>">
            <span><i class="glyphicon <?php echo $geo_btn_class ?>" title="<?php echo ($all_configs['geo_button'] == '1')?__('Current Location','asl_locator'):__('Search Location','asl_locator') ?>"></i></span>
        </div>
        <div class="Num_of_store">
          <span><?php echo $all_configs['head_title'] ?>: <span class="count-result">0</span></span>
        </div>    
      </div>
      <?php else: ?>
      <div class="Num_of_store">
        <span><?php echo $all_configs['head_title'] ?>: <span class="count-result">0</span></span>
      </div> 
      <?php endif; ?>
      <!--  Panel Listing -->
      <div id="asl-list" class="storelocator-panel">
        <div class="asl-overlay" id="map-loading">
          <div class="white"></div>
          <div class="loading"><img style="margin-right: 10px;" class="loader" src="<?php echo ASL_URL_PATH ?>public/Logo/loading.gif"><?php echo esc_attr__('Loading...', 'asl_locator') ?></div>
        </div>
        <div class="panel-cont">
            <div class="panel-inner">
              <div class="col-md-12 asl-p-0">
                  <ul id="p-statelist"  role="tablist" aria-multiselectable="true">
                  </ul>
              </div>
            </div>
        </div>
        <div class="directions-cont hide">
          <div class="agile-modal-header">
            <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
            <h4><?php echo esc_attr__('Directions', 'asl_locator') ?></h4>
          </div>
          <div class="rendered-directions"></div>
        </div>
      </div>
    </div> 
  </div>
</div>

<script id="tmpl_list_item" type="text/x-jsrender">
  <div class="sl-item" data-id="{{:id}}">
    <div class="row">
      <div class="col-md-12">
        <div class="item-thumb">
            <a class="thumb-a">
              {{if path}}
                <img src="<?php echo ASL_UPLOAD_URL ?>Logo/{{:path}}" alt="logo">
              {{/if}}
            </a>
        </div>
      </div>
      <div class="col-md-12 addr-sec">
        <p class="p-title">{{:title}}</p>
        {{if description_2}}
        <p class="p-description">{{:description_2}}</p>
        {{/if}}
        {{if description }}
        <p class="asl-price">{{:description}}</p>
        {{/if}}
        <p class="p-area"><span class="glyphicon icon-location"></span><span>{{:address}}</span></p>
        {{if phone}}
          <p class="p-area"><span class="glyphicon icon-phone-outline"></span><a href="tel:{{:phone}}">{{:phone}}</a></p>
        {{/if}}
        {{if email}}
        <p class="p-area"><span class="glyphicon icon-at"></span><a href="mailto:{{:email}}" style="text-transform: lowercase">{{:email}}</a></p>
        {{/if}}
        {{if fax}}
          <p class="p-area"><span class="glyphicon icon-fax"></span> <?php echo esc_attr__('Fax', 'asl_locator') ?>:{{:fax}}</p>
        {{/if}}
        {{if c_names}}
        <p class="p-area"><span class="glyphicon icon-tag"></span> {{:c_names}}</p>
        {{/if}}
        {{if open_hours}}
        <p class="p-area"><span class="glyphicon icon-clock-1"></span> {{:open_hours}}</p>
        {{/if}}
        {{if str_brand}}
        <p class="p-description"> {{:str_brand}}</p>
        {{/if}}
      </div>
    </div>
  </div>
</script>



<script id="asl_too_tip" type="text/x-jsrender">
{{if path}}
<div class="image_map_popup" style="display:none"><img src="{{:URL}}Logo/{{:path}}" /></div>
{{/if}}
  <h3>{{:title}}</h3>
  <div class="infowindowContent">
    <div class="info-box-cont">
      <div class="row">
        <div class="col-md-12">
          <div class="{{if path}}info-addr{{else}}info-addr w-100-p{{/if}}">
            <div class="address"><span class="glyphicon icon-location"></span><span>{{:address}}</span></div>
            {{if phone}}
            <div class="phone"><span class="glyphicon icon-phone-outline"></span><a href="tel:{{:phone}}">{{:phone}}</a></div>
            {{/if}}
            {{if email}}
            <div class="p-time"><span class="glyphicon icon-at"></span><a href="mailto:{{:email}}" style="text-transform: lowercase">{{:email}}</a></div>
            {{/if}}
          </div>
          {{if path}}
          <div class="img_box" style="display:none">
            <img src="{{:URL}}Logo/{{:path}}" alt="logo">
          </div>
          {{/if}}
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 asl-tt-details">
          {{if dist_str}}
          <div class="distance"><?php echo esc_attr__( 'Distance','asl_locator') ?>: {{:dist_str}}</div>
          {{/if}}
        </div>
      </div>
    </div>
  <div class="asl-buttons"></div>
</div><div class="arrow-down"></div>
</script>