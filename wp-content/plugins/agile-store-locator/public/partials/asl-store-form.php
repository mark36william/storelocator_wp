<?php

$default_country = (isset($all_configs['default_country']))? $all_configs['default_country']: 'null';

?>
<section class="asl-cont asl-store-form">
  <div class="sl-container">
      <div class="sl-row">
          <!-- Section Titile -->
          <div class="pol-md-12">
              <h1 class="section-title"><?php echo asl_esc_lbl('reg_store') ?></h1>
              <p><?php echo asl_esc_lbl('reg_store_ins') ?></p>
          </div>
      </div>
      <div class="sl-row">
          <div class="pol-md-12">
              <div id="sl-frm" class="asl-form sl-row">
                  <div class="pol-md-12">
                      <h3 class="sl-sub-title"><?php echo asl_esc_lbl('reg_store_info') ?></h3>
                  </div>
                  <!-- Name -->
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-title"><?php echo asl_esc_lbl('reg_company') ?></label>
                          <input class="form-control" id="sl-title" type="text" maxlength="255" name="title" required data-pristine-required-message="Please choose a username">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-description"><?php echo asl_esc_lbl('reg_name') ?></label>
                          <input class="form-control" id="sl-description" type="text" maxlength="255" name="description" required>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-website"><?php echo asl_esc_lbl('reg_web_url') ?></label>
                          <input class="form-control" id="sl-website" type="text" maxlength="255" name="website">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-phone"><?php echo asl_esc_lbl('phone') ?></label>
                          <input class="form-control" id="sl-phone" type="text" maxlength="255" name="phone">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-fax"><?php echo asl_esc_lbl('fax') ?></label>
                          <input class="form-control" id="sl-fax" type="text" maxlength="255" name="fax">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-email"><?php echo asl_esc_lbl('email') ?></label>
                          <input class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="sl-email" type="text" maxlength="255" name="email">
                          <div class="help-block with-errors"><?php echo asl_esc_lbl('Enter correct email address') ?></div>
                      </div>
                  </div>
                  <div class="pol-md-6">
                      <div class="sl-form-group sl-form-ddl sl-group">
                          <label for="sl-categories" class="control-label"><?php echo asl_esc_lbl('categories_tab') ?></label>
                          <select class="form-control custom-select" id="sl-categories" multiple="multiple">
                            <?php foreach($all_categories as $category): ?>
                            <option value="<?php echo $category->id ?>"><?php echo esc_attr($category->category_name) ?></option>
                            <?php endforeach ?>
                          </select>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-brand" class="pol-md-6">
                      <div class="sl-form-group sl-form-ddl sl-group">
                          <label for="sl-brand" class="control-label"><?php echo asl_esc_lbl('reg_brands') ?></label>
                          <select class="form-control custom-select" id="sl-brand" multiple="multiple">
                            <?php foreach($all_brand as $brand): ?>
                            <option value="<?php echo $brand->id ?>"><?php echo esc_attr($brand->name) ?></option>
                            <?php endforeach ?>
                          </select>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-special" class="pol-md-6">
                      <div class="sl-form-group sl-form-ddl sl-group">
                          <label for="sl-special" class="control-label"><?php echo asl_esc_lbl('reg_specialities') ?></label>
                          <select class="form-control custom-select" id="sl-special" multiple="multiple">
                            <?php foreach($all_special as $special): ?>
                            <option value="<?php echo $special->id ?>"><?php echo esc_attr($special->name) ?></option>
                            <?php endforeach ?>
                          </select>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="pol-md-12">
                      <h3 class="sl-sub-title"><?php echo asl_esc_lbl('reg_add_loc') ?></h3>
                  </div>
                  <div id="sl-grp-street" class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-street"><?php echo asl_esc_lbl('reg_street') ?></label>
                          <input class="form-control" id="sl-street" type="text" maxlength="255" name="street">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-city" class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-city"><?php echo asl_esc_lbl('label_city') ?></label>
                          <input class="form-control" id="sl-city" type="text" maxlength="255" required name="city">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-state" class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-state"><?php echo asl_esc_lbl('label_state') ?></label>
                          <input class="form-control" id="sl-state" type="text" maxlength="255" name="state">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-postal_code" class="pol-md-6">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-postal_code"><?php echo asl_esc_lbl('reg_post_code') ?></label>
                          <input class="form-control" id="sl-postal_code" type="text" maxlength="255" required name="postal_code">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-country" class="pol-md-6">
                      <div class="sl-form-group sl-form-ddl sl-group">
                          <label class="control-label" for="sl-country"><?php echo asl_esc_lbl('label_country') ?></label>
                          <select class="form-control custom-select" id="sl-country" required name="country">
                            <option value=""><?php echo asl_esc_lbl('Select') ?></option>
                            <?php foreach($countries as $country): ?>
                            <option <?php if($default_country && $default_country == $country->id) echo 'selected' ?> value="<?php echo $country->id ?>"><?php echo esc_attr($country->country) ?></option>
                            <?php endforeach ?>
                          </select>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <?php if($all_configs['map'] != '0'): ?>
                  <div class="pol-md-12">
                    <div id="asl-register-map" class="asl-register-map"></div>
                  </div>
                  <?php endif; ?>
                  <div id="sl-grp-lat" class="pol-md-6 d-none">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-lat"><?php echo asl_esc_lbl('reg_lat') ?></label>
                          <input class="form-control" id="sl-lat" type="text" maxlength="255" name="lat">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-lng" class="pol-md-6 d-none">
                      <div class="sl-form-group sl-group">
                          <label class="control-label" for="sl-lng"><?php echo asl_esc_lbl('reg_long') ?></label>
                          <input class="form-control" id="sl-lng" type="text" maxlength="255" name="lng">
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div id="sl-grp-desc" class="pol-md-12">
                    <div class="sl-row">
                      <div class="pol-md-12">
                        <h3 class="sl-sub-title"><?php echo asl_esc_lbl('reg_add_data') ?></h3>
                      </div>
                      <?php foreach($fields as $field): 
                      $field_name  = esc_attr($field['name']);
                      $field_label = esc_attr($field['label']);
                      ?>
                      <div class="pol-md-6">
                        <div class="sl-form-group sl-group">
                          <label class="control-label" for="custom-f-<?php echo $field_name; ?>"><?php echo $field_label; ?></label>
                          <input type="text" id="custom-f-<?php echo $field_name; ?>" name="<?php echo $field_name; ?>"  class="form-control">
                        </div>
                      </div>
                    <?php endforeach; ?>
                      <div class="pol-md-6">
                          <div class="sl-form-group sl-group">
                              <label for="sl-description_2" class="control-label"><?php echo asl_esc_lbl('reg_add_desc') ?></label>
                              <textarea class="form-control" rows="3" id="sl-description_2"  name="description_2"></textarea>
                              <div class="help-block with-errors"></div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="pol-md-12">
                    <div class="sl-form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" value="" id="sl-agr-check" required>
                        <label class="custom-control-label" for="sl-agr-check">
                          <?php echo asl_esc_lbl('reg_agree') ?>
                        </label>
                        <div class="invalid-feedback">
                          <?php echo asl_esc_lbl('reg_agree2') ?></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="pol-md-12">
                      <div class="sl-form-group mt-3">
                          <a data-loading-text="<?php echo asl_esc_lbl('reg_registering') ?>" class="btn btn-default btn-primary disabled" id="sl-btn-save"><?php echo asl_esc_lbl('reg_register') ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>