<?php

$geo_btn_class      = ($all_configs['geo_button'] == '1')? 'asl-geo icon-direction-outline':'icon-search';
$search_type_class  = ($all_configs['search_type'] == '1')? 'asl-search-name':'asl-search-address';
$panel_order        = (isset($all_configs['map_top']))? $all_configs['map_top']: '2';

$ddl_class_grid = ($all_configs['search_2'])? 'pol-lg-4 pol-md-6 pol-sm-12': 'pol-lg-4 pol-md-6 pol-sm-12';
$tsw_class_grid = ($all_configs['search_2'])? 'pol-lg-2 pol-md-3 pol-sm-12': 'pol-lg-2 pol-md-3 pol-sm-12';
$adv_class_grid = ($all_configs['search_2'])? 'pol-lg-8 pol-md-7': 'pol-lg-8 pol-md-7';


$ddl_class      = '';


$class = (isset($all_configs['css_class']))? ' '.$all_configs['css_class']: '';

if($all_configs['display_list'] == '0' || $all_configs['first_load'] == '3' || $all_configs['first_load'] == '4')
  $class .= ' map-full';
else if($all_configs['first_load'] == '5') {
  $class .= ' sl-search-only';
}

if($all_configs['pickup'] || $all_configs['ship_from'])
  $class .= ' sl-pickup-tmpl';

if($all_configs['full_width'])
  $class .= ' full-width';

if(isset($all_configs['full_map']))
  $class .= ' map-full-width';


if($all_configs['layout'] == '1' || $all_configs['layout'] == '2' || $all_configs['advance_filter'] == '0') {

  $all_configs['advance_filter'] = $all_configs['show_categories'] = '0';
}

if($all_configs['advance_filter'] == '0')
  $class .= ' no-asl-filters';


else if($all_configs['show_categories'] == '0') {
 $class .= ' asl-no-categories'; 
}


if($all_configs['advance_filter'] == '1' && $all_configs['layout'] == '1')
  $class .= ' asl-adv-lay1';

if($all_configs['tabs_layout'] == '1') {

  $ddl_class  .= ' asl-tabs-ddl pol-12 pol-lg-12 pol-md-12 pol-sm-12';
  $class      .= ' sl-category-tabs';
}

//add Full height
$class .= ' '.$all_configs['full_height'];



$layout_code        = ($all_configs['layout'] == '1'  || $all_configs['layout'] == '2')? '1': '0';
$default_addr       = (isset($all_configs['default-addr']))?$all_configs['default-addr']: '';
$container_class    = (isset($all_configs['full_width']) && $all_configs['full_width'])? 'sl-container-fluid': 'sl-container';
?>
<style type="text/css">
  <?php echo $css_code; ?>
  .asl-p-cont .onoffswitch .onoffswitch-label .onoffswitch-switch:before {content: "<?php echo asl_esc_lbl('open') ?>" !important;}
  .asl-p-cont .onoffswitch .onoffswitch-label .onoffswitch-switch:after {content: "<?php echo asl_esc_lbl('all') ?>" !important;}
  @media (max-width: 767px) {
    #asl-storelocator.asl-cont .asl-panel {order: <?php echo $panel_order ?>;}
  }
</style>
<div id="asl-storelocator" class="storelocator-main asl-cont asl-template-2 asl-layout-<?php echo $layout_code; ?> asl-bg-<?php echo $all_configs['color_scheme_2'].$class; ?> asl-text-<?php echo $all_configs['font_color_scheme'] ?>">
  <div class="asl-wrapper">
    <div class="<?php echo $container_class ?>">
      <?php if($all_configs['gdpr'] == '1'): ?>
      <div class="sl-gdpr-cont">
          <div class="gdpr-ol"></div>
          <div class="gdpr-ol-bg">
            <div class="gdpr-box">
              <p><?php echo asl_esc_lbl('label_gdpr') ?></p>
              <a class="btn btn-asl" id="sl-btn-gdpr"><?php echo asl_esc_lbl('load') ?></a>
            </div>
          </div>
      </div>
      <?php endif; ?>
      <div class="sl-row">
        <div class="pol-12">
            <div class="sl-main-cont">
                <div id="asl-panel" class="sl-row no-gutters sl-main-row">
                    <div id="asl-list" class="asl-panel pol-md-5">
                      <div class="asl-overlay" id="map-loading">
                        <div class="white"></div>
                        <div class="sl-loading">
                          <i class="animate-sl-spin icon-spin3"></i>
                          <?php echo asl_esc_lbl('loading') ?>
                        </div>
                      </div>
                      <?php if($all_configs['advance_filter']): ?> 
                        <!-- Filter Box -->
                        <div class="filter-box asl-dist-ctrl-0">
                          <div class="sl-row">
                            <?php if($all_configs['time_switch']): ?> 
                              <div class="pol Status_filter">
                                <div class="asl-filter-cntrl">
                                  <label class="asl-cntrl-lbl"><?php echo asl_esc_lbl('status') ?></label>
                                  <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="asl-open-close" checked>
                                    <label class="onoffswitch-label" for="asl-open-close">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <?php endif; ?>
                              <div class="pol range_filter hide asl-ddl-filters">
                                <div class="rangeFilter asl-filter-cntrl">
                                  <label class="asl-cntrl-lbl"><?php echo asl_esc_lbl('distance_tab') ?></label>
                                  <input id="asl-radius-slide" type="text" class="span2" />
                                  <span class="rad-unit"><?php echo asl_esc_lbl('radius') ?>: <span id="asl-radius-input"></span> <span id="asl-dist-unit"><?php echo asl_esc_lbl('km','asl_locator') ?></span></span>
                                </div>
                             </div>
                          </div>
                        </div>
                        <!-- Filter Box -->
                        <?php if($all_configs['show_categories'] == '1'): ?>
                        <!-- categories, change name asl-cats-main-panel - asl-categories-panel  -->
                        <div class="asl-categories-panel">
                          <div class="cats-title">
                            <h6><?php echo asl_esc_lbl('categories_tab') ?></h6>
                          </div>
                            <div class="asl-cats-inner-panel">
                              <!-- change name cats-inner-panel - asl-categories-list-->
                              <div class="asl-categories-list"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                      <?php endif;?>
                      <!-- list -->
                      <div class="asl-panel-inner">
                        <div class="top-title Num_of_store">
                          <span><?php echo $all_configs['head_title'] ?>: <span class="count-result">0</span></span>
                          <a class="back-button"><?php echo asl_esc_lbl('back') ?> <i class="icon-back"></i></a>
                        </div>
                        <div class="sl-main-cont-box">
                          <div id="asl-list" class="sl-list-wrapper">
                            <ul id="p-statelist" class="sl-list">
                            </ul>
                          </div>
                        </div>
                      </div>

                      <div class="directions-cont hide">
                          <div class="agile-modal-header">
                              <button type="button" class="close"><span aria-hidden="true">×</span></button>
                              <h4><?php echo asl_esc_lbl('store_direc') ?></h4>
                          </div>
                          <div class="rendered-directions" id="asl-rendered-dir" style="direction: ltr;"></div>
                      </div>
                    </div>
                    <div class="pol-md-7 asl-map">
                        <div class="map-image">
                            <div class="search_filter inside-map">
                              <div class="asl-search-box">
                                <input type="text" value="<?php echo $default_addr ?>" id="auto-complete-search" class="form-control <?php echo $search_type_class ?>" placeholder="<?php echo asl_esc_lbl('find_store') ?>">
                                <button type="button" class="asl-search-btn">
                                  <i class="glyphicon <?php echo $geo_btn_class ?>" title="<?php echo ($all_configs['geo_button'] == '1')?__('Current Location','asl_locator'):__('Search Location','asl_locator') ?>"></i>
                                </button>
                              </div>
                            </div>
                            <div id="asl-map-canv" class="asl-map-canv"></div>
                            <?php include ASL_PLUGIN_PATH.'public/partials/_agile_modal.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- This plugin is developed by "Agile Store Locator by WordPress" https://agilestorelocator.com -->