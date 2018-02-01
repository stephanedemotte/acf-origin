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
      'admin.php?page=origin',
    ];
    foreach($menus as $menu) { remove_menu_page($menu); }
    ?>
    <style>.wp-menu-separator{display: none} .toplevel_page_origin{display: none}</style>
    <?php
  }

}

new origin_acf_options();

endif;

?>
