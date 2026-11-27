<?php
/**
 * Clinician profile.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$harborview_id        = get_the_ID();
	$harborview_creds     = harborview_meta( $harborview_id, 'credentials' );
	$harborview_licenses  = array_filter( array_map( 'trim', explode( "\n", harborview_meta( $harborview_id, 'licensure' ) ) ) );
	$harborview_pronouns  = harborview_meta( $harborview_id, 'pronouns' );
	$harborview_languages = harborview_meta( $harborview_id, 'languages' );
	?>
	<article <?php post_class( 'hv-profile' ); ?>>
		<div class="hv-wrap">
			<nav class="hv-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'harborview' ); ?>">
				<a href="<?php echo esc_url( home_url( '/#clinicians' ) ); ?>"><?php esc_html_e( 'All clinicians', 'harborview' ); ?></a>
			</nav>
			<header class="hv-profile__head">
				<span class="hv-monogram hv-monogram--xl hv-monogram--<?php echo esc_attr( harborview_tone( $harborview_id ) ); ?>" aria-hidden="true"><?php echo esc_html( harborview_initials( get_the_title() ) ); ?></span>
				<div>
					<h1 class="hv-profile__name"><?php the_title(); ?><?php echo $harborview_creds ? '<span class="hv-profile__creds">, ' . esc_html( $harborview_creds ) . '</span>' : ''; ?></h1>
					<p class="hv-profile__role"><?php echo esc_html( harborview_meta( $harborview_id, 'role' ) ); ?><?php echo $harborview_pronouns ? ' · ' . esc_html( $harborview_pronouns ) : ''; ?></p>
					<div class="hv-profile__status"><?php echo harborview_status_pill( $harborview_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</div>
			</header>

			<div class="hv-profile__grid">
				<div class="hv-prose">
					<?php if ( has_excerpt() ) : ?>
						<p class="hv-profile__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<?php the_content(); ?>
				</div>
				<aside class="hv-profile__aside">
					<dl class="hv-facts">
						<div><dt><?php esc_html_e( 'Focus areas', 'harborview' ); ?></dt><dd><?php echo esc_html( implode( ', ', wp_list_pluck( harborview_specialties( $harborview_id ), 'name' ) ) ); ?></dd></div>
						<?php if ( $harborview_licenses ) : ?>
							<div><dt><?php esc_html_e( 'Licensure', 'harborview' ); ?></dt><dd><?php echo wp_kses( implode( '<br>', array_map( 'esc_html', $harborview_licenses ) ), array( 'br' => array() ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $harborview_languages ) : ?>
							<div><dt><?php esc_html_e( 'Languages', 'harborview' ); ?></dt><dd><?php echo esc_html( $harborview_languages ); ?></dd></div>
						<?php endif; ?>
						<div><dt><?php esc_html_e( 'Visits', 'harborview' ); ?></dt><dd><?php echo get_post_meta( $harborview_id, '_hv_telehealth', true ) ? esc_html__( 'In person and secure video', 'harborview' ) : esc_html__( 'In person', 'harborview' ); ?></dd></div>
					</dl>
					<div class="hv-profile__cta">
						<p><?php esc_html_e( 'To request this clinician, start in the portal and mention their name in your intake.', 'harborview' ); ?></p>
						<?php echo harborview_portal_link( __( 'Request an appointment', 'harborview' ), 'hv-button hv-button--light' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</aside>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
