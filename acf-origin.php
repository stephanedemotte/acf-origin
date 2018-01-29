<?php

/*
Plugin Name: ACF Origin
Plugin URI: PLUGIN_URL
Description: Origin ACF FIELDS
Version: 1.0.0
Author: Stephane Demotte
Author URI: NULL
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('origin_acf_plugin') ) :

  class origin_acf_plugin {
    var $settings;

    function __construct() {
      $this->settings = array(
        'version'	=> '1.0.0',
        'url'		=> plugin_dir_url( __FILE__ ),
        'path'		=> plugin_dir_path( __FILE__ )
      );
      add_action('acf/include_field_types', 	array($this, 'include_field_types'));
    }

    function include_field_types( $version = false ) {
      include_once('fields/origin-acf-field-unique.php');
      include_once('fields/origin-acf-field-slug.php');
    }
  }

  new origin_acf_plugin();
endif;

?>