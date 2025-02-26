<?php

namespace AgileStoreLocator\Vendors\WPBakery;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Agile Store Locator WPBakery Addon
 */
class Addon {
    
    /**
     * Agile Store Locator WPBakery Addon constructor.
     */
    public function __construct() {

        $this->widgets_registered();

    }

    /**
     * Register widget
     */
    public function widgets_registered() {

         $vc_search_widget  = new \AgileStoreLocator\Vendors\WPBakery\SearchWidget();
         $vc_store_grid     = new \AgileStoreLocator\Vendors\WPBakery\StoreGrid();
         $vc_store_detail   = new \AgileStoreLocator\Vendors\WPBakery\StoreDetail();
         $vc_store_locator  = new \AgileStoreLocator\Vendors\WPBakery\StoreLocator();
    }
}
