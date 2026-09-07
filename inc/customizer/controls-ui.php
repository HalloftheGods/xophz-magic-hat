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
		file_exists( get_template_directory() . '/assets/js/customizer-ai-architect.js' ) ? filemtime( get_template_directory() . '/assets/js/customizer-ai-architect.js' ) : wp_get_theme()->get( 'Version' ),
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

	wp_enqueue_script(
		'magic-hat-layout-modal-js',
		get_template_directory_uri() . '/assets/js/customizer-layout-modal.js',
		array( 'jquery', 'customize-controls' ),
		file_exists( get_template_directory() . '/assets/js/customizer-layout-modal.js' ) ? filemtime( get_template_directory() . '/assets/js/customizer-layout-modal.js' ) : wp_get_theme()->get( 'Version' ),
		true
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
		/* Section & Menu Emoji Sizing */
		.mh-section-emoji,
		.mh-nav-icon {
			font-size: 22px !important;
			line-height: 1 !important;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			vertical-align: middle !important;
			margin-right: 8px !important;
			width: 26px !important;
			text-align: center !important;
			filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.15));
			transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);
		}
		.accordion-section-title:hover .mh-section-emoji,
		.accordion-trigger:hover .mh-section-emoji,
		.customize-section-title:hover .mh-section-emoji,
		.mh-customizer-nav-item:hover .mh-nav-icon {
			transform: scale(1.18);
		}
		/* AI Studio Conversational Page Architect Styles */
		.mh-ai-studio-container {
			display: flex;
			flex-direction: column;
			gap: 10px;
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
		}
		.mh-ai-studio-header {
			background: linear-gradient(135deg, #090d16 0%, #151d2f 100%);
			border: 1px solid rgba(98, 201, 255, 0.3);
			border-radius: 8px;
			padding: 10px 12px;
			box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
		}
		.mh-ai-studio-header-top {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 4px;
		}
		.mh-ai-studio-title-wrap {
			display: flex;
			align-items: center;
			gap: 6px;
		}
		.mh-ai-studio-title {
			font-size: 11px;
			font-weight: 800;
			color: #62c9ff;
			letter-spacing: 0.8px;
			text-transform: uppercase;
		}
		.mh-ai-badge {
			font-size: 9px;
			padding: 2px 6px;
			border-radius: 4px;
			font-weight: 600;
			background: rgba(16, 185, 129, 0.18);
			color: #10b981;
		}
		.mh-ai-studio-actions-top {
			display: flex;
			gap: 4px;
		}
		.mh-ai-btn-studio {
			font-size: 10px !important;
			height: 22px !important;
			line-height: 20px !important;
			padding: 0 6px !important;
			background: rgba(255, 255, 255, 0.08) !important;
			border-color: rgba(98, 201, 255, 0.25) !important;
			color: #e2e8f0 !important;
		}
		.mh-ai-btn-studio:hover {
			background: rgba(98, 201, 255, 0.2) !important;
			color: #ffffff !important;
		}
		.mh-ai-studio-desc {
			margin: 0;
			font-size: 11px;
			color: #94a3b8;
			line-height: 1.35;
		}
		/* Settings Drawer */
		.mh-ai-settings-drawer {
			background: #0f172a;
			border: 1px solid rgba(98, 201, 255, 0.2);
			border-radius: 6px;
			padding: 10px;
			display: flex;
			flex-direction: column;
			gap: 8px;
		}
		.mh-ai-drawer-field label {
			display: block;
			font-size: 10px;
			font-weight: 700;
			color: #94a3b8;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			margin-bottom: 3px;
		}
		.mh-ai-field-label-row {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 3px;
		}
		.mh-ai-link {
			font-size: 10px;
			color: #62c9ff;
			text-decoration: none;
		}
		.mh-ai-select {
			width: 100% !important;
			font-size: 11px !important;
			background: #1e293b !important;
			color: #f8fafc !important;
			border: 1px solid #334155 !important;
			border-radius: 4px !important;
		}
		.mh-ai-checkbox-label {
			display: flex;
			align-items: center;
			gap: 6px;
			font-size: 11px;
			color: #cbd5e1;
			margin-top: 6px;
			cursor: pointer;
		}
		/* Starter Chips */
		.mh-ai-starter-chips-wrap {
			margin-bottom: 2px;
		}
		.mh-ai-chips-caption {
			display: block;
			font-size: 10px;
			font-weight: 700;
			color: #64748b;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			margin-bottom: 4px;
		}
		.mh-ai-starter-chips {
			display: flex;
			flex-wrap: wrap;
			gap: 4px;
		}
		.mh-ai-chip-btn {
			background: #ffffff;
			border: 1px solid #cbd5e1;
			border-radius: 12px;
			padding: 3px 8px;
			font-size: 10px;
			font-weight: 600;
			color: #334155;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.mh-ai-chip-btn:hover {
			background: #f0f9ff;
			border-color: #0284c7;
			color: #0284c7;
		}
		/* Chat Thread */
		.mh-ai-chat-thread-container {
			background: #080c14;
			border: 1px solid rgba(98, 201, 255, 0.2);
			border-radius: 8px;
			padding: 8px;
			max-height: 380px;
			min-height: 200px;
			overflow-y: auto;
			box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.4);
		}
		.mh-ai-chat-thread {
			display: flex;
			flex-direction: column;
			gap: 10px;
		}
		.mh-ai-msg {
			border-radius: 8px;
			padding: 8px 10px;
			font-size: 11px;
			line-height: 1.45;
		}
		.mh-ai-msg-user {
			align-self: flex-end;
			background: rgba(2, 132, 199, 0.22);
			border: 1px solid rgba(98, 201, 255, 0.35);
			color: #f0f9ff;
			max-width: 90%;
		}
		.mh-ai-msg-assistant {
			align-self: flex-start;
			background: #0f172a;
			border: 1px solid rgba(255, 255, 255, 0.12);
			color: #cbd5e1;
			width: 100%;
			box-sizing: border-box;
		}
		.mh-ai-msg-header {
			display: flex;
			align-items: center;
			gap: 5px;
			margin-bottom: 4px;
		}
		.mh-ai-msg-avatar {
			font-size: 12px;
		}
		.mh-ai-msg-name {
			font-weight: 700;
			font-size: 10px;
			color: #62c9ff;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
		.mh-ai-msg-time {
			font-size: 9px;
			color: #64748b;
			margin-left: auto;
		}
		.mh-ai-msg-body p {
			margin: 0 0 6px 0;
		}
		.mh-ai-msg-body p:last-child {
			margin-bottom: 0;
		}
		.mh-ai-msg-subtext {
			font-size: 10px;
			color: #94a3b8;
		}
		.mh-ai-thought-box {
			background: #060911;
			border: 1px solid rgba(98, 201, 255, 0.2);
			border-radius: 6px;
			padding: 6px 8px;
			margin: 6px 0;
			font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
			font-size: 10px;
		}
		.mh-ai-thought-box summary {
			cursor: pointer;
			color: #62c9ff;
			font-weight: 600;
			outline: none;
			padding: 2px 0;
		}
		.mh-ai-thought-steps {
			margin-top: 6px;
			border-top: 1px solid rgba(255, 255, 255, 0.08);
			padding-top: 4px;
		}
		.mh-ai-thought-step {
			margin-bottom: 4px;
			line-height: 1.35;
		}
		.mh-ai-thought-step-name {
			color: #38bdf8;
			font-weight: 700;
		}
		.mh-ai-thought-step-desc {
			color: #94a3b8;
		}
		.mh-ai-msg-actions {
			display: flex;
			gap: 6px;
			margin-top: 8px;
			padding-top: 6px;
			border-top: 1px solid rgba(255, 255, 255, 0.08);
		}
		.mh-ai-action-chip {
			background: rgba(255, 255, 255, 0.06);
			border: 1px solid rgba(255, 255, 255, 0.15);
			border-radius: 4px;
			padding: 2px 6px;
			font-size: 9px;
			color: #cbd5e1;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.mh-ai-action-chip:hover {
			background: #0284c7;
			border-color: #0284c7;
			color: #ffffff;
		}
		/* Progress Panel */
		.mh-ai-status-panel {
			background: #0a0e1a;
			border: 1px solid rgba(98, 201, 255, 0.3);
			border-radius: 6px;
			padding: 8px 10px;
			font-size: 11px;
			color: #cbd5e1;
		}
		/* Follow-up chips */
		.mh-ai-followup-chips-wrap {
			overflow-x: auto;
			padding: 2px 0;
		}
		.mh-ai-followup-chips {
			display: flex;
			gap: 4px;
			white-space: nowrap;
		}
		.mh-ai-followup-chip {
			background: #f1f5f9;
			border: 1px solid #cbd5e1;
			border-radius: 12px;
			padding: 2px 8px;
			font-size: 10px;
			color: #475569;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.mh-ai-followup-chip:hover {
			background: #0284c7;
			border-color: #0284c7;
			color: #ffffff;
		}
		/* Chat Dock */
		.mh-ai-chat-dock {
			background: #ffffff;
			border: 1px solid #cbd5e1;
			border-radius: 8px;
			padding: 6px 8px;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
		}
		.mh-ai-input-wrap {
			display: flex;
			flex-direction: column;
			gap: 6px;
		}
		.mh-ai-chat-input {
			width: 100% !important;
			border: none !important;
			outline: none !important;
			resize: none !important;
			padding: 0 !important;
			font-size: 12px !important;
			line-height: 1.4 !important;
			color: #1e293b !important;
			background: transparent !important;
			box-shadow: none !important;
		}
		.mh-ai-chat-input:focus {
			box-shadow: none !important;
		}
		.mh-ai-input-actions {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}
		.mh-ai-input-hints {
			display: flex;
			align-items: center;
			gap: 6px;
		}
		.mh-ai-mini-model-pill {
			font-size: 9px;
			padding: 1px 5px;
			background: #f1f5f9;
			border: 1px solid #e2e8f0;
			border-radius: 4px;
			color: #64748b;
			font-weight: 600;
		}
		.mh-ai-key-hint {
			font-size: 9px;
			color: #94a3b8;
		}
		.mh-ai-send-btn {
			height: 28px !important;
			line-height: 26px !important;
			padding: 0 10px !important;
			background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
			border-color: #0284c7 !important;
			font-size: 11px !important;
			font-weight: 700 !important;
			display: inline-flex !important;
			align-items: center !important;
			gap: 4px !important;
		}
		/* Checkpoints bar */
		.mh-ai-checkpoints-bar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 2px 0;
		}
		.mh-ai-checkpoint-btn {
			background: transparent;
			border: none;
			color: #64748b;
			font-size: 10px;
			cursor: pointer;
			padding: 2px 4px;
			border-radius: 4px;
		}
		.mh-ai-checkpoint-btn:hover {
			color: #0284c7;
			background: #f8fafc;
		}
		/* Wide Studio Mode for Customizer */
		body.mh-ai-studio-wide #customize-controls {
			width: 480px !important;
			transition: width 0.25s cubic-bezier(0.16, 1, 0.3, 1);
		}
		body.mh-ai-studio-wide .wp-full-overlay.expanded {
			margin-left: 480px !important;
			transition: margin-left 0.25s cubic-bezier(0.16, 1, 0.3, 1);
		}
		@keyframes mhSpin {
			100% { transform: rotate(360deg); }
		}
		.dashicons.spin {
			animation: mhSpin 1.2s linear infinite;
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
		/* Page Builder Control (Screen 2) */
		.mh-template-switch-wrap {
			margin-bottom: 14px;
			padding: 12px;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			border-radius: 6px;
		}
		.mh-template-switch-title {
			font-size: 11px;
			font-weight: 700;
			color: #0f172a;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			margin-bottom: 4px;
		}
		.mh-template-switch-desc {
			margin: 0 0 10px;
			font-size: 11px;
			color: #64748b;
			line-height: 1.3;
		}
		.mh-template-switch-btns {
			display: flex;
			gap: 6px;
		}
		.mh-template-switch-btns .mh-switch-btn {
			flex: 1;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 6px;
			font-size: 11px;
			font-weight: 600;
			padding: 6px 8px;
			border-radius: 4px;
			border: 1px solid #cbd5e1;
			background: #ffffff;
			color: #475569;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.mh-template-switch-btns .mh-switch-btn:hover {
			border-color: #2563eb;
			color: #2563eb;
		}
		.mh-template-switch-btns .mh-switch-btn.active {
			background: #2563eb;
			border-color: #2563eb;
			color: #ffffff;
			box-shadow: 0 1px 3px rgba(37, 99, 235, 0.3);
		}
		.mh-template-notice {
			margin-top: 8px;
			font-size: 11px;
			line-height: 1.35;
			padding: 6px 8px;
			border-radius: 4px;
		}
		.mh-template-notice.warning {
			background: #fffbeb;
			border: 1px solid #fde68a;
			color: #92400e;
		}
		.mh-template-notice.success {
			background: #ecfdf5;
			border: 1px solid #a7f3d0;
			color: #065f46;
		}
		.mh-add-section-wrap {
			margin-top: 10px;
		}
		.mh-add-section-wrap .mh-add-section {
			width: 100%;
			padding: 8px;
			font-weight: 600;
			font-size: 12px;
			border-radius: 4px;
			border: none;
			cursor: pointer;
			transition: opacity 0.2s;
		}
		.mh-add-section-wrap .mh-add-section:hover {
			opacity: 0.9;
		}
		.mh-add-section-wrap .mh-add-section.mh-btn-active {
			background: #2563eb;
			border-color: #2563eb;
			color: #ffffff;
		}
		.mh-add-section-wrap .mh-add-section.mh-btn-disabled {
			opacity: 0.4;
			cursor: not-allowed;
		}
		/* Plugin Required Notice */
		.mh-plugin-notice {
			margin-top: 8px;
			padding: 8px 10px;
			background: #fef2f2;
			border-left: 3px solid #dc3232;
			border-radius: 2px;
		}
		.mh-plugin-notice-title {
			margin: 0;
			color: #dc3232;
			font-weight: 600;
			font-size: 11px;
		}
		.mh-plugin-notice-desc {
			margin: 3px 0 0;
			font-size: 11px;
			color: #888888;
			line-height: 1.3;
		}
		/* Group Title / Subheading Control */
		.mh-customizer-group-heading {
			margin-top: 14px;
			margin-bottom: 6px;
			padding-top: 10px;
			border-top: 1px solid #dcdcde;
		}
		.mh-customizer-group-heading h4 {
			margin: 0;
			font-size: 12px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			color: #1d2327;
		}
		.mh-customizer-group-heading .description {
			margin-top: 2px;
			display: block;
		}
		/* Layout Picker Card Control */
		.mh-layout-picker-wrap {
			margin-bottom: 12px;
		}
		.mh-layout-picker-card {
			background: #f8fafc;
			border: 1px solid #cbd5e1;
			border-radius: 8px;
			padding: 12px;
			margin-top: 6px;
			display: flex;
			flex-direction: column;
			gap: 10px;
			transition: border-color 0.15s ease, box-shadow 0.15s ease;
		}
		.mh-layout-picker-card:hover {
			border-color: #2563eb;
			box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
		}
		.mh-layout-picker-card-info {
			display: flex;
			flex-direction: column;
			gap: 2px;
		}
		.mh-layout-picker-badge {
			font-size: 9px;
			font-weight: 800;
			letter-spacing: 0.6px;
			text-transform: uppercase;
			color: #2563eb;
			background: #eff6ff;
			padding: 2px 6px;
			border-radius: 4px;
			align-self: flex-start;
		}
		.mh-layout-picker-current-title {
			font-size: 13px;
			font-weight: 700;
			color: #0f172a;
			margin-top: 4px;
		}
		.mh-layout-picker-current-id {
			font-size: 11px;
			font-family: monospace;
			color: #64748b;
		}
		.mh-btn-open-layout-modal {
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 6px !important;
			width: 100% !important;
			height: 32px !important;
			line-height: 30px !important;
			font-size: 12px !important;
			font-weight: 600 !important;
			background: #2563eb !important;
			border-color: #2563eb !important;
			color: #ffffff !important;
			border-radius: 4px !important;
			cursor: pointer !important;
			transition: background 0.15s ease !important;
		}
		.mh-btn-open-layout-modal:hover {
			background: #1d4ed8 !important;
		}

		/* Modal Styles (Zero-Dependency Architecture) */
		.mh-modal-backdrop {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background: rgba(15, 23, 42, 0.82);
			-webkit-backdrop-filter: blur(8px);
			backdrop-filter: blur(8px);
			z-index: 999999;
			align-items: center;
			justify-content: center;
			padding: 24px;
			box-sizing: border-box;
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		}
		.mh-modal-container {
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			width: 100%;
			max-width: 1140px;
			height: 88vh;
			max-height: 860px;
			display: flex;
			flex-direction: column;
			box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
			overflow: hidden;
			position: relative;
		}
		.mh-layout-modal-container {
			max-width: 1140px;
			width: 100%;
			height: 88vh;
			max-height: 860px;
		}
		.mh-modal-header {
			padding: 14px 24px;
			border-bottom: 1px solid #e2e8f0;
			display: flex;
			justify-content: space-between;
			align-items: center;
			background: #f8fafc;
			gap: 16px;
			flex-shrink: 0;
		}
		.mh-modal-header-left {
			display: flex;
			align-items: center;
			gap: 12px;
		}
		.mh-modal-header-icon {
			font-size: 24px;
			width: 24px;
			height: 24px;
			color: #2563eb;
		}
		.mh-modal-title {
			margin: 0;
			font-size: 16px;
			font-weight: 700;
			color: #0f172a;
			line-height: 1.2;
		}
		.mh-modal-subtitle {
			margin: 2px 0 0;
			font-size: 12px;
			color: #64748b;
		}
		.mh-source-filter-group {
			display: flex;
			gap: 4px;
			background: #e2e8f0;
			padding: 3px;
			border-radius: 9999px;
		}
		.mh-source-pill {
			background: transparent;
			border: none;
			padding: 5px 14px;
			border-radius: 9999px;
			font-size: 11px;
			font-weight: 600;
			color: #475569;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			transition: all 0.15s ease;
		}
		.mh-source-pill:hover {
			color: #0f172a;
		}
		.mh-source-pill.active {
			background: #ffffff;
			color: #2563eb;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
		}
		.mh-source-badge {
			font-size: 10px;
			font-weight: 700;
			background: #e2e8f0;
			color: #64748b;
			padding: 1px 6px;
			border-radius: 9999px;
			line-height: 1.3;
		}
		.mh-source-pill.active .mh-source-badge {
			background: #eff6ff;
			color: #2563eb;
		}
		.mh-modal-header-right {
			display: flex;
			align-items: center;
			gap: 12px;
		}
		.mh-search-wrapper {
			position: relative;
			display: flex;
			align-items: center;
		}
		.mh-search-wrapper .mh-search-icon {
			position: absolute;
			left: 8px;
			color: #94a3b8;
			font-size: 16px;
			width: 16px;
			height: 16px;
			pointer-events: none;
		}
		#mh-layout-modal-search {
			border: 1px solid #cbd5e1;
			border-radius: 6px;
			padding: 6px 28px 6px 28px;
			font-size: 12px;
			color: #0f172a;
			outline: none;
			width: 200px;
			background: #ffffff;
			transition: border-color 0.15s, width 0.2s ease;
		}
		#mh-layout-modal-search:focus {
			border-color: #2563eb;
			width: 240px;
		}
		.mh-search-clear-btn {
			position: absolute;
			right: 6px;
			background: none;
			border: none;
			font-size: 16px;
			line-height: 1;
			color: #94a3b8;
			cursor: pointer;
			padding: 2px 4px;
		}
		.mh-search-clear-btn:hover {
			color: #0f172a;
		}
		.mh-close-modal {
			background: #f1f5f9;
			border: 1px solid #e2e8f0;
			border-radius: 6px;
			font-size: 20px;
			line-height: 1;
			width: 32px;
			height: 32px;
			cursor: pointer;
			color: #475569;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.15s ease;
		}
		.mh-close-modal:hover {
			background: #fee2e2;
			border-color: #fca5a5;
			color: #ef4444;
		}
		.mh-modal-body {
			flex: 1;
			display: flex;
			min-height: 0;
			position: relative;
			overflow: hidden;
		}
		.mh-modal-sidebar {
			width: 230px;
			flex-shrink: 0;
			background: #ffffff;
			border-right: 1px solid #e2e8f0;
			display: flex;
			flex-direction: column;
			overflow-y: auto;
			padding: 14px 10px;
			box-sizing: border-box;
		}
		.mh-sidebar-title {
			font-size: 10px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1px;
			color: #94a3b8;
			padding: 6px 12px;
			margin-bottom: 4px;
		}
		.mh-cat-nav {
			display: flex;
			flex-direction: column;
			gap: 3px;
		}
		.mh-cat-item {
			display: flex;
			align-items: center;
			gap: 8px;
			padding: 8px 12px;
			border-radius: 6px;
			font-size: 12px;
			font-weight: 600;
			color: #475569;
			text-decoration: none;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.mh-cat-item:hover {
			background: #f1f5f9;
			color: #0f172a;
		}
		.mh-cat-item.active {
			background: #2563eb;
			color: #ffffff;
		}
		.mh-cat-item .dashicons {
			font-size: 16px;
			width: 16px;
			height: 16px;
			color: inherit;
		}
		.mh-cat-label {
			flex: 1;
		}
		.mh-cat-count-badge {
			font-size: 10px;
			font-weight: 700;
			padding: 1px 6px;
			border-radius: 9999px;
			background: #e2e8f0;
			color: #64748b;
			margin-left: auto;
			transition: all 0.15s ease;
		}
		.mh-cat-item.active .mh-cat-count-badge {
			background: rgba(255, 255, 255, 0.25);
			color: #ffffff;
		}
		.mh-modal-main-view {
			flex: 1;
			min-width: 0;
			height: 100%;
			overflow-y: auto;
			padding: 20px 24px;
			background: #f8fafc;
			box-sizing: border-box;
		}
		.mh-layout-grid-wrap {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
			gap: 16px;
		}
		.mh-layout-card {
			position: relative;
			background: #ffffff;
			border: 1px solid #cbd5e1;
			border-radius: 8px;
			overflow: hidden;
			cursor: pointer;
			transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			display: flex;
			flex-direction: column;
		}
		.mh-layout-card:hover {
			border-color: #2563eb;
			box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
			transform: translateY(-2px);
		}
		.mh-card-thumb-wrap {
			position: relative;
			width: 100%;
			height: 120px;
			background: #0b0f19;
			border-bottom: 1px solid #e2e8f0;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 10px;
			box-sizing: border-box;
		}
		.mh-card-thumb-wrap svg {
			width: 100%;
			height: 100%;
			max-height: 100%;
			display: block;
		}
		.mh-card-source-tag {
			position: absolute;
			top: 8px;
			left: 8px;
			font-size: 9px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			padding: 2px 7px;
			border-radius: 4px;
			z-index: 2;
		}
		.mh-type-header {
			background: #2563eb !important;
			color: #ffffff !important;
		}
		.mh-type-footer {
			background: #0284c7 !important;
			color: #ffffff !important;
		}
		.mh-card-active-tag {
			position: absolute;
			top: 8px;
			right: 8px;
			background: #10b981;
			color: #ffffff;
			font-size: 9px;
			font-weight: 800;
			letter-spacing: 0.5px;
			padding: 2px 6px;
			border-radius: 4px;
			z-index: 2;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
		}
		.mh-card-hover-overlay {
			position: absolute;
			inset: 0;
			background: rgba(15, 23, 42, 0.65);
			-webkit-backdrop-filter: blur(2px);
			backdrop-filter: blur(2px);
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			opacity: 0;
			transition: opacity 0.2s ease;
			pointer-events: none;
			z-index: 3;
		}
		.mh-layout-card:hover .mh-card-hover-overlay {
			opacity: 1;
			pointer-events: auto;
		}
		.mh-btn-card-preview,
		.mh-btn-card-add,
		.mh-btn-card-apply {
			border: none;
			border-radius: 5px;
			padding: 8px 14px;
			font-size: 12px;
			font-weight: 700;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 5px;
			transition: all 0.15s ease;
		}
		.mh-btn-card-preview {
			background: #ffffff;
			color: #0f172a;
		}
		.mh-btn-card-preview:hover {
			background: #f1f5f9;
			color: #2563eb;
		}
		.mh-btn-card-add,
		.mh-btn-card-apply {
			background: #2563eb;
			color: #ffffff;
			box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
		}
		.mh-btn-card-add:hover,
		.mh-btn-card-apply:hover {
			background: #1d4ed8;
		}
		.mh-btn-card-preview .dashicons,
		.mh-btn-card-add .dashicons {
			font-size: 15px;
			width: 15px;
			height: 15px;
		}
		.mh-card-info {
			padding: 12px 14px;
			background: #ffffff;
		}
		.mh-card-title {
			font-size: 13px;
			font-weight: 700;
			color: #0f172a;
			margin: 0 0 4px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.mh-card-desc {
			font-size: 11px;
			color: #64748b;
			margin: 0 0 8px;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			line-height: 1.4;
			height: 31px;
		}
		.mh-card-tags {
			display: flex;
			flex-wrap: wrap;
			gap: 4px;
		}
		.mh-tag-chip {
			font-size: 10px;
			background: #f1f5f9;
			color: #475569;
			padding: 1px 6px;
			border-radius: 4px;
		}
		.mh-modal-empty-state {
			text-align: center;
			padding: 60px 20px;
			color: #94a3b8;
			font-size: 13px;
		}
		.mh-modal-empty-icon {
			font-size: 36px;
			width: 36px;
			height: 36px;
			margin-bottom: 8px;
			opacity: 0.5;
		}
		.mh-preview-pane {
			position: absolute;
			inset: 0;
			background: #080c14;
			z-index: 10;
			display: flex;
			flex-direction: column;
		}
		.mh-preview-toolbar {
			background: #0f172a;
			border-bottom: 1px solid #1e293b;
			padding: 10px 20px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
			flex-shrink: 0;
		}
		.mh-preview-meta {
			display: flex;
			align-items: center;
			gap: 10px;
		}
		#mh-layout-preview-title {
			margin: 0;
			font-size: 14px;
			font-weight: 700;
			color: #ffffff;
		}
		.mh-preview-badge {
			font-size: 10px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			padding: 2px 8px;
			border-radius: 9999px;
			background: #1e293b;
			color: #62c9ff;
			border: 1px solid rgba(98, 201, 255, 0.3);
		}
		.mh-preview-viewport-controls {
			display: flex;
			gap: 3px;
			background: #1e293b;
			padding: 3px;
			border-radius: 6px;
		}
		.mh-viewport-btn {
			background: transparent;
			border: none;
			padding: 5px 10px;
			border-radius: 4px;
			cursor: pointer;
			color: #94a3b8;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.15s ease;
		}
		.mh-viewport-btn:hover {
			color: #ffffff;
		}
		.mh-viewport-btn.active {
			background: #2563eb;
			color: #ffffff;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
		}
		.mh-viewport-btn .dashicons {
			font-size: 16px;
			width: 16px;
			height: 16px;
		}
		.mh-preview-canvas {
			flex: 1;
			overflow-y: auto;
			padding: 32px 24px;
			background: #04060a;
			display: flex;
			justify-content: center;
			align-items: flex-start;
		}
		.mh-preview-stage {
			background: transparent;
			border-radius: 8px;
			overflow: hidden;
			transition: width 0.25s cubic-bezier(0.16, 1, 0.3, 1);
			margin: 0 auto;
			max-width: 100%;
			width: 100%;
		}
		.mh-preview-content {
			width: 100%;
			box-sizing: border-box;
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
					if (panel && (panel.id === 'magic_hat_colors_panel' || panel.id === 'magic_hat_brand_settings' || panel.id === 'magic_hat_general_settings')) {
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

			// Automatic Customizer Section & Menu Emoji Sizing
			(function() {
				var emojiRegex = /(\p{Extended_Pictographic}(?:\uFE0F|\u200D\p{Extended_Pictographic})*)/gu;

				function wrapEmojisInTree(root) {
					if (!root) return;

					var targets = root.querySelectorAll ? root.querySelectorAll('.accordion-section-title, .accordion-trigger, .customize-section-title h3, .customize-panel-title, .mh-customizer-nav-item .mh-nav-item-title') : [];
					var all = Array.prototype.slice.call(targets);
					if (root.matches && (root.matches('.accordion-section-title') || root.matches('.accordion-trigger') || root.matches('.customize-section-title h3') || root.matches('.customize-panel-title') || root.matches('.mh-customizer-nav-item .mh-nav-item-title'))) {
						all.unshift(root);
					}

					for (var i = 0; i < all.length; i++) {
						var el = all[i];
						if (!el || el.querySelector('.mh-section-emoji')) continue;

						var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
						var textNodes = [];
						var currentNode;
						while ((currentNode = walker.nextNode())) {
							var parent = currentNode.parentElement;
							if (parent && (parent.classList.contains('screen-reader-text') || parent.classList.contains('mh-section-emoji') || parent.tagName === 'SCRIPT' || parent.tagName === 'STYLE')) {
								continue;
							}
							textNodes.push(currentNode);
						}

						for (var t = 0; t < textNodes.length; t++) {
							var textNode = textNodes[t];
							var text = textNode.nodeValue;
							if (!text || !emojiRegex.test(text)) continue;
							emojiRegex.lastIndex = 0;

							var frag = document.createDocumentFragment();
							var lastIdx = 0;
							var match;
							while ((match = emojiRegex.exec(text)) !== null) {
								if (match.index > lastIdx) {
									frag.appendChild(document.createTextNode(text.substring(lastIdx, match.index)));
								}
								var span = document.createElement('span');
								span.className = 'mh-section-emoji';
								span.textContent = match[1];
								frag.appendChild(span);
								lastIdx = emojiRegex.lastIndex;
							}
							if (lastIdx < text.length) {
								frag.appendChild(document.createTextNode(text.substring(lastIdx)));
							}
							if (textNode.parentNode) {
								textNode.parentNode.replaceChild(frag, textNode);
							}
						}
					}
				}

				function scan() {
					var container = document.getElementById('customize-theme-controls') || document.getElementById('customize-controls') || document.body;
					wrapEmojisInTree(container);
				}

				// Execute scan immediately
				scan();

				// Execute scan on document ready
				if (document.readyState === 'loading') {
					document.addEventListener('DOMContentLoaded', scan);
				} else {
					scan();
				}

				// Execute scan via WordPress Customizer ready lifecycle
				if (window.wp && window.wp.customize) {
					window.wp.customize(scan);
				}

				// Observe dynamic Customizer accordion insertions and panel expansions
				if (window.MutationObserver) {
					var scheduled = false;
					var observer = new MutationObserver(function() {
						if (!scheduled) {
							scheduled = true;
							requestAnimationFrame(function() {
								scheduled = false;
								scan();
							});
						}
					});
					var targetContainer = document.getElementById('customize-controls') || document.body;
					observer.observe(targetContainer, { childList: true, subtree: true });
				}
			})();
		});
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'xophz_magic_hat_customize_controls_scripts' );
