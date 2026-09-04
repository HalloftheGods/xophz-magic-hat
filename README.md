# Xophz Magic Hat Theme

**Xophz Magic Hat** is the foundational parent theme for the Elysium ecosystem. Designed to pair with the **Magic Wand** site editor and **Project Compass**, Magic Hat eliminates traditional bloated theme stylesheets in favor of runtime CSS Custom Properties, semantic HTML5 structure, continuous astronomical lighting, and Full Site Editing (FSE) block templates.

---

## Features

- **Full Site Editing (FSE)**: Native block templates (`templates/index.html`, `templates/page.html`, `templates/blank.html`) and template parts (`parts/header.html`, `parts/footer.html`).
- **Circadian Rhythm Engine**: Continuous 24-hour astronomical daylight calculations syncing color temperature and theme contrast to local time.
- **Magic Hat AI Page Architect**: Direct integration with Gemini and internal layout synthesizers to conjure native Gutenberg block layouts across diverse vibes (Starship Neon, Minimal Glass, Cyberpunk Dusk, Solar Dawn) and archetypes (Landing, Portfolio, SaaS, Editorial, Bento).
- **Design Tokens (`theme.json`)**: Semantic design tokens defining color palettes, typography scales, fluid layout widths, and spacing presets.
- **Block Patterns**: Curated block patterns for heroes, feature matrices, call-to-actions, headers, and footers.
- **Automated CI/CD**: Integrated with Hall of the Gods automated build pipeline for semantic versioning and release zip packaging.

---

## Installation & Usage

1. Clone or add as a submodule into your WordPress themes directory:
   ```bash
   git submodule add -b main git@github.com:HalloftheGods/xophz-magic-hat.git wp-content/themes/xophz-magic-hat
   ```
2. Activate the theme via WordPress Admin (**Appearance > Themes**) or WP-CLI:
   ```bash
   wp theme activate xophz-magic-hat
   ```
3. Open **Magic Wand** in Project Compass or **Appearance > Customize** to build pages and configure circadian lighting.

---

## License

GNU General Public License v2 or later.
