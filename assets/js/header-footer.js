/**
 * Magic Hat Header & Mobile Drawer Navigation Controller
 *
 * Handles responsive hamburger button toggle, slide-out mobile drawer,
 * backdrop dismissal, keyboard trapping (ESC to close), and smooth scrolling.
 *
 * @package Xophz_Magic_Hat
 */

(function() {
	'use strict';

	function initMobileMenu() {
		var hamburger = document.getElementById('mh-hamburger');
		var drawer = document.getElementById('mh-mobile-nav');
		var backdrop = document.getElementById('mh-mobile-backdrop');
		var closeBtn = document.getElementById('mh-mobile-close');

		if (!hamburger || !drawer) {
			return;
		}

		function openDrawer() {
			drawer.classList.add('is-open');
			hamburger.classList.add('is-active');
			hamburger.setAttribute('aria-expanded', 'true');
			drawer.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';
		}

		function closeDrawer() {
			drawer.classList.remove('is-open');
			hamburger.classList.remove('is-active');
			hamburger.setAttribute('aria-expanded', 'false');
			drawer.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';
		}

		hamburger.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var isOpen = drawer.classList.contains('is-open');
			if (isOpen) {
				closeDrawer();
			} else {
				openDrawer();
			}
		});

		if (closeBtn) {
			closeBtn.addEventListener('click', function(e) {
				e.preventDefault();
				closeDrawer();
			});
		}

		if (backdrop) {
			backdrop.addEventListener('click', function() {
				closeDrawer();
			});
		}

		// Close drawer when clicking any link inside drawer (smooth page navigation)
		drawer.addEventListener('click', function(e) {
			if (e.target && e.target.tagName === 'A') {
				closeDrawer();
			}
		});

		// Close drawer on Escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
				closeDrawer();
				hamburger.focus();
			}
		});
	}

	// Sticky Header Shadow on Scroll
	function initStickyHeader() {
		var header = document.getElementById('mw-header');
		if (!header || !header.classList.contains('mh-header-sticky')) {
			return;
		}

		function handleScroll() {
			var isScrolled = window.scrollY > 20;
			if (isScrolled) {
				header.classList.add('mh-is-scrolled');
			} else {
				header.classList.remove('mh-is-scrolled');
			}
		}

		window.addEventListener('scroll', handleScroll, { passive: true });
		handleScroll();
	}

	// Inline Search & Menu Quick-Jump Autocomplete
	function initInlineSearch() {
		var container = document.querySelector('.mh-header-search-container');
		if (!container) return;

		var input = container.querySelector('.mh-header-search-input');
		var dropdown = container.querySelector('.mh-search-autocomplete-dropdown');
		if (!input || !dropdown) return;

		var rawData = container.getAttribute('data-menu-items') || '[]';
		var menuItems = [];
		try {
			menuItems = JSON.parse(rawData);
		} catch (e) {
			menuItems = [];
		}

		window.addEventListener('keydown', function(e) {
			if ((e.metaKey || e.ctrlKey) && (e.key === 'k' || e.key === 'K')) {
				e.preventDefault();
				input.focus();
				input.select();
			}
		});

		input.addEventListener('input', function() {
			var q = input.value.toLowerCase().trim();
			if (!q) {
				dropdown.style.display = 'none';
				dropdown.innerHTML = '';
				return;
			}

			var matches = menuItems.filter(function(item) {
				return item.title && item.title.toLowerCase().indexOf(q) !== -1;
			});

			if (!matches.length) {
				dropdown.innerHTML = '<div style="padding: 8px 14px; font-size: 12px; color: #94a3b8;">No direct menu match. Press Enter to search site.</div>';
				dropdown.style.display = 'block';
				return;
			}

			var html = '';
			matches.slice(0, 6).forEach(function(m) {
				html += '<a href="' + m.url + '" class="mh-search-autocomplete-item">' +
					'<span class="dashicons dashicons-admin-links" style="font-size: 14px; width: 14px; height: 14px; color: #62c9ff;"></span>' +
					'<span>' + m.title + '</span>' +
				'</a>';
			});

			dropdown.innerHTML = html;
			dropdown.style.display = 'block';
		});

		document.addEventListener('click', function(e) {
			if (!container.contains(e.target)) {
				dropdown.style.display = 'none';
			}
		});
	}

	// CLI Terminal Copy Command Handler
	function initCliCopyButton() {
		function copyToClipboard(text) {
			return new Promise(function(resolve, reject) {
				function tryExecOnDoc(doc) {
					try {
						if (!doc || !doc.body) return false;
						var textarea = doc.createElement('textarea');
						textarea.value = text;
						textarea.style.fontSize = '12pt';
						textarea.style.border = '0';
						textarea.style.padding = '0';
						textarea.style.margin = '0';
						textarea.style.position = 'fixed';
						textarea.style.top = '0';
						textarea.style.left = '0';
						textarea.style.width = '2em';
						textarea.style.height = '2em';
						textarea.style.opacity = '0.01';
						textarea.style.zIndex = '-9999';
						textarea.setAttribute('readonly', '');
						doc.body.appendChild(textarea);
						textarea.focus({ preventScroll: true });
						textarea.select();
						textarea.setSelectionRange(0, text.length);
						var ok = doc.execCommand('copy');
						doc.body.removeChild(textarea);
						return ok;
					} catch (e) {
						return false;
					}
				}

				// Attempt 1: Window navigator.clipboard
				if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
					navigator.clipboard.writeText(text).then(resolve).catch(function() {
						// Attempt 2: Parent window clipboard if in iframe
						try {
							if (window.parent && window.parent !== window && window.parent.navigator && window.parent.navigator.clipboard) {
								window.parent.navigator.clipboard.writeText(text).then(resolve).catch(function() {
									if (tryExecOnDoc(document) || (window.parent && tryExecOnDoc(window.parent.document))) {
										resolve();
									} else {
										reject();
									}
								});
								return;
							}
						} catch (err) {}

						// Attempt 3: execCommand on current or parent document
						if (tryExecOnDoc(document)) {
							resolve();
						} else {
							try {
								if (window.parent && tryExecOnDoc(window.parent.document)) {
									resolve();
									return;
								}
							} catch (err2) {}
							reject();
						}
					});
					return;
				}

				// Attempt 4: Parent window clipboard if navigator.clipboard missing in subframe
				try {
					if (window.parent && window.parent !== window && window.parent.navigator && window.parent.navigator.clipboard) {
						window.parent.navigator.clipboard.writeText(text).then(resolve).catch(function() {
							if (tryExecOnDoc(document) || (window.parent && tryExecOnDoc(window.parent.document))) {
								resolve();
							} else {
								reject();
							}
						});
						return;
					}
				} catch (err3) {}

				// Attempt 5: execCommand
				if (tryExecOnDoc(document)) {
					resolve();
					return;
				}
				try {
					if (window.parent && tryExecOnDoc(window.parent.document)) {
						resolve();
						return;
					}
				} catch (err4) {}
				reject();
			});
		}

		document.addEventListener('click', function(e) {
			var btn = e.target && e.target.closest('.mh-cli-copy-btn');
			if (!btn) return;
			e.preventDefault();

			var terminalWrap = btn.closest('.mh-terminal-card, .mh-terminal-body, .mh-footer-terminal-wrap');
			var textEl = terminalWrap ? terminalWrap.querySelector('.mh-cli-text, .mh-terminal-code') : document.querySelector('.mh-cli-text, .mh-terminal-code');
			var textToCopy = (textEl ? textEl.textContent.trim() : '') || btn.getAttribute('data-copy-text') || '';
			if (!textToCopy) return;

			function handleCopiedState() {
				var originalText = btn.getAttribute('data-original-text') || btn.textContent.trim();
				if (!btn.getAttribute('data-original-text') && originalText !== 'Copied!') {
					btn.setAttribute('data-original-text', originalText);
				}
				btn.textContent = 'Copied!';
				btn.classList.add('copied');
				if (btn._copyTimeout) {
					clearTimeout(btn._copyTimeout);
				}
				btn._copyTimeout = setTimeout(function() {
					btn.textContent = btn.getAttribute('data-original-text') || 'Copy';
					btn.classList.remove('copied');
					btn._copyTimeout = null;
				}, 2000);
			}

			copyToClipboard(textToCopy).then(handleCopiedState).catch(handleCopiedState);
		});
	}

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			initMobileMenu();
			initStickyHeader();
			initInlineSearch();
			initCliCopyButton();
		});
	} else {
		initMobileMenu();
		initStickyHeader();
		initInlineSearch();
		initCliCopyButton();
	}

	// Support WordPress Customizer Selective Refresh re-initialization
	if (typeof wp !== 'undefined' && wp.customize && wp.customize.selectiveRefresh) {
		wp.customize.selectiveRefresh.bind('partial-content-rendered', function(placement) {
			if (placement && placement.partial && (placement.partial.id === 'mh_header_partial' || placement.partial.id === 'mh_footer_partial')) {
				initMobileMenu();
				initStickyHeader();
				initInlineSearch();
			}
		});
	}

})();
