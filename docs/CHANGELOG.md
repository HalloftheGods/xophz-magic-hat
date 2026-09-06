# Changelog

All notable changes to the Xophz Magic Hat theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [26.5.33] - 2026-09-06

### Added
- Modular Section Stylesheets (`assets/css/sections/`): Created 6 decomposed category stylesheets (`hero-overlap.css`, `content-about.css`, `features-numbers.css`, `team-testimonials.css`, `cta-contact.css`, `pricing-portfolio.css`) referencing central Magic Hat design tokens (`var(--mh-color-*)`, `var(--wp--preset--*)`) with zero raw hex color codes and zero unreferenced WebGradients.
- Editor Styles for Block Sections (`functions.php`): Registered all 6 section stylesheets in `add_editor_style()` for WYSIWYG Gutenberg Site Editor parity.
- Native Gutenberg Block Pattern Styling: Configured all category stylesheets to style 100% native Gutenberg core block markup under scoped `.mh-section-*` wrapper classes.

### Changed
- Modernized Section Enqueues (`functions.php`): Replaced legacy `magic-hat-ope-sections` enqueue with dynamic iteration over the 6 modular category section stylesheets.

### Fixed
- Gutenberg Block Support in Site Editor (`assets/js/editor-blocks.js`, `functions.php`, `inc/header-footer.php`, `inc/hero.php`): Fixed issue where Gutenberg Site Editor displayed "Your site doesn't include support for the block" error for `xophz-magic-hat/header`, `xophz-magic-hat/hero`, and `xophz-magic-hat/footer`. Created dedicated block editor script registering all three dynamic block types in Gutenberg runtime (`wp.blocks.registerBlockType`) with `wp.serverSideRender` support. Enqueued editor script and styles via `enqueue_block_editor_assets` and linked `editor_script` in PHP block registrations.

### Removed
- Monolithic Stylesheet Purge: Permanently removed the 5,307-line legacy monolith `assets/css/one-page-express-sections.css`.

## [26.5.32] - 2026-09-06

### Changed
- Hero Template Clean Restoration (`inc/hero.php`, `inc/customizer.php`): Restored hero template to standard surface card layout, eliminating full-bleed site logo background coverage and bottom separator SVGs.

### Removed
- Purged Alien Stock Photos and Separator Assets: Removed `assets/images/` and `assets/separators/`, preserving pure design token styling.

## [26.5.31] - 2026-09-06

### Added
- Accordion Toggle Control (`inc/customizer/controls/class-accordion-control.php`): Introduced `Magic_Hat_Accordion_Toggle_Control` enabling collapsible groups inside Customizer sections.
- General Settings Panel (`inc/customizer/sections/panel-general-settings.php`): Registered unified `magic_hat_general_settings` parent panel consolidating Site Identity, Homepage Settings, Site Colors, and design system sections.

### Changed
- Customizer Hierarchy Streamlining (`inc/customizer/sections/reorder-hierarchy.php`): Consolidated top-level navigation into a 7-item spatial structure: Header (10), General Settings (20), Shop Settings (30), Menu Settings (40), Hero (50), Page Settings / AI Architect (60 / 65), and Footer (70).
- Accordion Site Colors Section (`inc/customizer/sections/section-site-colors.php`): Converted Site Colors from a standalone top-level panel into a streamlined section inside General Settings with 8 collapsible accordion rows (Schedule Mode, Brand, Action CTA, Links, Text, Surfaces, Borders, Status, Editor Settings).
- Design System Sections Reparenting (`inc/customizer/sections/section-*.php`): Reparented Background, Typography, Spacing, Buttons, and Custom CSS into the General Settings panel.
- Controls UI Accordion Interactions (`inc/customizer/controls-ui.php`): Added accordion header CSS styling and animated toggle folding handlers with accessibility aria-expanded support.
- Stylebook Deep-Linking Updates (`inc/stylebook-template.php`): Updated Colors navigation target to `magic_hat_colors` and expanded panel checks for `magic_hat_general_settings`.

### Fixed
- Page Sections JSON Sanitization (`inc/customizer/sections/section-page-builder.php`): Replaced `sanitize_text_field` on the `mh_page_sections` Customizer setting with safe JSON validation. `sanitize_text_field` stripped newlines and corrupted multiline item lists (such as pricing features); the custom validator verifies valid JSON arrays while preserving item formatting.
- Accordion Child DOM Resolution (`inc/customizer/controls-ui.php`): Resolved accordion collapse failure by switching child control resolution to jQuery `nextUntil('.customize-control-mh_accordion_toggle')`. WordPress core `WP_Customize_Color_Control` ignores `input_attrs`, causing selector lookups to fail. Also added focus detection to auto-expand accordion groups on deep-link.
- Accordion Initial Fold State (`inc/customizer/sections/section-site-colors.php` & `inc/customizer/controls-ui.php`): Configured all 8 color accordion groups to start closed by default (`is_open => false`) and added default folded CSS rules on child controls, eliminating initial expansion and flash of uncollapsed content.

## [26.5.30] - 2026-09-06

### Changed
- Customizer Page Sections Transport (`inc/customizer/sections/section-page-builder.php`): Switched `mh_page_sections` setting transport from `refresh` to `postMessage`, preventing jarring full-page iframe reloads during section customization and content saves.

### Fixed
- Debounced Keyup Text Saving (`assets/js/customizer-preview-header-footer.js`): Added 750ms keyup debounce delay to inline text editing (`[data-mh-focus]`), preventing instantaneous saves and partial re-renders from interrupting typing mid-word. Implemented caret offset tracking and automatic restoration across selective refresh cycles.
- In-Canvas Inline Text Focus Retention (`assets/js/customizer-preview-header-footer.js`): Prevented `[data-mh-focus]` click handlers from unconditionally stealing browser focus and forcing sidebar control focus on direct text element clicks. In-place content editing now retains focus directly within the preview canvas, reserving sidebar navigation for Shift-click interactions or explicit settings buttons.

## [26.5.29] - 2026-09-06

### Changed
- Customizer Anatomical Reordering (`inc/customizer/sections/*` & `inc/hero.php`): Reordered Customizer sections and panels to model the website as a person from head to toe with the Hat on top (`🎩 Header Settings` at priority 10) and Boots anchoring the bottom (`🥾 Footer Settings` at priority 100).
- Section Hierarchy Priorities: Standardized priorities across standard and late hooks: Header Settings (10), Site Identity (15), Homepage Settings (20), Shop Settings (25), Menu Settings (30), Hero Settings (35), Page Settings (40), AI Page Architect (45), Site Colors (50), Site Styles (55), and Footer Settings (100).
- Stylebook Customizer Toggle Icon (`inc/customizer/controls-ui.php`): Changed the `#mh-toggle-sb` header shortcut button icon from `🎩` to the open book `📖`, resolving visual conflict with Header Settings.
- Section Nomenclature (`inc/customizer/sections/section-page-builder.php` & `reorder-hierarchy.php`): Renamed "🏗️ Page Builder" to "🏗️ Page Settings".

### Fixed
- Tracking Script Fallback Stubs (`inc/customizer/helpers.php`): Added early safe fallback definitions for `window.rdt` and `window.snaptr`, eliminating uncaught "Function rdt not implemented" and "Function snaptr not implemented" errors from WooCommerce tracking plugins in development and ad-blocker environments.

## [26.5.28] - 2026-09-06

### Added
- Visual Identity Refresh (`screenshot.png`, `icon.svg`, `icon.png`): Replaced legacy crude graphics with modern starship-aesthetic theme screenshot showcase (Concept A FSE dashboard card with circadian wave and glowing insignia) and circular vector emblem logo (Option 3 with glowing neon cyan ring, crescent moon buckle, and transparent perimeter).

### Changed
- Quantum Customizer Architecture Refactoring (`inc/customizer.php` & `inc/customizer/*`): Refactored monolithic 2,167-line Customizer file into a clean 63-line Table-of-Contents entrypoint delegating to an isolated, modular `inc/customizer/` capsule.
- Dedicated Customizer Controls (`inc/customizer/controls/*`): Extracted `Magic_Hat_Range_Slider_Control`, `Magic_Hat_Font_Control`, `Magic_Hat_AI_Architect_Control`, `Magic_Hat_Page_Builder_Control`, and `Magic_Hat_Group_Title_Control` into dedicated class files strictly under 185 lines each.
- Modular Panels and Sections (`inc/customizer/sections/*`): Decomposed section registration across single-responsibility modules: AI Page Architect, Page Builder, Site Styles panel, Site Colors panel (with dedicated sub-sections), Background & Canvas, Typography, Spacing, Buttons, Header, Footer, and Hierarchy Reordering.
- Dedicated CSS, UI Scripts, Circadian, and Canvas Engines (`inc/customizer/*`): Extracted runtime subsystems into `css-generator.php`, `controls-ui.php`, `circadian.php`, and `ambient-canvas.php`, achieving 100% compliance with file line limits (all files under 300 lines) with zero regressions to Customizer settings or identifiers.
- Restored Dedicated Site Colors Panel (`inc/customizer/sections/panel-site-colors.php` & `inc/stylebook-template.php`): Maintained "🎨 Site Colors" as its own top-level Customizer panel with its full set of individual sub-sections intact, ensuring seamless Stylebook navigation and preview synchronization.

### Removed
- Redundant Customize Submenu (`functions.php`): Removed `xophz_magic_hat_add_customize_menu()` manual registration of `customize.php` under Appearance, eliminating duplicate Customize entries in WP Admin.

## [26.5.27] - 2026-09-05

### Changed
- Core Customizer Nomenclature and Icons (`inc/customizer.php`): Renamed Menus to "🧭 Menu Settings" and assigned dedicated emojis across all core top-level items including "🆔 Site Identity" and "🏠 Homepage Settings" with persistent late adjustment overrides.

## [26.5.26] - 2026-09-05

### Added
- Custom Background Image Support (`inc/customizer.php`): Introduced "Custom Background Image" mode with full image upload control, sizing options (cover, contain, auto), repeat/tile settings, alignment positioning, parallax/fixed scroll toggle, and fallback background color.

### Changed
- Refreshed Customizer Emojis (`inc/customizer.php`): Updated Site Styles to use artist emoji (🧑‍🎨 Site Styles), Site Colors to use palette emoji (🎨 Site Colors), and Site Background & Canvas to use frame emoji (🖼️ Site Background & Canvas).

## [26.5.25] - 2026-09-05

### Changed
- Customizer Menus Priority (`inc/customizer.php`): Set `nav_menus` panel priority to 115 across standard registration and late adjustments hooks, placing Menus after Footer Settings in the hierarchy.

## [26.5.24] - 2026-09-05

### Changed
- Contextual Background Controls Visibility (`inc/customizer.php`): Added `active_callback` helpers (`mh_is_bg_mode_solid`, `mh_is_bg_mode_gradient`, `mh_is_bg_mode_canvas`) and client-side reactive bindings ensuring solid, gradient, and animated canvas controls only display when their corresponding Background Mode is active.

## [26.5.23] - 2026-09-05

### Changed
- Standardized Customizer Appearance (`inc/customizer.php`): Removed custom dark gradient background and colored borders from `Site Styles`, restoring 100% native WordPress Customizer styling uniformity across all sidebar items.
- Native Nested Hierarchy for Site Colors (`inc/customizer.php` & `inc/stylebook-template.php`): Converted "🎭 Site Colors" into a native Customizer section (`magic_hat_colors`) nested directly inside "🎨 Site Styles" with clean category headings (`Magic_Hat_Group_Title_Control`), eliminating the orphaned top-level row and removing obsolete DOM manipulation scripts.

## [26.5.22] - 2026-09-05

### Added
- Site Styles Parent Panel (`inc/customizer.php`): Created unified "🎨 Site Styles" parent panel (priority 30) grouping Site Background & Canvas, Typography, Spacing & Layout, Buttons, and Additional CSS.
- Nested Site Colors Sub-Panel (`inc/customizer.php`): Implemented seamless nested panel navigation embedding "🎭 Site Colors" inside Site Styles with smooth back navigation.

### Changed
- Reordered Top-Level Customizer Hierarchy (`inc/customizer.php` & `inc/hero.php`): Consolidated top-level navigation into 10 clean areas: AI Page Architect (10), Site Identity (20), Site Styles (30), Homepage Settings (40), Shop Settings (50), Menus (60), Header Settings (70), Hero Settings (80), Page Builder (90), Footer Settings (100).

## [26.5.21] - 2026-09-05

### Changed
- Uniform Settings Nomenclature (`inc/customizer.php` & `inc/hero.php`): Renamed all customizer sections containing "Options" to "Settings" for consistent naming: "🎩 Header Settings", "🌟 Hero Settings", and "🦶 Footer Settings".
- WooCommerce Renamed to Shop Settings (`inc/customizer.php`): Renamed WooCommerce panel and section title to "🛍️ Shop Settings" at priority 95, with late adjustment hook ensuring third-party registration priority compatibility.

## [26.5.20] - 2026-09-05

### Changed
- Live Typography Token Binding (`inc/stylebook-template.php` & `page-styleguide.php`): Updated `.type-preview` headings H1-H6 and paragraphs to consume live Customizer CSS properties (`--mh-font-size-h1` through `--mh-font-size-h6`, `--mh-font-size`, `--mh-heading-weight`, `--mh-heading-line-height`, `--mh-line-height`) instead of hardcoded fallback tokens.
- Customizer PostMessage Transport for Typography and Spacing (`inc/customizer.php`): Enabled `'transport' => 'postMessage'` on base font family, font size, line height, heading weight, heading line height, all H1-H6 size controls, and spacing tokens. Added `change` trigger in `Magic_Hat_Range_Slider_Control` for 60fps responsiveness.
- Global Element Typography Rules (`inc/customizer.php`): Bound `h1`-`h6`, `.wp-block-heading`, and paragraph elements in `xophz_magic_hat_customizer_css()` to their respective CSS variables across the theme.
- Customizer Live Preview Synchronizer (`assets/js/customizer-preview-header-footer.js` & `inc/stylebook-template.php`): Added real-time CSS variable and dynamic Google Fonts link updating in the preview iframe for all typography and layout adjustments.

## [26.5.19] - 2026-09-05

### Added
- Single Page Template (`page.php`): Created dedicated template supporting optional Hero Options (`mh_render_hero_markup()`) and clean semantic page content.

### Changed
- Customizer Hierarchy and Prioritization (`inc/customizer.php` & `inc/hero.php`): Reordered Customizer sections into intuitive flow: AI Page Architect (10), Site Identity (20), Site Background & Canvas (30), Site Colors (40), Typography (50), Spacing & Layout (60), Buttons (70), Additional CSS (80), Homepage Settings (90), WooCommerce (95), Menus (100), Header Options (110), Hero Options (120), Page Builder (130), Footer Options (140).
- Renamed Hero Section to Hero Options (`inc/hero.php` & `front-page.php`): Renamed section to "🌟 Hero Options", updated control labels to "Enable Hero Options", and added dynamic page title fallbacks for non-front pages.

### Fixed
- Magic Wand Detection in Customizer (`inc/customizer.php`): Resolved undefined `$is_active` variable in `Magic_Hat_Page_Builder_Control::render_content()` that caused a false "Magic Wand Required" warning and disabled the "+ Add Section" button.
- Preview Content Filter Scoping (`inc/customizer.php`): Scoped "+ Add Section" preview append helper to canvas mode and pages with sections to prevent polluting standard Gutenberg pages.

## [26.5.18] - 2026-09-05

### Fixed
- Horizontal Layout Overflow and Scrollbar Elimination (`assets/css/header-footer.css`): Resolved horizontal overflow caused by `.mh-mobile-nav-drawer` translating 320px off-screen inside `.mh-header-sticky`. Moved `backdrop-filter` from `.mh-header-sticky` to a pseudo-element (`::before`) to prevent the header from becoming an unintended containing block for `position: fixed` elements, added `overflow: hidden` to `.mh-mobile-nav`, and ensured sticky header functionality within Full Site Editing template parts (`.wp-block-template-part:has(.mh-header-sticky)`).

## [26.5.17] - 2026-09-05

### Added
- In-Canvas Header and Footer Layout Cycling (`assets/js/customizer-preview-header-footer.js`): Added "Cycle Layout" action buttons on both `#mw-header` and `#mw-footer` floating preview badges. Users can instantly cycle through Header layouts (Standard, Centered, Split, Minimal) and Footer layouts (4-Column Mega, 3-Column, Centered Minimal, Split Modern) with instant Selective Refresh re-rendering directly in the Customizer canvas.

### Changed
- Default WordPress Color Selectors (`inc/customizer.php`): Reverted custom CSS overrides and row header controls in Site Colors panel. Restored native WordPress `WP_Customize_Color_Control` selectors with standard full-width layout, default "Select Color" swatches, and clean inline Iris color pickers.

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
