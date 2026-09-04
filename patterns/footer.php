<?php
/**
 * Title: Magic Hat Footer
 * Slug: xophz-magic-hat/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: 5-column responsive footer with brand info, navigation links, and copyright.
 *
 * @package Xophz_Magic_Hat
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|8","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}},"border":{"top":{"color":"rgba(255,255,255,0.06)","width":"1px"}}},"backgroundColor":"surface-main","layout":{"type":"default"}} -->
<footer class="wp-block-group alignfull has-surface-main-background-color has-background" style="border-top-color:rgba(255,255,255,0.06);border-top-width:1px;padding-top:var(--wp--preset--spacing--12);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--8);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|8","left":"var:preset|spacing|6"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"width":"30%"} -->
			<div class="wp-block-column" style="flex-basis:30%">
				<!-- wp:site-logo {"width":48,"shouldSyncIcon":true,"style":{"border":{"radius":"8px"}}} /-->
				<!-- wp:site-title {"level":0,"style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontWeight":"700"}},"textColor":"text-heading"} /-->
				<!-- wp:site-tagline {"textColor":"text-muted","fontSize":"sm"} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"17.5%"} -->
			<div class="wp-block-column" style="flex-basis:17.5%">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"text-heading","fontSize":"base"} -->
				<h4 class="wp-block-heading has-text-heading-color has-text-color has-base-font-size" style="font-family:var(--wp--preset--font-family--heading)">Explore</h4>
				<!-- /wp:heading -->
				<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"},"fontSize":"sm"} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"17.5%"} -->
			<div class="wp-block-column" style="flex-basis:17.5%">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"text-heading","fontSize":"base"} -->
				<h4 class="wp-block-heading has-text-heading-color has-text-color has-base-font-size" style="font-family:var(--wp--preset--font-family--heading)">Resources</h4>
				<!-- /wp:heading -->
				<!-- wp:list {"fontSize":"sm"} -->
				<ul class="wp-block-list has-sm-font-size">
					<li><a href="/docs">Documentation</a></li>
					<li><a href="/guides">Guides</a></li>
					<li><a href="/community">Community</a></li>
					<li><a href="/status">System Status</a></li>
				</ul>
				<!-- /wp:list -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"17.5%"} -->
			<div class="wp-block-column" style="flex-basis:17.5%">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"text-heading","fontSize":"base"} -->
				<h4 class="wp-block-heading has-text-heading-color has-text-color has-base-font-size" style="font-family:var(--wp--preset--font-family--heading)">Legal</h4>
				<!-- /wp:heading -->
				<!-- wp:list {"fontSize":"sm"} -->
				<ul class="wp-block-list has-sm-font-size">
					<li><a href="/privacy">Privacy Policy</a></li>
					<li><a href="/terms">Terms of Service</a></li>
					<li><a href="/security">Security Protocols</a></li>
				</ul>
				<!-- /wp:list -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"17.5%"} -->
			<div class="wp-block-column" style="flex-basis:17.5%">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"text-heading","fontSize":"base"} -->
				<h4 class="wp-block-heading has-text-heading-color has-text-color has-base-font-size" style="font-family:var(--wp--preset--font-family--heading)">Contact</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"sm","textColor":"text-muted"} -->
				<p class="has-text-muted-color has-text-color has-sm-font-size">Translink: Ready for transmission</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->

		<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
		<hr class="wp-block-separator has-text-color has-surface-card-background-color has-background" style="margin-top:var(--wp--preset--spacing--8);margin-bottom:var(--wp--preset--spacing--6)"/>
		<!-- /wp:separator -->

		<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"},"textColor":"text-muted","fontSize":"xs"} -->
		<div class="wp-block-group has-text-muted-color has-text-color has-xs-font-size">
			<!-- wp:paragraph -->
			<p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p>Powered by Xophz Magic Hat &amp; Project Compass</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</footer>
<!-- /wp:group -->
