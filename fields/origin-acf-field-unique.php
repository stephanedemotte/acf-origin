<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('origin_acf_field_unique') ) :

class origin_acf_field_unique extends acf_field {

	function __construct( $settings ) {
		$this->name = 'unique';
		$this->label = __('Origin/Unique', 'origin');
		$this->category = 'basic';
		$this->settings = $settings;

    parent::__construct();
	}

	function render_field( $field ) {
    $class = '';
    if($field['origin_unique_is_title'])
      $class = 'origin_unique_is_title';

		?>
      <input
        type="text"
        class="<?php echo $class ?>"
        name="<?php echo esc_attr($field['name']) ?>"
        value="<?php echo esc_attr($field['value']) ?>" />
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
	}

  function input_admin_footer() {
    ?>
      <style>
        input[readonly] {
          opacity: .7;
          background-color: #eee !important;
        }
      </style>
      <script type="text/javascript">
				(function($) {
					function origin_unique_is_title() {
						var search = {
							'term-php': '#name',
							'edit-tags-php': '#tag-name',
							'post-new-php': '#title',
							'post-php': '#title',
						}

						if(!search[adminpage]) return

						var uniques = document.querySelectorAll('.origin_unique_is_title')
						var title = document.querySelector(search[adminpage])

						if(!uniques || !title) return

						title.readOnly = true

						var updateTitle = function() {
							// because of terms ajax update
							var names = []
							var uniques = document.querySelectorAll('.origin_unique_is_title')
							var title = document.querySelector(search[adminpage])
							uniques.forEach(function(unique) {
								if(names.indexOf(unique.value) === -1)
									names.push(unique.value)
								title.value = names.join(' / ')
							})
						}

						uniques.forEach(function(unique) {
							$(document).on('input', unique, updateTitle)
						})
					}
					origin_unique_is_title()
				})(jQuery);
      </script>
    <?php
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

?>