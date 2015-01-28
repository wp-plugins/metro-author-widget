<?php
/*
Plugin Name: Metro Author Widget
Plugin URI: https://wordpress.org/plugins/metro-author-widget/
Description: Metro Author Widget for sidebar and footer. Inspired by Windows Phone live tile.
Version: 1.0
Author: Zayed Baloch
Author URI: http://www.radlabs.biz/
License: GPL2
*/

defined('ABSPATH') or die("No script kiddies please!");
define( 'RLMAC_VERSION',   '1.0' );
define( 'RLMAC_URL', plugins_url( '', __FILE__ ) );
define( 'RADLABS_TEXTDOMAIN',  'rl_metro_author' );

class RadLabs_Metro_Author_Card {
    function __construct() {
        add_action( 'wp_loaded', array( $this, 'init') );
      }

  public function init() {

  }


}

//Load Widget
require_once('inc/widget-authorcard.php');


new RadLabs_Metro_Author_Card();