/**
 * Magic Hat Customizer Preview - AI Live Canvas Receiver
 *
 * Listens for live page generation events from the Customizer AI Page Architect
 * and injects generated block trees with smooth quantum transitions.
 *
 * @package Xophz_Magic_Hat
 */

(function($) {
	'use strict';

	if (typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	// Listen for generated page block payload from Customizer pane
	wp.customize.bind('mh-ai-page-rendered', function(data) {
		if (!data || !data.blocks_html) {
			return;
		}

		// Target the active main content container
		var targets = [
			'main#mw-content',
			'main.wp-block-group',
			'main',
			'.entry-content',
			'.wp-site-blocks > main',
			'#content',
			'body'
		];

		var $container = null;
		for (var i = 0; i < targets.length; i++) {
			var $found = $(targets[i]);
			if ($found.length) {
				$container = $found;
				break;
			}
		}

		if (!$container || !$container.length) {
			return;
		}

		// Inject quantum flash overlay
		var $glowOverlay = $(
			'<div class="mh-ai-flash" style="' +
			'position: fixed; inset: 0; pointer-events: none; z-index: 999999; ' +
			'background: radial-gradient(circle at center, rgba(98, 201, 255, 0.25) 0%, transparent 70%); ' +
			'opacity: 0; transition: opacity 0.4s ease-out;"></div>'
		);
		$('body').append($glowOverlay);

		// Animate pulse
		setTimeout(function() {
			$glowOverlay.css('opacity', '1');
		}, 20);

		setTimeout(function() {
			// Update DOM
			$container.html(data.blocks_html);

			// Add subtle entry animation
			$container.css({
				'animation': 'mhFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards'
			});

			// Fade out glow
			$glowOverlay.css('opacity', '0');
			setTimeout(function() {
				$glowOverlay.remove();
			}, 400);

			// Trigger window resize so responsive blocks recalculate
			window.dispatchEvent(new Event('resize'));
		}, 250);
	});

	// Inject keyframes
	var style = document.createElement('style');
	style.innerHTML = '@keyframes mhFadeUp { from { opacity: 0.4; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }';
	document.head.appendChild(style);

})(jQuery);
