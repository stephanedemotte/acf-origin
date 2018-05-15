<?php

if( ! class_exists('origin_acf_taxonomy') ) :

class origin_acf_taxonomy {

  function __construct() {
    add_action('admin_enqueue_scripts',	array($this, 'admin_enqueue_scripts'));
		add_action('acf/render_field_group_settings', [$this, 'origin_add_settings']);
	}

	function origin_add_settings($field_group) {
		acf_render_field_wrap([
			'label'     => 'Origin/Clean Taxonomy',
			'instructions' => 'Hide Description and Slug on Taxonomy page',
			'type'			=> 'true_false',
			'name'      => 'origin_acf_options_clean_taxonomy',
			'prefix'    => 'acf_field_group',
			'value'     => $field_group['origin_acf_options_clean_taxonomy'],
			'ui'			=> 1,
		]);
	}

  function validate_page() {
		global $pagenow;

		if( $pagenow === 'edit-tags.php' || $pagenow === 'term.php' )
			return true;

		return false;
	}

  function admin_enqueue_scripts() {
		if(!$this->validate_page())
			return;

    $screen = get_current_screen();
		$taxonomy = $screen->taxonomy;

		$field_groups = acf_get_field_groups(['taxonomy' => $taxonomy]);

		$clean_up = false;

		foreach($field_groups as $field_group):
			if($field_group['origin_acf_options_clean_taxonomy'])
				$clean_up = true;
		endforeach;

		if($clean_up)
			echo "<style>.inline-edit-col {display:none;} .term-slug-wrap {display:none;} .term-description-wrap {display:none;}</style>";
	}

}

new origin_acf_taxonomy();

endif;
