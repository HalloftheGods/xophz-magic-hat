/**
 * Magic Hat Customizer - Layout Template Library Modal & Live Preview
 *
 * Provides a uniform modal catalog for Header and Footer layout archetypes
 * with vector SVG wireframe previews, category filtering, search, and responsive device preview.
 *
 * @package Xophz_Magic_Hat
 */

(function(window, $) {
	'use strict';

	window.mhLayoutModal = window.mhLayoutModal || {};

	var api = null;
	var $modal = null;
	var activeType = 'all'; // 'all', 'header', 'footer'
	var activeFilter = 'all';
	var currentPreviewLayoutId = null;

	var templates = [
		// ── HEADER TEMPLATES ───────────────────────────
		{
			id: 'standard',
			type: 'header',
			name: 'Standard Corporate',
			desc: 'Logo on left, primary navigation in center/right, and prominent call-to-action button.',
			category: 'header',
			badge: 'Classic SaaS',
			tags: ['Sticky Ready', 'CTA Button', 'Full Nav']
		},
		{
			id: 'centered',
			type: 'header',
			name: 'Centered Showcase',
			desc: 'Logo prominently centered on top, with horizontal navigation and action bar balanced below.',
			category: 'header',
			badge: 'Editorial',
			tags: ['Centered Logo', 'Dual Tier', 'Boutique']
		},
		{
			id: 'split',
			type: 'header',
			name: 'Split Symmetrical',
			desc: 'Navigation split evenly on left and right flanks with brand logo centered in the middle.',
			category: 'header',
			badge: 'Symmetrical',
			tags: ['Balanced', 'Center Mark', 'Modern']
		},
		{
			id: 'minimal',
			type: 'header',
			name: 'Minimal Mobile-First',
			desc: 'Clean brand logo on left, direct CTA and hamburger drawer icon on right.',
			category: 'header',
			badge: 'Mobile First',
			tags: ['Distraction-Free', 'Drawer', 'Compact']
		},
		{
			id: 'floating_pill',
			type: 'header',
			name: 'Floating Glass Island',
			desc: 'Suspended glassmorphic pill bar elevated above page canvas with rounded corners and subtle glow.',
			category: 'header',
			badge: 'Modern AI / App',
			tags: ['Glassmorphic', 'Floating Dock', 'Linear Vibe']
		},
		{
			id: 'stacked_utility',
			type: 'header',
			name: 'Stacked Utility Bar',
			desc: 'Top announcement and social utility bar above primary logo and navigation row.',
			category: 'header',
			badge: 'E-Commerce / Enterprise',
			tags: ['Top Ticker', 'Social Links', 'Announcement']
		},
		{
			id: 'inline_search',
			type: 'header',
			name: 'Inline Search & Commands',
			desc: 'Brand logo left, integrated site search and quick-jump menu autocomplete center, nav right.',
			category: 'header',
			badge: 'App / Docs',
			tags: ['Live Search', 'Cmd+K', 'Menu Jump']
		},
		{
			id: 'offcanvas_focus',
			type: 'header',
			name: 'Off-Canvas Focus',
			desc: 'Ultra-minimal presentation with brand mark and high-contrast fullscreen drawer trigger.',
			category: 'header',
			badge: 'Creative Studio',
			tags: ['Max Space', 'Minimalist', 'Artistic']
		},
		{
			id: 'announcement_ticker',
			type: 'header',
			name: 'Announcement Ticker Bar',
			desc: 'Top compact highlight ticker with active pill badge and direct link above full navigation bar.',
			category: 'header',
			badge: 'SaaS / Notice',
			tags: ['Ticker Bar', 'Alert Pill', 'Clean Row']
		},
		{
			id: 'dual_cta',
			type: 'header',
			name: 'Dual Action Converter',
			desc: 'Brand mark left, primary navigation center, ghost login action and high-converting primary CTA right.',
			category: 'header',
			badge: 'High Conversion',
			tags: ['Dual CTA', 'Ghost + Primary', 'SaaS Funnel']
		},

		// ── FOOTER TEMPLATES ───────────────────────────
		{
			id: 'columns_4',
			type: 'footer',
			name: '4-Column Mega Footer',
			desc: 'Expansive multi-column architecture with brand identity column and 4 structured navigation lists.',
			category: 'footer',
			badge: 'Mega Footer',
			tags: ['4 Menus', 'Social Grid', 'Back to Top']
		},
		{
			id: 'columns_3',
			type: 'footer',
			name: '3-Column Balanced Footer',
			desc: 'Balanced 3-column layout pairing brand column with two high-priority navigation columns.',
			category: 'footer',
			badge: 'Corporate',
			tags: ['3 Columns', 'Clean Baseline', 'Structured']
		},
		{
			id: 'minimal_centered',
			type: 'footer',
			name: 'Centered Minimal Footer',
			desc: 'Centered brand identity, inline horizontal link list, social icons, and copyright.',
			category: 'footer',
			badge: 'Minimal',
			tags: ['Inline Links', 'Centered Mark', 'Clean']
		},
		{
			id: 'split',
			type: 'footer',
			name: 'Split Modern Footer',
			desc: 'Brand identity and copyright docked left, inline navigation and social links grouped right.',
			category: 'footer',
			badge: 'Modern Split',
			tags: ['2 Halves', 'Inline Nav', 'Social Icons']
		},
		{
			id: 'bento',
			type: 'footer',
			name: 'Bento Grid Footer',
			desc: 'Asymmetrical 4-tile bento grid with live status indicator pill, newsletter card, and menus.',
			category: 'footer',
			badge: 'Apple / Vercel Vibe',
			tags: ['Bento Grid', 'Status Pill', 'Newsletter']
		},
		{
			id: 'big_statement',
			type: 'footer',
			name: 'Big Statement CTA',
			desc: 'Massive display callout headline with direct primary button leading into clean legal baseline.',
			category: 'footer',
			badge: 'Lead Magnet',
			tags: ['Giant Headline', 'Oversized CTA', 'Agency']
		},
		{
			id: 'newsletter_first',
			type: 'footer',
			name: 'Newsletter Lead-In',
			desc: 'Full-width elevated newsletter subscription card spanning top, followed by 3 menu columns.',
			category: 'footer',
			badge: 'Marketing',
			tags: ['Email Form', 'Full Banner', 'Resource Grid']
		},
		{
			id: 'floating_dock',
			type: 'footer',
			name: 'Floating Dock / Micro-Footer',
			desc: 'Sleek single-line horizontal dock bar containing logo, inline links, and social icons in one row.',
			category: 'footer',
			badge: 'Web App / Micro',
			tags: ['Single Line', 'Dock Bar', 'Compact']
		},
		{
			id: 'sitemap_dense',
			type: 'footer',
			name: '5-Column Enterprise Sitemap',
			desc: 'High-density directory architecture with 5 structured columns and live system status beacon.',
			category: 'footer',
			badge: 'Enterprise',
			tags: ['5 Columns', 'Sitemap', 'Status Beacon']
		},
		{
			id: 'social_hub',
			type: 'footer',
			name: 'Interactive Social Hub',
			desc: 'Brand row, interactive social community cards for GitHub, X, YouTube, LinkedIn, and newsletter signup.',
			category: 'footer',
			badge: 'Community',
			tags: ['Social Cards', 'Community Hub', 'Newsletter']
		}
	];

	/**
	 * SVG Wireframe Generators
	 */
	function getWireframeSvg(tmpl) {
		var c = '#62c9ff';
		var bg = '#0f172a';
		var slate = '#94a3b8';
		var muted = '#334155';

		var svg = '<svg viewBox="0 0 240 85" width="100%" height="85" fill="none" xmlns="http://www.w3.org/2000/svg">';

		if (tmpl.id === 'standard') {
			svg += '<rect x="10" y="24" width="220" height="36" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="20" y="36" width="35" height="12" rx="2" fill="' + c + '"/>';
			svg += '<rect x="75" y="40" width="25" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="108" y="40" width="25" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="141" y="40" width="25" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="182" y="34" width="38" height="16" rx="3" fill="' + c + '"/>';
		} else if (tmpl.id === 'centered') {
			svg += '<rect x="10" y="10" width="220" height="65" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="95" y="18" width="50" height="14" rx="2" fill="' + c + '"/>';
			svg += '<line x1="20" y1="40" x2="220" y2="40" stroke="' + muted + '" stroke-width="1"/>';
			svg += '<rect x="50" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="86" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="122" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="158" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
		} else if (tmpl.id === 'split') {
			svg += '<rect x="10" y="24" width="220" height="36" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="22" y="40" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="54" y="40" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="98" y="34" width="44" height="16" rx="2" fill="' + c + '"/>';
			svg += '<rect x="150" y="40" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="182" y="36" width="36" height="12" rx="2" fill="' + c + '" fill-opacity="0.25" stroke="' + c + '"/>';
		} else if (tmpl.id === 'minimal') {
			svg += '<rect x="10" y="24" width="220" height="36" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="20" y="35" width="40" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="155" y="35" width="34" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="198" y="35" width="20" height="14" rx="2" fill="' + muted + '"/>';
			svg += '<line x1="202" y1="39" x2="214" y2="39" stroke="#ffffff" stroke-width="1.5"/>';
			svg += '<line x1="202" y1="42" x2="214" y2="42" stroke="#ffffff" stroke-width="1.5"/>';
			svg += '<line x1="202" y1="45" x2="214" y2="45" stroke="#ffffff" stroke-width="1.5"/>';
		} else if (tmpl.id === 'floating_pill') {
			svg += '<rect x="10" y="8" width="220" height="70" rx="4" fill="#080c14"/>';
			svg += '<rect x="22" y="22" width="196" height="32" rx="16" fill="' + bg + '" stroke="' + c + '" stroke-width="1.5"/>';
			svg += '<rect x="36" y="31" width="30" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="85" y="36" width="20" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="113" y="36" width="20" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="141" y="36" width="20" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="172" y="28" width="34" height="20" rx="10" fill="' + c + '"/>';
		} else if (tmpl.id === 'stacked_utility') {
			svg += '<rect x="10" y="10" width="220" height="16" rx="2" fill="#1e293b"/>';
			svg += '<rect x="20" y="15" width="60" height="5" rx="1" fill="' + slate + '"/>';
			svg += '<circle cx="190" cy="18" r="3" fill="' + c + '"/>';
			svg += '<circle cx="200" cy="18" r="3" fill="' + c + '"/>';
			svg += '<circle cx="210" cy="18" r="3" fill="' + c + '"/>';
			svg += '<rect x="10" y="28" width="220" height="42" rx="2" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="20" y="42" width="38" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="90" y="47" width="25" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="125" y="47" width="25" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="175" y="40" width="42" height="18" rx="3" fill="' + c + '"/>';
		} else if (tmpl.id === 'inline_search') {
			svg += '<rect x="10" y="24" width="220" height="38" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="18" y="36" width="30" height="13" rx="2" fill="' + c + '"/>';
			svg += '<rect x="56" y="33" width="90" height="19" rx="9" fill="#1e293b" stroke="' + c + '" stroke-width="1"/>';
			svg += '<circle cx="68" cy="42" r="3" stroke="' + slate + '" stroke-width="1"/>';
			svg += '<rect x="76" y="40" width="40" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="130" y="37" width="12" height="11" rx="2" fill="#334155"/>';
			svg += '<rect x="156" y="40" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="186" y="34" width="36" height="17" rx="3" fill="' + c + '"/>';
		} else if (tmpl.id === 'offcanvas_focus') {
			svg += '<rect x="10" y="24" width="220" height="36" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="22" y="34" width="42" height="16" rx="2" fill="' + c + '"/>';
			svg += '<rect x="168" y="32" width="50" height="20" rx="4" fill="#1e293b" stroke="' + c + '" stroke-width="1"/>';
			svg += '<line x1="174" y1="38" x2="182" y2="38" stroke="#ffffff" stroke-width="1.5"/>';
			svg += '<line x1="174" y1="42" x2="182" y2="42" stroke="#ffffff" stroke-width="1.5"/>';
			svg += '<rect x="188" y="39" width="24" height="4" rx="1" fill="' + c + '"/>';
		} else if (tmpl.id === 'announcement_ticker') {
			svg += '<rect x="10" y="8" width="220" height="16" rx="2" fill="#1e293b"/>';
			svg += '<rect x="18" y="11" width="28" height="10" rx="5" fill="' + c + '"/>';
			svg += '<rect x="52" y="14" width="90" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="10" y="28" width="220" height="46" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="20" y="44" width="34" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="80" y="49" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="110" y="49" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="140" y="49" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="180" y="42" width="40" height="18" rx="3" fill="' + c + '"/>';
		} else if (tmpl.id === 'dual_cta') {
			svg += '<rect x="10" y="22" width="220" height="42" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="18" y="36" width="32" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="75" y="41" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="103" y="41" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="131" y="41" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="162" y="34" width="28" height="18" rx="3" fill="' + bg + '" stroke="' + slate + '" stroke-width="1"/>';
			svg += '<rect x="194" y="34" width="30" height="18" rx="3" fill="' + c + '"/>';
		} else if (tmpl.id === 'columns_4') {
			svg += '<rect x="10" y="8" width="220" height="70" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="18" y="16" width="34" height="12" rx="2" fill="' + c + '"/>';
			svg += '<rect x="18" y="32" width="42" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="18" y="40" width="36" height="4" rx="1" fill="' + slate + '"/>';
			for (var i = 0; i < 4; i++) {
				var cx = 76 + i * 38;
				svg += '<rect x="' + cx + '" y="16" width="24" height="6" rx="1" fill="#ffffff"/>';
				svg += '<rect x="' + cx + '" y="27" width="28" height="4" rx="1" fill="' + slate + '"/>';
				svg += '<rect x="' + cx + '" y="35" width="22" height="4" rx="1" fill="' + slate + '"/>';
				svg += '<rect x="' + cx + '" y="43" width="26" height="4" rx="1" fill="' + slate + '"/>';
			}
			svg += '<line x1="18" y1="58" x2="222" y2="58" stroke="' + muted + '" stroke-width="1"/>';
			svg += '<rect x="18" y="64" width="70" height="4" rx="1" fill="' + muted + '"/>';
		} else if (tmpl.id === 'columns_3') {
			svg += '<rect x="10" y="8" width="220" height="70" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="22" y="18" width="40" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="22" y="36" width="50" height="5" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="95" y="18" width="32" height="7" rx="1" fill="#ffffff"/>';
			svg += '<rect x="95" y="30" width="36" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="95" y="38" width="30" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="160" y="18" width="32" height="7" rx="1" fill="#ffffff"/>';
			svg += '<rect x="160" y="30" width="36" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="160" y="38" width="30" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<line x1="22" y1="56" x2="218" y2="56" stroke="' + muted + '" stroke-width="1"/>';
			svg += '<rect x="22" y="63" width="60" height="4" rx="1" fill="' + muted + '"/>';
		} else if (tmpl.id === 'minimal_centered') {
			svg += '<rect x="10" y="8" width="220" height="70" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="96" y="16" width="48" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="50" y="37" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="84" y="37" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="118" y="37" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="152" y="37" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<circle cx="106" cy="52" r="3" fill="' + c + '"/>';
			svg += '<circle cx="120" cy="52" r="3" fill="' + c + '"/>';
			svg += '<circle cx="134" cy="52" r="3" fill="' + c + '"/>';
			svg += '<rect x="75" y="63" width="90" height="4" rx="1" fill="' + muted + '"/>';
		} else if (tmpl.id === 'split') {
			svg += '<rect x="10" y="12" width="220" height="62" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="22" y="22" width="45" height="14" rx="2" fill="' + c + '"/>';
			svg += '<rect x="22" y="42" width="65" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="22" y="52" width="50" height="4" rx="1" fill="' + muted + '"/>';
			svg += '<rect x="135" y="24" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="165" y="24" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="195" y="24" width="24" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<circle cx="175" cy="46" r="4" fill="' + c + '"/>';
			svg += '<circle cx="190" cy="46" r="4" fill="' + c + '"/>';
			svg += '<circle cx="205" cy="46" r="4" fill="' + c + '"/>';
		} else if (tmpl.id === 'bento') {
			svg += '<rect x="10" y="8" width="90" height="44" rx="4" fill="' + bg + '" stroke="' + c + '" stroke-width="1"/>';
			svg += '<rect x="18" y="16" width="35" height="10" rx="2" fill="' + c + '"/>';
			svg += '<rect x="60" y="16" width="32" height="10" rx="5" fill="#10b981" fill-opacity="0.25"/>';
			svg += '<circle cx="66" cy="21" r="2" fill="#10b981"/>';
			svg += '<rect x="18" y="32" width="65" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="106" y="8" width="55" height="44" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="114" y="15" width="30" height="5" rx="1" fill="#ffffff"/>';
			svg += '<rect x="114" y="24" width="38" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="114" y="32" width="30" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="167" y="8" width="63" height="44" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="175" y="15" width="40" height="5" rx="1" fill="' + c + '"/>';
			svg += '<rect x="175" y="25" width="48" height="12" rx="3" fill="#1e293b"/>';
			svg += '<rect x="10" y="58" width="220" height="16" rx="3" fill="#1e293b"/>';
			svg += '<rect x="20" y="64" width="70" height="4" rx="1" fill="' + slate + '"/>';
		} else if (tmpl.id === 'big_statement') {
			svg += '<rect x="10" y="6" width="220" height="74" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="90" y="12" width="60" height="5" rx="2" fill="' + c + '" fill-opacity="0.3"/>';
			svg += '<rect x="35" y="21" width="170" height="10" rx="2" fill="#ffffff"/>';
			svg += '<rect x="60" y="34" width="120" height="7" rx="2" fill="' + slate + '"/>';
			svg += '<rect x="95" y="46" width="50" height="14" rx="4" fill="' + c + '"/>';
			svg += '<line x1="20" y1="65" x2="220" y2="65" stroke="' + muted + '" stroke-width="1"/>';
			svg += '<rect x="22" y="69" width="30" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="180" y="69" width="38" height="4" rx="1" fill="' + slate + '"/>';
		} else if (tmpl.id === 'newsletter_first') {
			svg += '<rect x="10" y="6" width="220" height="30" rx="4" fill="#1e293b" stroke="' + c + '" stroke-width="1"/>';
			svg += '<rect x="20" y="13" width="65" height="7" rx="1" fill="#ffffff"/>';
			svg += '<rect x="20" y="22" width="85" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="135" y="12" width="55" height="16" rx="3" fill="#0f172a" stroke="' + muted + '"/>';
			svg += '<rect x="194" y="12" width="26" height="16" rx="3" fill="' + c + '"/>';
			svg += '<rect x="10" y="42" width="220" height="36" rx="3" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="20" y="48" width="32" height="10" rx="2" fill="' + c + '"/>';
			svg += '<rect x="90" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="135" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="180" y="48" width="28" height="4" rx="1" fill="' + slate + '"/>';
		} else if (tmpl.id === 'floating_dock') {
			svg += '<rect x="10" y="10" width="220" height="66" rx="4" fill="#080c14"/>';
			svg += '<rect x="20" y="26" width="200" height="30" rx="8" fill="' + bg + '" stroke="' + c + '" stroke-width="1"/>';
			svg += '<rect x="32" y="34" width="34" height="12" rx="2" fill="' + c + '"/>';
			svg += '<rect x="80" y="38" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="108" y="38" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="136" y="38" width="22" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<circle cx="178" cy="40" r="3" fill="' + c + '"/>';
			svg += '<circle cx="190" cy="40" r="3" fill="' + c + '"/>';
			svg += '<circle cx="202" cy="40" r="3" fill="' + c + '"/>';
		} else if (tmpl.id === 'sitemap_dense') {
			svg += '<rect x="10" y="6" width="220" height="74" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			for (var si = 0; si < 5; si++) {
				var sx = 18 + si * 42;
				svg += '<rect x="' + sx + '" y="14" width="28" height="6" rx="1" fill="#ffffff"/>';
				svg += '<rect x="' + sx + '" y="24" width="32" height="3" rx="1" fill="' + slate + '"/>';
				svg += '<rect x="' + sx + '" y="30" width="26" height="3" rx="1" fill="' + slate + '"/>';
				svg += '<rect x="' + sx + '" y="36" width="30" height="3" rx="1" fill="' + slate + '"/>';
				svg += '<rect x="' + sx + '" y="42" width="22" height="3" rx="1" fill="' + slate + '"/>';
			}
			svg += '<line x1="18" y1="52" x2="222" y2="52" stroke="' + muted + '" stroke-width="1"/>';
			svg += '<circle cx="24" cy="62" r="3" fill="#10b981"/>';
			svg += '<rect x="32" y="60" width="60" height="4" rx="1" fill="' + slate + '"/>';
			svg += '<rect x="160" y="60" width="60" height="4" rx="1" fill="' + muted + '"/>';
		} else if (tmpl.id === 'social_hub') {
			svg += '<rect x="10" y="6" width="220" height="74" rx="4" fill="' + bg + '" stroke="' + muted + '"/>';
			svg += '<rect x="18" y="12" width="40" height="10" rx="2" fill="' + c + '"/>';
			svg += '<rect x="18" y="24" width="70" height="3" rx="1" fill="' + slate + '"/>';
			for (var sh = 0; sh < 4; sh++) {
				var shx = 18 + sh * 52;
				svg += '<rect x="' + shx + '" y="30" width="46" height="24" rx="3" fill="#1e293b" stroke="' + muted + '"/>';
				svg += '<circle cx="' + (shx + 10) + '" cy="42" r="4" fill="' + c + '"/>';
				svg += '<rect x="' + (shx + 18) + '" y="40" width="22" height="4" rx="1" fill="#ffffff"/>';
			}
			svg += '<line x1="18" y1="60" x2="222" y2="60" stroke="' + muted + '" stroke-width="1"/>';
			svg += '<rect x="18" y="66" width="80" height="4" rx="1" fill="' + muted + '"/>';
		}

		svg += '</svg>';
		return svg;
	}

	/**
	 * Mount Modal DOM structure
	 */
	function mountModalDom() {
		if ($('#mh-layout-library-modal').length) {
			$modal = $('#mh-layout-library-modal');
			return;
		}

		var html =
			'<div id="mh-layout-library-modal" class="mh-modal-backdrop">' +
				'<div class="mh-modal-container mh-layout-modal-container">' +
					// Modal Header
					'<div class="mh-modal-header">' +
						'<div class="mh-modal-header-left">' +
							'<span class="dashicons dashicons-layout mh-modal-header-icon"></span>' +
							'<div>' +
								'<h3 class="mh-modal-title">Magic Hat Layout Library</h3>' +
								'<p class="mh-modal-subtitle">Choose from ' + templates.length + ' curated header and footer layout archetypes</p>' +
							'</div>' +
						'</div>' +

						// Filter Tabs
						'<div class="mh-source-filter-group">' +
							'<button type="button" class="mh-source-pill active" data-type="all">All <span class="mh-source-badge" id="mh-layout-count-all">20</span></button>' +
							'<button type="button" class="mh-source-pill" data-type="header">Headers <span class="mh-source-badge" id="mh-layout-count-header">10</span></button>' +
							'<button type="button" class="mh-source-pill" data-type="footer">Footers <span class="mh-source-badge" id="mh-layout-count-footer">10</span></button>' +
						'</div>' +

						// Search & Close
						'<div class="mh-modal-header-right">' +
							'<div class="mh-search-wrapper">' +
								'<span class="dashicons dashicons-search mh-search-icon"></span>' +
								'<input type="text" id="mh-layout-modal-search" placeholder="Search layouts..." autocomplete="off" />' +
								'<button type="button" id="mh-layout-search-clear" class="mh-search-clear-btn" style="display:none;">&times;</button>' +
							'</div>' +
							'<button type="button" class="mh-close-modal" title="Close modal">&times;</button>' +
						'</div>' +
					'</div>' +

					// Modal Body
					'<div class="mh-modal-body">' +
						// Sidebar Navigation
						'<aside class="mh-modal-sidebar">' +
							'<div class="mh-sidebar-title">Categories</div>' +
							'<nav class="mh-cat-nav">' +
								'<a href="#" class="mh-cat-item active" data-filter="all">' +
									'<span class="dashicons dashicons-screenoptions"></span>' +
									'<span class="mh-cat-label">All Templates</span>' +
									'<span class="mh-cat-count-badge">20</span>' +
								'</a>' +
								'<a href="#" class="mh-cat-item" data-filter="header">' +
									'<span class="dashicons dashicons-arrow-up-alt2"></span>' +
									'<span class="mh-cat-label">Header Layouts</span>' +
									'<span class="mh-cat-count-badge">10</span>' +
								'</a>' +
								'<a href="#" class="mh-cat-item" data-filter="footer">' +
									'<span class="dashicons dashicons-arrow-down-alt2"></span>' +
									'<span class="mh-cat-label">Footer Layouts</span>' +
									'<span class="mh-cat-count-badge">10</span>' +
								'</a>' +
							'</nav>' +
						'</aside>' +

						// Main Cards View
						'<main class="mh-modal-main-view">' +
							'<div id="mh-layout-cards-wrap" class="mh-layout-grid-wrap">' +
								renderCardsHtml() +
							'</div>' +
							'<div id="mh-layout-empty" class="mh-modal-empty-state is-hidden" style="display:none;">' +
								'<span class="dashicons dashicons-search mh-modal-empty-icon"></span>' +
								'<p class="mh-modal-empty-text">No matching layouts found.</p>' +
							'</div>' +
						'</main>' +

						// Live Preview Pane
						'<div id="mh-layout-preview-pane" class="mh-preview-pane is-hidden" style="display:none;">' +
							'<div class="mh-preview-toolbar">' +
								'<button type="button" id="mh-layout-preview-back-btn" class="button">' +
									'<span class="dashicons dashicons-arrow-left-alt2"></span> Back to Layouts' +
								'</button>' +
								'<div class="mh-preview-meta">' +
									'<h4 id="mh-layout-preview-title">Layout Title</h4>' +
									'<span id="mh-layout-preview-type-badge" class="mh-preview-badge">Header</span>' +
									'<span id="mh-layout-preview-arch-badge" class="mh-preview-badge mh-badge-category">Archetype</span>' +
								'</div>' +
								'<div class="mh-preview-viewport-controls">' +
									'<button type="button" class="mh-viewport-btn active" data-width="100%" title="Desktop (100%)">' +
										'<span class="dashicons dashicons-desktop"></span>' +
									'</button>' +
									'<button type="button" class="mh-viewport-btn" data-width="768px" title="Tablet (768px)">' +
										'<span class="dashicons dashicons-tablet"></span>' +
									'</button>' +
									'<button type="button" class="mh-viewport-btn" data-width="375px" title="Mobile (375px)">' +
										'<span class="dashicons dashicons-smartphone"></span>' +
									'</button>' +
								'</div>' +
								'<button type="button" id="mh-layout-preview-apply-btn" class="button button-primary">' +
									'<span class="dashicons dashicons-yes"></span> Apply Layout' +
								'</button>' +
							'</div>' +
							'<div class="mh-preview-canvas">' +
								'<div id="mh-layout-preview-stage" class="mh-preview-stage">' +
									'<div id="mh-layout-preview-content" class="mh-preview-content"></div>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>';

		$('body').append(html);
		$modal = $('#mh-layout-library-modal');
	}

	/**
	 * Build cards HTML
	 */
	function renderCardsHtml() {
		var html = '';
		var currentHeader = (api && api('mh_header_layout')) ? api('mh_header_layout').get() : 'standard';
		var currentFooter = (api && api('mh_footer_layout')) ? api('mh_footer_layout').get() : 'columns_4';

		templates.forEach(function(tmpl) {
			var isActive = (tmpl.type === 'header' && tmpl.id === currentHeader) ||
			               (tmpl.type === 'footer' && tmpl.id === currentFooter);
			var typeClass = (tmpl.type === 'header') ? 'mh-type-header' : 'mh-type-footer';
			var wireframe = getWireframeSvg(tmpl);

			html +=
				'<div class="mh-section-card mh-layout-card" data-id="' + tmpl.id + '" data-type="' + tmpl.type + '">' +
					'<div class="mh-card-thumb-wrap">' +
						'<span class="mh-card-source-tag ' + typeClass + '">' + tmpl.type.toUpperCase() + '</span>' +
						(isActive ? '<span class="mh-card-active-tag">CURRENT ACTIVE</span>' : '') +
						wireframe +
						'<div class="mh-card-hover-overlay">' +
							'<button type="button" class="mh-btn-card-preview" data-id="' + tmpl.id + '">' +
								'<span class="dashicons dashicons-visibility"></span> Preview' +
							'</button>' +
							'<button type="button" class="mh-btn-card-add mh-btn-card-apply" data-id="' + tmpl.id + '">' +
								'<span class="dashicons dashicons-yes"></span> Apply' +
							'</button>' +
						'</div>' +
					'</div>' +
					'<div class="mh-card-info">' +
						'<div class="mh-card-title">' + tmpl.name + '</div>' +
						'<div class="mh-card-desc">' + tmpl.desc + '</div>' +
						'<div class="mh-card-tags">';

			tmpl.tags.forEach(function(tag) {
				html += '<span class="mh-tag-chip">' + tag + '</span>';
			});

			html +=
						'</div>' +
					'</div>' +
				'</div>';
		});

		return html;
	}

	/**
	 * Filter cards
	 */
	function filterCards() {
		var query = ($('#mh-layout-modal-search').val() || '').toLowerCase().trim();
		var visibleCount = 0;

		$modal.find('.mh-layout-card').each(function() {
			var $card = $(this);
			var id = $card.data('id');
			var type = $card.data('type');
			var title = $card.find('.mh-card-title').text().toLowerCase();
			var desc = $card.find('.mh-card-desc').text().toLowerCase();

			var matchesQuery = !query || id.indexOf(query) !== -1 || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1;
			var matchesType = (activeType === 'all' || type === activeType);
			var matchesFilter = (activeFilter === 'all' || type === activeFilter);

			if (matchesQuery && matchesType && matchesFilter) {
				$card.show();
				visibleCount++;
			} else {
				$card.hide();
			}
		});

		if (visibleCount === 0) {
			$('#mh-layout-empty').show();
		} else {
			$('#mh-layout-empty').hide();
		}
	}

	/**
	 * Open Layout Modal
	 *
	 * @param {string} [type] 'header', 'footer', or 'all'
	 */
	function openLayoutModal(type) {
		activeType = type || 'all';
		activeFilter = type || 'all';

		if (!$modal) {
			mountModalDom();
			bindEvents();
		}

		// Update card list with fresh current active markers
		$('#mh-layout-cards-wrap').html(renderCardsHtml());

		$modal.find('.mh-source-pill').removeClass('active');
		$modal.find('.mh-source-pill[data-type="' + activeType + '"]').addClass('active');

		$modal.find('.mh-cat-item').removeClass('active');
		$modal.find('.mh-cat-item[data-filter="' + activeFilter + '"]').addClass('active');

		closePreview();
		$modal.css('display', 'flex').hide().fadeIn(150);
		$('#mh-layout-modal-search').val('');
		$('#mh-layout-search-clear').hide();
		filterCards();
	}

	/**
	 * Close Layout Modal
	 */
	function closeModal() {
		if ($modal) {
			$modal.fadeOut(150);
		}
		closePreview();
	}

	/**
	 * Open Live Preview
	 */
	function openPreview(layoutId) {
		var tmpl = templates.find(function(t) { return t.id === layoutId; });
		if (!tmpl) return;

		currentPreviewLayoutId = layoutId;
		$('#mh-layout-preview-title').text(tmpl.name);
		$('#mh-layout-preview-type-badge').text(tmpl.type.toUpperCase());
		$('#mh-layout-preview-arch-badge').text(tmpl.badge);

		// Render high-fidelity preview mockup
		var mockHtml =
			'<div class="mh-stage-mock-wrap" style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 240px; background: #0a0b10; border-radius: 8px;">' +
				'<div style="max-width: 600px; width: 100%; text-align: center; margin-bottom: 24px;">' +
					'<h3 style="color: #ffffff; font-size: 20px; font-weight: 700; margin-bottom: 8px;">' + tmpl.name + '</h3>' +
					'<p style="color: #94a3b8; font-size: 13px; line-height: 1.5; margin: 0;">' + tmpl.desc + '</p>' +
				'</div>' +
				'<div style="width: 100%; max-width: 820px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-radius: 8px; overflow: hidden;">' +
					getWireframeSvg(tmpl) +
				'</div>' +
			'</div>';

		$('#mh-layout-preview-content').html(mockHtml);
		$('#mh-layout-preview-stage').css('width', '100%');
		$modal.find('.mh-viewport-btn').removeClass('active');
		$modal.find('.mh-viewport-btn[data-width="100%"]').addClass('active');

		$('.mh-modal-main-view').hide();
		$('#mh-layout-preview-pane').removeClass('is-hidden').show();
	}

	/**
	 * Close Preview
	 */
	function closePreview() {
		$('#mh-layout-preview-pane').addClass('is-hidden').hide();
		$('.mh-modal-main-view').show();
		$('#mh-layout-preview-content').empty();
		currentPreviewLayoutId = null;
	}

	/**
	 * Apply selected layout
	 */
	function applyLayout(layoutId) {
		var tmpl = templates.find(function(t) { return t.id === layoutId; });
		if (!tmpl || !api) return;

		var settingId = (tmpl.type === 'header') ? 'mh_header_layout' : 'mh_footer_layout';

		if (api(settingId)) {
			api(settingId).set(tmpl.id);
		}

		// Update UI card in sidebar
		$('#' + settingId + '_current_title').text(tmpl.name);
		$('#' + settingId + '_current_id').text(tmpl.id);
		$('#' + settingId + '_input').val(tmpl.id);

		closeModal();

		if (api.previewer) {
			api.previewer.refresh();
		}
	}

	/**
	 * Bind events
	 */
	function bindEvents() {
		// Open modal from customizer controls
		$(document).on('click', '.mh-btn-open-layout-modal', function(e) {
			e.preventDefault();
			var type = $(this).data('type') || 'all';
			openLayoutModal(type);
		});

		// Close modal
		$modal.on('click', '.mh-close-modal', function() {
			closeModal();
		});

		$modal.on('click', function(e) {
			if (e.target === this) {
				closeModal();
			}
		});

		$(document).on('keydown', function(e) {
			if (e.key === 'Escape' && $modal && $modal.is(':visible')) {
				closeModal();
			}
		});

		// Filter pills
		$modal.on('click', '.mh-source-pill', function() {
			$modal.find('.mh-source-pill').removeClass('active');
			$(this).addClass('active');
			activeType = $(this).data('type') || 'all';
			closePreview();
			filterCards();
		});

		// Sidebar category filter
		$modal.on('click', '.mh-cat-item', function(e) {
			e.preventDefault();
			$modal.find('.mh-cat-item').removeClass('active');
			$(this).addClass('active');
			activeFilter = $(this).data('filter') || 'all';
			closePreview();
			filterCards();
		});

		// Search
		$modal.on('input', '#mh-layout-modal-search', function() {
			var val = $(this).val();
			if (val) {
				$('#mh-layout-search-clear').show();
			} else {
				$('#mh-layout-search-clear').hide();
			}
			filterCards();
		});

		$modal.on('click', '#mh-layout-search-clear', function() {
			$('#mh-layout-modal-search').val('').focus();
			$(this).hide();
			filterCards();
		});

		// Card actions
		$modal.on('click', '.mh-btn-card-preview', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var id = $(this).data('id');
			openPreview(id);
		});

		$modal.on('click', '.mh-layout-card', function(e) {
			var isApply = Boolean($(e.target).closest('.mh-btn-card-apply').length);
			if (isApply) return;
			e.preventDefault();
			var id = $(this).data('id');
			openPreview(id);
		});

		$modal.on('click', '.mh-btn-card-apply', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var id = $(this).data('id');
			applyLayout(id);
		});

		// Preview pane controls
		$modal.on('click', '#mh-layout-preview-back-btn', function(e) {
			e.preventDefault();
			closePreview();
		});

		$modal.on('click', '#mh-layout-preview-apply-btn', function(e) {
			e.preventDefault();
			if (currentPreviewLayoutId) {
				applyLayout(currentPreviewLayoutId);
			}
		});

		$modal.on('click', '.mh-viewport-btn', function() {
			var w = $(this).data('width') || '100%';
			$modal.find('.mh-viewport-btn').removeClass('active');
			$(this).addClass('active');
			$('#mh-layout-preview-stage').css('width', w);
		});
	}

	/**
	 * Initialize
	 */
	function init(wpApi) {
		api = wpApi;
		mountModalDom();
		bindEvents();

		// Listen to external setting changes and update card labels
		['mh_header_layout', 'mh_footer_layout'].forEach(function(settingId) {
			if (api(settingId)) {
				api(settingId).bind(function(newVal) {
					var tmpl = templates.find(function(t) { return t.id === newVal; });
					if (tmpl) {
						$('#' + settingId + '_current_title').text(tmpl.name);
						$('#' + settingId + '_current_id').text(tmpl.id);
						$('#' + settingId + '_input').val(tmpl.id);
					}
				});
			}
		});

		window.mhOpenLayoutModal = openLayoutModal;
	}

	// Expose to window and bind to wp.customize ready
	if (window.wp && window.wp.customize) {
		window.wp.customize.bind('ready', function() {
			init(window.wp.customize);
		});
	}

})(window, jQuery);
