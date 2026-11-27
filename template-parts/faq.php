<?php
/**
 * Accessible accordion FAQ (button + region pattern, WAI-ARIA APG).
 * Without JS every answer is visible; main.js collapses them on load.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

$harborview_faqs = apply_filters(
	'harborview_faqs',
	array(
		array( __( 'Do I need a referral?', 'harborview' ), __( 'Not for primary care or therapy. Some insurance plans require a referral for psychiatry; our coordinators check your benefits before your first visit, so you’ll know in advance.', 'harborview' ) ),
		array( __( 'How do I become a new patient?', 'harborview' ), __( 'Create an account in the patient portal and complete the intake forms there. A care coordinator reviews every intake and calls you within two business days to match you with a clinician.', 'harborview' ) ),
		array( __( 'Why can’t I send my information through this website?', 'harborview' ), __( 'Ordinary website forms and email aren’t built to protect health information. Keeping everything in the encrypted portal means your details are only ever seen by your care team, and access is logged under HIPAA.', 'harborview' ) ),
		array( __( 'Do you offer telehealth?', 'harborview' ), __( 'Yes. Most therapy and medication follow-ups can be done by secure video, as long as you are physically in Maine or New Hampshire at the time of the visit. Physicals and some first visits are in person.', 'harborview' ) ),
		array( __( 'What if I need help after hours?', 'harborview' ), __( 'Call our main number and choose the on-call option to reach a clinician for urgent but non-emergency questions. For a mental health crisis, call or text 988. For a medical emergency, call 911.', 'harborview' ) ),
		array( __( 'Do you have a sliding scale?', 'harborview' ), __( 'Yes. Reduced self-pay rates are available based on household size and income. Ask your care coordinator; it takes one short form in the portal.', 'harborview' ) ),
	)
);
?>
<section class="hv-section hv-wrap hv-faq" id="faq" aria-labelledby="hv-faq-title">
	<div class="hv-faq__grid">
		<div class="hv-faq__intro">
			<p class="hv-eyebrow"><?php esc_html_e( 'Questions', 'harborview' ); ?></p>
			<h2 id="hv-faq-title" class="hv-h2"><?php esc_html_e( 'Good to know before your first visit', 'harborview' ); ?></h2>
			<p class="hv-lead-sm">
				<?php
				printf(
					/* translators: %s phone number */
					esc_html__( 'Still unsure? Call the front desk at %s. We’re happy to talk it through.', 'harborview' ),
					'<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', harborview_setting( 'phone' ) ) ) . '">' . esc_html( harborview_setting( 'phone' ) ) . '</a>'
				);
				?>
			</p>
		</div>
		<div class="hv-accordion" data-hv-accordion>
			<?php foreach ( $harborview_faqs as $harborview_i => [ $harborview_q, $harborview_a ] ) : ?>
				<?php $harborview_uid = 'hv-faq-' . ( $harborview_i + 1 ); ?>
				<div class="hv-accordion__item">
					<h3 class="hv-accordion__heading">
						<button type="button" class="hv-accordion__trigger" id="<?php echo esc_attr( $harborview_uid ); ?>-btn" aria-expanded="true" aria-controls="<?php echo esc_attr( $harborview_uid ); ?>">
							<span><?php echo esc_html( $harborview_q ); ?></span>
							<span class="hv-accordion__icon" aria-hidden="true"></span>
						</button>
					</h3>
					<div class="hv-accordion__panel" id="<?php echo esc_attr( $harborview_uid ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $harborview_uid ); ?>-btn">
						<div class="hv-accordion__body"><p><?php echo esc_html( $harborview_a ); ?></p></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
