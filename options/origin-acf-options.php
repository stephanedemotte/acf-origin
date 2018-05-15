<?php

if( ! class_exists('origin_acf_options') ) :

class origin_acf_options {

  function __construct() {
    require_once 'origin-acf-fields.php';
    acf_add_options_page([ 'page_title' => 'Origin', 'post_id' => 'origin',  'menu_slug' => 'origin']);

    add_action( 'init', function() {
      $post_types = array_diff(get_post_types(['_builtin' => false]), ['acf-field-group', 'acf-field']);
      $post_types['option'] = 'option';

      // add taxonomy in filter
      $terms = get_taxonomies();
      foreach($terms as $term):
        $post_types[$term] = $term;
      endforeach;

      foreach($post_types as $post_type):
        add_filter('acf/rest_api/' . $post_type . '/get_fields', function($data, $request) {
          if(isset($request['id']) && post_password_required($request['id'])):
            $password = isset($_GET['password']) && ! empty( $_GET['password']) ? wp_unslash($_GET['password']) : NULL;
            if($password !== get_post_field('post_password', $request['id']))
              return NULL;
          endif;
          return $data;
        }, 10, 2);
        $this->check_acf_rest_api($post_type);
      endforeach;

      $this->check_clean_admin_ui();
    });
	}

  function check_acf_rest_api($post_type) {
    if(!get_field('split_language_keys', 'origin'))
      return;

		add_filter('acf/rest_api/' . $post_type . '/get_fields', function($data, $request) {
			$new = [];
			foreach(array_dot($data) as $k => $v):
				array_set($new, $k, $v);
			endforeach;
			return $new;
		}, 10, 2);
	}

  function check_clean_admin_ui() {
    $users = get_field('clean_admin_ui', 'origin');
    $current_user = wp_get_current_user();

    if(!$users)
      return;

    $clean = false;

    foreach($users as $user):
      if($user['ID'] == $current_user->ID)
        $clean = true;
    endforeach;

    if($clean):
      add_action('admin_menu', [$this, 'clean_admin_ui'], 9999);
      add_filter('acf/settings/show_admin', '__return_false');
    endif;
  }

  function clean_admin_ui() {
    $menus = [
      'themes.php',
      'options-general.php',
      'tools.php',
      'users.php',
      'plugins.php',
      'edit-comments.php',
      'edit.php?post_type=page',
      'edit.php',
      'update-core.php'
    ];
    foreach($menus as $menu) { remove_menu_page($menu); }

    $submenus = [
      ['index.php', 'update-core.php'],
    ];
    foreach($submenus as $submenu) { remove_submenu_page($submenu[0], $submenu[1]); }

    define('UPDRAFTPLUS_ADMINBAR_DISABLE', true);

		add_action('wp_dashboard_setup', function() {
			global $wp_meta_boxes;
				unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
				unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
				unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
				unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
				unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
				unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
				unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
				unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
				unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		}, 999);

    add_action( 'wp_before_admin_bar_render', function() {
      global $wp_admin_bar;
      $wp_admin_bar->remove_menu('comments');
      $wp_admin_bar->remove_menu('new-content');
    });

    add_action('admin_head', function() {
	echo '<style>.wp-menu-separator{display: none} .toplevel_page_origin{display: none}</style>';
      	echo '<style type="text/css">#post-preview { display: none; }</style>';
    });

  }

}

new origin_acf_options();

endif;
