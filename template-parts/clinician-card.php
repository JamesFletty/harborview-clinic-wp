<?php
/**
 * Directory card. Server-rendered so the directory works without JS; the
 * filter script hydrates from the REST endpoint and toggles these by ID.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

$harborview_id    = get_the_ID();
$harborview_specs = harborview_specialties( $harborview_id );
$harborview_creds = harborview_meta( $harborview_id, 'credentials' );
?>
<li class="hv-card" data-clinician-id="<?php echo esc_attr( (string) $harborview_id ); ?>" data-specialties="<?php echo esc_attr( implode( ' ', wp_list_pluck( $harborview_specs, 'slug' ) ) ); ?>" data-accepting="<?php echo get_post_meta( $harborview_id, '_hv_accepting', true ) ? '1' : '0'; ?>">
	<article class="hv-card__inner">
		<div class="hv-card__head">
			<span class="hv-monogram hv-monogram--<?php echo esc_attr( harborview_tone( $harborview_id ) ); ?>" aria-hidden="true"><?php echo esc_html( harborview_initials( get_the_title() ) ); ?></span>
			<div>
				<h3 class="hv-card__name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?><?php echo $harborview_creds ? '<span class="hv-card__creds">, ' . esc_html( $harborview_creds ) . '</span>' : ''; ?></a>
				</h3>
				<p class="hv-card__role"><?php echo esc_html( harborview_meta( $harborview_id, 'role' ) ); ?></p>
			</div>
		</div>
		<?php if ( has_excerpt() ) : ?>
			<p class="hv-card__bio"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
		<ul class="hv-tags" role="list" aria-label="<?php esc_attr_e( 'Specialties', 'harborview' ); ?>">
			<?php foreach ( $harborview_specs as $harborview_spec ) : ?>
				<li><?php echo esc_html( $harborview_spec->name ); ?></li>
			<?php endforeach; ?>
		</ul>
		<div class="hv-card__foot">
			<?php echo harborview_status_pill( $harborview_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( get_post_meta( $harborview_id, '_hv_telehealth', true ) ) : ?>
				<span class="hv-card__tele"><?php esc_html_e( 'Telehealth', 'harborview' ); ?></span>
			<?php endif; ?>
		</div>
	</article>
</li>
