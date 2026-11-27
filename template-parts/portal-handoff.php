<?php
/**
 * HIPAA-aware patient portal handoff.
 *
 * Design rule: the public website collects nothing. There is deliberately
 * no form here; every clinical interaction is handed to the practice's
 * encrypted, BAA-covered EHR portal over HTTPS.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="hv-portal" id="portal" aria-labelledby="hv-portal-title">
	<div class="hv-wrap hv-portal__grid">
		<div class="hv-portal__intro">
			<p class="hv-eyebrow hv-eyebrow--light"><?php esc_html_e( 'New & returning patients', 'harborview' ); ?></p>
			<h2 id="hv-portal-title" class="hv-portal__title"><?php esc_html_e( 'Your health information stays in the portal.', 'harborview' ); ?></h2>
			<p class="hv-portal__lead"><?php esc_html_e( 'This website has no contact or intake form, on purpose. Anything about your health goes through our secure patient portal, which is encrypted and covered by our HIPAA business associate agreement with our records provider.', 'harborview' ); ?></p>
			<ol class="hv-steps" role="list">
				<li><span class="hv-steps__n">1</span><div><h3><?php esc_html_e( 'Create your portal account', 'harborview' ); ?></h3><p><?php esc_html_e( 'Takes about five minutes. You’ll verify your identity by email or text.', 'harborview' ); ?></p></div></li>
				<li><span class="hv-steps__n">2</span><div><h3><?php esc_html_e( 'Complete intake securely', 'harborview' ); ?></h3><p><?php esc_html_e( 'Health history, insurance and consent forms, saved as you go.', 'harborview' ); ?></p></div></li>
				<li><span class="hv-steps__n">3</span><div><h3><?php esc_html_e( 'We call within two business days', 'harborview' ); ?></h3><p><?php esc_html_e( 'A care coordinator matches you with a clinician and books your first visit.', 'harborview' ); ?></p></div></li>
			</ol>
			<div class="hv-portal__actions">
				<?php echo harborview_portal_link( __( 'Open the patient portal', 'harborview' ), 'hv-button hv-button--light' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p class="hv-portal__fine"><?php esc_html_e( 'Prefer the phone? Call us and we’ll set up the portal with you.', 'harborview' ); ?></p>
			</div>
		</div>

		<div class="hv-where" aria-label="<?php esc_attr_e( 'What goes where', 'harborview' ); ?>">
			<div class="hv-where__col">
				<p class="hv-where__h"><span class="hv-where__icon" aria-hidden="true">◎</span><?php esc_html_e( 'On this website', 'harborview' ); ?></p>
				<ul role="list">
					<li><?php esc_html_e( 'Hours, location and parking', 'harborview' ); ?></li>
					<li><?php esc_html_e( 'Clinician profiles and availability', 'harborview' ); ?></li>
					<li><?php esc_html_e( 'Insurance and self-pay rates', 'harborview' ); ?></li>
					<li><?php esc_html_e( 'General questions answered below', 'harborview' ); ?></li>
				</ul>
				<p class="hv-where__note"><?php esc_html_e( 'No forms. No tracking pixels. No third-party fonts or chat widgets.', 'harborview' ); ?></p>
			</div>
			<div class="hv-where__col hv-where__col--secure">
				<p class="hv-where__h"><span class="hv-where__icon" aria-hidden="true">
					<svg viewBox="0 0 16 16" width="16" height="16"><path d="M4 7V5a4 4 0 0 1 8 0v2h1v8H3V7zm2 0h4V5a2 2 0 0 0-4 0z" fill="currentColor"/></svg>
				</span><?php esc_html_e( 'In the secure portal', 'harborview' ); ?></p>
				<ul role="list">
					<li><?php esc_html_e( 'Symptoms, history and intake forms', 'harborview' ); ?></li>
					<li><?php esc_html_e( 'Messages with your care team', 'harborview' ); ?></li>
					<li><?php esc_html_e( 'Appointments, refills and results', 'harborview' ); ?></li>
					<li><?php esc_html_e( 'Insurance cards and billing', 'harborview' ); ?></li>
				</ul>
				<p class="hv-where__note"><?php esc_html_e( 'Encrypted in transit and at rest. Access is logged.', 'harborview' ); ?></p>
			</div>
		</div>
	</div>
</section>
