<?php
$quest_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0171B6" class="bi bi-question-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path><path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"></path></svg>';
$cross_svg = '<svg width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.837 3.20948C14.5471 -1.06983 7.50732 -1.06983 3.21742 3.20948C-1.07247 7.48878 -1.07247 14.5112 3.21742 18.7905C7.50732 23.0698 14.4372 23.0698 18.727 18.7905C23.0169 14.5112 23.1269 7.48878 18.837 3.20948ZM14.1072 15.6085L11.0272 12.5362L7.94731 15.6085L6.40735 14.0723L9.48727 11L6.40735 7.92768L7.94731 6.39152L11.0272 9.46384L14.1072 6.39152L15.6471 7.92768L12.5672 11L15.6471 14.0723L14.1072 15.6085Z" fill="#FE4848"/></svg>';
$right_svg = '<svg width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 0C4.925 0 0 4.925 0 11C0 17.075 4.925 22 11 22C17.075 22 22 17.075 22 11C22 4.925 17.075 0 11 0ZM15.768 9.14C15.8558 9.03964 15.9226 8.92274 15.9646 8.79617C16.0065 8.6696 16.0227 8.53591 16.0123 8.40298C16.0018 8.27005 15.9648 8.14056 15.9036 8.02213C15.8423 7.90369 15.758 7.79871 15.6555 7.71334C15.5531 7.62798 15.4346 7.56396 15.3071 7.52506C15.1796 7.48616 15.0455 7.47316 14.9129 7.48683C14.7802 7.50049 14.6517 7.54055 14.5347 7.60463C14.4178 7.66872 14.3149 7.75554 14.232 7.86L9.932 13.019L7.707 10.793C7.5184 10.6108 7.2658 10.51 7.0036 10.5123C6.7414 10.5146 6.49059 10.6198 6.30518 10.8052C6.11977 10.9906 6.0146 11.2414 6.01233 11.5036C6.01005 11.7658 6.11084 12.0184 6.293 12.207L9.293 15.207C9.39126 15.3052 9.50889 15.3818 9.63842 15.4321C9.76794 15.4823 9.9065 15.505 10.0453 15.4986C10.184 15.4923 10.32 15.4572 10.4444 15.3954C10.5688 15.3337 10.6791 15.2467 10.768 15.14L15.768 9.14Z" fill="#97D865"/></svg>';

$level_mode = \AgileStoreLocator\Helper::expertise_level();

/*
$curl_version = function_exists('curl_version')? curl_version(): null;

if($curl_version) {
  $curl_version = $curl_version["version"];
}
else {
  $curl_version = $cross_svg;
}
*/



// Support Status
$support_status = \AgileStoreLocator\Admin\License::supported_info();

$support_text   = ($support_status)? $support_status:  $cross_svg;



//  simple level
if($level_mode == '1'): ?>
<style type="text/css">
  .sl-complx {display: none;}
</style>
<?php endif; ?>

<div class="asl-p-cont asl-new-bg asl-main-dashboard">
  <div class="hide">
    <svg xmlns="http://www.w3.org/2000/svg">
      <symbol id="i-cart" viewBox="0 0 32 32" width="40" height="40" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <path d="M6 6 L30 6 27 19 9 19 M27 23 L10 23 5 2 2 2" />
          <circle cx="25" cy="27" r="2" />
          <circle cx="12" cy="27" r="2" />
      </symbol>
      <symbol id="i-tag" viewBox="0 0 32 32" width="40" height="40" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <circle cx="24" cy="8" r="2" />
        <path d="M2 18 L18 2 30 2 30 14 14 30 Z" />
      </symbol>
      <symbol id="i-location" viewBox="0 0 32 32" width="40" height="40" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <circle cx="16" cy="11" r="4" />
          <path d="M24 15 C21 22 16 30 16 30 16 30 11 22 8 15 5 8 10 2 16 2 22 2 27 8 24 15 Z" />
      </symbol>
      <symbol id="i-search" viewBox="0 0 32 32" width="40" height="40" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <circle cx="14" cy="14" r="12" />
          <path d="M23 23 L30 30"  />
      </symbol>
    </svg>
  </div>
  <!-- Dashboard Body -->
  <div class="asl-top-header">
    <div class="row">
      <div class="col-md-8 col-sm-8">
        <div class="asl-main-title">
          <h1><?php echo esc_attr__('Agile Store Locator','asl_locator') ?>
          </h1>
        </div>
      </div>
      <div class="col-md-4 col-sm-4">
        <div class="asl-swicher-box">
          <span class="asl-lable-text"><?php echo esc_attr__('Advanced','asl_locator') ?></span>
          <form>
            <div class="input-group">
               <input type="checkbox" <?php if(\AgileStoreLocator\Helper::expertise_level()) echo 'checked="checked"' ?> id="asl-level-swtch" /><label for="asl-level-swtch">Toggle</label>
            </div>
          </form>
          <span class="asl-lable-text"><?php echo esc_attr__('Simple','asl_locator') ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="asl-inner-dashboard">
    <div class="asl-process-sec">
      <div class="asl-top-title">
        <h2><?php echo esc_attr__('How it works?','asl_locator') ?></h2>
        <p><?php echo esc_attr__('Discover how the Agile Store Locator Plugin works and explore the 3 most vital steps to use the plugin.','asl_locator') ?></p>
      </div>
      <div class="row">
        <div class="col-12">
        <?php if(!$all_configs['api_key']): ?>
            <h3  class="alert alert-danger" style="font-size: 14px"><?php echo esc_attr__('Alert! Google API KEY is missing, the Map search, geocoding and direction will not work without it, Please add Google API KEY first.','asl_locator') ?> <a href="https://agilestorelocator.com/blog/enable-google-maps-api-agile-store-locator-plugin/" target="_blank">How to Add API Key?</a></h3>
        <?php endif; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-6">
          <div class="asl-process-box">
            <h3 class="mb-4"><span>01.</span> <a href="<?php echo admin_url().'post-new.php?post_type=page' ?>"><?php echo esc_attr__('Add the shortcode','asl_locator') ?></a></h3>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/process_img2.png" class="img-fluid">
            <p class="mt-3"><?php echo __('Add the shortcode <b>[ASL_STORELOCATOR]</b> on the new page or whichever page you’d like to display it.','asl_locator') ?></p>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="asl-process-box">
            <h3 class="mb-4"><span>02.</span> <a href="<?php echo admin_url().'admin.php?page=asl-settings' ?>"><?php echo esc_attr__('Add the API Key','asl_locator') ?></a></h3>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/process_img3.png" class="img-fluid">
            <p class="mt-3"><?php echo esc_attr__('Generate your Google Maps API key so  Map related features can work.','asl_locator') ?> | <a  style="font-size: 13px;color: #0171B6 !important;" target="_blank" href="https://www.youtube.com/watch?v=CC0WMJcGpFM"><?php echo esc_attr__('Video Tutorial','asl_locator') ?></a></p>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="asl-process-box">
            <h3 class="mb-4"><span>03.</span> <a href="<?php echo admin_url().'admin.php?page=create-agile-store' ?>"><?php echo esc_attr__('Add your Stores','asl_locator') ?></a></h3>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/process_img1.png" class="img-fluid">
            <p class="mt-3"><?php echo esc_attr__('Remove the dummy stores through Manage Stores and add your own store locations.','asl_locator') ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="asl-counter-sec mt-3 mb-5">
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="counter-box stats-store">
              <div class="stats-a">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M35.9905 17.0016C35.6994 17.0396 35.4007 17.0625 35.0872 17.0625C33.1462 17.0625 31.4143 16.1941 30.2273 14.8535C29.0403 16.1941 27.3084 17.0625 25.3599 17.0625C23.4115 17.0625 21.6796 16.1941 20.4926 14.8535C19.3056 16.1941 17.5811 17.0625 15.6252 17.0625C13.6843 17.0625 11.9523 16.1941 10.7653 14.8535C9.57835 16.1941 7.84641 17.0625 5.89498 17.0625C5.59189 17.0625 5.28656 17.0396 4.99019 17.0016C0.8604 16.4379 -1.09364 11.4715 1.11497 7.90664L5.39481 1.00014C5.77554 0.37926 6.45563 0 7.182 0H33.8181C34.5422 0 35.2216 0.379184 35.6023 1.00014L39.8799 7.90664C42.0971 11.4791 40.1337 16.4379 35.9905 17.0016ZM36.304 19.4162C36.5579 19.3781 36.9237 19.3172 37.2223 19.241V34.125C37.2223 36.8139 35.0797 39 32.4445 39H8.55561C5.91738 39 3.77783 36.8139 3.77783 34.125V19.241C4.06674 19.3172 4.3646 19.3781 4.67142 19.4162H4.68038C5.07306 19.4695 5.48215 19.5 5.89498 19.5C6.82366 19.5 7.7195 19.3553 8.55561 19.0887V29.25H32.4445V19.0963C33.2806 19.3553 34.169 19.5 35.0872 19.5C35.5053 19.5 35.9084 19.4695 36.304 19.4162Z" fill="white"/>
                </svg>
              </div>
              <div class="stats-b">
                <span class="count"><?php echo esc_attr($all_stats['stores']); ?></span>
                <h6><?php echo esc_attr__('Stores','asl_locator') ?></h6>
              </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="counter-box stats-category">
              <div class="stats-a">
                <svg viewBox="0 0 40 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M36.0141 10.8273C39.9979 14.8645 39.9979 21.3543 36.0141 25.3914L27.4828 34.0217C26.7744 34.7377 25.6166 34.7453 24.9006 34.0369C24.177 33.3285 24.177 32.1707 24.8854 31.4471L33.409 22.8244C35.9912 20.2117 35.9912 16.007 33.409 13.3943L23.6818 3.55065C22.9658 2.83235 22.9734 1.6753 23.6971 0.965376C24.4131 0.255454 25.5709 0.26231 26.2107 0.980611L36.0141 10.8273ZM0 15.4815V4.09376C0 2.07444 1.63693 0.437505 3.65625 0.437505H15.0439C16.3389 0.437505 17.5729 0.950903 18.4869 1.86573L31.2838 14.6588C33.1881 16.5631 33.1881 19.6557 31.2838 21.56L21.1225 31.7213C19.2182 33.6256 16.1256 33.6256 14.2213 31.7213L1.42822 18.9244C0.513627 18.0104 0 16.7764 0 15.4815ZM8.53125 6.53126C7.18529 6.53126 6.09375 7.62051 6.09375 8.96875C6.09375 10.317 7.18529 11.4063 8.53125 11.4063C9.87949 11.4063 10.9688 10.317 10.9688 8.96875C10.9688 7.62051 9.87949 6.53126 8.53125 6.53126Z" fill="white"/>
                </svg>
              </div>
              <div class="stats-b">
                <span class="count"><?php echo esc_attr($all_stats['categories']); ?></span>
                <h6><?php echo esc_attr__('Categories','asl_locator') ?></h6>
              </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="counter-box stats-location">
              <div class="stats-a">
                <svg viewBox="0 0 30 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.8197 38.025C8.84355 33.1348 0 21.2824 0 14.625C0 6.54773 6.54773 0 14.625 0C22.6992 0 29.25 6.54773 29.25 14.625C29.25 21.2824 20.3379 33.1348 16.4303 38.025C15.4934 39.1904 13.7566 39.1904 12.8197 38.025ZM14.625 19.5C17.3139 19.5 19.5 17.3139 19.5 14.625C19.5 11.9361 17.3139 9.75 14.625 9.75C11.9361 9.75 9.75 11.9361 9.75 14.625C9.75 17.3139 11.9361 19.5 14.625 19.5Z" fill="white"/>
                </svg>
              </div>
              <div class="stats-b">
                <span class="count"><?php echo esc_attr($all_stats['markers']); ?></span>
                <h6><?php echo esc_attr__('Markers','asl_locator') ?></h6>
              </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="counter-box stats-search">
              <div class="stats-a">
                <svg viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M29.6538 27.3463L24.1288 21.8375C25.9114 19.5665 26.8786 16.7621 26.875 13.875C26.875 11.3038 26.1126 8.79043 24.6841 6.65259C23.2557 4.51475 21.2253 2.84851 18.8499 1.86457C16.4745 0.880633 13.8606 0.623189 11.3388 1.1248C8.81708 1.62641 6.5007 2.86454 4.68262 4.68262C2.86454 6.5007 1.62641 8.81708 1.1248 11.3388C0.623189 13.8606 0.880633 16.4745 1.86457 18.8499C2.84851 21.2253 4.51475 23.2557 6.65259 24.6841C8.79043 26.1126 11.3038 26.875 13.875 26.875C16.7621 26.8786 19.5665 25.9114 21.8375 24.1288L27.3463 29.6538C27.4973 29.8061 27.677 29.927 27.8751 30.0095C28.0731 30.092 28.2855 30.1344 28.5 30.1344C28.7145 30.1344 28.9269 30.092 29.1249 30.0095C29.323 29.927 29.5027 29.8061 29.6538 29.6538C29.8061 29.5027 29.927 29.323 30.0095 29.1249C30.092 28.9269 30.1344 28.7145 30.1344 28.5C30.1344 28.2855 30.092 28.0731 30.0095 27.8751C29.927 27.677 29.8061 27.4973 29.6538 27.3463ZM4.12501 13.875C4.12501 11.9466 4.69683 10.0616 5.76818 8.4582C6.83952 6.85482 8.36226 5.60513 10.1438 4.86718C11.9254 4.12923 13.8858 3.93614 15.7771 4.31235C17.6685 4.68856 19.4057 5.61715 20.7693 6.98071C22.1329 8.34428 23.0615 10.0816 23.4377 11.9729C23.8139 13.8642 23.6208 15.8246 22.8828 17.6062C22.1449 19.3877 20.8952 20.9105 19.2918 21.9818C17.6884 23.0532 15.8034 23.625 13.875 23.625C11.2891 23.625 8.80919 22.5978 6.98071 20.7693C5.15224 18.9408 4.12501 16.4609 4.12501 13.875Z" fill="white"/>
                </svg>
              </div>
              <div class="stats-b">
                <span class="count"><?php echo esc_attr($all_stats['searches']); ?>+</span>
                <h6><?php echo esc_attr__('Searches','asl_locator') ?></h6>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="asl-analytics-sec">
      <div class="asl-top-title">
        <h2><?php echo esc_attr__('Analytics','asl_locator') ?></h2>
        <span class="asl-lines"></span>
      </div>
      <div class="asl-short-decp">
        <p><?php echo esc_attr__('See how your stores are performing in the selected duration.','asl_locator') ?></p>
      </div>
      <ul class="nav nav-tabs">
        <li role="presentation" class="nav-item active"><a class="nav-link" href="#asl-analytics">Analytics</a></li>
        <li role="presentation" class="nav-item"><a class="nav-link" href="#asl-views"><?php echo esc_attr__('Top Views','asl_locator') ?></a></li>
      </ul>

      <div class="tab-content" id="asl-tabs">

        <div class="row asl-form-box">
          <div class="col-md-6">
            <div class="form-group">
              <label for="sl-datetimepicker"><?php echo esc_attr__('Period','asl_locator') ?></label>
              <div class="input-group">
                <input type="text" id="sl-datetimepicker" class="form-control">
                <span class="asl-input-inner-icon">
                  <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <rect width="22" height="22" fill="url(#pattern0)"/>
                  <defs>
                  <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                  <use xlink:href="#image0_31_567" transform="scale(0.015625)"/>
                  </pattern>
                  <image id="image0_31_567" width="64" height="64" xlink:href="data:image/png;base64,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"/>
                  </defs>
                  </svg>
                </span>
              </div>
              <a title="<?php echo esc_attr__('Export Analytics','asl_locator') ?>" id="sl-btn-export-stats" class="asl-Upload-icon">
                <svg width="27" height="30" viewBox="0 0 30 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3333 23.1H16.6667V8.25H21.6667L15 0L8.33333 8.25H13.3333V23.1Z" fill="#3F3F3F"/>
                <path d="M3.33333 33H26.6667C28.505 33 30 31.5199 30 29.7V14.85C30 13.03 28.505 11.55 26.6667 11.55H20V14.85H26.6667V29.7H3.33333V14.85H10V11.55H3.33333C1.495 11.55 0 13.03 0 14.85V29.7C0 31.5199 1.495 33 3.33333 33Z" fill="#3F3F3F"/></svg>
              </a>
            </div>
          </div>
        </div>

        <div class="tab-pane active asl-lock-boxs" role="tabpanel" id="asl-analytics" aria-labelledby="asl-analytics">
          <div class="row">
            <div class="col-md-12">
              <div class="canvas-holder" style="width:100%">
                  <canvas id="asl_search_canvas" style="width:300px;height:400px"></canvas>
              </div>
              <div class="asl-lock-inner">
                <svg width="70" height="100" viewBox="0 0 90 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M82.5 52.5H75V30C75 13.5 61.5 0 45 0C28.5 0 15 13.5 15 30V52.5H7.5C3.75 52.5 0 56.25 0 60V112.5C0 116.25 3.75 120 7.5 120H82.5C86.25 120 90 116.25 90 112.5V60C90 56.25 86.25 52.5 82.5 52.5ZM52.5 105H37.5L40.5 88.5C36.75 87 33.75 82.5 33.75 78.75C33.75 72.75 39 67.5 45 67.5C51 67.5 56.25 72.75 56.25 78.75C56.25 83.25 54 87 49.5 88.5L52.5 105ZM60 52.5H30V30C30 21.75 36.75 15 45 15C53.25 15 60 21.75 60 30V52.5Z" fill="white"/></svg>
                <h6>Upgrade Plugin To View Your Analytics</h6>
                <a href="#"><?php echo esc_attr__('Get Pro Version Now !','asl_locator') ?></a>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane" role="tabpanel" id="asl-views" aria-labelledby="asl-views">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="asl-search-len"><?php echo esc_attr__('Rows','asl_locator') ?></label>
                <div class="input-group">
                  <select id="asl-search-len" class="custom-select">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="0"><?php echo esc_attr__('ALL','asl_locator') ?></option>
                  </select>
                  <span class="asl-input-inner-icon">
                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.7778 4.5H2.22222C1 4.5 0 3.4875 0 2.25C0 1.0125 1 0 2.22222 0H17.7778C19 0 20 1.0125 20 2.25C20 3.4875 19 4.5 17.7778 4.5ZM17.7778 6.75H2.22222C1 6.75 0 7.7625 0 9C0 10.2375 1 11.25 2.22222 11.25H17.7778C19 11.25 20 10.2375 20 9C20 7.7625 19 6.75 17.7778 6.75ZM17.7778 13.5H2.22222C1 13.5 0 14.5125 0 15.75C0 16.9875 1 18 2.22222 18H17.7778C19 18 20 16.9875 20 15.75C20 14.5125 19 13.5 17.7778 13.5Z" fill="#3F3F3F"/>
                    </svg>
                  </span>
                </div>
              </div>
            </div>
          </div>
         <div class="row">
           <div class="col-md-6">
            <div class="asl-table-box">
              <div class="asl-list-header">
                <div class="row">
                  <div class="col-3">
                    <div class="list-items">
                      <?php echo esc_attr__('Store ID','asl_locator') ?>
                    </div>
                  </div>
                  <div class="col-7">
                    <div class="list-items">
                      <?php echo esc_attr__('Most Views Stores','asl_locator') ?>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="list-items">
                      <?php echo esc_attr__('Views','asl_locator') ?>
                    </div>
                  </div>
                </div>
              </div>
              <ul class="list-group" id="asl-stores-views">
              </ul>
            </div>
           </div>
           <div class="col-md-6"> 
            <div class="asl-table-box">
              <div class="asl-list-header">
                <div class="row">
                  <div class="col-9">
                    <div class="list-items">
                      <?php echo esc_attr__('Most Search Locations','asl_locator') ?>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="list-items">
                      <?php echo esc_attr__('Views','asl_locator') ?>
                    </div>
                  </div>
                </div>
              </div>
              <ul class="list-group" id="asl-searches-views">
              </ul>
            </div>
           </div>
         </div>

        </div>
      </div> 
    </div>
    <div class="asl-feature-sec sl-complx">
      <div class="asl-top-title">
        <h2><?php echo esc_attr__('Agile Store Locator Features','asl_locator') ?></h2>
        <span class="asl-lines"></span>
        <!-- <div class="asl-right-box">
          <span class="asl-plus">+</span>
          <span class="asl-text">Add New Tag</span>
        </div> -->
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="asl-feature-box asl-lock-boxs">
            <h3><?php echo esc_attr__('Store Locator','asl_locator') ?></h3>
            <p><?php echo esc_attr__('You can leverage store locators in multiple themes, layouts, color schemes, and language that you can easily personalize.','asl_locator') ?></p>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/feature_img1.png" class="img-fluid">
            <div class="asl-lock-inner">
              <svg width="70" height="100" viewBox="0 0 90 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M82.5 52.5H75V30C75 13.5 61.5 0 45 0C28.5 0 15 13.5 15 30V52.5H7.5C3.75 52.5 0 56.25 0 60V112.5C0 116.25 3.75 120 7.5 120H82.5C86.25 120 90 116.25 90 112.5V60C90 56.25 86.25 52.5 82.5 52.5ZM52.5 105H37.5L40.5 88.5C36.75 87 33.75 82.5 33.75 78.75C33.75 72.75 39 67.5 45 67.5C51 67.5 56.25 72.75 56.25 78.75C56.25 83.25 54 87 49.5 88.5L52.5 105ZM60 52.5H30V30C30 21.75 36.75 15 45 15C53.25 15 60 21.75 60 30V52.5Z" fill="white"/></svg>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="asl-feature-box asl-lock-boxs">
            <h3><?php echo esc_attr__('Search Widget','asl_locator') ?></h3>
            <p><?php echo esc_attr__('Our plugin includes a search widget that can be added anywhere on your website for address searching.','asl_locator') ?></p>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/tmpl-search.png" class="img-fluid">
            <div class="asl-lock-inner">
              <svg width="70" height="100" viewBox="0 0 90 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M82.5 52.5H75V30C75 13.5 61.5 0 45 0C28.5 0 15 13.5 15 30V52.5H7.5C3.75 52.5 0 56.25 0 60V112.5C0 116.25 3.75 120 7.5 120H82.5C86.25 120 90 116.25 90 112.5V60C90 56.25 86.25 52.5 82.5 52.5ZM52.5 105H37.5L40.5 88.5C36.75 87 33.75 82.5 33.75 78.75C33.75 72.75 39 67.5 45 67.5C51 67.5 56.25 72.75 56.25 78.75C56.25 83.25 54 87 49.5 88.5L52.5 105ZM60 52.5H30V30C30 21.75 36.75 15 45 15C53.25 15 60 21.75 60 30V52.5Z" fill="white"/></svg>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="asl-feature-box asl-lock-boxs">
            <h3><?php echo esc_attr__('Store Registration Form','asl_locator') ?></h3>
            <p><?php echo esc_attr__('Our Plugin lets you register multiple stores on the registration page to display on the store locator page.','asl_locator') ?></p>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/tmpl-form.png" class="img-fluid">
            <div class="asl-lock-inner">
              <svg width="70" height="100" viewBox="0 0 90 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M82.5 52.5H75V30C75 13.5 61.5 0 45 0C28.5 0 15 13.5 15 30V52.5H7.5C3.75 52.5 0 56.25 0 60V112.5C0 116.25 3.75 120 7.5 120H82.5C86.25 120 90 116.25 90 112.5V60C90 56.25 86.25 52.5 82.5 52.5ZM52.5 105H37.5L40.5 88.5C36.75 87 33.75 82.5 33.75 78.75C33.75 72.75 39 67.5 45 67.5C51 67.5 56.25 72.75 56.25 78.75C56.25 83.25 54 87 49.5 88.5L52.5 105ZM60 52.5H30V30C30 21.75 36.75 15 45 15C53.25 15 60 21.75 60 30V52.5Z" fill="white"/></svg>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="asl-feature-box asl-lock-boxs">
            <h3><?php echo esc_attr__('Lead Contact Form','asl_locator') ?></h3>
            <p><?php echo esc_attr__('Create a lead form page that can be used to get the contact information of potential customers easily.','asl_locator') ?></p>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/tmpl-lead.png" class="img-fluid">
            <div class="asl-lock-inner">
              <svg width="70" height="100" viewBox="0 0 90 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M82.5 52.5H75V30C75 13.5 61.5 0 45 0C28.5 0 15 13.5 15 30V52.5H7.5C3.75 52.5 0 56.25 0 60V112.5C0 116.25 3.75 120 7.5 120H82.5C86.25 120 90 116.25 90 112.5V60C90 56.25 86.25 52.5 82.5 52.5ZM52.5 105H37.5L40.5 88.5C36.75 87 33.75 82.5 33.75 78.75C33.75 72.75 39 67.5 45 67.5C51 67.5 56.25 72.75 56.25 78.75C56.25 83.25 54 87 49.5 88.5L52.5 105ZM60 52.5H30V30C30 21.75 36.75 15 45 15C53.25 15 60 21.75 60 30V52.5Z" fill="white"/></svg>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="asl-benefits-sec mt-3 d-none">
      <div class="asl-top-title">
        <span class="asl-lines"></span>
        <h2><?php echo esc_attr__('Want To Upgrade Plugin?','asl_locator') ?></h2>
        <span class="asl-lines"></span>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="asl-benefits-ctn-box">
            <div class="position-relative" style="z-index: 1;">
              <h3><?php echo esc_attr__('Enjoy Attractive Store Locator Layouts With Unlimited Features.','asl_locator') ?></h3>
              <ul>
                <li><?php echo esc_attr__('Display Where to Buy Locations to Millions','asl_locator') ?></li>
                <li><?php echo esc_attr__('Use 5 Beautiful Store Locator Themes','asl_locator') ?></li>
                <li><?php echo esc_attr__('Try Multiple Layouts with Listing and Accordion','asl_locator') ?></li>
                <li><?php echo esc_attr__('Multiple Color Schemes For Templates','asl_locator') ?></li>
                <li><?php echo esc_attr__('Support Multiple Languages & Localization','asl_locator') ?></li>
                <li><?php echo esc_attr__('Easily Personalize Your Store Locator','asl_locator') ?></li>
              </ul>
              <a href="https://codecanyon.net/item/agile-store-locator-google-maps-for-wordpress/16973546"><?php echo esc_attr__('Get Pro Version Now !','asl_locator') ?></a>
            </div>
            <img src="<?php echo ASL_URL_PATH ?>admin/images/new/vector_img.png" class="asl-object-img">
          </div>
        </div>
      </div>
    </div>
    <div class="asl-basic-info-sec mt-3 sl-complx">
      <div class="asl-top-title">
        <h2><?php echo esc_attr__('Basic System Info','asl_locator') ?></h2>
        <span class="asl-lines"></span>
      </div>
      <div class="row">
        <div class="col-md-4">
          <ul class="asl-info-list">
            <li>
              <span><?php echo esc_attr__('Support status','asl_locator'); ?> <a class="text-primary mr-3" id="asl-support-status-btn"><small><?php if($support_status !== null) { echo '('.esc_attr__('Refresh','asl_locator').')'; } ?></small></a></span>
              <?php echo $support_text;  ?>
            </li>
            <li>
              <span><?php echo esc_attr__('Plugin version','asl_locator'); ?></span>
              <?php echo ASL_CVERSION; ?>
            </li>
            <li>
              <span><?php echo esc_attr__('Google API Key','asl_locator'); ?><i title="<?php echo esc_attr__('Green sign mean the Google API key is added in the plugin, doesn\'t mean you have configured it properly in Google Cloud Console.','asl_locator'); ?>" class="ml-2"><?php echo $quest_svg; ?></i></span>
              <?php echo (!$all_configs['api_key'])? $cross_svg: $right_svg; ?>
            </li>
            <li>
              <span><?php echo esc_attr__('JSON Cache','asl_locator'); ?><i title="<?php echo esc_attr__('Locator JSON files are cached to serve speedy response.','asl_locator'); ?>" class="ml-2"><?php echo $quest_svg; ?></i></span>
              <?php 

                $cache_files = \AgileStoreLocator\Helper::getCacheFileName();
                echo ($cache_files)? $cache_files: $cross_svg;
              ?>
            </li>
            <li>
              <span><?php echo esc_attr__('CSV Upload Permissions','asl_locator'); ?></span>
              <?php echo (!is_writable(ASL_PLUGIN_PATH.'public/import'))? $cross_svg: $right_svg;  ?>
            </li>
            <li>
              <span><?php echo esc_attr__('Extension Installed','asl_locator'); ?></span>
              <?php echo (\AgileStoreLocator\Helper::extensionStats())? $right_svg: $cross_svg;  ?>
            </li>
            <li>
              <span><?php echo esc_attr__('Memory Limit','asl_locator'); ?></span>
              <?php echo ini_get('memory_limit');  ?>
            </li>
          </ul>
        </div>
        <div class="col-md-8">
          <div class="asl-right-box">
            <div class="asl-head">
              <h4><?php echo esc_attr__('Backup Template','asl_locator') ?></h4>
              <a id="sl-btn-tmpl-remove"><?php echo esc_attr__('Delete Template','asl_locator') ?></a>
            </div>
            <div class="row">
              <?php 

              $tmpl_backups = \AgileStoreLocator\Helper::getBackupTemplates(); 

              foreach ($tmpl_backups as $tmpl): ?>
              <div class="col-md-3 col-sm-3 col-6 mb-4">
                <div class="asl-backup-tmpl-box">
                  <img src="<?php echo ASL_URL_PATH ?>admin/images/new/<?php echo esc_attr__($tmpl['image'], 'asl_locator') ?>" class="img-fluid">
                  <h5><?php echo esc_attr__($tmpl['title'], 'asl_locator') ?></h5>
                </div>
              </div>
              <?php endforeach; ?>
              <div class="col-md-3 col-sm-3 col-6">
                <a id="sl-btn-tmpl-backup" class="asl-backup-tmpl-box">
                  <div class="asl-solid-box">
                    <img src="<?php echo ASL_URL_PATH ?>admin/images/new/solid.png" class="img-fluid">
                    <span class="asl-Upload-btn">+</span>
                  </div>
                  <h5><?php echo esc_attr__('Backup Template','asl_locator') ?></h5>
                </a>
              </div>
            </div>
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
</script>