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

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			initMobileMenu();
			initStickyHeader();
			initInlineSearch();
		});
	} else {
		initMobileMenu();
		initStickyHeader();
		initInlineSearch();
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
