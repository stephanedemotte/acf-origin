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
        data-ref="<?php echo esc_attr($field['origin_slug_select']) ?>"
        name="<?php echo esc_attr($field['name']) ?>"
        value="<?php echo esc_attr($field['value']) ?>" />
		<?php
	}

  function render_field_settings( $field ) {
    acf_enable_filter('local');
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
    acf_disable_filter('local');
	}

  function input_admin_head() {
    ?>
      <style>
        .acf-field-object-slug .acf-field-setting-required { display: none }
      </style>
    <?php
  }

  function load_field( $field ) {
    $field['required'] = 0;
		return $field;
	}

	function get_slug_setting_choices( $value ) {
		$field = acf_get_field($value);
		return [$value => $field['name'] . ' (' . $field['type'] . ')'];
	}

  function input_admin_enqueue_scripts() {
		wp_register_script('acf-origin-slug', "{$this->settings['url']}assets/js/origin-acf-field-slug.js", array( 'acf-input'), $this->settings['version']);
    wp_enqueue_script('acf-origin-slug');
	}

}

new origin_acf_field_slug( $this->settings );

endif;

?>