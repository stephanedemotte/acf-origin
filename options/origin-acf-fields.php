<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5a733fff991cf',
	'title' => 'Options',
	'fields' => array(
		array(
			'key' => 'field_5bbf92f771f4b',
			'label' => 'Clean Admin UI',
			'name' => 'clean_admin_ui_role',
			'type' => 'role_selector',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_value' => 'name',
			'allowed_roles' => '',
			'field_type' => 'multi_select',
		),
		array(
			'key' => 'field_5a75b9d6337d3',
			'label' => '[ACF-REST-API] Split language keys',
			'name' => 'split_language_keys',
			'type' => 'true_false',
			'instructions' => 'Change la structure des datas recupérés par acf-rest-api.<br>
				Nomer les champs dans ce style : fr.title, fr.meta.title, en.title, en.meta.title.<br>
				[acf][fr.title] => [acf][fr][title]<br>
				[acf][fr.meta.title] => [acf][fr][meta][title]',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'origin',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'origin_acf_options_clean_taxonomy' => 0,
));

endif;
