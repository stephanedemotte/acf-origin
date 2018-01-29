(function($){

	function initialize_field_slug( $field ) {
		var $input = $field.find('#acf_fields-' + $field.data('id') + '-origin_slug_select')

		var requiredRow = $field.find('.acf-field.acf-field-true-false.acf-field-setting-required')
		// .hide()

		var setup_select = function() {
			var $inputs = $('.acf-field-object-unique, .acf-field-object-text')

			$.each($inputs, function(key, i) {
				var data = {
					id: $(i).data('id'),
					key: $(i).data('key'),
					type: $(i).data('type'),
				}

				if($input.find("option[value='" + data.key + "']").length)
					return

				var field_name = $(i).find('.field-name')
				var name = $(field_name).val() + ' (' + data.type  + ')'
				var newOption = new Option(name, data.key, false, false)
				$input.append(newOption).trigger('change')
			})
		}

		$input.on('select2:open', setup_select)
		setup_select()
	}

	acf.add_action('ready append change_field_type', function( $el ){
		acf.get_fields({ type : 'object-slug'}, $el).each(function(){
			initialize_field_slug( $(this) )
		})
	})


})(jQuery);
