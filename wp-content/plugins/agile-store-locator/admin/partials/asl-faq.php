<?php


$faq_basic = array(
  array(
    'q'   => 'How to add the store locator on the page?',
    'ans' => 'To add the store locator on the page write down the shortcode of the plugin <b>[ASL_STORELOCATOR]</b>, after that click publish to save the page.'
  ),
  array(
    'q'   => 'How to include the Search Widget that redirect to the Store Locator page?',
    'ans' => 'To add the separate <b>Search Widget</b> over any page, please add the [ASL_SEARCH redirect="https://your-site.com/store-locator"] shortcode, please check the <a target="_blank" href="https://agilestorelocator.com/wiki/address-search-widget/">documentation</a> to see more options.'
  ),
  array(
    'q'   => 'How to change the default map location?',
    'ans' => 'To change the location of the Google Map, add the <b>Default Coordinates</b> value of your desired location in the ASL Settings, you can get your values by right-click over Google Maps.'
  ),
  array(
    'q'   => 'Why the newly added stores are not appearing?',
    'ans' => 'Make sure to refresh the Fast Cache if it is enabled in the ASL Settings, and click over Validate Coordinates on the Manage Stores page to see all stores have valid coordinates.'
  ),
  array(
    'q'   => 'Why I am not getting auto-updates?',
    'ans' => 'Premium version is hosting on Envato market, so follow this <a target="_blank" href="https://agilestorelocator.com/wiki/automatic-updates/">article guide</a> to recieve updates.'
  ),
  array(
    'q'   => 'Why th Google Map is showing "development" watermark?',
    'ans' => 'The "Development" watermark appears over the Google Maps when the Google API isn\'t configured properly or the required libraries are not enabled, please follow this <a target="_blank" href="https://agilestorelocator.com/blog/enable-google-maps-api-agile-store-locator-plugin/">guide article</a>.'
  )
);

$faq_links = array(
  array(
    'title' => 'How to translate the static content of the plugin?',
    'link'  => 'https://agilestorelocator.com/wiki/language-translation-store-locator/'
  ),
  array(
    'title' => 'How can we avoid the template to be overwrite by updates?',
    'link'  => 'https://agilestorelocator.com/wiki/customize-template-without-modifying-core-plugin/'
  ),
  array(
    'title' => 'How can we pre-load filter values by the URL?',
    'link'  => 'https://agilestorelocator.com/wiki/load-parameter-with-query-string/'
  ),
  array(
    'title' => 'How to create multiple Store Locator on different pages?',
    'link'  => 'https://agilestorelocator.com/wiki/create-multiple-store-locator-different-wordpress-pages/'
  ),
  array(
    'title' => 'How can we sort by the categories?',
    'link'  => 'https://agilestorelocator.com/wiki/sort-store-attribute/'
  ),
  array(
    'title' => 'How can we change the address format?',
    'link'  => 'https://agilestorelocator.com/wiki/change-address-format/'
  ),
  array(
    'title' => 'How to change the user location marker?',
    'link'  => 'https://agilestorelocator.com/wiki/change-user-location-marker-image/'
  ),
  array(
    'title' => 'How to add custom tag in the template?',
    'link'  => 'https://agilestorelocator.com/wiki/custom-script-method-store-locator/'
  ),
  array(
    'title' => 'Why Store Locator doesn’t appear at all?',
    'link'  => 'https://agilestorelocator.com/wiki/store-locator-doesnot-appear/'
  ),
  array(
    'title' => 'How to change the cluster color or size?',
    'link'  => 'https://agilestorelocator.com/wiki/store-locator-clusters/'
  ),
  array(
    'title' => 'How to change the font sizing?',
    'link'  => 'https://agilestorelocator.com/wiki/how-to-adjust-the-font-size/'
  ),
  array(
    'title' => 'How to change the "Website" text?',
    'link'  => 'https://agilestorelocator.com/wiki/language-translation-store-locator/'
  )
);

?>

<div class="row asl-setting-cont">
        <div class="col-md-12">
          <div class="asl-seting-faq p-0 mb-4 mt-0">
             <h3 class="card-title"><?php echo esc_attr__('FAQ & Help','asl_locator') ?></h3>
             <div class="asl-seting-body">
                <div class="alert border-0 alert-primary d-flex py-3 mb-4" role="alert">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                   </svg>
                   <p class="m-0"><?php echo esc_attr__('Create a support ticket by emailing us at ','asl_locator') ?> <a target="_blank" href="mailto:support@agilelogix.com" class="text-decoration-underline">support@agilelogix.com</a>, <?php echo esc_attr__('we will get back to you as soon as possible, please include ("Store Locator" in the Subject) to avoid the spam list.','asl_locator') ?></p>
                </div>
                <!-- Accordian -->
                <div class="faqs-accordion" id="accordionfaqs">
                  <?php foreach ($faq_basic as $key => $faq): ?>
                   <div class="cards p-0">
                      <div class="card-header py-3 px-2">
                         <h2 class="mb-0 d-flex align-items-center">
                            <span>0<?php echo $key + 1?></span>
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse-<?php echo $key ?>" aria-expanded="true" aria-controls="collapseOne">
                            <?php echo $faq['q'] ?>
                            </button>
                         </h2>
                      </div>
                      <div id="collapse-<?php echo $key ?>" class="collapse" aria-labelledby="store-locator" data-parent="#accordionExample">
                         <div class="card-body">
                            <p><?php echo $faq['ans'] ?></p>
                         </div>
                      </div>
                   </div>
                  <?php endforeach; ?>
                </div>
                <!-- Accordian -->
                <!-- Videos Slider -->
                <div class="asl-video-sec">
                   <div class="top-title mt-5 mb-3 d-flex align-items-center justify-content-between">
                      <b>FAQ Videos</b>
                      <a target="_blank" href="https://www.youtube.com/channel/UCtr44_UG4DoxcEAhzWepYJw/videos" class="d-flex align-items-center">See All <span class="dashicons dashicons-arrow-right-alt"></span></a>
                   </div>
                   <div class="row">
                      <div class="col-md-6">
                         <a target="_blank" href="https://www.youtube.com/watch?v=CC0WMJcGpFM&amp;feature=emb_title" class="placeholder video">
                          <h4>How to add Google Maps API Keys into Store Locator WordPress Plugin</h4>
                        </a>
                      </div>
                      <div class="col-md-6">
                         <a target="_blank" href="https://www.youtube.com/watch?v=WpPUMxlNX4M&amp;feature=emb_title" class="placeholder video">
                          <h4>How to Customize the Store Locator WordPress Plugin</h4>
                        </a>
                      </div>
                   </div>
                </div>
                <!-- Videos Slider -->
                <!-- FAQ'S Links -->
                <div class="asl-faq-link mt-5">
                   <div class="top-title text-center">
                      <b>FAQ Links</b>
                   </div>
                   <div class="row faq-slider">
                      <?php foreach ($faq_links as $key => $faq): ?>
                      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                         <a target="_blank" class="asl-faq-inner-box card" href="<?php echo $faq['link'] ?>">
                            <div class="row align-items-center">
                               <div class="col-9">
                                  <span class="link-title"><?php echo $faq['title'] ?></span>
                               </div>
                               <div class="col-3">
                                  <span class="dashicons dashicons-admin-links"></span>
                               </div>
                            </div>
                         </a>
                      </div>
                      <?php endforeach; ?>
                   </div>
                </div>
                <!-- FAQ'S Links -->
                <div class="row">
                   <div class="col-md-12">
                      <div class="alert alert-light" role="alert">
                         <p class="text-muted">If you have any problem with the plugin or suggestion, please email us at <a  href="mailto:support@agilelogix.com">support@agilelogix.com</a> We will respond as soon as possible to resolve your problem, please include ("Store Locator" in the Subject) to avoid the spam list.</p>
                         <div class="d-flex align-items-center">
                            <a target="_blank" href="https://codecanyon.net/item/agile-store-locator-google-maps-for-wordpress/reviews/16973546">If you like our Plugin, please rate us 5 stars.</a>
                            <ul class="reviews-stars d-flex p-0 ml-2 mb-0">
                               <li class="mb-0"><span class="dashicons dashicons-star-filled"></span></li>
                               <li class="mb-0"><span class="dashicons dashicons-star-filled"></span></li>
                               <li class="mb-0"><span class="dashicons dashicons-star-filled"></span></li>
                               <li class="mb-0"><span class="dashicons dashicons-star-filled"></span></li>
                               <li class="mb-0"><span class="dashicons dashicons-star-filled"></span></li>
                            </ul>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="row">
                  <div class="col-md-12 justify-content-md-center text-center">
                    <a href="https://agilestorelocator.com/multi-stores-inventory-for-woocommerce/" target="_blank" class="figure">
                      <img src="<?php echo ASL_URL_PATH ?>admin/images/asl-wc-addon.png" alt="Agile Stores Addons for WooCommerce" class="figure-img img-fluid rounded">
                      <figcaption class="figure-caption text-center"><?php echo esc_attr__('Extension for WooCommerce','asl_locator') ?></figcaption>
                    </a>
                  </div>
                </div>
             </div>
          </div>
        </div>
      </div>