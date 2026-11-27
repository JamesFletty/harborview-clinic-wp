<?php
/**
 * Front-end assets: filemtime() cache busting, defer strategy, no jQuery.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cache-busting version for a theme-relative file.
 *
 * @param string $relative Path relative to the theme root.
 */
function harborview_asset_version( string $relative ): string {
	$path = HARBORVIEW_DIR . '/' . ltrim( $relative, '/' );
	return file_exists( $path ) ? (string) filemtime( $path ) : HARBORVIEW_VERSION;
}

add_action( 'wp_enqueue_scripts', 'harborview_enqueue_assets' );

/**
 * Enqueue styles and scripts.
 */
function harborview_enqueue_assets(): void {
	$styles = array(
		'base'       => array(),
		'layout'     => array( 'harborview-base' ),
		'components' => array( 'harborview-layout' ),
	);
	foreach ( $styles as $name => $deps ) {
		$relative = "assets/css/{$name}.css";
		wp_enqueue_style( "harborview-{$name}", get_theme_file_uri( $relative ), $deps, harborview_asset_version( $relative ) );
	}

	$script_args = array(
		'strategy'  => 'defer',
		'in_footer' => true,
	);

	wp_enqueue_script( 'harborview-main', get_theme_file_uri( 'assets/js/main.js' ), array(), harborview_asset_version( 'assets/js/main.js' ), $script_args );

	// Directory filter only where the directory renders.
	if ( is_front_page() ) {
		wp_enqueue_script( 'harborview-clinician-filter', get_theme_file_uri( 'assets/js/clinician-filter.js' ), array(), harborview_asset_version( 'assets/js/clinician-filter.js' ), $script_args );
		wp_localize_script(
			'harborview-clinician-filter',
			'harborviewDirectory',
			array(
				'endpoint' => esc_url_raw( rest_url( 'harborview/v1/clinicians' ) ),
				'i18n'     => array(
					/* translators: %d: number of clinicians */
					'count'   => __( 'Showing %d clinicians', 'harborview' ),
					/* translators: %d: number of clinicians */
					'countOne' => __( 'Showing %d clinician', 'harborview' ),
				),
			)
		);
	}

	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_head', 'harborview_preload_fonts', 3 );

/**
 * Preload the faces used above the fold. Self-hosted: no font CDN, so no
 * third party sees patients' IP addresses when they visit.
 */
function harborview_preload_fonts(): void {
	foreach ( array( 'source-serif-4-opsz.woff2', 'public-sans-wght.woff2' ) as $font ) {
		printf(
			"<link rel=\"preload\" href=\"%s\" as=\"font\" type=\"font/woff2\" crossorigin>\n",
			esc_url( get_theme_file_uri( 'assets/fonts/' . $font ) )
		);
	}
}
