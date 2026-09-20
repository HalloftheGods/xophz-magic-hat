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

	// Helper to extract the exact text from the terminal code element
	function mhGetCliText(btn) {
		var wrap = (btn && btn.closest) ? btn.closest('.mh-terminal-body, .mh-terminal-card, .mh-footer-terminal-wrap') : null;
		var codeEl = wrap ? wrap.querySelector('.mh-cli-text, .mh-terminal-code') : document.querySelector('.mh-cli-text, .mh-terminal-code');
		if (codeEl) {
			var str = (codeEl.innerText || codeEl.textContent || '').trim();
			if (str) return str;
		}
		return (btn && btn.getAttribute) ? (btn.getAttribute('data-copy-text') || '') : '';
	}

	// Synchronous and multi-tier clipboard execution
	function mhExecuteCopy(text) {
		if (!text) return false;

		function runExecCopy(doc) {
			try {
				if (!doc || !doc.body) return false;
				var ta = doc.createElement('textarea');
				ta.value = text;
				ta.style.position = 'fixed';
				ta.style.top = '0';
				ta.style.left = '0';
				ta.style.width = '2em';
				ta.style.height = '2em';
				ta.style.padding = '0';
				ta.style.border = 'none';
				ta.style.outline = 'none';
				ta.style.boxShadow = 'none';
				ta.style.background = 'transparent';
				doc.body.appendChild(ta);
				ta.focus();
				ta.select();
				ta.setSelectionRange(0, ta.value.length);
				var ok = false;
				try {
					ok = doc.execCommand('copy');
				} catch (err) {
					ok = false;
				}
				doc.body.removeChild(ta);
				return ok;
			} catch (e) {
				return false;
			}
		}

		// 1. Synchronous document execCommand (highest reliability inside user click gesture)
		var copied = runExecCopy(document);
		if (copied) return true;

		// 2. Synchronous parent document execCommand (if inside an iframe like Customizer)
		try {
			if (window.parent && window.parent !== window && window.parent.document) {
				if (runExecCopy(window.parent.document)) return true;
			}
		} catch (e1) {}

		// 3. Native navigator.clipboard.writeText
		if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
			try {
				navigator.clipboard.writeText(text).catch(function() {
					try {
						if (window.parent && window.parent !== window && window.parent.navigator && window.parent.navigator.clipboard) {
							window.parent.navigator.clipboard.writeText(text).catch(function() {});
						}
					} catch (e2) {}
				});
				return true;
			} catch (e3) {}
		}

		// 4. Parent window navigator.clipboard
		try {
			if (window.parent && window.parent !== window && window.parent.navigator && window.parent.navigator.clipboard) {
				window.parent.navigator.clipboard.writeText(text).catch(function() {});
				return true;
			}
		} catch (e4) {}

		return false;
	}

	window.mhCopyCliText = function(btn, e) {
		if (e && e.preventDefault) e.preventDefault();
		if (!btn) return;

		var text = mhGetCliText(btn);
		if (!text) return;

		mhExecuteCopy(text);

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
	};

	// CLI Terminal Copy Command Handler
	function initCliCopyButton() {
		document.addEventListener('click', function(e) {
			var btn = e.target && e.target.closest ? e.target.closest('.mh-cli-copy-btn') : null;
			if (btn) {
				window.mhCopyCliText(btn, e);
			}
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
