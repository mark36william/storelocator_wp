<?php


$class = (isset($all_configs['css_class']))? ' '.$all_configs['css_class']: '';

if($all_configs['display_list'] == '0' || $all_configs['first_load'] == '3' || $all_configs['first_load'] == '4')
  $class .= ' map-full';
else if($all_configs['first_load'] == '5') {
  $class .= ' sl-search-only';
}

//add Full height
$class   .= ' '.$all_configs['full_height'];

if($all_configs['pickup'] || $all_configs['ship_from'])
  $class .= ' sl-pickup-tmpl';

if(isset($all_configs['address_ddl']) && $all_configs['address_ddl'] == '1')
  $class .= ' with-addr-ddl';


$default_addr = (isset($all_configs['default-addr']))?$all_configs['default-addr']: '';


$container_class    = (isset($all_configs['full_width']) && $all_configs['full_width'])? 'sl-container-fluid': 'sl-container';
$geo_btn_class      = ($all_configs['geo_button'] == '1')?'asl-geo icon-direction':'icon-search';
$geo_btn_text       = ($all_configs['geo_button'] == '1')?__('Current Location', 'asl_locator'):__('Search', 'asl_locator');
$search_type_class  = ($all_configs['search_type'] == '1')?'asl-search-name':'asl-search-address';
$panel_order        = (isset($all_configs['map_top']))?$all_configs['map_top']: '2';

?>
<style type="text/css">
    <?php echo $css_code; ?>
    @media(max-width:991px){
        #asl-storelocator.asl-cont .asl-panel {order: <?php echo $panel_order ?>;}
    }
</style>
<section id="asl-storelocator" class="asl-cont asl-template-3 asl-layout-<?php echo ($all_configs['layout'] != '0')? '1': '0'; ?> asl-bg-<?php echo $all_configs['color_scheme'].$class; ?> asl-text-<?php echo $all_configs['font_color_scheme'] ?>">
    <div class="asl-wrapper mb-5">
        <div class="<?php echo $container_class ?>">
            <?php if($all_configs['gdpr'] == '1'): ?>
              <div class="sl-gdpr-cont">
                  <div class="gdpr-ol"></div>
                  <div class="gdpr-ol-bg">
                    <div class="gdpr-box">
                      <p><?php echo asl_esc_lbl('label_gdpr') ?></p>
                      <a class="btn btn-asl" id="sl-btn-gdpr"><?php echo asl_esc_lbl('load','asl_locator') ?></a>
                    </div>
                  </div>
              </div>
            <?php endif; ?>
            <?php if(isset($all_configs['address_ddl']) && $all_configs['address_ddl'] == '1'): ?>
            <div class="asl-ddl-filters">
                <div class="sl-row"></div>
            </div>
            <?php endif; ?>
            <div class="sl-row">
                <div class="pol-12">
                    <div class="sl-main-cont">
                        <div class="sl-row no-gutters">
                            <div id="asl-panel" class="asl-panel pol-md-6 pol-lg-5 pol-xl-4">
                                <div class="asl-overlay" id="map-loading">
                                  <div class="white"></div>
                                    <div class="sl-loading">
                                        <i class="animate-sl-spin icon-spin3"></i>
                                        <?php echo asl_esc_lbl('loading') ?>
                                    </div>
                                </div>
                                <div class="asl-filter-sec hide"></div>
                                <!-- list -->
                                <div class="asl-panel-inner">
                                    <div class="sl-filter-sec">
                                        <div class="asl-addr-search">
                                            <input value="<?php echo $default_addr ?>" id="sl-main-search" type="text" class="<?php echo $search_type_class ?> form-control" placeholder="<?php echo asl_esc_lbl('enter_add') ?>">
                                            <span class="sl-search-btn"><i title="<?php echo $geo_btn_text ?>" class="<?php echo $geo_btn_class ?>"></i></span>
                                        </div>  
                                    </div>
                                    <div class="asl-filter-tabs media">
                                        <div class="input-group">
                                            <ul class="sl-filt-a-list nav nav-pills" role="tablist"></ul>  
                                            <div class="input-group-append">
                                                <div class="aswth-btn hide">
                                                    <span class="aswth">
                                                        <input type="checkbox" class="aswth-input" id="aswth-id" checked>
                                                        <label for="aswth-id"></label>
                                                        <div class="aswth-text">
                                                            <div class="contentA"><?php echo asl_esc_lbl('open') ?></div>
                                                            <div class="contentB"><?php echo asl_esc_lbl('all') ?></div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
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
                            <div class="pol-md-6 pol-xl-8 pol-lg-7 asl-map">
                                <div class="map-image">
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
</section>
<!-- This plugin is developed by "Agile Store Locator for WordPress" https://agilestorelocator.com -->