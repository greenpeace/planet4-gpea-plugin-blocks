/* global wp */

function MetaBlock(shortcode_tag) { // eslint-disable-line no-unused-vars

  var me = this;
  me.shortcode_tag = shortcode_tag;
  me.add_btn_class  = me.shortcode_tag + '-add-element';
  me.remove_btn_class  = me.shortcode_tag + '-remove-element';


  /**
   * Called when a new metablock is rendered in the backend.
   * @param shortcode Shortcake backbone model.
   */
  me.render_new = function () {
    var element_map = me.get_element_map();
    var buttons = me.make_buttons(element_map);
    var $shortcode_div = $('.shortcode-ui-edit-' + me.shortcode_tag);
    $shortcode_div.append(buttons);
    var element_count = element_map.element_count;
    this.hide_all_elements(element_count);
    this.add_click_event_handlers(element_count);
  };

  /**
   * Called when en existing metablock is rendered in the backend.
   * @param shortcode Shortcake backbone model.
   */
  me.render_edit = function () {

    var element_map = me.get_element_map();
    var element_types = element_map.element_types;
    var element_count = element_map.element_count;

    var buttons = me.make_buttons(element_map);
    var $shortcode_div = $('.shortcode-ui-edit-' + me.shortcode_tag);
    $shortcode_div.append(buttons);

    var inactive_elements = [];
    var all_elements = me.get_element_map_array(element_count, element_types);
    all_elements.forEach(function (element_id) {
        var input_values = $('.field-block')
            .filter($('div[class$=\'_' + element_id + '\']'))
            .children()
            .filter($('input, textarea, select'))
            .map(function (idx, elem) {
              return $(elem).val();
            }).get().join('');

        if ('' === input_values) {
          inactive_elements.push(element_id);
        }
    });

    var active_elements = all_elements.filter(function(el) {
      return !inactive_elements.includes(el);
    });

    var max_row = active_elements.length === 0 ? 0 :
        Math.max.apply(null, active_elements.map(function(el) {
          return parseInt(el.match(/\d+$/g)[0]);
        }));

    $('.' + me.add_btn_class).parent().data('row', max_row);
    max_row !== 0 && $('.' + me.remove_btn_class).removeAttr('disabled');

    inactive_elements.map(function(element_id) {
      $('.field-block').filter($('div[class$=\'_' + element_id + '\']')).hide();
    });

    if (all_elements.length - inactive_elements.length === element_count) {
      $('.' + me.add_btn_class).attr('disabled', 'disabled');
    }

    this.toggle_images(element_count);
    this.add_click_event_handlers(element_count);
  };

  /**
   * Add click event handlers for add/remove buttons in metablock.
   */
  me.add_click_event_handlers = function (element_count) {
    var elements = this;

    // Add click event handlers for the elements.
    $('.' + me.add_btn_class).on('click', function (event) {
      event.preventDefault();
      var $element = $(event.currentTarget);
      var row = $element.parent().data('row');
      var element_type = $element.data('element-type');

      // Special control, if block type added is post, and if input element has special data attribute data-input-transform == js-select2-enable then dynamically transform into select2
      if (element_type == 'post') {        
        var selectName = 'select[name="post_post_'+ (row+1) +'"]';
        if ( $(selectName).data('input-transform') === 'js-select2-enable' ) {
          $(selectName).select2({
            placeholder: 'Select post',
          });
        }        
      }

      if (row <= element_count) {
        // elements.show_element_type_selector();
        elements.show_element(++row, element_type, element_count);
        $element.parent().data('row', row);
        $('.' + me.remove_btn_class).removeAttr('disabled');
        if (row === element_count) {
          $('.' + me.add_btn_class).attr('disabled', 'disabled');
        }
      }
    });

    $('.' + me.remove_btn_class).on('click', function (event) {
      event.preventDefault();
      var $element = $(event.currentTarget);
      var row = $element.parent().data('row');

      if (row >= 0) {
        elements.hide_element(row--);
        $element.parent().data('row', row);
        $('.' + me.add_btn_class).removeAttr('disabled');
        if (row === 0) {
          $element.attr('disabled', 'disabled');
        }
      }
    });

    $('input[name=elements_block_style]').off('click').on('click', function() {
      me.toggle_images(element_count);
    });
  };

  /**
   * Hide a metablock row and reset the values of it's fields.
   *
   * @param row
   */
  me.hide_element = function (row) {
    var $element = $('.field-block').filter($('div[class$=\'_' + row + '\']'));
    // Clear all text, textarea fields for this row/element.
    $element.
      children().
      filter($('input, textarea')).each(function (index, element) {
        $(element).val('').trigger('input');
      });
    // Clear image attachment if set in this row/element.
    $element.
      find($('.attachment-previews .remove')).each(function (index, element) {
        $(element).click();
      });
    // Hide element's fields.
    $element.hide(300);
  };

  /**
   * Hide all metablock rows.
   *
   * @param row
   */
  me.hide_all_elements = function (element_count) {
    me.get_element_map_array(element_count).forEach(function (row) {
      $( '.field-block' ).filter( $( 'div[class$=\'_'+row+'\']' ) ).hide();
    });
  };

  /**
   * Show a metablock elements selector and scroll to bottom.
   *
   * @param row
   */
  me.show_element = function (row, element_type, element_count) {
    $('.field-block').filter($('div[class$=\'_' + element_type + '_' +row + '\']')).show(300, function () {
      me.toggle_images(element_count);
      $('.media-frame-content').animate({
        scrollTop: $('.shortcode-ui-content').prop('scrollHeight'),
      }, 300);
    });
  };

  /**
   * Show/hide images inputs depending on element block style.
   */
  me.toggle_images = function(element_count) {
    me.get_element_map_array(element_count).forEach(function(row) {
      var element_is_visible = $('.field-block').filter($('div[class$=\'title_' + row + '\']')).is(':visible');
      var block_style_allows_images = 'no_image' != $('input[name=elements_block_style]:checked').val();
      $('.shortcode-ui-attribute-attachment_'+ row).toggle(element_is_visible && block_style_allows_images);
    });
  };

  /**
   * Called when a new metablock is rendered in the backend.
   * @param shortcode Shortcake backbone model.
   */
  me.get_element_map = function() {

    var element_types = $('[data-element-type]')
      .map( function() {
        return $(this).data('element-type');
      }).toArray();
    element_types = element_types.filter(function(item, pos) {
      return element_types.indexOf(item) == pos;
    });

    var element_names = $('[data-element-name]')
      .map( function() {
        return $(this).data('element-name');
      }).toArray();
    element_names = element_names.filter(function(item, pos) {
      return element_names.indexOf(item) == pos;
    });

    var element_count = $('[data-element-number]')
      .map( function() {
        return $(this).data('element-number');
      }).toArray();
    element_count = Math.max.apply(null, element_count); 

    return {
      element_types: element_types,
      element_names: element_names,
      element_count: element_count
    };

  };

  me.get_element_map_array = function(element_count, element_types) {
    var arr = Array.apply(null, Array(element_count));
    arr = arr.map(function (_, i) { return i + 1; });
    if(element_types) {
      arr = arr
        .map(function(x) {
          return element_types
            .map(function(el) {
              return el + '_' + x;
            }, []);})
        .reduce(function(x, y) {
          return x.concat(y);
        });
    }
    return arr;
  };

  /**
   * Called when a new metablock is rendered in the backend.
   * @param shortcode Shortcake backbone model.
   */
  me.make_buttons = function(element_map) {
    var btn_HTML = '<div data-row="0">';
    var types = element_map.element_types;
    var names = element_map.element_names;
    if( names.length !== types.length ) { names = types; }
    types.map( function(type, idx) {
      btn_HTML += (
        '<button class="button button-small ' + me.add_btn_class + '" data-element-type="' + type + '">Add ' + names[idx] + '</button>'
      );
    });
    btn_HTML += '<button class="button button-small ' + me.remove_btn_class + '" disabled="disabled">Remove Element</button></div>';
    return btn_HTML;
  };

}


jQuery(document).ready(function() {

  var allowed_shortcodes = [
    'shortcake_metablock',
    'shortcake_mixed_content_row',
    'shortcake_repeater',
    'shortcake_milestones',
    'shortcake_slideshow',
    'shortcake_world_slideshow',
    'shortcake_donation_dollar_handles',
    'shortcake_link_list',
  ];

  // Attach hooks when rendering a new metablock.
  wp.shortcake.hooks.addAction('shortcode-ui.render_new', function (shortcode) {
    var shortcode_tag = shortcode.get('shortcode_tag');
    if(allowed_shortcodes.includes(shortcode_tag)) {
      var mb = new MetaBlock(shortcode_tag);
      mb.render_new();
    }
  });

  // Trigger hooks when shortcode renders an existing metablock.
  wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function (shortcode) {
    var shortcode_tag = shortcode.get('shortcode_tag');
    if(allowed_shortcodes.includes(shortcode_tag)) {
      var mb = new MetaBlock(shortcode_tag);
      mb.render_edit();
    }
  });

});
