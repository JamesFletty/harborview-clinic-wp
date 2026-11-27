<?php
/**
 * Template helpers.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

/**
 * Initials for monogram avatars ("Dr. Maya Okafor" -> "MO").
 *
 * @param string $name Full name.
 */
function harborview_initials( string $name ): string {
	$name  = preg_replace( '/^(dr\.?|mr\.?|ms\.?|mx\.?)\s+/i', '', trim( $name ) );
	$parts = preg_split( '/\s+/', (string) $name );
	$first = mb_substr( $parts[0] ?? '', 0, 1 );
	$last  = count( $parts ) > 1 ? mb_substr( end( $parts ), 0, 1 ) : '';
	// WordPress core polyfills mb_substr(); mb_strtoupper() needs mbstring.
	return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $first . $last ) : strtoupper( $first . $last );
}

/**
 * Typed clinician meta accessor.
 *
 * @param int    $post_id Post ID.
 * @param string $field   Field key without the "_hv_" prefix.
 */
function harborview_meta( int $post_id, string $field ): string {
	return trim( (string) get_post_meta( $post_id, '_hv_' . $field, true ) );
}

/**
 * Specialty terms for a clinician.
 *
 * @param int $post_id Post ID.
 * @return WP_Term[]|object[]
 */
function harborview_specialties( int $post_id ): array {
	$terms = get_the_terms( $post_id, 'hv_specialty' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms : array();
}

/**
 * Accepting-patients pill.
 *
 * @param int $post_id Post ID.
 */
function harborview_status_pill( int $post_id ): string {
	$accepting = (bool) get_post_meta( $post_id, '_hv_accepting', true );
	return sprintf(
		'<span class="hv-pill %1$s"><span class="hv-pill__dot" aria-hidden="true"></span>%2$s</span>',
		$accepting ? 'hv-pill--open' : 'hv-pill--wait',
		$accepting ? esc_html__( 'Accepting new patients', 'harborview' ) : esc_html__( 'Waitlist only', 'harborview' )
	);
}

/**
 * Deterministic monogram tone so the directory has rhythm without photos.
 *
 * @param int $post_id Post ID.
 */
function harborview_tone( int $post_id ): string {
	$tones = array( 'sage', 'pine', 'clay', 'sand' );
	return $tones[ $post_id % count( $tones ) ];
}

/**
 * Outbound portal link. rel="noopener" plus a strict referrer policy so the
 * portal vendor never receives the page path a patient came from.
 *
 * @param string $label   Link text.
 * @param string $classes CSS classes.
 */
function harborview_portal_link( string $label, string $classes = 'hv-button hv-button--solid' ): string {
	return sprintf(
		'<a class="%1$s" href="%2$s" rel="noopener external" referrerpolicy="no-referrer" target="_blank">%3$s<span class="screen-reader-text"> %4$s</span><span aria-hidden="true" class="hv-ext">↗</span></a>',
		esc_attr( $classes ),
		esc_url( harborview_setting( 'portal_url' ) ),
		esc_html( $label ),
		esc_html__( '(opens the secure patient portal in a new tab)', 'harborview' )
	);
}

/**
 * Primary menu fallback until a menu is assigned.
 *
 * @param array $args wp_nav_menu() args.
 */
function harborview_fallback_menu( array $args = array() ): string {
	$items = array(
		array( __( 'Care', 'harborview' ), home_url( '/#care' ) ),
		array( __( 'Clinicians', 'harborview' ), home_url( '/#clinicians' ) ),
		array( __( 'New patients', 'harborview' ), home_url( '/#portal' ) ),
		array( __( 'Insurance', 'harborview' ), home_url( '/#insurance' ) ),
		array( __( 'FAQ', 'harborview' ), home_url( '/#faq' ) ),
	);
	$html = sprintf( '<ul class="%s">', esc_attr( $args['menu_class'] ?? 'hv-menu' ) );
	foreach ( $items as [ $label, $url ] ) {
		$html .= sprintf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	$html .= '</ul>';
	if ( $args['echo'] ?? true ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
	return $html;
}

/**
 * Brand mark (sun over two swells).
 */
function harborview_mark(): string {
	return '<svg class="hv-mark" viewBox="0 0 40 40" aria-hidden="true" focusable="false"><rect width="40" height="40" rx="11" fill="#1D332E"/><circle cx="20" cy="17" r="6.5" fill="#B86B4B"/><path d="M6 25.5c4.7-2.6 9.3-2.6 14 0s9.3 2.6 14 0M6 31c4.7-2.6 9.3-2.6 14 0s9.3 2.6 14 0" fill="none" stroke="#F8F7F4" stroke-width="2" stroke-linecap="round"/></svg>';
}
