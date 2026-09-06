<?php
/**
 * Magic Hat Header (Classic PHP Template)
 *
 * @package Xophz_Magic_Hat
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
    <?php mh_render_header_markup(); ?>
