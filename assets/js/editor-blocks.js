/**
 * Gutenberg Block Editor Registration for Magic Hat Dynamic Blocks
 *
 * Registers client-side block types for xophz-magic-hat/header,
 * xophz-magic-hat/hero, and xophz-magic-hat/footer with ServerSideRender.
 *
 * @package Xophz_Magic_Hat
 */

(function(wp) {
	'use strict';

	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var el = wp.element.createElement;
	var ServerSideRender = wp.serverSideRender;
	var __ = wp.i18n ? wp.i18n.__ : function(s) { return s; };

	var blockConfigs = [
		{
			name: 'xophz-magic-hat/header',
			title: __( 'Magic Hat Header', 'xophz-magic-hat' ),
			description: __( 'Dynamic responsive header with navigation, branding, and mobile drawer.', 'xophz-magic-hat' ),
			icon: 'heading',
			category: 'theme'
		},
		{
			name: 'xophz-magic-hat/hero',
			title: __( 'Magic Hat Hero', 'xophz-magic-hat' ),
			description: __( 'Dynamic hero banner with layout controls, typography, and call-to-action buttons.', 'xophz-magic-hat' ),
			icon: 'cover-image',
			category: 'theme'
		},
		{
			name: 'xophz-magic-hat/footer',
			title: __( 'Magic Hat Footer', 'xophz-magic-hat' ),
			description: __( 'Dynamic multi-column footer with navigation menus and copyright.', 'xophz-magic-hat' ),
			icon: 'table-footer',
			category: 'theme'
		}
	];

	blockConfigs.forEach(function(cfg) {
		if ( wp.blocks.getBlockType( cfg.name ) ) {
			return;
		}

		wp.blocks.registerBlockType( cfg.name, {
			title: cfg.title,
			description: cfg.description,
			icon: cfg.icon,
			category: cfg.category,
			supports: {
				html: false,
				inserter: true,
				reusable: false
			},
			edit: function(props) {
				if ( ServerSideRender ) {
					return el(
						'div',
						{ className: 'mh-editor-block-wrap mh-editor-block-' + cfg.name.replace('/', '-') },
						el( ServerSideRender, {
							block: cfg.name,
							attributes: props.attributes || {}
						} )
					);
				}

				return el(
					'div',
					{
						className: 'mh-editor-block-placeholder',
						style: {
							padding: '24px',
							textAlign: 'center',
							border: '1px dashed #94a3b8',
							borderRadius: '6px',
							background: '#f8fafc',
							color: '#334155',
							fontWeight: 600
						}
					},
					cfg.title
				);
			},
			save: function() {
				return null;
			}
		} );
	});
})(window.wp);
