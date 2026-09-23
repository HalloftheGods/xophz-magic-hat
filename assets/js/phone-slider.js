/**
 * Magic Hat - Wide Phone Apps Iframe Slider Controller
 *
 * Lightweight, zero-dependency slider controller supporting hardware-bezel
 * phone viewports, touch gestures, keyboard accessibility, and dynamic
 * Customizer live-preview rehydration.
 *
 * @package Xophz_Magic_Hat
 */

(function(window, document) {
	'use strict';

	/**
	 * Initialize all phone slider instances on the page.
	 */
	function initPhoneSliders() {
		var sliders = document.querySelectorAll('.mh-phone-slider');
		if (!sliders.length) return;

		sliders.forEach(function(slider) {
			setupSingleSlider(slider);
		});
	}

	/**
	 * Configure a single phone slider component.
	 *
	 * @param {HTMLElement} slider Root slider element.
	 */
	function setupSingleSlider(slider) {
		var track = slider.querySelector('.mh-phone-slider-track');
		var slides = slider.querySelectorAll('.mh-phone-slide');
		var prevBtn = slider.querySelector('.mh-phone-nav-prev');
		var nextBtn = slider.querySelector('.mh-phone-nav-next');
		var dotsContainer = slider.querySelector('.mh-phone-dots');

		if (!track || !slides.length) return;

		var totalSlides = slides.length;
		var currentIndex = 0;
		var startX = 0;
		var currentX = 0;
		var isDragging = false;

		// Build or synchronize dot indicators
		if (dotsContainer) {
			dotsContainer.innerHTML = '';
			for (var i = 0; i < totalSlides; i++) {
				var dot = document.createElement('button');
				dot.type = 'button';
				dot.className = 'mh-phone-dot' + (i === 0 ? ' is-active' : '');
				dot.setAttribute('aria-label', 'Go to app slide ' + (i + 1));
				dot.setAttribute('data-slide-index', i);
				dotsContainer.appendChild(dot);
			}
		}

		var dots = dotsContainer ? dotsContainer.querySelectorAll('.mh-phone-dot') : [];

		/**
		 * Transition to specified slide index.
		 *
		 * @param {number} targetIndex Slide destination index.
		 */
		function goToSlide(targetIndex) {
			if (targetIndex < 0) {
				targetIndex = totalSlides - 1;
			} else if (targetIndex >= totalSlides) {
				targetIndex = 0;
			}

			currentIndex = targetIndex;
			track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

			slides.forEach(function(slide, idx) {
				if (idx === currentIndex) {
					slide.classList.add('is-active');
					slide.setAttribute('aria-hidden', 'false');

					// Ensure iframe loads if deferred
					var iframe = slide.querySelector('iframe.mh-phone-iframe');
					if (iframe && iframe.dataset.src && !iframe.src) {
						iframe.src = iframe.dataset.src;
					}
				} else {
					slide.classList.remove('is-active');
					slide.setAttribute('aria-hidden', 'true');
				}
			});

			dots.forEach(function(dot, idx) {
				if (idx === currentIndex) {
					dot.classList.add('is-active');
				} else {
					dot.classList.remove('is-active');
				}
			});

			slider.dispatchEvent(new CustomEvent('mh:slide-change', {
				detail: { index: currentIndex, total: totalSlides }
			}));
		}

		// Previous / Next Button Handlers
		if (prevBtn) {
			prevBtn.onclick = function(e) {
				e.preventDefault();
				goToSlide(currentIndex - 1);
			};
		}

		if (nextBtn) {
			nextBtn.onclick = function(e) {
				e.preventDefault();
				goToSlide(currentIndex + 1);
			};
		}

		// Dot Pagination Clicks
		if (dotsContainer) {
			dotsContainer.onclick = function(e) {
				var targetDot = e.target.closest('.mh-phone-dot');
				if (!targetDot) return;
				var slideIdx = parseInt(targetDot.getAttribute('data-slide-index'), 10);
				if (!isNaN(slideIdx)) {
					goToSlide(slideIdx);
				}
			};
		}

		// Touch / Swipe Navigation
		slider.addEventListener('touchstart', function(e) {
			if (!e.touches || !e.touches[0]) return;
			startX = e.touches[0].clientX;
			isDragging = true;
		}, { passive: true });

		slider.addEventListener('touchmove', function(e) {
			if (!isDragging || !e.touches || !e.touches[0]) return;
			currentX = e.touches[0].clientX;
		}, { passive: true });

		slider.addEventListener('touchend', function() {
			if (!isDragging) return;
			isDragging = false;
			var diffX = startX - currentX;
			var threshold = 50;

			if (Math.abs(diffX) > threshold && currentX !== 0) {
				if (diffX > 0) {
					goToSlide(currentIndex + 1);
				} else {
					goToSlide(currentIndex - 1);
				}
			}
			startX = 0;
			currentX = 0;
		}, { passive: true });

		// Keyboard Navigation when slider is focused
		slider.setAttribute('tabindex', '0');
		slider.addEventListener('keydown', function(e) {
			if (e.key === 'ArrowLeft') {
				e.preventDefault();
				goToSlide(currentIndex - 1);
			} else if (e.key === 'ArrowRight') {
				e.preventDefault();
				goToSlide(currentIndex + 1);
			}
		});

		// Initialize active slide state
		goToSlide(0);

		// Hide loader when iframe finishes loading
		slides.forEach(function(slide) {
			var iframe = slide.querySelector('iframe.mh-phone-iframe');
			var loader = slide.querySelector('.mh-phone-loader');
			if (iframe && loader) {
				iframe.addEventListener('load', function() {
					loader.style.opacity = '0';
					setTimeout(function() {
						loader.style.display = 'none';
					}, 300);
				});
			}
		});
	}

	// Expose for external and live preview initialization
	window.mhInitPhoneSliders = initPhoneSliders;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initPhoneSliders);
	} else {
		initPhoneSliders();
	}
})(window, document);
