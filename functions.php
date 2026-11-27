<?php
/**
 * Harborview Clinic — theme bootstrap.
 *
 * functions.php only loads modules; each concern lives in inc/.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

define( 'HARBORVIEW_VERSION', '1.0.0' );
define( 'HARBORVIEW_DIR', get_template_directory() );
define( 'HARBORVIEW_URI', get_template_directory_uri() );

$harborview_modules = array(
	'setup',          // Supports, menus, practice settings (theme mods), meta description.
	'assets',         // Cache-busted, deferred, dependency-free enqueues.
	'cpt-clinicians', // hv_clinician post type, hv_specialty taxonomy, native meta box.
	'rest-endpoints', // GET /wp-json/harborview/v1/clinicians (public, read-only).
	'hardening',      // Head cleanup, XML-RPC off, security headers, no-PHI guards.
	'template-tags',  // Render helpers shared by templates.
);

foreach ( $harborview_modules as $harborview_module ) {
	require_once HARBORVIEW_DIR . '/inc/' . $harborview_module . '.php';
}

unset( $harborview_modules, $harborview_module );
