<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('origin_acf_field_slug') ) :

class origin_acf_field_slug extends acf_field {

	function __construct( $settings ) {
		$this->name = 'slug';
		$this->label = 'Origin/Slug';
		$this->category = 'basic';
		$this->settings = $settings;

    parent::__construct();
	}

	function render_field( $field ) {
		?>
      <input
        type="text"
        readonly
        name="<?php echo esc_attr($field['name']) ?>"
        value="<?php echo esc_attr($field['value']) ?>" />
		<?php
	}

  function render_field_settings( $field ) {
		acf_render_field_setting( $field, array(
			'label'			=> 'Fields',
			'instructions'	=> 'Select text field to slug',
			'type'			=> 'select',
			'name'			=> 'origin_slug_select',
			'multiple' 		=> 0,
			'allow_null' 	=> 0,
      'required' => 1,
      'choices'   => $this->get_slug_setting_choices( $field['origin_slug_select'] ),
			'ui'			=> 1,
			'placeholder'	=> '',
		));
	}

	function get_slug_setting_choices( $value ) {
		$field = acf_get_field($value);
		return [$value => $field['name'] . ' (' . $field['type'] . ')'];
	}

  function update_value( $value, $post_id, $field  ) {
		return sanitize_title($_POST['acf'][$field['origin_slug_select']]);
  }

  function input_admin_enqueue_scripts() {
		wp_register_script('acf-origin', "{$this->settings['url']}assets/js/input.js", array( 'acf-input'), $this->settings['version']);
    wp_enqueue_script('acf-origin');
	}

}

new origin_acf_field_slug( $this->settings );

endif;

?>