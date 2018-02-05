(function($){

  acf.add_action('ready', function( $el ){
    if(acf.field_group)
      var acf_settings_slug = acf.field_group.field_object.extend({
        type: 'slug',
        actions: {
          'render_settings': 'render',
        },
        render: function () {
          var $choices = $('.acf-field-object-text, .acf-field-object-unique')
          var $input = this.setting('origin_slug_select').find('select')

          $input.off('select2:open')
          $input.on('select2:open', this.render.bind(this))

          $.each($choices, function(k, c) {
            var data = acf.get_data($(c))
            var name = $('#acf_fields-' + data.id +'-name').val()
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
    acf.fields.slug = acf.field.extend({
      type: 'slug',
      $input: null,
      ref: null,
      actions: {
        'ready': 'render',
        'append': 'render',
      },
      focus: function() {
        this.$input = this.$field.find('input')
        this.ref = this.$input.data('ref')
        this.$input
          .prop('readonly', true)
          .css({ opacity: .7, backgroundColor: '#eee' })

        this.$ref = $('input[name="acf[' + this.ref + ']"]')
        this.$visibility = $('input[name="visibility"]')

        this.pattern = this.$input.data('preview')
        if(this.pattern != '') {
          this.preview_link = this.pattern.replace("%value%", this.$input.val())
          var password = $('#post_password').val() != '' ? '&password=' + $('#post_password').val() : ''
          this.$preview_link = $('<a href="" target="_blank"></a>')
            .addClass('button')
            .css({ 'marginTop': '15px', 'display': 'inline-block'})
            .text(this.preview_link.substr(0, 30))
            .attr('href', window.location.origin + this.preview_link + '?id=' + $('#post_ID').val() + password)

          $('#preview-action').append(this.$preview_link).css({ 'text-align': 'left' })
        }
      },
      render: function () {
        var $input = this.$input
				var $preview_link = this.$preview_link

        var removeLink = function() {
          if($preview_link)
            $preview_link.hide()
        }

        var onType = function() {
          var value = acf.str_sanitize(this.value)
          value = value.split('_').join('-')
          $input.val(value)
          removeLink()
        }

        this.$visibility.off('change', removeLink)
        this.$visibility.on('change', removeLink)

        this.$ref.off('input', onType)
        this.$ref.on('input', onType)
      }
    })
  })

})(jQuery);
