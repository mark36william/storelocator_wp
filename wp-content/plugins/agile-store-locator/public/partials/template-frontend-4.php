<?php

// support only the list layout
$all_configs['layout'] = '0';


$class=(isset($all_configs['css_class']))? ' '.$all_configs['css_class']: '';

if($all_configs['display_list'] == '0' || $all_configs['first_load'] == '3' || $all_configs['first_load'] == '4')
  $class .= ' map-full';


$ddl_class_grid = 'pol-md-6 pol-sm-12';


$ddl_class      = '';

//add sl-full-star height
$class .= ' '.$all_configs['full_height'];

$default_addr = (isset($all_configs['default-addr']))?$all_configs['default-addr']: '';


$container_class    = (isset($all_configs['sl-full-star_width']) && $all_configs['sl-full-star_width'])? 'container-fluid': 'container';
$geo_btn_class      = ($all_configs['geo_button'] == '1')?'asl-geo icon-direction':'icon-search';
$geo_btn_text       = ($all_configs['geo_button'] == '1')?__('Current Location', 'asl_locator'):__('Search', 'asl_locator');
$search_type_class  = ($all_configs['search_type'] == '1')?'asl-search-name':'asl-search-address';
$panel_order        = (isset($all_configs['map_top']))?$all_configs['map_top']: '2';



if($all_configs['tabs_layout'] == '1') {

   $ddl_class_grid = 'pol-sm-12';
   $ddl_class     .= ' asl-tabs-ddl pol-12 pol-lg-12 pol-md-12 pol-sm-12';
   $class         .= ' sl-category-tabs';
}

?>
<style type="text/css">
   <?php echo $css_code; ?>
</style>

<section id="asl-storelocator" class="asl-cont asl-template-4 asl-layout-<?php echo $all_configs['layout']; ?> asl-bg-<?php echo $all_configs['color_scheme'].$class; ?> asl-text-<?php echo $all_configs['font_color_scheme'] ?>">
    <?php if($all_configs['gdpr'] == '1'): ?>
    <div class="sl-gdpr-cont">
        <div class="gdpr-ol"></div>
        <div class="gdpr-ol-bg"></div>
        <div class="gdpr-box">
          <p><?php echo asl_esc_lbl('label_gdpr') ?></p>
          <a class="btn btn-asl" id="sl-btn-gdpr"><?php echo asl_esc_lbl('accpt') ?></a>
        </div>
    </div>
    <?php endif; ?>
    <div class="asl-overlay" id="map-loading">
        <div class="white"></div>
        <div class="sl-loading">
          <i class="animate-sl-spin icon-spin3"></i>
          <?php echo asl_esc_lbl('loading') ?>
        </div>
    </div>
     <div class="asl-wrapper">
         <div class="sl-container">
            <div class="sl-row asl-top-area">
               <div class="pol-md-6">
                  <div class="Filter_section">
                    <div class="search_filter">
                       <h3 class="asl-filter-top-title"><?php echo asl_esc_lbl('search_near') ?></h3>
                       <div class="sl-search-group">
                          <input type="text" value="<?php echo $default_addr ?>" data-submit="disable"  tabindex="2" id="auto-complete-search" placeholder="<?php echo asl_esc_lbl('search_loc') ?>"  class="asl-search-address isp_ignore">
                          <button type="button" class="span-geo"><i class="asl-geo icon-direction-outline" title="Current Location"></i></button>
                       </div>
                    </div>
                    <div class="asl-advance-filters hide">
                       <div class="sl-row">
                          <?php if($all_configs['search_2']): ?>
                          <div class="pol-md-12 asl-name-search">
                             <div class="asl-filter-cntrl">
                                <label class="asl-cntrl-lbl"><?php echo asl_esc_lbl('search_name') ?></label>
                                <div class="sl-search-group">
                                   <input type="text" tabindex="2"  placeholder="<?php echo asl_esc_lbl('search_name') ?>"  class="asl-search-name form-control isp_ignore">
                                </div>
                             </div>
                          </div>
                          <?php endif ?>
                          <?php if($all_configs['show_categories']): ?>
                          <div class="<?php echo $ddl_class_grid ?> <?php echo $ddl_class ?> asl-ddl-filters">
                             <div class="asl-filter-cntrl">
                                <label class="asl-cntrl-lbl"><?php echo $all_configs['category_title']  ?></label>
                                <div class="sl-dropdown-cont" id="categories_filter">
                                </div>
                             </div>
                          </div>
                          <?php endif ?>
                          <?php if($filter_ddl): ?>
                          <?php foreach ($filter_ddl as $a_filter):?>
                          <div class="<?php echo $ddl_class_grid ?> <?php echo $ddl_class ?> asl-ddl-filters">
                              <div class="asl-filter-cntrl">
                                <label class="asl-cntrl-lbl"><?php echo esc_attr__( $a_filter, 'asl_locator') ?></label>
                                <div class="sl-dropdown-cont" id="<?php echo $a_filter ?>_filter">
                                </div>
                              </div>
                          </div>
                          <?php endforeach; ?>
                          <?php endif; ?>
                          <div class="pol-lg-6 pol-md-12 pol-sm-6 col-12 range_filter hide asl-ddl-filters">
                             <div class="asl-filter-cntrl rangeFilter">
                                <label class="asl-cntrl-lbl"><?php echo asl_esc_lbl('distance_tab') ?></label>
                                <input id="asl-radius-slide" type="text" class="span2" />
                                <span class="rad-unit"><?php echo asl_esc_lbl('radius') ?>: <span id="asl-radius-input"></span> <span id="asl-dist-unit"><?php echo asl_esc_lbl('km') ?></span></span>
                             </div>
                          </div>
                          <div class="pol-lg-6 pol-md-12 pol-sm-6 col-12 Status_filter">
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
                          
                       </div>
                    </div>
                    <div class="search_btn_box">
                       <div class="asl-filter-cntrl">
                          <a  title="<?php echo asl_esc_lbl('search_loc') ?> ?>" class="sl-search-btn icon-search">
                            <?php echo asl_esc_lbl('search') ?>
                          </a>
                       </div>
                    </div>
                  </div>
               </div>
               <div class="pol-md-6">
                  <div class="asl-map">
                    <div class="map-image">
                       <div id="asl-map-canv" class="asl-map-canv"></div>
                        <?php include ASL_PLUGIN_PATH.'public/partials/_agile_modal.php'; ?>
                    </div>
                  </div>
               </div>
            </div>
            <div class="sl-row">
               <div class="pol-12">
                  <div class="sl-main-cont">
                     <div id="asl-panel" class="sl-main-row">
                        <div id="asl-list" class="asl-panel">
                           <div class="asl-panel-inner">
                                <div class="Num_of_store pb-3">
                                  <span class="count-result-text"><?php echo $all_configs['head_title'] ?>: <span class="count-result">0</span></span>
                                  <a class="asl-print-btn"><i class="icon-print"></i> <?php echo asl_esc_lbl('print') ?></a>
                                </div>
                              <div class="sl-main-cont-box">
                                 <div class="sl-list-wrapper">
                                    <ul id="p-statelist" class="sl-list sl-row panel-inner">
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
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</section>
<!-- This plugin is developed by "Agile Store Locator for WordPress" https://agilestorelocator.com -->
