/* eslint no-console: ["error", { allow: ["log", "warn", "error"] }] */
/* global wp */

jQuery(document).ready(function() {

    function redefineSelectData(shortcode) {
        function addOldData($select, $list) {
            $select.find('option[data-select2-id]').each(function(k, v) {
                const $item = $list.find('.select2-selection__choice').eq(k);
                if(!$item || $item.data('data')) {
                    return true;
                }
                $item.data('data', {
                    id: $(this).attr('value')
                });
            });
        }
        $('.shortcode-ui-field-post-select select').each(function() {
            const $select = $(this);
            const $list = $select.data('select2')?.$container;
            if(!$list) {
                return true;
            }
            addOldData($select, $list);
            const observer = new MutationObserver(function() {
                addOldData($select, $list);
            });
            observer.observe($list.get(0), { childList: true, subtree: true });
        });
    }

    wp.shortcake.hooks.addAction('shortcode-ui.render_new', function (shortcode) {
        redefineSelectData(shortcode);
    });
    wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function (shortcode) {
        redefineSelectData(shortcode);
    });

});
