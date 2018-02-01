<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5a733fff991cf',
	'title' => 'Options',
	'fields' => array(
		array(
			'key' => 'field_5a734005957ef',
			'label' => 'Clean Admin UI',
			'name' => 'clean_admin_ui',
			'type' => 'user',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'role' => '',
			'allow_null' => 0,
			'multiple' => 1,
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
?>
