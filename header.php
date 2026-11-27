<?php
/**
 * Document head and site header.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#F8F7F4">
	<meta name="format-detection" content="telephone=no">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="hv-skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'harborview' ); ?></a>
<?php get_template_part( 'template-parts/site-header' ); ?>
<main id="main" class="hv-main" tabindex="-1">
