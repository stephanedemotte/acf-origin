<?php
class acf_field_role_selector extends acf_field {

	function __construct() {
		$this->name     = 'role_selector';
		$this->label    = 'Origin/Role-selector';
		$this->category = 'basic';
		$this->defaults = array(
			'return_value' => 'name',
			'field_type'   => 'checkbox',
			'allowed_roles'   => '',
		);

		parent::__construct();
	}

	function render_field_settings( $field ) {
		acf_render_field_setting( $field, array(
			'label'			=> __('Return Format','acf-role-selector-field'),
			'instructions'	=> __('Specify the returned value type','acf-role-selector-field'),
			'type'			=> 'radio',
			'name'			=> 'return_value',
			'layout'  =>  'horizontal',
			'choices' =>  array(
				'name'   => __( 'Role Name', 'acf-role-selector-field' ),
				'object' => __( 'Role Object', 'acf-role-selector-field' ),
			)
		));

		global $wp_roles;
		acf_render_field_setting( $field, array(
			'label'			=> __('Allowed Roles','acf-role-selector-field'),
			'type'			=> 'select',
			'name'			=> 'allowed_roles',
			'multiple'      => true,
			'instructions'   => __( 'To allow all roles, select none or all of the options to the right', 'acf-role-selector-field' ),
			'choices' => $wp_roles->role_names
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Field Type','acf-role-selector-field'),
			'type'			=> 'select',
			'name'			=> 'field_type',
			'choices' => array(
				__( 'Multiple Values', 'acf-role-selector-field' ) => array(
					'checkbox' => __( 'Checkbox', 'acf-role-selector-field' ),
					'multi_select' => __( 'Multi Select', 'acf-role-selector-field' )
				),
				__( 'Single Value', 'acf-role-selector-field' ) => array(
					'radio' => __( 'Radio Buttons', 'acf-role-selector-field' ),
					'select' => __( 'Select', 'acf-role-selector-field' )
				)
			)
		));
	}

	function render_field( $field ) {
		global $wp_roles;
		$roles = $wp_roles->roles;

		foreach( $roles as $role => $data ) {
			if( is_array( $field['allowed_roles'] ) && !in_array( $role, $field['allowed_roles'] ) ) {
				unset( $roles[$role] );
			}
		}

		$roles = apply_filters( 'acfrsf/allowed_roles', $roles, $field );

		// Select and multiselect fields
	    if( $field['field_type'] == 'select' || $field['field_type'] == 'multi_select' ) :
	    	$multiple = ( $field['field_type'] == 'multi_select' ) ? 'multiple="multiple"' : '';
		?>

			<select name='<?php echo $field['name'] ?>[]' <?php echo $multiple ?>>
				<?php
					foreach( $roles as $role => $data ) :
					if ( 'object' === $field['return_value'] ) {
						$selected = ( !empty( $field['value'] ) && in_array( $role, wp_list_pluck( $field['value'], 'name' ) ) ) ? 'selected="selected"' : '';
					} else {
						$selected = ( !empty( $field['value'] ) && in_array( $role, $field['value'] ) ) ? 'selected="selected"' : '';
					}
				?>
					<option <?php echo $selected ?> value='<?php echo $role ?>'><?php echo $data['name'] ?></option>
				<?php endforeach; ?>
			</select>
		<?php
		// checkbox and radio button fields
		else :
			echo '<ul class="acf-'.$field['field_type'].'-list '.$field['field_type'].' vertical">';
			foreach( $roles as $role => $data ) :
				if ( 'object' === $field['return_value'] ) {
					$checked = ( !empty( $field['value'] ) && in_array( $role, wp_list_pluck( $field['value'], 'name' ) ) ) ? 'checked="checked"' : '';
				} else {
					$checked = ( !empty( $field['value'] ) && in_array( $role, $field['value'] ) ) ? 'checked="checked"' : '';
				}
		?>
		<li><label><input <?php echo $checked ?> type="<?php echo $field['field_type'] ?>" name="<?php echo $field['name'] ?>[]" value="<?php echo $role ?>"><?php echo $data['name'] ?></label></li>
		<?php
			endforeach;

			echo '<input type="hidden" name="' .  $field['name'] . '[]" value="" />';

			echo '</ul>';
		endif;
	}

	function format_value($value, $post_id, $field) {
		if( $field['return_value'] == 'object' && is_array( $value ) ) {
			foreach( $value as $key => $role ) {
				$value[$key] = get_role( $role->name );
			}
		}
		return $value;
	}

	function load_value($value, $post_id, $field) {
		if( $field['return_value'] == 'object' && is_array( $value ) ) {
			foreach( $value as $key => $name ) {
				$value[$key] = get_role( $name );
			}
		}
		return $value;
	}

}

new acf_field_role_selector();
