<?php
/**
 * Theme setup and practice-level settings.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'harborview_setup' );

/**
 * Register theme features.
 */
function harborview_setup(): void {
	load_theme_textdomain( 'harborview', HARBORVIEW_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	remove_theme_support( 'core-block-patterns' );

	add_editor_style( array( 'assets/css/base.css' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'harborview' ),
			'footer'  => __( 'Footer navigation', 'harborview' ),
		)
	);

	add_image_size( 'hv-portrait', 720, 900, true );
}

/**
 * Practice settings with safe defaults. Stored as theme mods so the office
 * manager can change hours or the portal URL in the Customizer.
 *
 * @param string $key Setting key.
 */
function harborview_setting( string $key ): string {
	$defaults = array(
		'phone'        => '(207) 555-0186',
		'fax'          => '(207) 555-0187',
		'address_1'    => '412 Fore Street, Suite 3',
		'address_2'    => 'Portland, ME 04101',
		'portal_url'   => 'https://portal.harborviewwellness.example/',
		'portal_name'  => __( 'Harborview Patient Portal', 'harborview' ),
		'hours_week'   => __( 'Mon–Thu 8:00 am – 6:00 pm', 'harborview' ),
		'hours_fri'    => __( 'Fri 8:00 am – 4:00 pm', 'harborview' ),
		'city_line'    => __( 'Portland, Maine', 'harborview' ),
	);
	return (string) get_theme_mod( 'harborview_' . $key, $defaults[ $key ] ?? '' );
}

add_action( 'customize_register', 'harborview_customize_register' );

/**
 * Expose practice settings in the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function harborview_customize_register( $wp_customize ): void {
	$wp_customize->add_section(
		'harborview_practice',
		array(
			'title'    => __( 'Practice details', 'harborview' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'phone'      => array( __( 'Main phone', 'harborview' ), 'sanitize_text_field' ),
		'address_1'  => array( __( 'Street address', 'harborview' ), 'sanitize_text_field' ),
		'address_2'  => array( __( 'City, state, ZIP', 'harborview' ), 'sanitize_text_field' ),
		'portal_url' => array( __( 'Patient portal URL (must be https)', 'harborview' ), 'harborview_sanitize_https_url' ),
		'hours_week' => array( __( 'Weekday hours', 'harborview' ), 'sanitize_text_field' ),
		'hours_fri'  => array( __( 'Friday hours', 'harborview' ), 'sanitize_text_field' ),
	);

	foreach ( $fields as $key => [ $label, $sanitize ] ) {
		$wp_customize->add_setting(
			'harborview_' . $key,
			array(
				'default'           => harborview_setting( $key ),
				'sanitize_callback' => $sanitize,
			)
		);
		$wp_customize->add_control(
			'harborview_' . $key,
			array(
				'label'   => $label,
				'section' => 'harborview_practice',
				'type'    => 'portal_url' === $key ? 'url' : 'text',
			)
		);
	}
}

/**
 * The portal handoff must never downgrade to plain HTTP.
 *
 * @param string $url Raw URL.
 */
function harborview_sanitize_https_url( string $url ): string {
	$url = esc_url_raw( trim( $url ), array( 'https' ) );
	return str_starts_with( $url, 'https://' ) ? $url : '';
}

add_filter( 'excerpt_length', 'harborview_excerpt_length', 20 );

/**
 * Short, readable excerpts.
 */
function harborview_excerpt_length(): int {
	return 30;
}

add_action( 'wp_head', 'harborview_meta_description', 2 );

/**
 * Meta description without an SEO plugin; yields to Yoast/Rank Math.
 */
function harborview_meta_description(): void {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}
	$description = is_singular() && has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' );
	if ( $description ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( wp_strip_all_tags( $description ) ) );
	}
}

add_action( 'wp_head', 'harborview_favicon', 4 );

/**
 * Inline SVG favicon until a Site Icon is set.
 */
function harborview_favicon(): void {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" rx="8" fill="#1D332E"/><circle cx="16" cy="14" r="5" fill="#B86B4B"/><path d="M5 20c4-2 7-2 11 0s7 2 11 0M5 24c4-2 7-2 11 0s7 2 11 0" stroke="#F8F7F4" stroke-width="1.6" fill="none"/></svg>';
	printf( "<link rel=\"icon\" href=\"data:image/svg+xml,%s\">\n", rawurlencode( $svg ) );
}
