<?php
/**
 * Customizer Controls UI Scripts & Enqueue
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Customizer Control Scripts
 */
function xophz_magic_hat_customize_controls_enqueue() {
	wp_enqueue_script( 'wp-api' );
	wp_enqueue_script(
		'magic-hat-ai-architect-js',
		get_template_directory_uri() . '/assets/js/customizer-ai-architect.js',
		array( 'jquery', 'customize-controls', 'wp-api' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
	wp_localize_script(
		'magic-hat-ai-architect-js',
		'mhAiSettings',
		array(
			'nonce'      => wp_create_nonce( 'wp_rest' ),
			'restUrl'    => esc_url_raw( rest_url() ),
			'connectors' => class_exists( 'Magic_Hat_AI_Architect' ) ? Magic_Hat_AI_Architect::get_available_connectors() : array(),
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'xophz_magic_hat_customize_controls_enqueue' );

/**
 * Inject Global Style Guide Toggle into Customizer Sidebar
 */
function xophz_magic_hat_customize_controls_scripts() {
	?>
	<style>
		#customize-theme-controls .customize-pane-child.accordion-section-content {
			padding: 12px;
			height: 100%;
		}
		/* AI Page Architect Styles */
		.mh-vibe-pill, .mh-arch-pill {
			transition: all 0.2s ease;
		}
		.mh-vibe-pill:hover, .mh-arch-pill:hover {
			border-color: #0284c7 !important;
			color: #0284c7 !important;
		}
		.mh-vibe-pill.active, .mh-arch-pill.active {
			background: #0284c7 !important;
			color: #ffffff !important;
			border-color: #0284c7 !important;
			font-weight: 700;
			box-shadow: 0 1px 4px rgba(2,132,199,0.3);
		}
		@keyframes mhSpin {
			100% { transform: rotate(360deg); }
		}
		.dashicons.spin {
			animation: mhSpin 1.2s linear infinite;
		}
		#mh-ai-conjure-btn.loading {
			opacity: 0.7;
			cursor: not-allowed;
		}
		/* Default folded state for Site Colors accordion child controls */
		#sub-accordion-section-magic_hat_colors li.customize-control:not(.customize-control-mh_accordion_toggle):not(#customize-control-mh_color_schedule_mode) {
			display: none;
		}
		/* Accordion Toggle Control Styles */
		.customize-control-mh_accordion_toggle {
			margin: 10px 0 4px 0 !important;
			padding: 0 !important;
			cursor: pointer;
		}
		.mh-accordion-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			background: #ffffff;
			border: 1px solid #dcdcde;
			border-radius: 4px;
			padding: 8px 12px;
			cursor: pointer;
			user-select: none;
			transition: background 0.15s ease, border-color 0.15s ease;
		}
		.mh-accordion-header:hover, .mh-accordion-header:focus {
			background: #f6f7f7;
			border-color: #2271b1;
			outline: none;
		}
		.mh-accordion-header.is-expanded {
			background: #f0f6fc;
			border-color: #2271b1;
		}
		.mh-accordion-title-wrap {
			display: flex;
			align-items: center;
			gap: 8px;
			pointer-events: none;
		}
		.mh-accordion-title {
			font-size: 12px;
			font-weight: 600;
			color: #1d2327;
			pointer-events: none;
		}
		.mh-accordion-badge {
			font-size: 10px;
			color: #64748b;
			background: #e2e8f0;
			padding: 1px 6px;
			border-radius: 8px;
			pointer-events: none;
		}
		.mh-accordion-icon {
			font-size: 18px;
			width: 18px;
			height: 18px;
			color: #50575e;
			transition: transform 0.2s ease;
			pointer-events: none;
		}
		.mh-accordion-header.is-expanded .mh-accordion-icon {
			transform: rotate(180deg);
			color: #2271b1;
		}
	</style>
	<script>
		jQuery(document).ready(function($) {
			// Helper to get child controls belonging to an accordion toggle
			function getAccordionChildren($toggleLi) {
				return $toggleLi.nextUntil('.customize-control-mh_accordion_toggle');
			}

			// Initialize and synchronize open/closed accordion states
			function initAccordions() {
				var $toggles = $('.customize-control-mh_accordion_toggle');
				if (!$toggles.length) return;

				$toggles.each(function() {
					var $toggleLi = $(this);
					var $header = $toggleLi.find('.mh-accordion-header');
					var isExpanded = $header.hasClass('is-expanded');
					var $children = getAccordionChildren($toggleLi);

					if (isExpanded) {
						$children.show();
					} else {
						$children.hide();
					}
				});
			}

			// Click handler for accordion toggles
			$(document).on('click', '.customize-control-mh_accordion_toggle, .mh-accordion-header', function(e) {
				e.preventDefault();
				e.stopPropagation();

				var $toggleLi = $(this).closest('.customize-control-mh_accordion_toggle');
				var $header = $toggleLi.find('.mh-accordion-header');
				var willExpand = !$header.hasClass('is-expanded');
				var $children = getAccordionChildren($toggleLi);

				if (willExpand) {
					// Collapse sibling accordion groups in this section
					var $siblingToggles = $toggleLi.siblings('.customize-control-mh_accordion_toggle');
					$siblingToggles.each(function() {
						var $otherToggle = $(this);
						var $otherHeader = $otherToggle.find('.mh-accordion-header');
						if ($otherHeader.hasClass('is-expanded')) {
							$otherHeader.removeClass('is-expanded').attr('aria-expanded', 'false');
							getAccordionChildren($otherToggle).stop(true, true).slideUp(150);
						}
					});

					$header.addClass('is-expanded').attr('aria-expanded', 'true');
					$children.stop(true, true).slideDown(150);
				} else {
					$header.removeClass('is-expanded').attr('aria-expanded', 'false');
					$children.stop(true, true).slideUp(150);
				}
			});

			// Keyboard navigation support
			$(document).on('keydown', '.mh-accordion-header', function(e) {
				if (e.which === 13 || e.which === 32) {
					e.preventDefault();
					$(this).closest('.customize-control-mh_accordion_toggle').trigger('click');
				}
			});

			// Auto-expand accordion group when any child control receives focus
			$(document).on('focusin', 'li.customize-control:not(.customize-control-mh_accordion_toggle)', function() {
				var $controlLi = $(this);
				var $prevToggle = $controlLi.prevAll('.customize-control-mh_accordion_toggle').first();
				if ($prevToggle.length) {
					var $header = $prevToggle.find('.mh-accordion-header');
					if (!$header.hasClass('is-expanded')) {
						$prevToggle.trigger('click');
					}
				}
			});

			// Add hover styles matching the native Customizer X button
			$('head').append('<style>#mh-toggle-sb:hover, #mh-toggle-home:hover, #mh-toggle-dark:hover { background: #f0f0f1; color: #2271b1 !important; }</style>');
			
			var styleguideBtn = $(
				'<button type="button" id="mh-toggle-sb" title="Stylebook" style="width: 45px; height: 46px; border: none; border-right: 1px solid #ddd; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #50575e; box-sizing: border-box; padding: 0;">🔮</button>'
			);
			var homeBtn = $(
				'<button type="button" id="mh-toggle-home" title="Homepage" style="width: 45px; height: 46px; border: none; border-right: 1px solid #ddd; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #50575e; box-sizing: border-box; padding: 0;"><span class="dashicons dashicons-admin-home" style="font-size: 20px; width: 20px; height: 20px;"></span></button>'
			);
			
			// Inject next to the Close button at the top left
			var actionWrapper = $('<div style="position: absolute; left: 45px; top: 0; bottom: 0; display: flex; align-items: center;"></div>');
			actionWrapper.append(homeBtn).append(styleguideBtn);
			$('#customize-header-actions').append(actionWrapper);

			$('#mh-toggle-sb').on('click', function(e) {
				e.preventDefault();
				wp.customize.previewer.previewUrl('<?php echo esc_url( home_url( '?magic_hat_stylebook=1' ) ); ?>');
			});
			$('#mh-toggle-home').on('click', function(e) {
				e.preventDefault();
				wp.customize.previewer.previewUrl('<?php echo esc_url( home_url( '/' ) ); ?>');
			});

			// Anchor scrolling in Style Guide when sections or panels are expanded
			wp.customize.bind('ready', function() {
				initAccordions();
				setTimeout(initAccordions, 100);
				setTimeout(initAccordions, 400);

				if (wp.customize.section('magic_hat_colors')) {
					wp.customize.section('magic_hat_colors').expanded.bind(function(isExpanded) {
						if (isExpanded) {
							setTimeout(initAccordions, 50);
						}
					});
				}

				wp.customize.state('expandedSection').bind(function(section) {
					if (section) {
						var map = {
							'magic_hat_colors': 'section-colors',
							'magic_hat_typography': 'section-typography',
							'magic_hat_spacing': 'section-spacing',
							'magic_hat_buttons': 'section-buttons',
							'nav_menus': 'section-menus',
							'magic_hat_media': 'section-media',
							'magic_hat_post': 'section-post',
							'magic_hat_comments': 'section-comments'
						};
						if (section.id && section.id.indexOf('mh_colors_') === 0) {
							wp.customize.previewer.send('mh-scroll-to', 'section-colors');
						} else if (map[section.id]) {
							wp.customize.previewer.send('mh-scroll-to', map[section.id]);
						}
					}
				});
				wp.customize.state('expandedPanel').bind(function(panel) {
					if (panel && (panel.id === 'magic_hat_colors_panel' || panel.id === 'magic_hat_general_settings')) {
						wp.customize.previewer.send('mh-scroll-to', 'section-colors');
					}
				});
			});

			// Contextual controls live visibility toggle for Site Background & Canvas
			wp.customize.bind('ready', function() {
				if (wp.customize('mh_bg_mode')) {
					var updateBgControlsVisibility = function(mode) {
						var isSolid    = (mode === 'solid');
						var isGradient = (mode === 'gradient');
						var isImage    = (mode === 'image');
						var isCanvas   = (mode === 'canvas');

						var setControlActive = function(controlId, active) {
							if (wp.customize.control(controlId)) {
								wp.customize.control(controlId).active.set(active);
							}
						};

						setControlActive('mh_bg_solid_color', isSolid);
						setControlActive('mh_bg_gradient_start', isGradient);
						setControlActive('mh_bg_gradient_end', isGradient);
						setControlActive('mh_bg_image', isImage);
						setControlActive('mh_bg_image_size', isImage);
						setControlActive('mh_bg_image_repeat', isImage);
						setControlActive('mh_bg_image_position', isImage);
						setControlActive('mh_bg_image_attachment', isImage);
						setControlActive('mh_bg_image_bg_color', isImage);
						setControlActive('mh_bg_canvas_preset', isCanvas);
						setControlActive('mh_bg_canvas_color', isCanvas);
						setControlActive('mh_bg_canvas_opacity', isCanvas);
						setControlActive('mh_bg_canvas_speed', isCanvas);
					};

					updateBgControlsVisibility(wp.customize('mh_bg_mode').get());
					wp.customize('mh_bg_mode').bind(updateBgControlsVisibility);
				}
			});
		});
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'xophz_magic_hat_customize_controls_scripts' );
