<?php


//  Font Size
$attr_key     = 'title-size';
$title_font   = isset($fields->$attr_key) ? $fields->$attr_key : $default_fonts['title-size'];

$attr_key     = 'font-size';
$content_font = isset($fields->$attr_key) ? $fields->$attr_key : $default_fonts['font-size'];

$attr_key     = 'btn-size';
$button_font  = isset($fields->$attr_key) ? $fields->$attr_key : $default_fonts['btn-size'];

?>

<ul class="nav nav-pills justify-content-center">
    <li class="rounded active"><a data-toggle="pill" href="#asl-color"><?php echo esc_attr__('Colors','asl_locator') ?></a></li>
    <li class="rounded"><a data-toggle="pill" href="#sl-font-size"><?php echo esc_attr__('Font Size','asl_locator') ?></a></li>
</ul>
<div class="tab-content">
   <div id="sl-font-size" class="tab-pane">
      <div class="row mt-2">
        <?php 
        foreach($default_fonts as $font_key => $font_value): 

          $font_lbl = $font_labels[$font_key];

          $attr_key     = $font_key;
          $font_size    = isset($fields->$attr_key) ? $fields->$attr_key : $default_fonts[$attr_key];

        ?>
        <div class="col-md-6 col-12 mb-5">
          <div class="form-group d-lg-flex d-md-block">
            <label class="custom-control-label" for="asl-font-size"><?php echo esc_attr($font_lbl) ?></label>
            <div class="form-group-inner">
              <div class="input-group">
                <input  type="number" class="form-control" name="<?php echo esc_attr($font_key) ?>" id="asl-<?php echo esc_attr($font_key) ?>" placeholder="<?php echo esc_attr__('13','asl_locator') ?>" value="<?php echo esc_attr($font_size); ?>">
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
   </div>
   <div id="asl-color" class="tab-pane in active">
      <div class="row">
        <?php 
        foreach ($colors[$template] as $color => $type) {

          $text    = str_replace('-',' ',$color);
          $text    = ucfirst($text);
          $f_color = isset($fields->$color) ? $fields->$color : $default_colors[$color];
        ?>
          <div class="col-md-6 col-sm-6 col-12 mb-5">
            <div class="form-group d-lg-flex d-md-block color-row">
              <label class="custom-control-label" for="asl-<?php echo esc_attr($color) ?>"><?php echo esc_attr($text) ?></label>
              <div class="form-group-inner">
                  <input type="text" id="asl-<?php echo esc_attr($color) ?>-text" value="<?php echo esc_attr($f_color) ?>" class="hexcolor" name="<?php echo esc_attr($color) ?>" >
                  <input type="color" class="colorpicker <?php echo esc_attr($type); ?>" id="asl-<?php echo esc_attr($color) ?>"  value="<?php echo esc_attr($f_color) ?>">
              </div>
            </div>
          </div>
        <?php
        }
        ?>
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