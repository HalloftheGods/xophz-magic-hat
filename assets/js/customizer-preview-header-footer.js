/**
 * Magic Hat Customizer In-Canvas Interactive Shortcuts (One Page Express Pattern)
 *
 * Injects floating action handles directly on `#mw-header`, `#mh-front-page-hero`,
 * and `#mw-footer` inside the Customizer preview iframe so users can directly jump
 * to Header, Hero, and Footer settings with a single click.
 * Also enables direct in-canvas clicking, inline text editing, and media replacement.
 *
 * @package Xophz_Magic_Hat
 */

(function($) {
	'use strict';

	if ( ! window.parent || ! window.parent.wp || ! window.parent.wp.customize ) {
		return;
	}

	var parentApi = window.parent.wp.customize;

	var handleCss =
		'.mh-preview-badge-wrap { position: absolute; z-index: 99995; pointer-events: auto; display: none; }' +
		'.mh-preview-badge-wrap.active { display: flex; }' +
		'.mh-preview-badge { display: inline-flex; align-items: center; gap: 4px; background: #0f172a; border: 1px solid rgba(255,255,255,0.15); border-radius: 9999px; padding: 3px 6px; box-shadow: 0 4px 14px rgba(0,0,0,0.3); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }' +
		'.mh-preview-btn { background: transparent; color: #f8fafc; border: none; border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.15s ease; }' +
		'.mh-preview-btn:hover { background: #2563eb; color: #ffffff; }' +
		'.mh-preview-btn .dashicons { font-size: 14px; width: 14px; height: 14px; }' +
		'#mw-header:hover { outline: 1px dashed #2563eb; outline-offset: -1px; }' +
		'#mh-front-page-hero:hover { outline: 1px dashed #2563eb; outline-offset: -1px; }' +
		'#mw-footer:hover { outline: 1px dashed #2563eb; outline-offset: -1px; }' +
		'[data-mh-focus] { position: relative; transition: outline 0.15s, box-shadow 0.15s; }' +
		'[data-mh-focus]:hover { outline: 2px dashed #2563eb !important; outline-offset: 4px; cursor: pointer; }' +
		'[data-mh-focus]:focus { outline: 2px solid #2563eb !important; outline-offset: 4px; background: rgba(37,99,235,0.05); }' +
		'[data-mh-image]:hover { outline: 2px dashed #2563eb !important; outline-offset: 4px; cursor: pointer; filter: brightness(0.95); }';

	$('head').append('<style>' + handleCss + '</style>');

	var $headerHandle = $(
		'<div id="mh-header-preview-handle" class="mh-preview-badge-wrap">' +
			'<div class="mh-preview-badge">' +
				'<button type="button" class="mh-preview-btn" data-action="header-options">' +
					'<span class="dashicons dashicons-admin-customizer"></span> Header Settings' +
				'</button>' +
				'<button type="button" class="mh-preview-btn" data-action="header-layout">' +
					'<span class="dashicons dashicons-layout"></span> Cycle Layout' +
				'</button>' +
				'<button type="button" class="mh-preview-btn" data-action="header-menus">' +
					'<span class="dashicons dashicons-menu"></span> Menus' +
				'</button>' +
			'</div>' +
		'</div>'
	);

	var $heroHandle = $(
		'<div id="mh-hero-preview-handle" class="mh-preview-badge-wrap">' +
			'<div class="mh-preview-badge">' +
				'<button type="button" class="mh-preview-btn" data-action="hero-options">' +
					'<span class="dashicons dashicons-star-filled"></span> Hero Settings' +
				'</button>' +
				'<button type="button" class="mh-preview-btn" data-action="hero-layout">' +
					'<span class="dashicons dashicons-layout"></span> Cycle Layout' +
				'</button>' +
				'<button type="button" class="mh-preview-btn" data-action="hero-width">' +
					'<span class="dashicons dashicons-editor-expand"></span> Toggle Width' +
				'</button>' +
			'</div>' +
		'</div>'
	);

	var $footerHandle = $(
		'<div id="mh-footer-preview-handle" class="mh-preview-badge-wrap">' +
			'<div class="mh-preview-badge">' +
				'<button type="button" class="mh-preview-btn" data-action="footer-options">' +
					'<span class="dashicons dashicons-admin-customizer"></span> Footer Settings' +
				'</button>' +
				'<button type="button" class="mh-preview-btn" data-action="footer-layout">' +
					'<span class="dashicons dashicons-layout"></span> Cycle Layout' +
				'</button>' +
				'<button type="button" class="mh-preview-btn" data-action="footer-menus">' +
					'<span class="dashicons dashicons-menu"></span> Menus' +
				'</button>' +
			'</div>' +
		'</div>'
	);

	$('body').append($headerHandle).append($heroHandle).append($footerHandle);

	function positionHandles() {
		var $header = $('#mw-header');
		if ($header.length) {
			var hOffset = $header.offset();
			$headerHandle.css({
				top: hOffset.top + 8,
				left: hOffset.left + 16
			});
		}

		var $hero = $('#mh-front-page-hero');
		if ($hero.length && $hero.is(':visible')) {
			var heroOffset = $hero.offset();
			$heroHandle.css({
				top: heroOffset.top + 16,
				left: heroOffset.left + 24
			});
		}

		var $footer = $('#mw-footer');
		if ($footer.length) {
			var fOffset = $footer.offset();
			$footerHandle.css({
				top: fOffset.top + 16,
				left: fOffset.left + 16
			});
		}
	}

	// Show/hide on hover
	var headerTimeout, heroTimeout, footerTimeout;

	$(document).on('mouseenter', '#mw-header, #mh-header-preview-handle', function() {
		clearTimeout(headerTimeout);
		positionHandles();
		$headerHandle.addClass('active');
	});

	$(document).on('mouseleave', '#mw-header, #mh-header-preview-handle', function() {
		headerTimeout = setTimeout(function() {
			$headerHandle.removeClass('active');
		}, 180);
	});

	$(document).on('mouseenter', '#mh-front-page-hero, #mh-hero-preview-handle', function() {
		clearTimeout(heroTimeout);
		positionHandles();
		$heroHandle.addClass('active');
	});

	$(document).on('mouseleave', '#mh-front-page-hero, #mh-hero-preview-handle', function() {
		heroTimeout = setTimeout(function() {
			$heroHandle.removeClass('active');
		}, 180);
	});

	$(document).on('mouseenter', '#mw-footer, #mh-footer-preview-handle', function() {
		clearTimeout(footerTimeout);
		positionHandles();
		$footerHandle.addClass('active');
	});

	$(document).on('mouseleave', '#mw-footer, #mh-footer-preview-handle', function() {
		footerTimeout = setTimeout(function() {
			$footerHandle.removeClass('active');
		}, 180);
	});

	// Handle button clicks to navigate parent Customizer sidebar
	$(document).on('click', '.mh-preview-btn', function(e) {
		e.preventDefault();
		e.stopPropagation();

		var action = $(this).data('action');

		if (action === 'header-options') {
			if (parentApi.section && parentApi.section('magic_hat_header')) {
				parentApi.section('magic_hat_header').focus();
			}
		} else if (action === 'header-layout') {
			var headerLayouts = ['standard', 'centered', 'split', 'minimal'];
			var currentHeaderLayout = parentApi('mh_header_layout') ? parentApi('mh_header_layout').get() : 'standard';
			var nextHeaderIdx = (headerLayouts.indexOf(currentHeaderLayout) + 1) % headerLayouts.length;
			if (parentApi('mh_header_layout')) {
				parentApi('mh_header_layout').set(headerLayouts[nextHeaderIdx]);
			}
		} else if (action === 'hero-options') {
			if (parentApi.section && parentApi.section('mh_front_page_hero')) {
				parentApi.section('mh_front_page_hero').focus();
			}
		} else if (action === 'hero-layout') {
			var layouts = ['split', 'centered', 'editorial', 'app', 'video'];
			var currentLayout = parentApi('mh_hero_layout') ? parentApi('mh_hero_layout').get() : 'split';
			var nextIdx = (layouts.indexOf(currentLayout) + 1) % layouts.length;
			if (parentApi('mh_hero_layout')) {
				parentApi('mh_hero_layout').set(layouts[nextIdx]);
			}
		} else if (action === 'hero-width') {
			var currentWidth = parentApi('mh_hero_width') ? parentApi('mh_hero_width').get() : 'boxed';
			var nextWidth = (currentWidth === 'full') ? 'boxed' : 'full';
			if (parentApi('mh_hero_width')) {
				parentApi('mh_hero_width').set(nextWidth);
			}
		} else if (action === 'footer-options') {
			if (parentApi.section && parentApi.section('magic_hat_footer')) {
				parentApi.section('magic_hat_footer').focus();
			}
		} else if (action === 'footer-layout') {
			var footerLayouts = ['columns_4', 'columns_3', 'minimal_centered', 'split'];
			var currentFooterLayout = parentApi('mh_footer_layout') ? parentApi('mh_footer_layout').get() : 'columns_4';
			var nextFooterIdx = (footerLayouts.indexOf(currentFooterLayout) + 1) % footerLayouts.length;
			if (parentApi('mh_footer_layout')) {
				parentApi('mh_footer_layout').set(footerLayouts[nextFooterIdx]);
			}
		} else if (action === 'header-menus' || action === 'footer-menus') {
			if (parentApi.panel && parentApi.panel('nav_menus')) {
				parentApi.panel('nav_menus').focus();
			} else if (parentApi.section && parentApi.section('menu_locations')) {
				parentApi.section('menu_locations').focus();
			}
		}
	});

	// ── Direct In-Canvas Element Clicking & Inline Editing ─────
	function initHeroEditables() {
		$('[data-mh-focus]').each(function() {
			var $el = $(this);
			// Enable inline editing for text elements
			if ( ! $el.is('img') && ! $el.is('input') ) {
				$el.attr('contenteditable', 'true').attr('spellcheck', 'false');
			}
		});
	}

	// Click to focus Customizer control
	$(document).on('click', '[data-mh-focus]', function(e) {
		var settingId = $(this).attr('data-mh-focus');
		if ( settingId && parentApi.control && parentApi.control(settingId) ) {
			parentApi.control(settingId).focus();
		} else if ( parentApi.section && parentApi.section('mh_front_page_hero') ) {
			parentApi.section('mh_front_page_hero').focus();
		}
	});

	// Sync inline typing back to Customizer setting
	$(document).on('input blur', '[data-mh-focus]', function() {
		var $el = $(this);
		if ( $el.is('img') ) return;
		var settingId = $el.attr('data-mh-focus');
		var newText = $el.text().trim();
		if ( settingId && parentApi(settingId) && parentApi(settingId).get() !== newText ) {
			parentApi(settingId).set(newText);
		}
	});

	// Hero Image replacement via WP Media Library
	var heroMediaFrame;
	$(document).on('click', '[data-mh-image]', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $img = $(this);
		var settingId = $img.attr('data-mh-image');

		if ( heroMediaFrame ) {
			heroMediaFrame.open();
			return;
		}

		heroMediaFrame = parent.wp.media({
			title: 'Select or Upload Hero Graphic',
			button: { text: 'Use for Hero' },
			multiple: false
		});

		heroMediaFrame.on('select', function() {
			var attachment = heroMediaFrame.state().get('selection').first().toJSON();
			if ( attachment && attachment.url ) {
				$img.attr('src', attachment.url);
				if ( settingId && parentApi(settingId) ) {
					parentApi(settingId).set(attachment.url);
				}
			}
		});

		heroMediaFrame.open();
	});

	// In-canvas clicking on Header components
	$(document).on('click', '#mw-header .mh-logo-link, #mw-header .mh-site-title, #mw-header .mh-logo-img', function(e) {
		e.preventDefault();
		if ( parentApi.control && parentApi.control('blogname') ) {
			parentApi.control('blogname').focus();
		} else if ( parentApi.control && parentApi.control('custom_logo') ) {
			parentApi.control('custom_logo').focus();
		}
	});

	$(document).on('click', '#mw-header .mh-header-cta', function(e) {
		e.preventDefault();
		if ( parentApi.control && parentApi.control('mh_header_cta_text') ) {
			parentApi.control('mh_header_cta_text').focus();
		}
	});

	$(document).on('click', '#mw-header .mh-desktop-nav, #mw-header .mh-hamburger', function(e) {
		e.preventDefault();
		if ( parentApi.section && parentApi.section('menu_locations') ) {
			parentApi.section('menu_locations').focus();
		} else if ( parentApi.panel && parentApi.panel('nav_menus') ) {
			parentApi.panel('nav_menus').focus();
		}
	});

	// In-canvas clicking on Footer components
	$(document).on('click', '#mw-footer', function(e) {
		if ( $(e.target).closest('a').length && parentApi.section && parentApi.section('magic_hat_footer') ) {
			parentApi.section('magic_hat_footer').focus();
		}
	});

	$(window).on('resize scroll', function() {
		positionHandles();
	});

	$(document).ready(function() {
		setTimeout(function() {
			positionHandles();
			initHeroEditables();
		}, 500);
	});

	// Re-bind when WordPress Selective Refresh updates header, hero, or footer
	if (parentApi.selectiveRefresh) {
		parentApi.selectiveRefresh.bind('partial-content-rendered', function(placement) {
			if (placement && placement.partial) {
				setTimeout(function() {
					positionHandles();
					initHeroEditables();
				}, 200);
			}
		});
	}

	// Live Preview: Animated Canvas & Background settings
	if ( window.wp && window.wp.customize ) {
		wp.customize( 'mh_bg_canvas_preset', function( value ) {
			value.bind( function( newPreset ) {
				if ( window.mhCanvasInstance && typeof window.mhCanvasInstance.switchPreset === 'function' ) {
					window.mhCanvasInstance.switchPreset( newPreset );
				}
			} );
		} );

		wp.customize( 'mh_bg_canvas_color', function( value ) {
			value.bind( function( newColor ) {
				if ( window.mhCanvasInstance && typeof window.mhCanvasInstance.updateOptions === 'function' ) {
					window.mhCanvasInstance.updateOptions( { color: newColor } );
				}
			} );
		} );

		wp.customize( 'mh_bg_canvas_opacity', function( value ) {
			value.bind( function( newOpacity ) {
				if ( window.mhCanvasInstance && typeof window.mhCanvasInstance.updateOptions === 'function' ) {
					window.mhCanvasInstance.updateOptions( { opacity: parseFloat( newOpacity ) } );
				}
			} );
		} );

		wp.customize( 'mh_bg_canvas_speed', function( value ) {
			value.bind( function( newSpeed ) {
				if ( window.mhCanvasInstance && typeof window.mhCanvasInstance.updateOptions === 'function' ) {
					window.mhCanvasInstance.updateOptions( { speed: parseFloat( newSpeed ) } );
				}
			} );
		} );

		wp.customize( 'mh_bg_mode', function( value ) {
			value.bind( function( newMode ) {
				var $canvas = $( '#mh-ambient-canvas' );
				if ( newMode === 'canvas' ) {
					$canvas.show();
					if ( window.mhCanvasInstance && ! window.mhCanvasInstance.isRunning ) {
						window.mhCanvasInstance.start();
					}
				} else {
					$canvas.hide();
					if ( window.mhCanvasInstance && window.mhCanvasInstance.isRunning ) {
						window.mhCanvasInstance.stop();
					}
				}
			} );
		} );

		// Live Preview: Typography settings
		wp.customize( 'mh_font_family', function( value ) {
			value.bind( function( newFont ) {
				if ( ! newFont ) return;
				var fontName = newFont.split(',')[0].trim();
				var linkId = 'mh-preview-google-font';
				var $link = $( '#' + linkId );
				var fontUrl = 'https://fonts.googleapis.com/css2?family=' + encodeURIComponent(fontName) + ':wght@300;400;500;600;700;800;900&display=swap';
				if ( ! $link.length ) {
					$( 'head' ).append( '<link id="' + linkId + '" rel="stylesheet" href="' + fontUrl + '">' );
				} else {
					$link.attr( 'href', fontUrl );
				}
				document.documentElement.style.setProperty( '--mh-font-family', newFont );
				document.documentElement.style.setProperty( '--mh-font-heading', newFont );
				document.documentElement.style.setProperty( '--mh-font-body', newFont );
			} );
		} );

		var typographyVariables = {
			'mh_font_size': { prop: '--mh-font-size', unit: 'px' },
			'mh_line_height': { prop: '--mh-line-height', unit: '' },
			'mh_heading_weight': { prop: '--mh-heading-weight', unit: '' },
			'mh_heading_line_height': { prop: '--mh-heading-line-height', unit: '' },
			'mh_font_size_h1': { prop: '--mh-font-size-h1', unit: 'px' },
			'mh_font_size_h2': { prop: '--mh-font-size-h2', unit: 'px' },
			'mh_font_size_h3': { prop: '--mh-font-size-h3', unit: 'px' },
			'mh_font_size_h4': { prop: '--mh-font-size-h4', unit: 'px' },
			'mh_font_size_h5': { prop: '--mh-font-size-h5', unit: 'px' },
			'mh_font_size_h6': { prop: '--mh-font-size-h6', unit: 'px' }
		};

		$.each( typographyVariables, function( settingId, meta ) {
			wp.customize( settingId, function( value ) {
				value.bind( function( newVal ) {
					document.documentElement.style.setProperty( meta.prop, newVal + meta.unit );
				} );
			} );
		} );

		// Live Preview: Spacing & Layout settings
		wp.customize( 'mh_space_base', function( value ) {
			value.bind( function( to ) {
				var base = parseFloat( to ) || 8;
				document.documentElement.style.setProperty( '--mh-space-base', base + 'px' );
				document.documentElement.style.setProperty( '--mh-space-1', ( base * 0.5 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-2', ( base * 1 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-3', ( base * 1.5 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-4', ( base * 2 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-5', ( base * 3 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-6', ( base * 4 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-7', ( base * 6 ) + 'px' );
				document.documentElement.style.setProperty( '--mh-space-8', ( base * 8 ) + 'px' );
			} );
		} );
		wp.customize( 'mh_content_width', function( value ) {
			value.bind( function( to ) {
				document.documentElement.style.setProperty( '--mh-content-width', to + 'px' );
			} );
		} );
		wp.customize( 'mh_radius_base', function( value ) {
			value.bind( function( to ) {
				document.documentElement.style.setProperty( '--mh-border-radius', to + 'px' );
			} );
		} );
		wp.customize( 'mh_border_width', function( value ) {
			value.bind( function( to ) {
				document.documentElement.style.setProperty( '--mh-border-width', to + 'px' );
			} );
		} );
	}

})(jQuery);
