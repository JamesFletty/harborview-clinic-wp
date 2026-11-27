<?php
/**
 * Site footer.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="hv-footer">
	<div class="hv-wrap">
		<div class="hv-footer__top">
			<div class="hv-footer__brand">
				<a class="hv-brand hv-brand--inverse" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo harborview_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="hv-brand__text"><span class="hv-brand__name"><?php bloginfo( 'name' ); ?></span><span class="hv-brand__sub"><?php esc_html_e( 'Behavioral & Primary Wellness', 'harborview' ); ?></span></span>
				</a>
				<p class="hv-footer__tagline"><?php esc_html_e( 'An independent, clinician-owned practice on the Portland waterfront.', 'harborview' ); ?></p>
			</div>
			<div class="hv-footer__col">
				<h2 class="hv-footer__h"><?php esc_html_e( 'Visit', 'harborview' ); ?></h2>
				<address><?php echo esc_html( harborview_setting( 'address_1' ) ); ?><br><?php echo esc_html( harborview_setting( 'address_2' ) ); ?></address>
			</div>
			<div class="hv-footer__col">
				<h2 class="hv-footer__h"><?php esc_html_e( 'Hours', 'harborview' ); ?></h2>
				<p><?php echo esc_html( harborview_setting( 'hours_week' ) ); ?><br><?php echo esc_html( harborview_setting( 'hours_fri' ) ); ?></p>
			</div>
			<div class="hv-footer__col">
				<h2 class="hv-footer__h"><?php esc_html_e( 'Contact', 'harborview' ); ?></h2>
				<p><?php echo esc_html( sprintf( /* translators: %s phone */ __( 'Phone %s', 'harborview' ), harborview_setting( 'phone' ) ) ); ?><br><?php echo esc_html( sprintf( /* translators: %s fax */ __( 'Fax %s', 'harborview' ), harborview_setting( 'fax' ) ) ); ?></p>
			</div>
		</div>

		<div class="hv-footer__notice">
			<p><?php esc_html_e( 'Please don’t email or text us health information. Use the secure patient portal for anything about your care. If you are in crisis, call or text 988.', 'harborview' ); ?></p>
		</div>

		<div class="hv-footer__base">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'Harborview Behavioral & Primary Wellness, PLLC', 'harborview' ); ?></span>
			<nav aria-label="<?php esc_attr_e( 'Legal', 'harborview' ); ?>" class="hv-footer__legal">
				<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Notice of Privacy Practices', 'harborview' ); ?></a>
				<a href="<?php echo esc_url( get_privacy_policy_url() . '#nondiscrimination' ); ?>"><?php esc_html_e( 'Nondiscrimination & language access', 'harborview' ); ?></a>
				<a href="<?php echo esc_url( get_privacy_policy_url() . '#website-privacy' ); ?>"><?php esc_html_e( 'Website privacy', 'harborview' ); ?></a>
			</nav>
		</div>
	</div>
</footer>
