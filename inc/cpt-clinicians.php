<?php
/**
 * Clinicians: hv_clinician post type, hv_specialty taxonomy, native meta box.
 *
 * Only public, professional information is stored here (credentials,
 * licensure, availability). Nothing about patients ever touches WordPress.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'harborview_register_clinicians' );

/**
 * Register post type, taxonomy and meta.
 */
function harborview_register_clinicians(): void {
	register_post_type(
		'hv_clinician',
		array(
			'labels'        => array(
				'name'           => _x( 'Clinicians', 'post type general name', 'harborview' ),
				'singular_name'  => _x( 'Clinician', 'post type singular name', 'harborview' ),
				'add_new_item'   => __( 'Add new clinician', 'harborview' ),
				'edit_item'      => __( 'Edit clinician', 'harborview' ),
				'view_item'      => __( 'View profile', 'harborview' ),
				'all_items'      => __( 'All clinicians', 'harborview' ),
				'search_items'   => __( 'Search clinicians', 'harborview' ),
				'not_found'      => __( 'No clinicians found.', 'harborview' ),
				'featured_image' => __( 'Portrait', 'harborview' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => false, // The directory lives on the front page.
			'rewrite'       => array(
				'slug'       => 'clinicians',
				'with_front' => false,
			),
			'menu_position' => 21,
			'menu_icon'     => 'dashicons-heart',
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'hv_specialty',
		array( 'hv_clinician' ),
		array(
			'labels'            => array(
				'name'          => _x( 'Specialties', 'taxonomy general name', 'harborview' ),
				'singular_name' => _x( 'Specialty', 'taxonomy singular name', 'harborview' ),
				'all_items'     => __( 'All specialties', 'harborview' ),
				'add_new_item'  => __( 'Add new specialty', 'harborview' ),
			),
			'hierarchical'      => false,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'specialty' ),
		)
	);

	foreach ( harborview_clinician_fields() as $key => $field ) {
		register_post_meta(
			'hv_clinician',
			$key,
			array(
				'type'              => 'checkbox' === $field['type'] ? 'boolean' : 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $field['sanitize'],
				'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
			)
		);
	}
}

/**
 * Field schema: drives registration, UI and saving.
 *
 * @return array<string, array{label: string, type: string, sanitize: callable, help?: string}>
 */
function harborview_clinician_fields(): array {
	return array(
		'_hv_credentials' => array(
			'label'    => __( 'Credentials', 'harborview' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'help'     => __( 'Post-nominals, e.g. "MD" or "LCSW".', 'harborview' ),
		),
		'_hv_role'        => array(
			'label'    => __( 'Role', 'harborview' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'_hv_pronouns'    => array(
			'label'    => __( 'Pronouns', 'harborview' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'_hv_licensure'   => array(
			'label'    => __( 'Licensure', 'harborview' ),
			'type'     => 'textarea',
			'sanitize' => 'sanitize_textarea_field',
			'help'     => __( 'One license per line, e.g. "Maine Board of Licensure in Medicine".', 'harborview' ),
		),
		'_hv_languages'   => array(
			'label'    => __( 'Languages', 'harborview' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'_hv_accepting'   => array(
			'label'    => __( 'Accepting new patients', 'harborview' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
		'_hv_telehealth'  => array(
			'label'    => __( 'Offers telehealth visits', 'harborview' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
	);
}

add_action( 'add_meta_boxes_hv_clinician', 'harborview_add_clinician_meta_box' );

/**
 * Register the meta box.
 */
function harborview_add_clinician_meta_box(): void {
	add_meta_box( 'harborview-clinician', __( 'Clinician details', 'harborview' ), 'harborview_render_clinician_meta_box', 'hv_clinician', 'side', 'high' );
}

/**
 * Meta box UI.
 *
 * @param WP_Post $post Post.
 */
function harborview_render_clinician_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'harborview_save_clinician', 'harborview_clinician_nonce' );

	foreach ( harborview_clinician_fields() as $key => $field ) {
		$id    = 'harborview' . $key;
		$value = get_post_meta( $post->ID, $key, true );

		if ( 'checkbox' === $field['type'] ) {
			printf(
				'<p><label for="%1$s"><input type="checkbox" id="%1$s" name="%1$s" value="1"%3$s> %2$s</label></p>',
				esc_attr( $id ),
				esc_html( $field['label'] ),
				checked( (bool) $value, true, false )
			);
			continue;
		}

		printf( '<p><label for="%1$s"><strong>%2$s</strong></label><br>', esc_attr( $id ), esc_html( $field['label'] ) );
		if ( 'textarea' === $field['type'] ) {
			printf( '<textarea class="widefat" rows="3" id="%1$s" name="%1$s">%2$s</textarea>', esc_attr( $id ), esc_textarea( (string) $value ) );
		} else {
			printf( '<input class="widefat" type="text" id="%1$s" name="%1$s" value="%2$s">', esc_attr( $id ), esc_attr( (string) $value ) );
		}
		if ( ! empty( $field['help'] ) ) {
			printf( '<span class="description">%s</span>', esc_html( $field['help'] ) );
		}
		echo '</p>';
	}
}

add_action( 'save_post_hv_clinician', 'harborview_save_clinician_meta', 10, 2 );

/**
 * Save meta: nonce, autosave, revision and capability checks; schema-driven
 * sanitizing. Unchecked checkboxes are stored as false explicitly.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post.
 */
function harborview_save_clinician_meta( int $post_id, WP_Post $post ): void {
	$nonce = isset( $_POST['harborview_clinician_nonce'] )
		? sanitize_text_field( wp_unslash( $_POST['harborview_clinician_nonce'] ) )
		: '';

	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'harborview_save_clinician' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( harborview_clinician_fields() as $key => $field ) {
		$name = 'harborview' . $key;

		if ( 'checkbox' === $field['type'] ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $name ] ) );
			continue;
		}

		if ( ! isset( $_POST[ $name ] ) ) {
			continue;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized by schema callback.
		$value = call_user_func( $field['sanitize'], wp_unslash( $_POST[ $name ] ) );
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}

	// Directory responses are cached; any edit invalidates them.
	harborview_flush_directory_cache();
}

add_filter( 'manage_hv_clinician_posts_columns', 'harborview_clinician_columns' );
add_action( 'manage_hv_clinician_posts_custom_column', 'harborview_clinician_column_content', 10, 2 );

/**
 * Admin list columns.
 *
 * @param string[] $columns Columns.
 * @return string[]
 */
function harborview_clinician_columns( array $columns ): array {
	$columns['hv_accepting'] = __( 'Accepting', 'harborview' );
	return $columns;
}

/**
 * Admin list column content.
 *
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 */
function harborview_clinician_column_content( string $column, int $post_id ): void {
	if ( 'hv_accepting' === $column ) {
		echo get_post_meta( $post_id, '_hv_accepting', true ) ? esc_html__( 'Yes', 'harborview' ) : esc_html__( 'Waitlist', 'harborview' );
	}
}
