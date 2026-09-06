<?php
/**
 * Ambient Generative Canvas Renderer, Enqueue & Page Builder Preview Helpers
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Ambient Background Canvas
 */
function xophz_magic_hat_render_ambient_canvas() {
	$bg_mode    = get_theme_mod( 'mh_bg_mode', 'default' );
	$is_preview = is_customize_preview();
	if ( ! $is_preview && $bg_mode !== 'canvas' ) {
		return;
	}
	$preset  = get_theme_mod( 'mh_bg_canvas_preset', 'electric-wave' );
	$color   = get_theme_mod( 'mh_bg_canvas_color', '#2563eb' );
	$opacity = floatval( get_theme_mod( 'mh_bg_canvas_opacity', '0.6' ) );
	$speed   = floatval( get_theme_mod( 'mh_bg_canvas_speed', '1.0' ) );
	$display = ( $bg_mode === 'canvas' ) ? 'block' : 'none';
	?>
	<canvas id="mh-ambient-canvas" class="mh-ambient-canvas" style="position: fixed; inset: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: 0; display: <?php echo esc_attr( $display ); ?>;"></canvas>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (window.MagicHatCanvases && document.getElementById('mh-ambient-canvas')) {
				window.mhCanvasInstance = window.MagicHatCanvases.mount(
					document.getElementById('mh-ambient-canvas'),
					<?php echo json_encode( $preset ); ?>,
					{
						color: <?php echo json_encode( $color ); ?>,
						opacity: <?php echo $opacity; ?>,
						speed: <?php echo $speed; ?>
					}
				);
			}
		});
	</script>
	<?php
}
add_action( 'wp_body_open', 'xophz_magic_hat_render_ambient_canvas', 1 );

/**
 * Enqueue Animated Canvas Engine Assets
 */
function xophz_magic_hat_enqueue_canvas_assets() {
	$bg_mode = get_theme_mod( 'mh_bg_mode', 'default' );
	if ( is_customize_preview() || $bg_mode === 'canvas' ) {
		wp_enqueue_script(
			'magic-hat-canvases',
			get_template_directory_uri() . '/assets/js/canvases/magic-hat-canvases.js',
			array(),
			'1.0.0',
			false
		);
	}
}
add_action( 'wp_enqueue_scripts', 'xophz_magic_hat_enqueue_canvas_assets' );

add_filter( 'body_class', function( $classes ) {
	if ( get_theme_mod( 'mh_bg_mode', 'default' ) === 'canvas' || is_customize_preview() ) {
		$classes[] = 'mh-has-ambient-canvas';
	}
	return $classes;
} );

/**
 * Page Builder Preview: Add Section Button
 */
add_filter( 'the_content', function( $content ) {
	global $post;
	if ( is_customize_preview() && $post && $post->post_type === 'page' ) {
		$is_active = class_exists( 'Xophz_Compass_Magic_Wand' ) || defined( 'XOPHZ_COMPASS_MAGIC_WAND_VERSION' );

		// If Magic Wand isn't active, show the floating install prompt
		if ( ! $is_active ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( file_exists( WP_PLUGIN_DIR . '/xophz-compass-magic-wand/xophz-compass-magic-wand.php' ) ) {
				$action_url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=xophz-compass-magic-wand/xophz-compass-magic-wand.php&plugin_status=all&paged=1&s' ), 'activate-plugin_xophz-compass-magic-wand/xophz-compass-magic-wand.php' );
				$btn_text   = __( 'Activate now', 'xophz-magic-hat' );
			} else {
				$action_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=xophz-compass-magic-wand' ), 'install-plugin_xophz-compass-magic-wand' );
				$btn_text   = __( 'Install now', 'xophz-magic-hat' );
			}
			
			$prompt = '
			<div id="mh-plugin-prompt" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 40px; border-radius: 4px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 999999; max-width: 450px; text-align: center; font-family: sans-serif;">
				<button type="button" onclick="document.getElementById(\'mh-plugin-prompt\').style.display=\'none\';" style="position: absolute; top: 10px; right: 15px; background: transparent; border: none; font-size: 24px; cursor: pointer; color: #50575e;">&times;</button>
				<h3 style="margin-top: 0; font-size: 16px; color: #3c434a; font-weight: 400; line-height: 1.5; margin-bottom: 25px;">' . esc_html__( 'Please Install the Magic Wand Companion Plugin to Enable All the Theme Features', 'xophz-magic-hat' ) . '</h3>
				<a href="' . esc_url( $action_url ) . '" class="button" style="display: inline-block; text-decoration: none; background: #2563eb; border: 1px solid #2563eb; color: #fff; padding: 10px 30px; font-weight: 600; text-transform: uppercase; font-size: 13px; border-radius: 4px;">' . esc_html( $btn_text ) . '</a>
			</div>';
			$content .= $prompt;
		}

		// Only append Add Section helper if page is in Magic Hat canvas mode or has sections
		$show_on_front = get_option( 'show_on_front', 'posts' );
		$is_canvas     = ( $show_on_front === 'page' && is_front_page() );
		$page_sections = get_post_meta( $post->ID, '_mh_page_sections', true );
		if ( $is_canvas || ! empty( $page_sections ) ) {
			$add_section = '
			<div class="mh-add-section-preview" style="margin-top: 40px; padding: 60px 0; border: 2px dashed var(--mh-color-border-muted, #cbd5e1); text-align: center; background: var(--mh-color-section, #f8fafc); border-radius: 8px;">';
				if ( $is_active ) {
					$add_section .= '<button type="button" class="mh-add-section" onclick="if(typeof parent.mhOpenAddSectionPanel === \'function\') { parent.mhOpenAddSectionPanel(); } else { parent.wp.customize.section(\'mh_page_builder\').focus(); }" style="background: var(--mh-color-cta-base, #2563eb); border: none; color: var(--mh-color-text-inverse, #fff); padding: 12px 32px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border-radius: 6px; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">' . __( '+ Add Section', 'xophz-magic-hat' ) . '</button>';
				} else {
					$add_section .= '<button type="button" class="btn-primary" style="background: #2563eb; border-color: #2563eb; text-transform: uppercase; letter-spacing: 1px;" onclick="if(window.parent && window.parent.wp && window.parent.wp.customize){ window.parent.wp.customize.section(\'mh_page_builder\').focus(); }">
					' . esc_html__( '+ ADD SECTION', 'xophz-magic-hat' ) . '
				</button>';
				}
			$add_section .= '</div>';
			$content     .= $add_section;
		}
	}
	return $content;
}, PHP_INT_MAX );
