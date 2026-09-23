<?php
/**
 * Title: Wide Phone Apps Iframe Slider
 * Slug: xophz-magic-hat/phone-slider
 * Categories: portfolio, gallery, featured
 * Description: Interactive horizontal slider displaying mobile applications in wide phone hardware frames with borderless iframes.
 *
 * @package Xophz_Magic_Hat
 */

?>
<!-- wp:group {"align":"full","className":"mh-section mh-section-full-width mh-section-phone-slider","style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-body","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull mh-section mh-section-full-width mh-section-phone-slider has-surface-body-background-color has-background" style="padding-top:var(--wp--preset--spacing--12);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--12);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"textAlign":"center","level":2,"textColor":"text-heading"} -->
		<h2 class="wp-block-heading has-text-align-center has-text-heading-color has-text-color">Mobile Application Gallery</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"text-muted","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|8"}}}} -->
		<p class="has-text-align-center has-text-muted-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--8)">Live interactive prototypes and web applications running inside simulated mobile hardware.</p>
		<!-- /wp:paragraph -->

		<!-- Slider Wrapper -->
		<div class="mh-phone-slider mh-phone-slider-container">
			<!-- Slider Stage -->
			<div class="mh-phone-slider-stage">
				<button type="button" class="mh-phone-nav-btn mh-phone-nav-prev" aria-label="Previous App">
					<span class="dashicons dashicons-arrow-left-alt2"></span>
				</button>

				<div class="mh-phone-slider-track">
					<!-- Slide 1: Compass Phone OS -->
					<div class="mh-phone-slide is-active" data-slide-index="0" data-mw-item-idx="0">
						<div class="mh-phone-device-wrap">
							<div class="mh-phone-chassis">
								<div class="mh-phone-notch">
									<span class="mh-phone-camera"></span>
									<span class="mh-phone-speaker"></span>
								</div>
								<span class="mh-phone-hardware-btn mh-phone-btn-vol-up"></span>
								<span class="mh-phone-hardware-btn mh-phone-btn-vol-down"></span>
								<span class="mh-phone-hardware-btn mh-phone-btn-power"></span>
								<div class="mh-phone-screen">
									<div class="mh-phone-loader">
										<div class="mh-phone-loader-spinner"></div>
										<span>Booting App...</span>
									</div>
									<iframe
										class="mh-phone-iframe"
										src="/wp-admin/admin.php?page=xophz-compass#/phone"
										title="My Compass Phone"
										loading="lazy"
										sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
										allow="fullscreen; clipboard-read; clipboard-write">
									</iframe>
									<div class="mh-phone-glare"></div>
								</div>
							</div>
						</div>
						<div class="mh-phone-meta">
							<span class="mh-phone-badge" data-mw-item-prop="badge">Phone OS</span>
							<h3 class="mh-phone-app-title" data-mw-item-prop="title">My Compass Phone</h3>
							<p class="mh-phone-app-desc" data-mw-item-prop="desc">Sovereign mobile operating system with WebOS apps and spatial launcher.</p>
							<a href="/wp-admin/admin.php?page=xophz-compass#/phone" class="mh-phone-app-link" target="_blank" rel="noopener" data-mw-item-prop="link">
								<span>Launch Fullscreen</span> &rarr;
							</a>
						</div>
					</div>

					<!-- Slide 2: Bomb Bag File Explorer -->
					<div class="mh-phone-slide" data-slide-index="1" data-mw-item-idx="1">
						<div class="mh-phone-device-wrap">
							<div class="mh-phone-chassis">
								<div class="mh-phone-notch">
									<span class="mh-phone-camera"></span>
									<span class="mh-phone-speaker"></span>
								</div>
								<span class="mh-phone-hardware-btn mh-phone-btn-vol-up"></span>
								<span class="mh-phone-hardware-btn mh-phone-btn-vol-down"></span>
								<span class="mh-phone-hardware-btn mh-phone-btn-power"></span>
								<div class="mh-phone-screen">
									<div class="mh-phone-loader">
										<div class="mh-phone-loader-spinner"></div>
										<span>Booting App...</span>
									</div>
									<iframe
										class="mh-phone-iframe"
										src="/wp-admin/admin.php?page=xophz-compass#/bomb-bag"
										title="Bomb Bag File Explorer"
										loading="lazy"
										sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
										allow="fullscreen; clipboard-read; clipboard-write">
									</iframe>
									<div class="mh-phone-glare"></div>
								</div>
							</div>
						</div>
						<div class="mh-phone-meta">
							<span class="mh-phone-badge" data-mw-item-prop="badge">File Vault</span>
							<h3 class="mh-phone-app-title" data-mw-item-prop="title">Bomb Bag File Explorer</h3>
							<p class="mh-phone-app-desc" data-mw-item-prop="desc">Decentralized spatial asset storage and quantum file vault.</p>
							<a href="/wp-admin/admin.php?page=xophz-compass#/bomb-bag" class="mh-phone-app-link" target="_blank" rel="noopener" data-mw-item-prop="link">
								<span>Launch Fullscreen</span> &rarr;
							</a>
						</div>
					</div>

					<!-- Slide 3: Magic Formula Canvas -->
					<div class="mh-phone-slide" data-slide-index="2" data-mw-item-idx="2">
						<div class="mh-phone-device-wrap">
							<div class="mh-phone-chassis">
								<div class="mh-phone-notch">
									<span class="mh-phone-camera"></span>
									<span class="mh-phone-speaker"></span>
								</div>
								<span class="mh-phone-hardware-btn mh-phone-btn-vol-up"></span>
								<span class="mh-phone-hardware-btn mh-phone-btn-vol-down"></span>
								<span class="mh-phone-hardware-btn mh-phone-btn-power"></span>
								<div class="mh-phone-screen">
									<div class="mh-phone-loader">
										<div class="mh-phone-loader-spinner"></div>
										<span>Booting App...</span>
									</div>
									<iframe
										class="mh-phone-iframe"
										src="/wp-admin/admin.php?page=xophz-compass#/magic-formula"
										title="Magic Formula Canvas"
										loading="lazy"
										sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
										allow="fullscreen; clipboard-read; clipboard-write">
									</iframe>
									<div class="mh-phone-glare"></div>
								</div>
							</div>
						</div>
						<div class="mh-phone-meta">
							<span class="mh-phone-badge" data-mw-item-prop="badge">Formula</span>
							<h3 class="mh-phone-app-title" data-mw-item-prop="title">Magic Formula Canvas</h3>
							<p class="mh-phone-app-desc" data-mw-item-prop="desc">Visual node-based shortcode synthesizer and reactive component builder.</p>
							<a href="/wp-admin/admin.php?page=xophz-compass#/magic-formula" class="mh-phone-app-link" target="_blank" rel="noopener" data-mw-item-prop="link">
								<span>Launch Fullscreen</span> &rarr;
							</a>
						</div>
					</div>
				</div>

				<button type="button" class="mh-phone-nav-btn mh-phone-nav-next" aria-label="Next App">
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</button>
			</div>

			<!-- Dot Indicators -->
			<div class="mh-phone-dots" role="tablist" aria-label="App Slider Pagination"></div>
		</div>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
