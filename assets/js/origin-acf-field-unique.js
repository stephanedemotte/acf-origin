(function($){
  var origin_acf_field_uniques = {}

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
    },
    render: function () {
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

})(jQuery);
