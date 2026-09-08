/**
 * Magic Hat Customizer Preview - AI Live Canvas Receiver
 *
 * Listens for live page generation events from the Customizer AI Page Architect
 * and injects generated block trees into #mw-front-content with smooth transitions.
 *
 * @package Xophz_Magic_Hat
 */

(function($) {
	'use strict';

	// Target candidate selectors for main canvas injection (prioritize post-content container)
	var targets = [
		'main.mh-front-page-main .entry-content',
		'.entry-content.wp-block-post-content',
		'.entry-content',
		'main.mh-front-page-main',
		'#mw-front-content',
		'main#mw-content',
		'main.wp-block-group',
		'main',
		'.wp-site-blocks > main',
		'#content',
		'body'
	];

	function getTargetContainer() {
		for (var i = 0; i < targets.length; i++) {
			var $found = $(targets[i]);
			if ($found.length) {
				return $found;
			}
		}
		return null;
	}

	function updatePreviewHud(statusText, isSyncing) {
		var $label = $('#mh-ai-hud-label');
		var $pulse = $('#mh-ai-hud-pulse');
		if ($label.length) {
			$label.text(statusText);
		}
		if ($pulse.length) {
			if (isSyncing) {
				$pulse.css({ background: '#62c9ff', 'box-shadow': '0 0 12px #62c9ff' });
			} else {
				$pulse.css({ background: '#10b981', 'box-shadow': '0 0 8px #10b981' });
			}
		}
	}

	function handleAiBlocksInjection(data) {
		if (!data) {
			return;
		}

		var htmlContent = data.rendered_html || data.blocks_html || '';
		if (!htmlContent) {
			return;
		}

		var $container = getTargetContainer();
		if (!$container || !$container.length) {
			return;
		}

		updatePreviewHud('🪄 Synchronizing blocks...', true);

		// Inject quantum flash overlay
		var $glowOverlay = $(
			'<div class="mh-ai-flash" style="' +
			'position: fixed; inset: 0; pointer-events: none; z-index: 999999; ' +
			'background: radial-gradient(circle at center, rgba(98, 201, 255, 0.28) 0%, transparent 70%); ' +
			'opacity: 0; transition: opacity 0.35s ease-out;"></div>'
		);
		$('body').append($glowOverlay);

		setTimeout(function() {
			$glowOverlay.css('opacity', '1');
		}, 20);

		setTimeout(function() {
			// Update DOM container
			$container.html(htmlContent);

			// Subtle quantum entry animation
			$container.css({
				'animation': 'mhFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards'
			});

			// Fade out glow overlay
			$glowOverlay.css('opacity', '0');
			setTimeout(function() {
				$glowOverlay.remove();
			}, 350);

			// Update HUD back to ready state
			updatePreviewHud('🪄 AI Studio: Live Synced', false);

			// Trigger window resize so layout recalculates
			window.dispatchEvent(new Event('resize'));

			// Smooth scroll to top of content canvas
			var topOffset = $container.offset() ? $container.offset().top : 0;
			if (topOffset > 0 && Math.abs($(window).scrollTop() - topOffset) > 200) {
				$('html, body').animate({ scrollTop: Math.max(0, topOffset - 60) }, 300);
			}
		}, 200);
	}

	// 1. Direct window method for parent controls invocation
	window.mhInjectAiBlocks = function(html, meta) {
		handleAiBlocksInjection({
			rendered_html: html,
			meta: meta
		});
	};

	// 2. WordPress Customizer postMessage messenger listener
	function initPreviewReceiver() {
		if (typeof wp !== 'undefined' && wp.customize) {
			// Listen to Customizer Messenger
			if (wp.customize.preview) {
				wp.customize.preview.bind('mh-ai-page-rendered', function(data) {
					handleAiBlocksInjection(data);
				});
			}

			// Listen to Setting change
			wp.customize('mh_ai_generated_blocks', function(value) {
				value.bind(function(newBlocks) {
					if (newBlocks) {
						handleAiBlocksInjection({ blocks_html: newBlocks });
					}
				});
			});
		}
	}

	// 3. Fallback standard HTML5 window postMessage receiver
	window.addEventListener('message', function(event) {
		if (!event.data) {
			return;
		}
		if (event.data.action === 'mh-ai-page-rendered' || event.data.type === 'mh-ai-page-rendered') {
			handleAiBlocksInjection(event.data);
		}
	});

	// Inject keyframes and floating HUD
	var style = document.createElement('style');
	style.innerHTML = '@keyframes mhFadeUp { from { opacity: 0.3; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }';
	document.head.appendChild(style);

	// Floating AI Studio HUD badge inside preview
	$(document).ready(function() {
		if ($('#mh-ai-preview-hud').length === 0) {
			var hudHtml =
				'<div id="mh-ai-preview-hud" style="position: fixed; bottom: 16px; right: 16px; z-index: 99998; font-family: -apple-system, BlinkMacSystemFont, sans-serif; pointer-events: auto;">' +
					'<div style="background: rgba(11, 15, 25, 0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(98, 201, 255, 0.35); border-radius: 9999px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 7px; box-shadow: 0 4px 16px rgba(0,0,0,0.45);">' +
						'<span id="mh-ai-hud-pulse" style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981; transition: all 0.2s ease;"></span>' +
						'<span id="mh-ai-hud-label" style="font-size: 11px; font-weight: 700; color: #f8fafc; letter-spacing: 0.3px;">🪄 AI Studio: Live Synced</span>' +
						'<button type="button" id="mh-ai-hud-focus-btn" style="background: rgba(98, 201, 255, 0.15); border: 1px solid rgba(98, 201, 255, 0.4); border-radius: 9999px; color: #62c9ff; font-size: 10px; font-weight: 700; padding: 1px 7px; cursor: pointer; transition: all 0.15s ease;">Focus</button>' +
					'</div>' +
				'</div>';
			$('body').append(hudHtml);

			$(document).on('click', '#mh-ai-hud-focus-btn', function(e) {
				e.preventDefault();
				if (window.parent && window.parent.wp && window.parent.wp.customize) {
					if (window.parent.wp.customize.section && window.parent.wp.customize.section('mh_ai_page_architect')) {
						window.parent.wp.customize.section('mh_ai_page_architect').focus();
					}
				}
			});
		}
	});

	// Attach listener
	if (typeof wp !== 'undefined' && wp.customize) {
		if (wp.customize.bind) {
			wp.customize.bind('preview-ready', initPreviewReceiver);
			wp.customize.bind('ready', initPreviewReceiver);
		}
		initPreviewReceiver();
	}

})(jQuery);
