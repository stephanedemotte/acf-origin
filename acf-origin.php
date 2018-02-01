<?php

/*
Plugin Name: Origin
Plugin URI: PLUGIN_URL
Description: Origin
Version: 1.0.0
Author: Stephane Demotte
Author URI: NULL
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('origin_plugin') ) :

  class origin_plugin {
    var $settings;

    function __construct() {
      $this->settings = array(
        'version'	=> '1.0.0',
        'url'		=> plugin_dir_url( __FILE__ ),
        'path'		=> plugin_dir_path( __FILE__ )
      );

      add_action('acf/init', 	[$this, 'acf_init']);
			add_action('acf/include_field_types', [$this, 'acf_include_field_types']);
    }

    function acf_init() {
      include_once('options/origin-acf-options.php');
      include_once('options/origin-acf-taxonomy.php');
    }

    function acf_include_field_types( $version = false ) {
      include_once('fields/origin-acf-field-unique.php');
      include_once('fields/origin-acf-field-slug.php');
    }
  }

  new origin_plugin();
endif;

?>