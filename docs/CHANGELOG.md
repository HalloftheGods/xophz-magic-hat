# Changelog

All notable changes to the Xophz Magic Hat theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [26.5.16] - 2026-09-05

### Added
- Dedicated Front Page Hero Section (`inc/hero.php` & `templates/front-page.html`): One Page Express style prominent hero banner placed immediately beneath the header with 5 configurable layouts (Split, Centered, Editorial, App Showcase, Ambient Media), section width controls (Boxed vs Full Width), height presets, badge pills, dual action CTAs, media showcases, and instant Selective Refresh.
- Shortcode `[magic_hat_hero]`: Registered native shortcode executing `mh_render_hero_markup()` for clean placement in block templates.

### Fixed
- Front Page Page Title Removal: Created dedicated `templates/front-page.html` with unconstrained layout (`{"type":"default"}`) and zero `wp:post-title` block, permanently eliminating the intrusive "Homepage" heading from the front page canvas.
- AI Page Architect Empty Model Dropdown: Pre-populated `<select id="mh-ai-model">` server-side in `Magic_Hat_AI_Architect_Control::render_content()` and embedded `window.mhAiConnectors` inline so model options are instantly rendered on page load and protected against accidental erasure.
- AI Page Architect Button Interactions: Refactored `assets/js/customizer-ai-architect.js` with top-level document-delegated event listeners for Vibe pills, Archetype pills, Preset builders, and the Conjure button.
- Browser `window.crypto.randomUUID` Polyfill: Added early polyfill at priority 0 to `customize_controls_print_scripts`, `admin_print_scripts`, `admin_head`, and `wp_head` in `inc/customizer.php`, eliminating uncaught TypeErrors from tracking scripts on local unencrypted HTTP origins.

## [26.5.15] - 2026-09-05

### Added
- Universal Animated Canvas Background Library (`assets/js/canvases/magic-hat-canvases.js`): High-performance, zero-dependency HTML5 2D canvas engine with 21 animated generative presets synchronized from YouMeOS and Project COMPASS:
  - `electric-wave` (Harmonic sine waves and energy sparks)
  - `aurora-smoke` (Ethereal drifting mist and orbital particles)
  - `celestial-cosmos` (Starfield, constellations, cosmic dust, shooting stars)
  - `quantum-particles` (Interactive connected nodes responding to mouse proximity)
  - `cyber-matrix` (Cyberpunk digital glyph stream)
  - `tesseract-4d` (4D wireframe hypercube matrix with pulse lines)
  - `bubblegum` (Bouncing pastel elastic bubbles)
  - `alphabet-soup` (Floating typography noodles with water ripples)
  - `midnight-nerd` (Retro synthwave horizon grid)
  - `wormhole` (High-speed space warp tunnel)
  - `sun-corona` (Radiant solar flares and energy corona)
  - `saturn-rings` (Orbital particle ring system)
  - `fluid-mesh` (Ambient morphing gradient blobs)
  - `wizards-tower` (Arcane runes, mystic orbs, and hovering energy particles)
  - `magic-formula` (Bubbling potion flask with popping bubbles and reactive fizz)
  - `enchiridion` (Neural knowledge web with pulsed synaptic nodes)
  - `omega-source` (Gravitational particle vortex and energetic solar tendrils)
  - `telescope` (Deep space star parallax field)
  - `logos` (Crystalline magnetic constellation mesh)
  - `nucleos` (Multi-layer atomic orbital ring system)
  - `jupiter-gravity` (Gravitational lensing and cosmic accretion flow)
- Monorepo Canvas Sync Script (`scripts/sync-canvases.mjs`): Automatically validates, packages, and syncs canvas components across `apps/youmeos`, `src/components/primitives`, and `wp-content/themes/xophz-magic-hat`.
- Customizer Site Background & Canvas Section (`magic_hat_background`): Configure background surface style with 4 modes (Default Circadian Surface, Solid Color, Linear Gradient, Animated Generative Canvas), full 21-preset selector, accent tint color, opacity, and animation speed multiplier.
- Customizer Day / Night Schedule & Color Mode Section (`mh_colors_schedule`): Toggle between dynamic 24-hour astronomical circadian rhythm synchronization or permanent 24/7 locked Light, Twilight, or Dark modes.
- PostMessage Live Preview Integration (`assets/js/customizer-preview-header-footer.js`): Real-time live background updates and animated canvas preset switching without reloading the preview frame.

### Fixed
- Customizer Site Colors 3-to-a-Row Triad Layout: Resolved selector mismatch by targeting `ul[id*="sub-accordion-section-mh_colors_"]` and `.control-section[id*="mh_colors_"] .accordion-section-content` with flexbox layout (`width: calc(33.333% - 4px)`), row header labels, compact pick buttons, and non-clipping color picker popovers (`z-index: 999999`).
- Gutenberg Site Editor Contrast: Resolved invisible text on white canvas by binding editor palette dynamically to active circadian mode and loading high-contrast typography styles via `assets/css/editor-style.css` and `add_editor_style()`.

## [26.5.14] - 2026-09-05

### Added
- Unified Dynamic Header & Footer Engine (`inc/header-footer.php`): Registered dynamic Gutenberg blocks `xophz-magic-hat/header` and `xophz-magic-hat/footer` unifying FSE block templates (`parts/header.html`, `parts/footer.html`) and classic PHP templates (`header.php`, `footer.php`).
- Customizer Header Options (`magic_hat_header`): Select between Standard, Centered, Split, and Minimal layouts, toggle sticky header on scroll, select container width (contained vs full), pick navigation menu, and customize CTA action button.
- Customizer Footer Options (`magic_hat_footer`): Select between 4-Column Mega, 3-Column, Centered Minimal, and Split Modern layouts, configure background surface styles, and customize copyright text.
- Responsive Mobile Hamburger Navigation Drawer: Animated hamburger toggle button (`#mh-hamburger`) with off-canvas slide-out drawer (`#mh-mobile-nav`), backdrop overlay, keyboard trapping (ESC to close), and touch-friendly tap targets.
- One Page Express Style In-Canvas Interactive Shortcuts (`assets/js/customizer-preview-header-footer.js`): Injected direct floating hover badges on `#mw-header` and `#mw-footer` in Customizer preview iframe allowing 1-click navigation to layout controls and navigation menus.
- Selective Refresh Partials: Registered `mh_header_partial` and `mh_footer_partial` with native WordPress edit shortcuts and instant live updates without full page reloads.

### Fixed
- Navigation Menu Spillover: Eliminated Gutenberg unconfigured `wp:navigation` fallback that dumped all 25+ published site pages into the header and footer, strictly honoring user-assigned menus (`primary` and `footer_1` through `footer_4`).
- Purged hardcoded legacy dark inline styles from `header.php` and `footer.php` in favor of design tokens on clean white canvas.

## [26.5.13] - 2026-09-05

### Added
- Default Blank White Canvas baseline: Pristine `#ffffff` canvas surface, `#0f172a` slate headings, `#334155` body copy, and `#2563eb` modern primary accent.
- 1-Click Front Page Template Switcher in Customizer Page Builder: Toggle between modular Magic Hat Canvas and standard WordPress Blog Posts.
- Multi-Provider WP Connectors & Model Selection in AI Page Architect: Dynamically queries registered AI connectors (Google Gemini, Anthropic Claude, OpenAI, OpenRouter, and Local Ollama) allowing users to choose their preferred provider and model with live configuration indicators.

### Changed
- Refactored `theme.json` and `assets/css/variables.css` root design tokens to default to bright, clean white surfaces with subtle border elevations.
- Updated core block patterns (`hero.php`, `features.php`, `cta.php`, `header.php`, `footer.php`) to render cleanly with high contrast on white canvas.
- Modernized Customizer color palette matrix defaults to reflect the universal light baseline.
- Unlocked `magic_hat_colors_panel`, `magic_hat_typography`, `magic_hat_spacing`, and `magic_hat_buttons` in the standard Customizer view by removing stylebook-only restrictions.

### Fixed
- Post title and archive title electric blue bleed: Switched `wp:post-title` text color from `brand-base` to `text-heading` (`#0f172a`) and added global anchor inheritance rules.
- Post card border visibility on white canvas: Replaced invisible `rgba(255,255,255,0.06)` border with `#e2e8f0` and soft elevation shadow.
- Header CTA button squashing: Added `flex-shrink: 0` and `white-space: nowrap` preventing "Get Started" from wrapping vertically.
- Purged legacy `#c3a486` tan accents and dark card border artifacts from theme files.

## [26.5.12] - 2026-09-04

### Added
- Full Site Editing (FSE) block templates:
  - `templates/index.html`: Default site template with site header, post query loop, and footer.
  - `templates/page.html`: Standard static page template.
  - `templates/single.html`: Single post detail template.
  - `templates/archive.html`: Category and archive listing template.
  - `templates/404.html`: Custom 404 error template with search.
  - `templates/blank.html`: Clean canvas template for page builders.
- FSE template parts:
  - `parts/header.html`: Semantic site header with navigation and branding.
  - `parts/footer.html`: Multi-column site footer with copyright.
- Block patterns (`patterns/`):
  - `patterns/hero.php`: Bold headline with action buttons.
  - `patterns/features.php`: 3-column glassmorphic feature card grid.
  - `patterns/cta.php`: Call-to-action banner with subscription input.
  - `patterns/header.php`: Standard header block pattern.
  - `patterns/footer.php`: Standard footer block pattern.
- WordPress `theme.json`:
  - Curated color palette including neon cyan, starship deep space, and ambient accents.
  - Typography scales (small through xx-large) and font families.
  - Spacing steps and content/wide layout sizes.
- Magic Hat AI Page Architect (`inc/class-magic-hat-ai-architect.php`):
  - Customizer section priority 5 with vibe and archetype selection.
  - REST API endpoint `/xophz/v1/magic-hat/ai-generate` bridging to Gemini and local layout synthesizer.
  - Live preview scripts `customizer-preview-ai.js` and controls `customizer-ai-architect.js`.
- GitHub Actions CI/CD workflow (`.github/workflows/compass.yml`) calling `HalloftheGods/.github/.github/workflows/master.yml@main` for automated versioning and theme release packaging.

### Changed
- Extended `functions.php` to declare theme support for `align-wide`, `wp-block-styles`, `block-templates`, `block-template-parts`, and `editor-styles`.
