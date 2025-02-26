<div class="asl-p-cont asl-new-bg">
    <div class="container">
        <div class="asl-customize-map">
            <div class="card p-0 mb-4 asl-inner-cont">
                <h3 class="card-title"><?php echo esc_attr__('Customize Map','asl_locator') ?></h3>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <div class="btn-group  btn-group-toggle"  data-toggle="buttons" id="asl-fill-option">
                                      <label class="btn btn-sm btn-dark" data-value="0">
                                        <?php echo esc_attr__('Border','asl_locator') ?>
                                      </label>
                                      <label class="btn btn-sm btn-dark" data-value="1">
                                        <?php echo esc_attr__('Solid','asl_locator') ?>
                                      </label>
                                    </div>
                                    <button class="btn btn-sm btn-danger" type="button" id="asl-delete-shape"><span style="margin-right:10px;font-size:12px" class="glyphicon glyphicon-trash"></span><span><?php echo esc_attr__('Delete','asl_locator') ?></span></button>
                                    <button class="btn btn-sm btn-warning" type="button" id="asl-clear-all"><span><?php echo esc_attr__('Clear All','asl_locator') ?></span></button>
                                </div>
                                <div class="col-md-7">
                                    <div class="color_scheme">
                                        <div id="radio" class="map_cange">
                                            <span>
                                                <input type="radio" id="asl-color_scheme-0" value="#CC3333" name="data[color_scheme]">
                                                <label class="color-box color-0" for="asl-color_scheme-0"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-1" value="#E11619" name="data[color_scheme]">
                                                <label class="color-box color-1" for="asl-color_scheme-1"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-2" value="#542733" name="data[color_scheme]">
                                                <label class="color-box color-2" for="asl-color_scheme-2"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-3" value="#278BBC" name="data[color_scheme]">
                                                <label class="color-box color-3" for="asl-color_scheme-3"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-4" value="#78C1E4" name="data[color_scheme]">
                                                <label class="color-box color-4" for="asl-color_scheme-4"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-5" value="#ACD55D" name="data[color_scheme]">
                                                <label class="color-box color-5" for="asl-color_scheme-5"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-6" value="#A8BD78" name="data[color_scheme]">
                                                <label class="color-box color-6" for="asl-color_scheme-6"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-7" value="#EAAE40" name="data[color_scheme]">
                                                <label class="color-box color-7" for="asl-color_scheme-7"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-8" value="#E68EC1" name="data[color_scheme]">
                                                <label class="color-box color-8" for="asl-color_scheme-8"></label>
                                            </span>
                                            <span>
                                                <input type="radio" id="asl-color_scheme-9" value="#B39571" name="data[color_scheme]">
                                                <label class="color-box color-9" for="asl-color_scheme-9"></label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input id="asl-setting-search-box" type="text" class="form-control" placeholder="<?php echo esc_attr('Search','asl_locator') ?>">
                                </div>
                                <div class="col-12">
                                    <div class="map_canvas" style="height:500px" id="map_canvas"></div>
                                </div>
                            </div>
                        </div>
                        <!-- col 4 -->
                        <div class="col-lg-4 col-md-12">
                            <div class="asl-kml-side-bar">
                                <h3 class="asl-kml-title"><?php echo esc_attr__('KML Files','asl_locator') ?></h3>
                                <div class="row">
                                    <div class="col-12">
                                        <form id="sl-frm-kml">
                                            <div class="input-group" id="drop-zone-1">
                                                <div class="custom-file">
                                                    <input type="file" class="btn btn-default" style="width:100%;opacity:0;position:absolute;top:0;left:0"  name="files" id="file-img-2" />
                                                    <label  class="custom-file-label" for="file-img-2"><?php echo esc_attr__('File Path...','asl_locator') ?></label>
                                                </div>
                                                <div class="form-group">
                                                    <button type="button" id="btn-asl-upload-kml" class="btn btn-primary btn-start"><?php echo esc_attr__('Upload KML','asl_locator') ?></button>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <ul></ul>
                                            </div>
                                        </form>
                                        <p class="help-p mt-0"><a target="_blank" class="text-muted" href="https://agilestorelocator.com/wiki/intro-to-kml-files/"><?php echo esc_attr__('How to use KML files?','asl_locator') ?></a></p>
                                    </div>
                                </div>
                                <h6 class="asl-kml-list-title mt-3"><?php echo esc_attr__('The List of KML Files','asl_locator') ?></h6>
                                <ul class="asl-kml-list">
                                    <?php 

                                        $files = \AgileStoreLocator\Helper::get_kml_files();

                                        if(empty($files)): ?>
                                            <li>
                                                <?php echo esc_attr__('No KML File Exist!','asl_locator'); ?>
                                            </li>
                                        <?php 
                                        endif;

                                        foreach($files as $file): ?>
                                            <li>
                                                <a>
                                                    <span class="asl-file-name"><?php echo esc_attr($file); ?></span>
                                                    <span data-file="<?php echo esc_attr($file); ?>" title="<?php echo esc_attr__('Delete','asl_locator') ?>" class="dashicons dashicons-trash asl-trash-icon"></span>
                                                </a>
                                            </li>
                                        <?php   
                                        endforeach;
                                    ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <form id="frm-asl-layers" class="asl-setting-cont">
                        <div class="row map-option-bottom my-3">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="asl-trafic_layer"><?php echo esc_attr__('Traffic Layer','asl_locator') ?></label>
                                    <div class="a-swith a-swith-alone">
                                        <input type="checkbox" class="cmn-toggle cmn-toggle-round" id="asl-trafic_layer" name="data[trafic_layer]">
                                        <label for="asl-trafic_layer"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="asl-transit_layer"><?php echo esc_attr__('Transit Layer','asl_locator') ?></label>
                                    <div class="a-swith a-swith-alone">
                                        <input type="checkbox" class="cmn-toggle cmn-toggle-round" id="asl-transit_layer" name="data[transit_layer]">
                                        <label for="asl-transit_layer"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="asl-bike_layer"><?php echo esc_attr__('Bike Layer','asl_locator') ?></label>
                                    <div class="a-swith a-swith-alone">
                                        <input type="checkbox" class="cmn-toggle cmn-toggle-round" id="asl-bike_layer" name="data[bike_layer]">
                                        <label for="asl-bike_layer"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="asl-marker_animations"><?php echo esc_attr__('Marker Animation','asl_locator') ?></label>
                                    <div class="a-swith a-swith-alone">
                                        <input type="checkbox" class="cmn-toggle cmn-toggle-round" id="asl-marker_animations" name="data[marker_animations]">
                                        <label for="asl-marker_animations"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row align-items-center">
                        <div class="col-md-12 mt-3">
                            <button type="button" id="asl-save-map" data-loading-text="<?php echo esc_attr__('Saving...','asl_locator') ?>" class="float-right btn btn-success"><?php echo esc_attr__('Save Customization','asl_locator') ?></button>
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
        url: '<?php echo ASL_UPLOAD_URL ?>'
    };

    var asl_configs       =  <?php echo json_encode($all_configs); ?>;
    var asl_map_customize =  <?php echo $map_customize; ?>;
    
    window.addEventListener("load", function() {
    asl_engine.pages.customize_map(asl_map_customize);
    });
</script>