<?php
/**
 * Public, read-only directory endpoint.
 *
 *   GET /wp-json/harborview/v1/clinicians
 *   GET /wp-json/harborview/v1/clinicians?specialty=trauma-ptsd&accepting=1
 *
 * Returns only public profile fields. The response is cached in a transient
 * and invalidated whenever a clinician or specialty changes.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

const HARBORVIEW_DIRECTORY_CACHE = 'harborview_directory_v1';

add_action( 'rest_api_init', 'harborview_register_rest_routes' );

/**
 * Register routes.
 */
function harborview_register_rest_routes(): void {
	register_rest_route(
		'harborview/v1',
		'/clinicians',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'harborview_rest_clinicians',
			// Intentionally public: this is the same information printed on the
			// website. Explicit __return_true documents that decision.
			'permission_callback' => '__return_true',
			'args'                => array(
				'specialty' => array(
					'description'       => __( 'Limit to a specialty slug.', 'harborview' ),
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_key',
					'default'           => '',
				),
				'accepting' => array(
					'description' => __( 'Only clinicians accepting new patients.', 'harborview' ),
					'type'        => 'boolean',
					'default'     => false,
				),
			),
		)
	);
}

/**
 * Endpoint callback.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function harborview_rest_clinicians( WP_REST_Request $request ): WP_REST_Response {
	$all = harborview_directory_data();

	$specialty = (string) $request->get_param( 'specialty' );
	$accepting = (bool) $request->get_param( 'accepting' );

	$items = array_values(
		array_filter(
			$all,
			static function ( array $c ) use ( $specialty, $accepting ): bool {
				if ( $accepting && ! $c['accepting'] ) {
					return false;
				}
				return ! $specialty || in_array( $specialty, wp_list_pluck( $c['specialties'], 'slug' ), true );
			}
		)
	);

	$response = rest_ensure_response( $items );
	$response->header( 'Cache-Control', 'public, max-age=300' );
	$response->header( 'X-Harborview-Total', (string) count( $items ) );

	return $response;
}

/**
 * Build (or read from cache) the public directory payload.
 *
 * @return array<int, array<string, mixed>>
 */
function harborview_directory_data(): array {
	$cached = get_transient( HARBORVIEW_DIRECTORY_CACHE );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'hv_clinician',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);

	$data = array_map( 'harborview_clinician_payload', $query->posts );
	set_transient( HARBORVIEW_DIRECTORY_CACHE, $data, HOUR_IN_SECONDS );

	return $data;
}

/**
 * Public fields for one clinician.
 *
 * @param WP_Post $post Clinician post.
 * @return array<string, mixed>
 */
function harborview_clinician_payload( WP_Post $post ): array {
	$terms = get_the_terms( $post->ID, 'hv_specialty' );
	$terms = ( $terms && ! is_wp_error( $terms ) ) ? $terms : array();

	return array(
		'id'          => $post->ID,
		'name'        => get_the_title( $post ),
		'url'         => get_permalink( $post ),
		'initials'    => harborview_initials( get_the_title( $post ) ),
		'credentials' => (string) get_post_meta( $post->ID, '_hv_credentials', true ),
		'role'        => (string) get_post_meta( $post->ID, '_hv_role', true ),
		'languages'   => (string) get_post_meta( $post->ID, '_hv_languages', true ),
		'accepting'   => (bool) get_post_meta( $post->ID, '_hv_accepting', true ),
		'telehealth'  => (bool) get_post_meta( $post->ID, '_hv_telehealth', true ),
		'specialties' => array_map(
			static fn( $t ): array => array(
				'slug' => $t->slug,
				'name' => $t->name,
			),
			$terms
		),
	);
}

/**
 * Drop the cached payload.
 */
function harborview_flush_directory_cache(): void {
	delete_transient( HARBORVIEW_DIRECTORY_CACHE );
}

add_action( 'trashed_post', 'harborview_flush_directory_cache' );
add_action( 'deleted_post', 'harborview_flush_directory_cache' );
add_action( 'edited_hv_specialty', 'harborview_flush_directory_cache' );
add_action( 'created_hv_specialty', 'harborview_flush_directory_cache' );
add_action( 'delete_hv_specialty', 'harborview_flush_directory_cache' );
