<?php
/**
 * Front page: hero, care areas, clinician directory, portal handoff,
 * insurance, FAQ and visit details.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

get_header();

$harborview_care = array(
	array( 'primary', __( 'Primary care', 'harborview' ), __( 'Physicals, preventive screenings, sick visits and steady management of conditions like diabetes, hypertension and asthma.', 'harborview' ), __( 'Adults 18 and over', 'harborview' ) ),
	array( 'therapy', __( 'Therapy', 'harborview' ), __( 'Individual, couples and family therapy grounded in CBT, ACT and EMDR, with clinicians who specialize in trauma, anxiety and life transitions.', 'harborview' ), __( 'Individuals, couples, families', 'harborview' ) ),
	array( 'psychiatry', __( 'Psychiatry & medication', 'harborview' ), __( 'Diagnostic evaluation and ongoing medication management, coordinated with your therapist so nothing is decided in isolation.', 'harborview' ), __( 'Adults; referral may be needed', 'harborview' ) ),
	array( 'integrated', __( 'Integrated care', 'harborview' ), __( 'With your consent, your primary care clinician and therapist share one chart and meet weekly, so your body and mind are treated as one person.', 'harborview' ), __( 'Every Harborview patient', 'harborview' ) ),
);

$harborview_directory = new WP_Query(
	array(
		'post_type'      => 'hv_clinician',
		'posts_per_page' => 24,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
);
$harborview_specialties = get_terms(
	array(
		'taxonomy'   => 'hv_specialty',
		'hide_empty' => true,
	)
);
?>

<section class="hv-hero" aria-labelledby="hv-hero-title">
	<div class="hv-wrap hv-hero__grid">
		<div class="hv-hero__copy">
			<p class="hv-eyebrow"><?php echo esc_html( sprintf( /* translators: %s city */ __( 'Independent practice · %s', 'harborview' ), harborview_setting( 'city_line' ) ) ); ?></p>
			<h1 id="hv-hero-title" class="hv-hero__title"><?php echo wp_kses( get_theme_mod( 'harborview_hero_title', __( 'Primary care and mental health, <em>finally</em> in the same room.', 'harborview' ) ), array( 'em' => array() ) ); ?></h1>
			<p class="hv-hero__lead"><?php echo esc_html( get_theme_mod( 'harborview_hero_lead', __( 'Harborview is a small, clinician-owned practice where your physician, therapist and psychiatric prescriber work from one shared plan. Unhurried visits, a real person on the phone, and care that treats you as a whole person.', 'harborview' ) ) ); ?></p>
			<div class="hv-hero__actions">
				<?php echo harborview_portal_link( __( 'Become a patient', 'harborview' ), 'hv-button hv-button--solid hv-button--lg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<a class="hv-link" href="#clinicians"><?php esc_html_e( 'Meet our clinicians', 'harborview' ); ?></a>
			</div>
			<ul class="hv-assure" role="list">
				<li><?php esc_html_e( 'Accepting new patients in primary care and therapy', 'harborview' ); ?></li>
				<li><?php esc_html_e( 'Telehealth across Maine and New Hampshire', 'harborview' ); ?></li>
				<li><?php esc_html_e( 'Most major plans, MaineCare and Medicare', 'harborview' ); ?></li>
			</ul>
		</div>

		<div class="hv-hero__visual">
			<?php get_template_part( 'template-parts/hero-illustration' ); ?>
			<aside class="hv-availability" aria-labelledby="hv-availability-title">
				<p id="hv-availability-title" class="hv-availability__title"><?php esc_html_e( 'Next available', 'harborview' ); ?></p>
				<dl>
					<div><dt><?php esc_html_e( 'Primary care', 'harborview' ); ?></dt><dd><?php echo esc_html( get_theme_mod( 'harborview_next_primary', __( 'This week', 'harborview' ) ) ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Individual therapy', 'harborview' ); ?></dt><dd><?php echo esc_html( get_theme_mod( 'harborview_next_therapy', __( '1–2 weeks', 'harborview' ) ) ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Psychiatry intake', 'harborview' ); ?></dt><dd class="is-wait"><?php echo esc_html( get_theme_mod( 'harborview_next_psych', __( 'Waitlist, ~3 weeks', 'harborview' ) ) ); ?></dd></div>
				</dl>
				<p class="hv-availability__note"><?php esc_html_e( 'Updated every Monday by our front desk.', 'harborview' ); ?></p>
			</aside>
		</div>
	</div>
</section>

<section class="hv-section hv-wrap hv-care" id="care" aria-labelledby="hv-care-title">
	<div class="hv-care__head">
		<p class="hv-eyebrow"><?php esc_html_e( 'How we care for you', 'harborview' ); ?></p>
		<h2 id="hv-care-title" class="hv-h2"><?php esc_html_e( 'One team, one chart, one plan.', 'harborview' ); ?></h2>
		<p class="hv-lead-sm"><?php esc_html_e( 'Most people see a doctor in one building and a therapist in another, and the two never speak. We built Harborview so they do.', 'harborview' ); ?></p>
	</div>
	<ol class="hv-care__list" role="list">
		<?php foreach ( $harborview_care as $harborview_i => [ $harborview_key, $harborview_title, $harborview_text, $harborview_for ] ) : ?>
			<li class="hv-care__item hv-care__item--<?php echo esc_attr( $harborview_key ); ?>" data-hv-reveal>
				<span class="hv-care__n"><?php echo esc_html( sprintf( '%02d', $harborview_i + 1 ) ); ?></span>
				<h3 class="hv-care__title"><?php echo esc_html( $harborview_title ); ?></h3>
				<p class="hv-care__text"><?php echo esc_html( $harborview_text ); ?></p>
				<p class="hv-care__for"><span><?php esc_html_e( 'For', 'harborview' ); ?></span> <?php echo esc_html( $harborview_for ); ?></p>
			</li>
		<?php endforeach; ?>
	</ol>
</section>

<section class="hv-section hv-directory" id="clinicians" aria-labelledby="hv-dir-title">
	<div class="hv-wrap">
		<header class="hv-directory__head">
			<div>
				<p class="hv-eyebrow"><?php esc_html_e( 'Our clinicians', 'harborview' ); ?></p>
				<h2 id="hv-dir-title" class="hv-h2"><?php esc_html_e( 'Find someone who fits.', 'harborview' ); ?></h2>
			</div>
			<p class="hv-lead-sm"><?php esc_html_e( 'Filter by what you’d like help with. You can also skip this step: our coordinators match every new patient personally.', 'harborview' ); ?></p>
		</header>

		<?php if ( $harborview_directory->have_posts() ) : ?>
			<div class="hv-filter" data-hv-filter>
				<div class="hv-filter__chips" role="group" aria-label="<?php esc_attr_e( 'Filter by specialty', 'harborview' ); ?>">
					<button type="button" class="hv-chip" data-specialty="" aria-pressed="true"><?php esc_html_e( 'All', 'harborview' ); ?></button>
					<?php foreach ( $harborview_specialties as $harborview_term ) : ?>
						<button type="button" class="hv-chip" data-specialty="<?php echo esc_attr( $harborview_term->slug ); ?>" aria-pressed="false"><?php echo esc_html( $harborview_term->name ); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="hv-filter__meta">
					<label class="hv-switch">
						<input type="checkbox" data-accepting-toggle>
						<span class="hv-switch__track" aria-hidden="true"></span>
						<span><?php esc_html_e( 'Accepting new patients only', 'harborview' ); ?></span>
					</label>
					<p class="hv-filter__count" aria-live="polite" data-hv-count>
						<?php echo esc_html( sprintf( /* translators: %d: count */ _n( 'Showing %d clinician', 'Showing %d clinicians', $harborview_directory->post_count, 'harborview' ), $harborview_directory->post_count ) ); ?>
					</p>
				</div>
			</div>

			<ul class="hv-cards" role="list" data-hv-cards>
				<?php
				while ( $harborview_directory->have_posts() ) :
					$harborview_directory->the_post();
					get_template_part( 'template-parts/clinician-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
			<p class="hv-empty" data-hv-empty hidden><?php esc_html_e( 'No one matches that combination right now. Try another specialty, or join the waitlist through the portal and we’ll contact you when a spot opens.', 'harborview' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/portal-handoff' ); ?>

<section class="hv-section hv-wrap hv-insurance" id="insurance" aria-labelledby="hv-ins-title">
	<div class="hv-insurance__grid">
		<div>
			<p class="hv-eyebrow"><?php esc_html_e( 'Insurance & fees', 'harborview' ); ?></p>
			<h2 id="hv-ins-title" class="hv-h2"><?php esc_html_e( 'Clear costs, before you come in.', 'harborview' ); ?></h2>
			<p class="hv-lead-sm"><?php esc_html_e( 'We verify your benefits before the first visit and tell you what to expect. No surprise bills.', 'harborview' ); ?></p>
			<ul class="hv-plans" role="list" aria-label="<?php esc_attr_e( 'Accepted insurance plans', 'harborview' ); ?>">
				<li>Anthem Blue Cross</li><li>Harvard Pilgrim</li><li>Aetna</li><li>Cigna</li><li>Community Health Options</li><li>MaineCare</li><li>Medicare</li>
			</ul>
		</div>
		<div class="hv-rates">
			<h3 class="hv-rates__title"><?php esc_html_e( 'Self-pay rates', 'harborview' ); ?></h3>
			<dl>
				<div><dt><?php esc_html_e( 'New patient primary care visit', 'harborview' ); ?></dt><dd>$210</dd></div>
				<div><dt><?php esc_html_e( 'Follow-up visit', 'harborview' ); ?></dt><dd>$135</dd></div>
				<div><dt><?php esc_html_e( 'Therapy session, 53 minutes', 'harborview' ); ?></dt><dd>$165</dd></div>
				<div><dt><?php esc_html_e( 'Psychiatric evaluation', 'harborview' ); ?></dt><dd>$320</dd></div>
			</dl>
			<p class="hv-rates__note"><?php esc_html_e( 'Sliding scale available. You have the right to a Good Faith Estimate under the No Surprises Act.', 'harborview' ); ?></p>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/faq' ); ?>

<section class="hv-section hv-wrap hv-visit" id="visit" aria-labelledby="hv-visit-title">
	<div class="hv-visit__card">
		<div class="hv-visit__main">
			<p class="hv-eyebrow"><?php esc_html_e( 'Visit us', 'harborview' ); ?></p>
			<h2 id="hv-visit-title" class="hv-h2"><?php esc_html_e( 'On Fore Street, a block from the water.', 'harborview' ); ?></h2>
			<address class="hv-visit__address"><?php echo esc_html( harborview_setting( 'address_1' ) ); ?><br><?php echo esc_html( harborview_setting( 'address_2' ) ); ?></address>
		</div>
		<dl class="hv-visit__facts">
			<div><dt><?php esc_html_e( 'Hours', 'harborview' ); ?></dt><dd><?php echo esc_html( harborview_setting( 'hours_week' ) ); ?><br><?php echo esc_html( harborview_setting( 'hours_fri' ) ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Parking', 'harborview' ); ?></dt><dd><?php esc_html_e( 'Validated parking at the Fore Street garage, entrance on Union Street.', 'harborview' ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Access', 'harborview' ); ?></dt><dd><?php esc_html_e( 'Step-free entrance, elevator, all-gender restrooms. ASL interpreters on request.', 'harborview' ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Transit', 'harborview' ); ?></dt><dd><?php esc_html_e( 'METRO routes 1 and 8, Monument Square stop.', 'harborview' ); ?></dd></div>
		</dl>
	</div>
</section>

<?php
get_footer();
