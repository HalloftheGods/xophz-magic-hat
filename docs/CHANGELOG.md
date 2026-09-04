# Changelog

All notable changes to the Xophz Magic Hat theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
