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

	var chatTurns  = [];
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
		var connectors   = getConnectors();
		var info         = connectors[connectorId];
		var $modelSelect = $('#mh-ai-model');
		var $statusBadge = $('#mh-ai-status-badge');
		var $pillBadge   = $('#mh-ai-active-model-indicator');

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

		var activeModelName = $modelSelect.find('option:selected').text().replace(/\s*\(.*\)/, '').trim() || 'Gemini 3.5 Flash';
		if ($pillBadge.length) {
			$pillBadge.text(activeModelName);
		}

		if ($statusBadge.length) {
			if (info && info.configured) {
				var shortName = info.name.replace(/ AI.*/, '').replace(/ Runner.*/, '').replace(/ Proxy.*/, '');
				$statusBadge.css({ background: 'rgba(16, 185, 129, 0.18)', color: '#10b981' }).text(shortName + ' Active');
			} else if (connectorId === 'procedural') {
				$statusBadge.css({ background: 'rgba(98, 201, 255, 0.15)', color: '#62c9ff' }).text('Synthesizer Active');
			} else {
				$statusBadge.css({ background: 'rgba(239, 68, 68, 0.15)', color: '#ef4444' }).text('Setup Required');
			}
		}
	}

	function scrollToChatBottom() {
		var $thread = $('.mh-ai-chat-thread-container');
		if ($thread.length) {
			$thread.stop().animate({ scrollTop: $thread[0].scrollHeight }, 250);
		}
	}

	function appendUserBubble(text) {
		var time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
		var html = '';
		html += '<div class="mh-ai-msg mh-ai-msg-user">';
		html += '  <div class="mh-ai-msg-header">';
		html += '    <span class="mh-ai-msg-name" style="color: #62c9ff;">You</span>';
		html += '    <span class="mh-ai-msg-time">' + time + '</span>';
		html += '  </div>';
		html += '  <div class="mh-ai-msg-body"><p>' + $('<div>').text(text).html() + '</p></div>';
		html += '</div>';

		$('#mh-ai-chat-thread').append(html);
		scrollToChatBottom();
	}

	function appendAssistantBubble(res, turnIdx) {
		var time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
		var html = '';
		html += '<div class="mh-ai-msg mh-ai-msg-assistant" data-turn-index="' + turnIdx + '">';
		html += '  <div class="mh-ai-msg-header">';
		html += '    <span class="mh-ai-msg-avatar">🪄</span>';
		html += '    <span class="mh-ai-msg-name">Architect</span>';
		html += '    <span class="mh-ai-badge" style="margin-left: 4px;">' + $('<div>').text(res.source || 'AI Engine').html() + '</span>';
		html += '    <span class="mh-ai-msg-time">' + time + '</span>';
		html += '  </div>';
		html += '  <div class="mh-ai-msg-body">';

		// Conversational Summary
		var summary = res.summary || 'Constructed Gutenberg block architecture seamlessly.';
		html += '    <p>' + $('<div>').text(summary).html() + '</p>';

		// Zero-Token Thought Process Accordion
		if (res.thought_stream && res.thought_stream.length) {
			html += '    <details class="mh-ai-thought-box" open>';
			html += '      <summary>🧠 AI Thought Process (' + res.thought_stream.length + ' phases) <span style="float: right; color: #10b981; font-weight: 600;">0 Extra Tokens</span></summary>';
			html += '      <div class="mh-ai-thought-steps">';
			$.each(res.thought_stream, function(_, t) {
				html += '        <div class="mh-ai-thought-step">';
				html += '          <span class="mh-ai-thought-step-name">[' + t.step + '] ' + $('<div>').text(t.phase).html() + ':</span> ';
				html += '          <span class="mh-ai-thought-step-desc">' + $('<div>').text(t.detail).html() + '</span>';
				html += '        </div>';
			});
			html += '      </div>';
			html += '    </details>';
		}

		// Quick Action Chips
		html += '    <div class="mh-ai-msg-actions">';
		html += '      <button type="button" class="mh-ai-action-chip mh-ai-revert-btn" data-turn="' + turnIdx + '">⏮️ Revert to this</button>';
		html += '      <button type="button" class="mh-ai-action-chip mh-ai-copy-btn" data-turn="' + turnIdx + '">📋 Copy Blocks</button>';
		html += '      <span style="font-size: 9px; color: #10b981; margin-left: auto; display: inline-flex; align-items: center; gap: 3px;">⚡ Live Synced</span>';
		html += '    </div>';

		html += '  </div>';
		html += '</div>';

		$('#mh-ai-chat-thread').append(html);
		scrollToChatBottom();
	}

	function showThinkingBubble(promptText) {
		var html = '';
		html += '<div id="mh-ai-thinking-indicator" class="mh-ai-msg mh-ai-msg-assistant" style="opacity: 0.85;">';
		html += '  <div class="mh-ai-msg-header">';
		html += '    <span class="mh-ai-msg-avatar"><span class="dashicons dashicons-update-alt spin" style="font-size: 13px; width: 13px; height: 13px; color: #62c9ff;"></span></span>';
		html += '    <span class="mh-ai-msg-name">Architect Thinking</span>';
		html += '  </div>';
		html += '  <div class="mh-ai-msg-body"><p style="font-size: 11px; color: #94a3b8;">Calibrating circadian curve and synthesizing blocks for <em>"' + $('<div>').text(promptText).html() + '"</em>...</p></div>';
		html += '</div>';

		$('#mh-ai-chat-thread').append(html);
		scrollToChatBottom();
	}

	function removeThinkingBubble() {
		$('#mh-ai-thinking-indicator').remove();
	}

	function syncToLivePreview(htmlToInject, blocksHtml, res) {
		var payload = {
			rendered_html: htmlToInject,
			blocks_html: blocksHtml,
			vibe: res.vibe || 'starship-neon',
			archetype: res.archetype || 'landing',
			source: res.source || 'AI Engine',
			thought_stream: res.thought_stream || []
		};

		// 1. WordPress Customizer Messenger
		if (window.wp && wp.customize && wp.customize.previewer) {
			wp.customize.previewer.send('mh-ai-page-rendered', payload);

			if (wp.customize('mh_ai_generated_blocks')) {
				wp.customize('mh_ai_generated_blocks').set(blocksHtml);
			}
		}

		// 2. Direct DOM insertion into preview iframe (same origin)
		try {
			var previewIframe = $('#customize-preview iframe')[0];
			if (previewIframe && previewIframe.contentWindow) {
				if (typeof previewIframe.contentWindow.mhInjectAiBlocks === 'function') {
					previewIframe.contentWindow.mhInjectAiBlocks(htmlToInject, payload);
				}
				previewIframe.contentWindow.postMessage({
					action: 'mh-ai-page-rendered',
					rendered_html: htmlToInject,
					blocks_html: blocksHtml,
					vibe: res.vibe,
					archetype: res.archetype
				}, '*');
			}

			var $iframe = $('#customize-preview iframe');
			if ($iframe.length) {
				var iframeDoc = $iframe.contents();
				var $targetCont = iframeDoc.find('.entry-content, main.mh-front-page-main, #mw-front-content, main#mw-content, main');
				if ($targetCont.length) {
					$targetCont.first().html(htmlToInject);
					$targetCont.find('.mh-add-section-preview').remove();
				}
			}
		} catch (e) {
			// Cross origin fallback handled by postMessage
		}
	}

	function triggerGeneration(overridePrompt) {
		var $promptInput     = $('#mh-ai-prompt');
		var $vibeSelect      = $('#mh-ai-vibe');
		var $archSelect      = $('#mh-ai-archetype');
		var $targetSelect    = $('#mh-ai-target-page');
		var $connectorSelect = $('#mh-ai-connector');
		var $modelSelect     = $('#mh-ai-model');
		var $conjureBtn      = $('#mh-ai-conjure-btn');

		var promptText = typeof overridePrompt === 'string' ? overridePrompt : $.trim($promptInput.val());
		if (!promptText && !lastBlocks) {
			promptText = 'Build an autonomous SaaS launch page with hero section, 3-card feature matrix, interactive metrics, and conversion banner';
		}

		var vibe       = $vibeSelect.val() || 'starship-neon';
		var archetype  = $archSelect.val() || 'landing';
		var targetPage = parseInt($targetSelect.val(), 10) || 0;
		var autoSave   = $('#mh-ai-auto-save').length ? $('#mh-ai-auto-save').is(':checked') : (targetPage > 0);
		var action     = (targetPage > 0 && autoSave) ? 'apply_to_page' : 'generate_only';

		// Clear input and focus
		$promptInput.val('');
		appendUserBubble(promptText);
		showThinkingBubble(promptText);

		$conjureBtn.prop('disabled', true).addClass('loading');
		$('#mh-ai-conjure-icon').addClass('dashicons-update-alt spin').removeClass('dashicons-admin-customizer');

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
				action: action,
				connector: $connectorSelect.val() || 'gemini',
				model: $modelSelect.val() || '',
				current_blocks: lastBlocks
			}),
			success: function(res) {
				removeThinkingBubble();
				$conjureBtn.prop('disabled', false).removeClass('loading');
				$('#mh-ai-conjure-icon').removeClass('dashicons-update-alt spin');

				if (res && (res.blocks_html || res.rendered_html)) {
					lastBlocks = res.blocks_html;

					var turnObj = {
						role: 'assistant',
						prompt: promptText,
						blocks_html: res.blocks_html,
						rendered_html: res.rendered_html || res.blocks_html,
						vibe: res.vibe,
						archetype: res.archetype,
						source: res.source,
						thought_stream: res.thought_stream,
						summary: res.summary
					};

					var turnIdx = chatTurns.length;
					chatTurns.push(turnObj);

					appendAssistantBubble(res, turnIdx);

					var htmlToInject = res.rendered_html || res.blocks_html;
					syncToLivePreview(htmlToInject, res.blocks_html, res);

					if (targetPage > 0) {
						$('#mh-ai-save-to-page').show();
					}
				} else {
					var errHtml = '<div class="mh-ai-msg mh-ai-msg-assistant" style="border-color: #ef4444;"><p style="color: #ef4444;">Generation returned an empty response. Please try another prompt or model.</p></div>';
					$('#mh-ai-chat-thread').append(errHtml);
					scrollToChatBottom();
				}
			},
			error: function(xhr) {
				removeThinkingBubble();
				$conjureBtn.prop('disabled', false).removeClass('loading');
				$('#mh-ai-conjure-icon').removeClass('dashicons-update-alt spin');

				var msg = 'Synthesis failed. Please verify API keys or rate limits.';
				if (xhr.responseJSON && xhr.responseJSON.message) {
					msg = xhr.responseJSON.message;
				}

				var errHtml = '<div class="mh-ai-msg mh-ai-msg-assistant" style="border-color: #ef4444;"><p style="color: #ef4444;">' + $('<div>').text(msg).html() + '</p></div>';
				$('#mh-ai-chat-thread').append(errHtml);
				scrollToChatBottom();
			}
		});
	}

	// Delegated Event Handlers

	// Toggle Wide Studio Mode
	$(document).on('click', '#mh-ai-toggle-studio-width', function(e) {
		e.preventDefault();
		$('body').toggleClass('mh-ai-studio-wide');
		var isWide = $('body').hasClass('mh-ai-studio-wide');
		$(this).text(isWide ? '⇄ Collapse' : '⇄ Expand');
		setTimeout(function() {
			window.dispatchEvent(new Event('resize'));
		}, 300);
	});

	// Toggle Settings Drawer
	$(document).on('click', '#mh-ai-toggle-settings', function(e) {
		e.preventDefault();
		$('#mh-ai-settings-drawer').slideToggle(180);
	});

	// Connector and Model changes
	$(document).on('change', '#mh-ai-connector', function() {
		populateModelsForConnector($(this).val());
	});

	$(document).on('change', '#mh-ai-model', function() {
		var activeName = $(this).find('option:selected').text().replace(/\s*\(.*\)/, '').trim();
		$('#mh-ai-active-model-indicator').text(activeName);
	});

	// Starter Blueprint Chips
	$(document).on('click', '.mh-ai-chip-btn', function(e) {
		e.preventDefault();
		var prompt = $(this).data('prompt');
		var vibe   = $(this).data('vibe');
		var arch   = $(this).data('arch');

		if (vibe) {
			$('#mh-ai-vibe').val(vibe);
		}
		if (arch) {
			$('#mh-ai-archetype').val(arch);
		}

		triggerGeneration(prompt);
	});

	// Quick Follow-up Iteration Chips
	$(document).on('click', '.mh-ai-followup-chip', function(e) {
		e.preventDefault();
		var appendText = $(this).data('append');
		triggerGeneration(appendText);
	});

	// Send button click
	$(document).on('click', '#mh-ai-conjure-btn', function(e) {
		e.preventDefault();
		triggerGeneration();
	});

	// Enter to send in textarea (Shift+Enter for newline)
	$(document).on('keydown', '#mh-ai-prompt', function(e) {
		if (e.key === 'Enter' && !e.shiftKey) {
			e.preventDefault();
			triggerGeneration();
		}
	});

	// Revert to turn checkpoint
	$(document).on('click', '.mh-ai-revert-btn', function(e) {
		e.preventDefault();
		var turnIdx = parseInt($(this).data('turn'), 10);
		if (typeof chatTurns[turnIdx] !== 'undefined') {
			var turn = chatTurns[turnIdx];
			lastBlocks = turn.blocks_html;

			syncToLivePreview(turn.rendered_html, turn.blocks_html, turn);

			var revertNotice = '<div class="mh-ai-msg mh-ai-msg-assistant" style="background: rgba(98, 201, 255, 0.08); border-color: rgba(98, 201, 255, 0.3);"><p style="color: #38bdf8;">⏮️ Reverted canvas to checkpoint: <strong>' + $('<div>').text(turn.prompt).html() + '</strong>.</p></div>';
			$('#mh-ai-chat-thread').append(revertNotice);
			scrollToChatBottom();
		}
	});

	// Copy Gutenberg blocks to clipboard
	$(document).on('click', '.mh-ai-copy-btn', function(e) {
		e.preventDefault();
		var turnIdx = parseInt($(this).data('turn'), 10);
		var blocksToCopy = (typeof chatTurns[turnIdx] !== 'undefined') ? chatTurns[turnIdx].blocks_html : lastBlocks;

		if (navigator.clipboard && blocksToCopy) {
			navigator.clipboard.writeText(blocksToCopy);
			var $btn = $(this);
			$btn.text('Copied!');
			setTimeout(function() {
				$btn.text('📋 Copy Blocks');
			}, 2000);
		}
	});

	// Fresh Page / Start Over
	$(document).on('click', '#mh-ai-new-thread-btn', function(e) {
		e.preventDefault();
		lastBlocks = '';
		var freshGreeting = '<div class="mh-ai-msg mh-ai-msg-assistant"><div class="mh-ai-msg-header"><span class="mh-ai-msg-avatar">✨</span><span class="mh-ai-msg-name">Fresh Blueprint</span></div><div class="mh-ai-msg-body"><p>Started fresh canvas thread. Ready for your next vision prompt.</p></div></div>';
		$('#mh-ai-chat-thread').append(freshGreeting);
		scrollToChatBottom();
	});

	// Direct save button
	$(document).on('click', '#mh-ai-save-to-page', function(e) {
		e.preventDefault();
		var pageId = parseInt($('#mh-ai-target-page').val(), 10) || 0;
		if (!pageId || !lastBlocks) {
			alert('Please select a target page and generate content first.');
			return;
		}

		var $btn = $(this);
		$btn.prop('disabled', true).text('Saving...');

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
				$btn.prop('disabled', false).text('Saved!');
				setTimeout(function() {
					$btn.text('💾 Save to DB');
				}, 2200);
			},
			error: function() {
				$btn.prop('disabled', false).text('Save Failed');
			}
		});
	});

	// Boot & Lifecycle Synchronization
	function bootAIStudio() {
		var $connector = $('#mh-ai-connector');
		if ($connector.length) {
			populateModelsForConnector($connector.val());
		}
	}

	$(document).ready(function() {
		bootAIStudio();
		if (typeof wp !== 'undefined' && wp.customize) {
			wp.customize.bind('ready', bootAIStudio);
			if (wp.customize.section) {
				wp.customize.section('mh_ai_page_architect', function(section) {
					section.expanded.bind(function(isExpanded) {
						if (isExpanded) {
							setTimeout(bootAIStudio, 50);
						}
					});
				});
			}
		}
	});

	bootAIStudio();

})(jQuery);
