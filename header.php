<?php
/**
 * Blank Header
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-mw-canvas="true">
    <?php wp_body_open(); ?>
    <header id="mw-header" class="mw-template-part mw-dropzone" data-mw-type="header" style="min-height: 50px; outline: 1px dashed rgba(0,0,0,0.1); padding: 10px;">
        <h1 class="site-title"><?php bloginfo('name'); ?></h1>
    </header>
