(function($){
  var origin_acf_field_uniques = {}

  acf.add_action('prepare', function( $el ){
    acf.fields.unique = acf.field.extend({
      type: 'unique',
      $input: null,
      key: null,
      search: {
        'term-php': '#name',
        'edit-tags-php': '#tag-name',
        'post-new-php': '#title',
        'post-php': '#title',
      },
      actions: {
        'ready': 'render',
        'append': 'render',
      },
      events: {
        'input input': 'render',
      },
      focus: function() {
        this.$input = this.$field.find('.origin_unique_is_title')
        this.$title = $(this.search[adminpage])
        this.key = this.$field.data('key')
        if(this.$input.length)
          this.$title
            .prop('readonly', true)
            .css({ opacity: .7, backgroundColor: '#eee' })

        this.$inputSlug = this.$field.find('.origin_unique_is_slug')
      },
      render: function () {
        // slug
        if(this.$inputSlug.length > 0) {
          const inputSlug = this.$inputSlug
          var onType = function() {
            var value = acf.str_sanitize(this.value)
            if(value.slice(-1) == '_')
              value = value.slice(0, -1)
            value = value.split('_').join('-')
            inputSlug.val(value)
          }

          this.$inputSlug.off('input', onType)
          this.$inputSlug.on('input', onType)
        }

        // replace title
        if(!this.$input.length || !this.$title.length) return
        origin_acf_field_uniques[this.key] = this.$input.val()
        var unique_array = []
        $.each(origin_acf_field_uniques, function(k, o) {
          if(unique_array.indexOf(o) == -1)
            unique_array.push(o)
        })
        this.$title.val(unique_array.join(' / '))
      }
    })
  })


})(jQuery);

