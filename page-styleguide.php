<?php
/**
 * Template Name: Style Guide
 * 
 * The template for displaying the Magic Hat Stylebook and Design Tokens.
 */
get_header(); ?>

<div class="mh-styleguide-container" style="max-width: 1200px; margin: 0 auto; padding: var(--mh-spacing-8); color: var(--mh-color-text-main); font-family: var(--mh-font-body); background-color: var(--mh-color-body); min-height: 100vh;">
    <header style="margin-bottom: var(--mh-spacing-12);">
        <h1 style="font-family: var(--mh-font-heading); color: var(--mh-color-brand); text-shadow: var(--mh-glow-brand); font-size: var(--mh-text-4xl);">Magic Hat Stylebook</h1>
        <p style="color: var(--mh-color-text-muted); font-size: var(--mh-text-lg);">Real-time preview of all active Design Tokens.</p>
    </header>

    <section style="margin-bottom: var(--mh-spacing-12);">
        <h2 style="font-family: var(--mh-font-heading); font-size: var(--mh-text-2xl); border-bottom: 1px solid var(--mh-glass-border); padding-bottom: var(--mh-spacing-2); margin-bottom: var(--mh-spacing-6);">1. Color System (Semantic Intent)</h2>
        
        <h3 style="color: var(--mh-color-text-muted); font-size: var(--mh-text-lg); margin-bottom: var(--mh-spacing-2);">Brand & CTA</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--mh-spacing-4); margin-bottom: var(--mh-spacing-6);">
            <div style="background: var(--mh-color-brand); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-inverse); font-weight: bold;">--mh-color-brand</div>
            <div style="background: var(--mh-color-brand-alt); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-inverse); font-weight: bold;">--mh-color-brand-alt</div>
            <div style="background: var(--mh-color-cta); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-main); font-weight: bold;">--mh-color-cta</div>
            <div style="background: var(--mh-color-cta-alt); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-main); font-weight: bold;">--mh-color-cta-alt</div>
        </div>

        <h3 style="color: var(--mh-color-text-muted); font-size: var(--mh-text-lg); margin-bottom: var(--mh-spacing-2);">Links</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--mh-spacing-4); margin-bottom: var(--mh-spacing-6);">
            <div style="background: var(--mh-color-link); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-inverse); font-weight: bold;">--mh-color-link</div>
            <div style="background: var(--mh-color-link-hover); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-main); font-weight: bold;">--mh-color-link-hover</div>
        </div>

        <h3 style="color: var(--mh-color-text-muted); font-size: var(--mh-text-lg); margin-bottom: var(--mh-spacing-2);">Surfaces & Backgrounds</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--mh-spacing-4); margin-bottom: var(--mh-spacing-6);">
            <div style="background: var(--mh-color-body); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); border: 1px solid var(--mh-glass-border);">--mh-color-body</div>
            <div style="background: var(--mh-color-main); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); border: 1px solid var(--mh-glass-border);">--mh-color-main</div>
            <div style="background: var(--mh-color-section); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); border: 1px solid var(--mh-glass-border);">--mh-color-section</div>
            <div style="background: var(--mh-color-card); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); border: 1px solid var(--mh-glass-border);">--mh-color-card</div>
        </div>

        <h3 style="color: var(--mh-color-text-muted); font-size: var(--mh-text-lg); margin-bottom: var(--mh-spacing-2);">Status System</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--mh-spacing-4); margin-bottom: var(--mh-spacing-6);">
            <div style="background: var(--mh-color-success); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-inverse); font-weight: bold;">--mh-color-success</div>
            <div style="background: var(--mh-color-warning); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-inverse); font-weight: bold;">--mh-color-warning</div>
            <div style="background: var(--mh-color-danger); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-main); font-weight: bold;">--mh-color-danger</div>
            <div style="background: var(--mh-color-info); padding: var(--mh-spacing-4); border-radius: var(--mh-radius-md); color: var(--mh-color-text-inverse); font-weight: bold;">--mh-color-info</div>
        </div>
    </section>

    <section style="margin-bottom: var(--mh-spacing-12);">
        <h2 style="font-family: var(--mh-font-heading); font-size: var(--mh-text-2xl); border-bottom: 1px solid var(--mh-glass-border); padding-bottom: var(--mh-spacing-2); margin-bottom: var(--mh-spacing-6);">2. Typography</h2>
        <div style="background: var(--mh-color-card); padding: var(--mh-spacing-6); border-radius: var(--mh-radius-lg); backdrop-filter: blur(var(--mh-glass-blur-md)); border: 1px solid var(--mh-glass-border);">
            <h1 style="font-size: var(--mh-text-4xl); font-family: var(--mh-font-heading); margin-bottom: var(--mh-spacing-4);">Heading 1 (4xl)</h1>
            <h2 style="font-size: var(--mh-text-3xl); font-family: var(--mh-font-heading); margin-bottom: var(--mh-spacing-4);">Heading 2 (3xl)</h2>
            <h3 style="font-size: var(--mh-text-2xl); font-family: var(--mh-font-heading); margin-bottom: var(--mh-spacing-4);">Heading 3 (2xl)</h3>
            <p style="font-size: var(--mh-text-base); margin-bottom: var(--mh-spacing-4);">Body Text (base): This is an example of the standard body text using the defined font family and sizing. Magic Hat aims to make reading effortless.</p>
            <p style="font-size: var(--mh-text-sm); color: var(--mh-color-text-muted); margin-bottom: 0;">Muted Text (sm): Secondary information or captions use this token to establish visual hierarchy without adding clutter.</p>
        </div>
    </section>

    <section style="margin-bottom: var(--mh-spacing-12);">
        <h2 style="font-family: var(--mh-font-heading); font-size: var(--mh-text-2xl); border-bottom: 1px solid var(--mh-glass-border); padding-bottom: var(--mh-spacing-2); margin-bottom: var(--mh-spacing-6);">3. Glassmorphism & UI Components</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--mh-spacing-6);">
            
            <!-- Glass Card -->
            <div style="background: var(--mh-glass-bg); backdrop-filter: blur(var(--mh-glass-blur-md)); border: 1px solid var(--mh-glass-border); border-radius: var(--mh-radius-lg); padding: var(--mh-spacing-6); box-shadow: var(--mh-shadow-md);">
                <h3 style="margin-top: 0; color: var(--mh-color-brand); font-family: var(--mh-font-heading); font-size: var(--mh-text-xl); margin-bottom: var(--mh-spacing-4);">Glass Card</h3>
                <p style="margin-bottom: var(--mh-spacing-6);">This card utilizes the glass blur, background opacity, and radius tokens. It perfectly demonstrates the high-tech aesthetic.</p>
                <button style="background: var(--mh-color-cta); color: var(--mh-color-text-main); border: none; padding: var(--mh-spacing-2) var(--mh-spacing-4); border-radius: var(--mh-radius-sm); font-weight: bold; cursor: pointer; transition: all var(--mh-transition-fast); font-family: var(--mh-font-body);">Call To Action</button>
            </div>

            <!-- Glowing Element -->
            <div style="background: var(--mh-color-section); border-radius: var(--mh-radius-lg); padding: var(--mh-spacing-6); border: 1px solid var(--mh-color-brand); box-shadow: var(--mh-glow-brand);">
                <h3 style="margin-top: 0; color: var(--mh-color-brand); font-family: var(--mh-font-heading); font-size: var(--mh-text-xl); margin-bottom: var(--mh-spacing-4);">Neon Accent Card</h3>
                <p style="margin-bottom: 0;">This card demonstrates the brand glow token and elevated section token to draw attention to critical elements.</p>
            </div>

        </div>
    </section>

</div>

<?php get_footer(); ?>
