jQuery(function ($) {
	'use strict';

	if ('undefined' !== typeof( wp.shortcake )) {

		shortcodeUIFieldData.p4_select = {
			encode: false,
			template: "shortcode-ui-field-p4-select",
			view: "editAttributeHeading"
		};
		shortcodeUIFieldData.p4_checkbox = {
			encode: false,
			template: "shortcode-ui-field-p4-checkbox",
			view: "editAttributeHeading"
		};
		shortcodeUIFieldData.p4_radio = {
			encode: false,
			template: "shortcode-ui-field-p4-radio",
			view: "editAttributeHeading"
		};

		wp.shortcake.hooks.addAction('shortcode-ui.render_edit', function (shortcodeModel) {
			$(".shortcode-ui-attribute-heading2").parent().before('<p></p>');
		});

		wp.shortcake.hooks.addAction('shortcode-ui.render_new', function (shortcodeModel) {
			$(".shortcode-ui-attribute-heading2").parent().before('<p></p>');
		});
	}
});