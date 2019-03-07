<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('origin_acf_field_unique') ) :

class origin_acf_field_unique extends acf_field {

	function __construct( $settings ) {
		$this->name = 'unique';
		$this->label = 'Origin/Unique';
		$this->category = 'basic';
		$this->settings = $settings;

    parent::__construct();
	}

	function render_field( $field ) {
    $class = '';
    if($field['origin_unique_is_title'])
      $class .= ' origin_unique_is_title';

    if($field['origin_unique_is_slug'])
      $class .= ' origin_unique_is_slug';

		?>

    <?php if($field['origin_unique_is_textarea']): ?>
      <textarea
        type="text"
        class="<?php echo $class ?>"
        name="<?php echo esc_attr($field['name']) ?>"
      ><?php echo esc_attr($field['value']) ?></textarea>
    <?php else: ?>
      <input
        type="text"
        class="<?php echo $class ?>"
        name="<?php echo esc_attr($field['name']) ?>"
        value="<?php echo esc_attr($field['value']) ?>" />
    <?php endif; ?>

		<?php
	}

  function render_field_settings( $field ) {
		acf_render_field_setting( $field, array(
			'label'			=> __('Replace Title ?','origin'),
			'instructions'	=> __('Use me as title page','origin'),
			'type'			=> 'true_false',
      'ui' => 1,
			'name'			=> 'origin_unique_is_title',
		));

		acf_render_field_setting( $field, array(
			'label'			=> 'Slugify',
			'instructions'	=> 'slugify me',
			'type'			=> 'true_false',
      'ui' => 1,
			'name'			=> 'origin_unique_is_slug',
		));

		acf_render_field_setting( $field, array(
			'label'			=> 'Use textarea',
			'instructions'	=> '',
			'type'			=> 'true_false',
      'ui' => 1,
			'name'			=> 'origin_unique_is_textarea',
		));
	}

  function input_admin_enqueue_scripts() {
		wp_register_script('acf-origin-unique', "{$this->settings['url']}assets/js/origin-acf-field-unique.js", array( 'acf-input'), $this->settings['version']);
    wp_enqueue_script('acf-origin-unique');
	}

  function input_admin_head() {
    ?>
      <style>
        .acf-field-object-unique .acf-field-setting-required { display: none }
      </style>
    <?php
  }

  function load_field( $field ) {
    $field['required'] = 1;
		return $field;
	}

	function validate_value( $valid, $value, $field, $input ){
    if(!$valid)
      return $valid;

    $tax = $_POST['taxonomy'];
    $existing = false;

    if($tax) {
      $existing = get_terms([
        'taxonomy' => $tax,
        'meta_key' => $field['name'],
        'meta_value' => $value,
        'hide_empty' => false,
        'number' => 1,
        'exclude' => [$_POST['tag_ID']]
      ]);
    } else {
      $existing = get_posts([
        'numberposts'	=> 1,
        'post_type'		=> $_POST['post_type'],
        'meta_key'		=> $field['name'],
        'meta_value'	=> $value,
        'exclude' => [$_POST['post_ID']]
      ]);
    }

    if($existing)
      $valid = 'Value already exist';

		return $valid;
	}

}

new origin_acf_field_unique( $this->settings );

endif;
