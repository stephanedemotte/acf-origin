<?php

if( ! class_exists('origin_acf_options') ) :

class origin_acf_options {

  function __construct() {
    require_once 'origin-acf-options-fields.php';
    acf_add_options_page([ 'page_title' => 'Origin', 'post_id' => 'origin',  'menu_slug' => 'origin']);
    $this->check_clean_admin_ui();
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

    ?>
    <style>.wp-menu-separator{display: none} .toplevel_page_origin{display: none}</style>
    <?php

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
				// bbpress
				//  unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
				//    // yoast seo
				//      unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
				//        // gravity forms
				//          unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
		}, 999);

    add_action( 'wp_before_admin_bar_render', function() {
      global $wp_admin_bar;
      $wp_admin_bar->remove_menu('comments');
      $wp_admin_bar->remove_menu('new-content');
    });
  }

}

new origin_acf_options();

endif;

?>

