/**
 * Magic Hat AI Page Architect - Customizer Controls Controller
 *
 * Handles natural language prompt dispatching, vibe and archetype selection,
 * progress phase animations, and live Customizer preview synchronization.
 *
 * @package Xophz_Magic_Hat
 */

(function($) {
	'use strict';

	var phrases = [
		'Calibrating circadian solar curve...',
		'Scanning prompt coordinates...',
		'Assembling semantic design tokens...',
		'Synthesizing Gutenberg block matrix...',
		'Aligning glassmorphic surfaces...',
		'Rendering layout to canvas...'
	];

	var historyStack = [];

	function initAIArchitect() {
		var $root = $('.mh-ai-architect-container');
		if (!$root.length) {
			return;
		}

		var $promptInput = $('#mh-ai-prompt');
		var $vibeSelect  = $('#mh-ai-vibe');
		var $archSelect  = $('#mh-ai-archetype');
		var $targetSelect= $('#mh-ai-target-page');
		var $conjureBtn  = $('#mh-ai-conjure-btn');
		var $statusBox   = $('#mh-ai-status');
		var $historyList = $('#mh-ai-history-list');
		var $saveBtn     = $('#mh-ai-save-to-page');
		var lastBlocks   = '';

		// Vibe quick buttons
		$root.on('click', '.mh-vibe-pill', function(e) {
			e.preventDefault();
			$('.mh-vibe-pill').removeClass('active');
			$(this).addClass('active');
			$vibeSelect.val($(this).data('vibe'));
		});

		// Archetype quick buttons
		$root.on('click', '.mh-arch-pill', function(e) {
			e.preventDefault();
			$('.mh-arch-pill').removeClass('active');
			$(this).addClass('active');
			$archSelect.val($(this).data('arch'));
		});

		// Preset template quick builders
		$root.on('click', '.mh-preset-btn', function(e) {
			e.preventDefault();
			var presetPrompt = $(this).data('prompt') || '';
			var presetVibe   = $(this).data('vibe') || 'starship-neon';
			var presetArch   = $(this).data('arch') || 'landing';

			$promptInput.val(presetPrompt);
			$vibeSelect.val(presetVibe);
			$archSelect.val(presetArch);

			$('.mh-vibe-pill').removeClass('active').filter('[data-vibe="' + presetVibe + '"]').addClass('active');
			$('.mh-arch-pill').removeClass('active').filter('[data-arch="' + presetArch + '"]').addClass('active');

			triggerGeneration();
		});

		// Conjure button click
		$conjureBtn.on('click', function(e) {
			e.preventDefault();
			triggerGeneration();
		});

		// Direct database save button
		$saveBtn.on('click', function(e) {
			e.preventDefault();
			var pageId = parseInt($targetSelect.val(), 10) || 0;
			if (!pageId || !lastBlocks) {
				alert('Please select a target page and generate content first.');
				return;
			}

			$saveBtn.prop('disabled', true).text('Saving to Page...');

			$.ajax({
				url: '/wp-json/xophz/v1/ai/save-page',
				method: 'POST',
				beforeSend: function(xhr) {
					if (window.wpApiSettings && window.wpApiSettings.nonce) {
						xhr.setRequestHeader('X-WP-Nonce', window.wpApiSettings.nonce);
					}
				},
				contentType: 'application/json',
				data: JSON.stringify({
					page_id: pageId,
					content: lastBlocks
				}),
				success: function(res) {
					$saveBtn.prop('disabled', false).text('Saved Successfully!');
					setTimeout(function() {
						$saveBtn.text('Save to Database');
					}, 2500);
				},
				error: function(err) {
					$saveBtn.prop('disabled', false).text('Save Failed');
					console.error('Error saving page:', err);
				}
			});
		});

		function triggerGeneration() {
			var promptText = $.trim($promptInput.val());
			var vibe       = $vibeSelect.val() || 'starship-neon';
			var archetype  = $archSelect.val() || 'landing';
			var targetPage = parseInt($targetSelect.val(), 10) || 0;

			$conjureBtn.prop('disabled', true).addClass('loading');
			$statusBox.show().html('<span class="dashicons dashicons-update-alt spin" style="margin-right: 6px;"></span> ' + phrases[0]);

			var phraseIdx = 0;
			var phraseTimer = setInterval(function() {
				phraseIdx = (phraseIdx + 1) % phrases.length;
				$statusBox.html('<span class="dashicons dashicons-update-alt spin" style="margin-right: 6px;"></span> ' + phrases[phraseIdx]);
			}, 2200);

			var nonce = (window.mhAiSettings && window.mhAiSettings.nonce) ? window.mhAiSettings.nonce : ((window.wpApiSettings && window.wpApiSettings.nonce) ? window.wpApiSettings.nonce : '');
			var restUrl = (window.mhAiSettings && window.mhAiSettings.restUrl) ? (window.mhAiSettings.restUrl + 'xophz/v1/magic-hat/ai-generate') : '/wp-json/xophz/v1/magic-hat/ai-generate';

			$.ajax({
				url: restUrl,
				method: 'POST',
				beforeSend: function(xhr) {
					if (nonce) {
						xhr.setRequestHeader('X-WP-Nonce', nonce);
					}
				},
				contentType: 'application/json',
				data: JSON.stringify({
					prompt: promptText,
					vibe: vibe,
					archetype: archetype,
					target_page_id: targetPage,
					action: 'generate_only'
				}),
				success: function(res) {
					clearInterval(phraseTimer);
					$conjureBtn.prop('disabled', false).removeClass('loading');

					if (res && res.blocks_html) {
						lastBlocks = res.blocks_html;
						$statusBox.html('<span class="dashicons dashicons-yes-alt" style="color: #62c9ff; margin-right: 6px;"></span> Page conjured via ' + (res.source === 'gemini-api' ? 'Gemini 2.5 Flash' : 'Synthesizer Engine'));

						// Send to preview iframe
						if (wp && wp.customize && wp.customize.previewer) {
							wp.customize.previewer.send('mh-ai-page-rendered', {
								blocks_html: res.blocks_html,
								vibe: res.vibe,
								archetype: res.archetype
							});

							// Set customizer setting if registered
							if (wp.customize('mh_ai_generated_blocks')) {
								wp.customize('mh_ai_generated_blocks').set(res.blocks_html);
							}
						}

						// Enable Save button
						$saveBtn.show();

						// Add to history
						var historyItem = {
							time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
							prompt: promptText || (archetype + ' (' + vibe + ')'),
							blocks_html: res.blocks_html,
							vibe: res.vibe,
							archetype: res.archetype
						};
						historyStack.unshift(historyItem);
						updateHistoryUI();
					} else {
						$statusBox.html('<span class="dashicons dashicons-warning" style="color: #ff3366; margin-right: 6px;"></span> Generation returned empty structure.');
					}
				},
				error: function(xhr, status, error) {
					clearInterval(phraseTimer);
					$conjureBtn.prop('disabled', false).removeClass('loading');
					$statusBox.html('<span class="dashicons dashicons-warning" style="color: #ff3366; margin-right: 6px;"></span> Generation error: ' + error);
				}
			});
		}

		function updateHistoryUI() {
			if (!historyStack.length) {
				$historyList.html('<li style="color: #8c8f94; font-size: 11px; padding: 6px 0;">No layouts conjured in this session.</li>');
				return;
			}

			var html = '';
			for (var i = 0; i < Math.min(historyStack.length, 5); i++) {
				var item = historyStack[i];
				html += '<li class="mh-history-item" data-index="' + i + '" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; margin-bottom: 6px; cursor: pointer; font-size: 11px;">';
				html += '<span style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 75%;">' + $('<div>').text(item.prompt).html() + '</span>';
				html += '<span style="color: #94a3b8; font-size: 10px;">' + item.time + '</span>';
				html += '</li>';
			}
			$historyList.html(html);
		}

		// Restore from history
		$root.on('click', '.mh-history-item', function(e) {
			e.preventDefault();
			var idx = $(this).data('index');
			if (typeof historyStack[idx] !== 'undefined') {
				var item = historyStack[idx];
				lastBlocks = item.blocks_html;

				if (wp && wp.customize && wp.customize.previewer) {
					wp.customize.previewer.send('mh-ai-page-rendered', {
						blocks_html: item.blocks_html,
						vibe: item.vibe,
						archetype: item.archetype
					});
				}

				$statusBox.html('<span class="dashicons dashicons-undo" style="color: #62c9ff; margin-right: 6px;"></span> Restored layout from ' + item.time);
			}
		});
	}

	$(document).ready(function() {
		// Native Customizer ready event
		if (typeof wp !== 'undefined' && wp.customize) {
			wp.customize.bind('ready', function() {
				initAIArchitect();
			});
		} else {
			initAIArchitect();
		}
	});

})(jQuery);
