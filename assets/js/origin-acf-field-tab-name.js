(function($){

  acf.add_action('ready', function( $el ){
    if(acf.field_group)
      var acf_settings_tn = acf.field_group.field_object.extend({
        type: 'tn',
        actions: {
          'render_settings': 'render',
        },
        render: function () {
          var $choices = $('.acf-field-object-tab')
          var $input = this.setting('origin_tn_select').find('select')

          $input.off('select2:open')
          $input.on('select2:open', this.render.bind(this))

          $.each($choices, function(k, c) {
            var data = acf.get_data($(c))
            var name = $('#acf_fields-' + data.id +'-label').val()
            var text = name + ' (' + data.type + ')'

            if($input.find("option[value='" + data.key + "']").length)
              return

            var newOption = new Option(text, data.key, false, false)
            $input.append(newOption).trigger('change')
          })
        }
      })
  })

  acf.add_action('prepare', function( $el ){
    acf.fields.tn = acf.field.extend({
      type: 'tn',
      $input: null,
      ref: null,
      actions: {
        'ready': 'render',
        'append': 'render',
      },
      focus: function() {
        this.$input = this.$field.find('input')
        this.ref = this.$input.data('ref')
        this.$ref = $('a[data-key="' + this.ref + '"]')
      },
      render: function () {
        var $ref = this.$ref

        var onType = function() {
          $ref.html(this.value)
        }

        if(this.$input.val() != ''){
          $ref.html(this.$input.val())
        }

        this.$input.off('input', onType)
        this.$input.on('input', onType)
      }
    })
  })

})(jQuery);

