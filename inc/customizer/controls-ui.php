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
	</style>
	<script>
		jQuery(document).ready(function($) {
			// Add hover styles matching the native Customizer X button
			$('head').append('<style>#mh-toggle-sb:hover, #mh-toggle-home:hover, #mh-toggle-dark:hover { background: #f0f0f1; color: #2271b1 !important; }</style>');
			
			var styleguideBtn = $(
				'<button type="button" id="mh-toggle-sb" title="Stylebook" style="width: 45px; height: 46px; border: none; border-right: 1px solid #ddd; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #50575e; box-sizing: border-box; padding: 0;">📖</button>'
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
					if (panel && panel.id === 'magic_hat_colors_panel') {
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
