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
	var lastBlocks = '';

	function getConnectors() {
		if (window.mhAiConnectors && typeof window.mhAiConnectors === 'object') {
			return window.mhAiConnectors;
		}
		if (window.mhAiSettings && window.mhAiSettings.connectors && typeof window.mhAiSettings.connectors === 'object') {
			return window.mhAiSettings.connectors;
		}
		return {};
	}

	function populateModelsForConnector(connectorId) {
		var connectors = getConnectors();
		var info = connectors[connectorId];
		var $modelSelect = $('#mh-ai-model');
		var $statusBadge = $('#mh-ai-status-badge');

		if (info && info.models && info.models.length) {
			$modelSelect.empty();
			$.each(info.models, function(_, m) {
				$modelSelect.append($('<option>', {
					value: m.id,
					text: m.name
				}));
			});
			if (info.default_model) {
				$modelSelect.val(info.default_model);
			}
		}

		// Update header status badge
		if ($statusBadge.length) {
			if (info && info.configured) {
				var shortName = info.name.replace(/ AI.*/, '').replace(/ Runner.*/, '').replace(/ Proxy.*/, '');
				$statusBadge.css({ background: 'rgba(16, 185, 129, 0.2)', color: '#10b981' }).text(shortName + ' Active');
			} else if (connectorId === 'procedural') {
				$statusBadge.css({ background: 'rgba(98, 201, 255, 0.15)', color: '#62c9ff' }).text('Synthesizer Active');
			} else {
				$statusBadge.css({ background: 'rgba(239, 68, 68, 0.15)', color: '#ef4444' }).text('Setup Required');
			}
		}
	}

	function updateHistoryUI() {
		var $historyList = $('#mh-ai-history-list');
		if (!$historyList.length) {
			return;
		}
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

	function triggerGeneration() {
		var $promptInput     = $('#mh-ai-prompt');
		var $vibeSelect      = $('#mh-ai-vibe');
		var $archSelect      = $('#mh-ai-archetype');
		var $targetSelect    = $('#mh-ai-target-page');
		var $connectorSelect = $('#mh-ai-connector');
		var $modelSelect     = $('#mh-ai-model');
		var $conjureBtn      = $('#mh-ai-conjure-btn');
		var $statusBox       = $('#mh-ai-status');

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

		var nonce = (window.mhAiNonce) ? window.mhAiNonce : ((window.mhAiSettings && window.mhAiSettings.nonce) ? window.mhAiSettings.nonce : ((window.wpApiSettings && window.wpApiSettings.nonce) ? window.wpApiSettings.nonce : ''));
		var restBase = (window.mhAiRestUrl) ? window.mhAiRestUrl : ((window.mhAiSettings && window.mhAiSettings.restUrl) ? window.mhAiSettings.restUrl : '/wp-json/');
		var restUrl = restBase + (restBase.slice(-1) === '/' ? '' : '/') + 'xophz/v1/magic-hat/ai-generate';

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
				action: 'generate_only',
				connector: $connectorSelect.val() || 'gemini',
				model: $modelSelect.val() || ''
			}),
			success: function(res) {
				clearInterval(phraseTimer);
				$conjureBtn.prop('disabled', false).removeClass('loading');

				if (res && res.blocks_html) {
					lastBlocks = res.blocks_html;
					var engineLabel = res.source ? res.source : 'AI Engine';
					$statusBox.html('<span class="dashicons dashicons-yes-alt" style="color: #62c9ff; margin-right: 6px;"></span> Page conjured via ' + engineLabel);

					if (targetPage > 0) {
						$('#mh-ai-save-to-page').show();
					}

					// Add to history
					historyStack.unshift({
						time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
						prompt: promptText || (archetype + ' (' + vibe + ')'),
						blocks_html: res.blocks_html,
						vibe: res.vibe,
						archetype: res.archetype
					});
					updateHistoryUI();

					// Send to preview iframe
					if (window.wp && wp.customize && wp.customize.previewer) {
						wp.customize.previewer.send('mh-ai-page-rendered', {
							blocks_html: res.blocks_html,
							vibe: res.vibe,
							archetype: res.archetype
						});

						if (wp.customize('mh_ai_generated_blocks')) {
							wp.customize('mh_ai_generated_blocks').set(res.blocks_html);
						}
					}
				} else {
					$statusBox.html('<span class="dashicons dashicons-warning" style="color: #ff3366; margin-right: 6px;"></span> Generation returned empty structure.');
				}
			},
			error: function(xhr, status, error) {
				clearInterval(phraseTimer);
				$conjureBtn.prop('disabled', false).removeClass('loading');
				var msg = 'Synthesis failed. Please check connector keys.';
				if (xhr.responseJSON && xhr.responseJSON.message) {
					msg = xhr.responseJSON.message;
				}
				$statusBox.html('<span class="dashicons dashicons-warning" style="color: #ff3366; margin-right: 6px;"></span> ' + msg);
			}
		});
	}

	// Delegated Event Handlers (Global on document)

	// Vibe quick buttons
	$(document).on('click', '.mh-vibe-pill', function(e) {
		e.preventDefault();
		$('.mh-vibe-pill').removeClass('active');
		$(this).addClass('active');
		var vibe = $(this).data('vibe');
		$('#mh-ai-vibe').val(vibe).trigger('change');
	});

	// Archetype quick buttons
	$(document).on('click', '.mh-arch-pill', function(e) {
		e.preventDefault();
		$('.mh-arch-pill').removeClass('active');
		$(this).addClass('active');
		var arch = $(this).data('arch');
		$('#mh-ai-archetype').val(arch).trigger('change');
	});

	// Sync pills when select dropdown changes
	$(document).on('change', '#mh-ai-vibe', function() {
		var val = $(this).val();
		$('.mh-vibe-pill').removeClass('active').filter('[data-vibe="' + val + '"]').addClass('active');
	});

	$(document).on('change', '#mh-ai-archetype', function() {
		var val = $(this).val();
		$('.mh-arch-pill').removeClass('active').filter('[data-arch="' + val + '"]').addClass('active');
	});

	// Connector change
	$(document).on('change', '#mh-ai-connector', function() {
		populateModelsForConnector($(this).val());
	});

	// Preset template quick builders
	$(document).on('click', '.mh-preset-btn', function(e) {
		e.preventDefault();
		var presetPrompt = $(this).data('prompt') || '';
		var presetVibe   = $(this).data('vibe') || 'starship-neon';
		var presetArch   = $(this).data('arch') || 'landing';

		$('#mh-ai-prompt').val(presetPrompt);
		$('#mh-ai-vibe').val(presetVibe).trigger('change');
		$('#mh-ai-archetype').val(presetArch).trigger('change');

		triggerGeneration();
	});

	// Conjure button click
	$(document).on('click', '#mh-ai-conjure-btn', function(e) {
		e.preventDefault();
		triggerGeneration();
	});

	// Direct database save button
	$(document).on('click', '#mh-ai-save-to-page', function(e) {
		e.preventDefault();
		var pageId = parseInt($('#mh-ai-target-page').val(), 10) || 0;
		if (!pageId || !lastBlocks) {
			alert('Please select a target page and generate content first.');
			return;
		}

		var $btn = $(this);
		$btn.prop('disabled', true).text('Saving to Page...');

		var nonce = (window.mhAiNonce) ? window.mhAiNonce : ((window.mhAiSettings && window.mhAiSettings.nonce) ? window.mhAiSettings.nonce : ((window.wpApiSettings && window.wpApiSettings.nonce) ? window.wpApiSettings.nonce : ''));
		var restBase = (window.mhAiRestUrl) ? window.mhAiRestUrl : ((window.mhAiSettings && window.mhAiSettings.restUrl) ? window.mhAiSettings.restUrl : '/wp-json/');
		var restUrl = restBase + (restBase.slice(-1) === '/' ? '' : '/') + 'xophz/v1/ai/save-page';

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
				page_id: pageId,
				content: lastBlocks
			}),
			success: function() {
				$btn.prop('disabled', false).text('Saved Successfully!');
				setTimeout(function() {
					$btn.text('Save to Database');
				}, 2500);
			},
			error: function(err) {
				$btn.prop('disabled', false).text('Save Failed');
				console.error('Error saving page:', err);
			}
		});
	});

	// Restore from history
	$(document).on('click', '.mh-history-item', function(e) {
		e.preventDefault();
		var idx = $(this).data('index');
		if (typeof historyStack[idx] !== 'undefined') {
			var item = historyStack[idx];
			lastBlocks = item.blocks_html;

			if (window.wp && wp.customize && wp.customize.previewer) {
				wp.customize.previewer.send('mh-ai-page-rendered', {
					blocks_html: item.blocks_html,
					vibe: item.vibe,
					archetype: item.archetype
				});
			}

			$('#mh-ai-status').html('<span class="dashicons dashicons-undo" style="color: #62c9ff; margin-right: 6px;"></span> Restored layout from ' + item.time);
		}
	});

	// Boot & Lifecycle Synchronization
	function bootAIArchitect() {
		var $connector = $('#mh-ai-connector');
		if ($connector.length) {
			populateModelsForConnector($connector.val());
		}
	}

	$(document).ready(function() {
		bootAIArchitect();
		if (typeof wp !== 'undefined' && wp.customize) {
			wp.customize.bind('ready', bootAIArchitect);
			if (wp.customize.section) {
				wp.customize.section('mh_ai_page_architect', function(section) {
					section.expanded.bind(function(isExpanded) {
						if (isExpanded) {
							setTimeout(bootAIArchitect, 50);
						}
					});
				});
			}
		}
	});

	// Immediate run in case DOM is already parsed
	bootAIArchitect();

})(jQuery);
