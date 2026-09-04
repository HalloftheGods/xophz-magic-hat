<?php
/**
 * Title: Magic Hat Header
 * Slug: xophz-magic-hat/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Semantic glassmorphic header with site logo, title, responsive navigation, and CTA.
 *
 * @package Xophz_Magic_Hat
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|4","bottom":"var:preset|spacing|4","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}},"border":{"bottom":{"color":"rgba(255,255,255,0.06)","width":"1px"}}},"backgroundColor":"surface-main","layout":{"type":"default"}} -->
<header class="wp-block-group alignfull has-surface-main-background-color has-background" style="border-bottom-color:rgba(255,255,255,0.06);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--4);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--4);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:site-logo {"width":36,"shouldSyncIcon":true,"style":{"border":{"radius":"4px"}}} /-->
			<!-- wp:site-title {"level":0,"style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontWeight":"700","letterSpacing":"0.5px"}},"textColor":"text-heading"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|6"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
		<div class="wp-block-group">
			<!-- wp:navigation {"overlayBackgroundColor":"surface-main","overlayTextColor":"text-main","layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} /-->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"brand-base","textColor":"surface-body","style":{"border":{"radius":"4px"},"typography":{"fontWeight":"600"}}} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-surface-body-color has-brand-base-background-color has-text-color has-background wp-element-button" href="#contact" style="border-radius:4px;font-weight:600">Get Started</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</header>
<!-- /wp:group -->
