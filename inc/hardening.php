<?php
/**
 * Hardening, head cleanup and privacy guards.
 *
 * A healthcare site should leak as little as possible: no fingerprinting
 * tags, no XML-RPC, no anonymous user enumeration, conservative headers,
 * and no comment forms anywhere (comments are a free-text box the public
 * could type health details into).
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'harborview_cleanup_head' );

/**
 * Strip emoji assets, generator and discovery tags.
 */
function harborview_cleanup_head(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );

	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	add_filter( 'the_generator', '__return_empty_string' );
}

// XML-RPC off, pingback header gone.
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );
add_filter( 'wp_headers', 'harborview_remove_pingback_header' );

/**
 * Remove X-Pingback.
 *
 * @param array $headers Headers.
 */
function harborview_remove_pingback_header( array $headers ): array {
	unset( $headers['X-Pingback'] );
	return $headers;
}

// No public free-text inputs: close comments and pings site-wide.
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 10 );

add_filter( 'rest_endpoints', 'harborview_restrict_rest_endpoints' );

/**
 * Hide user listing and the comments endpoint from anonymous visitors.
 *
 * @param array $endpoints Endpoints.
 */
function harborview_restrict_rest_endpoints( array $endpoints ): array {
	if ( ! is_user_logged_in() ) {
		foreach ( array( '/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)', '/wp/v2/comments', '/wp/v2/comments/(?P<id>[\d]+)' ) as $route ) {
			unset( $endpoints[ $route ] );
		}
	}
	return $endpoints;
}

add_action( 'send_headers', 'harborview_security_headers' );

/**
 * Security headers. "no-referrer" on cross-origin requests means the portal
 * (or any outbound link) never learns which page a visitor came from.
 */
function harborview_security_headers(): void {
	if ( headers_sent() ) {
		return;
	}
	$headers = apply_filters(
		'harborview_security_headers',
		array(
			'X-Content-Type-Options'    => 'nosniff',
			'X-Frame-Options'           => 'SAMEORIGIN',
			'Referrer-Policy'           => 'strict-origin-when-cross-origin',
			'Permissions-Policy'        => 'camera=(), microphone=(), geolocation=(), payment=(), interest-cohort=()',
			'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
		)
	);
	foreach ( $headers as $name => $value ) {
		header( sprintf( '%s: %s', $name, $value ) );
	}
}
