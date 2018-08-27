<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('origin_acf_field_tn') ) :

class origin_acf_field_tn extends acf_field {

	function __construct( $settings ) {
		$this->name = 'tn';
		$this->label = 'Origin/Tab Name';
		$this->category = 'basic';
		$this->settings = $settings;
    parent::__construct();
	}

	function render_field( $field ) {
		?>
      <input
        type="text"
        data-ref="<?php echo esc_attr($field['origin_tn_select']) ?>"
        name="<?php echo esc_attr($field['name']) ?>"
        value="<?php echo esc_attr($field['value']) ?>" />
		<?php
	}

  function render_field_settings( $field ) {
		acf_render_field_setting($field, [
			'label'			=> 'Fields',
			'instructions'	=> 'Select a tab',
			'type'			=> 'select',
			'name'			=> 'origin_tn_select',
			'multiple' 		=> 0,
			'allow_null' 	=> 0,
      'required' => 1,
      'choices'   => $this->get_tn_setting_choices( $field['origin_tn_select'] ),
			'ui'			=> 1,
			'placeholder'	=> '',
		]);
	}

	function get_tn_setting_choices( $value ) {
		$field = acf_get_field($value);
		return [$value => $field['label'] . ' (' . $field['type'] . ')'];
	}

  function input_admin_enqueue_scripts() {
		wp_register_script('acf-origin-tn', "{$this->settings['url']}assets/js/origin-acf-field-tab-name.js", array( 'acf-input'), $this->settings['version']);
    wp_enqueue_script('acf-origin-tn');
	}
}

new origin_acf_field_tn( $this->settings );

endif;
