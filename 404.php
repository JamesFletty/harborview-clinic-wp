<?php
/**
 * 404.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="hv-page hv-wrap hv-404" aria-labelledby="hv-404-title">
	<p class="hv-eyebrow"><?php esc_html_e( 'Page not found', 'harborview' ); ?></p>
	<h1 id="hv-404-title" class="hv-page__title"><?php esc_html_e( 'We couldn’t find that page.', 'harborview' ); ?></h1>
	<p class="hv-lead-sm"><?php esc_html_e( 'It may have moved. These are the places most people are looking for:', 'harborview' ); ?></p>
	<ul class="hv-404__links" role="list">
		<li><a class="hv-link" href="<?php echo esc_url( home_url( '/#clinicians' ) ); ?>"><?php esc_html_e( 'Our clinicians', 'harborview' ); ?></a></li>
		<li><a class="hv-link" href="<?php echo esc_url( home_url( '/#portal' ) ); ?>"><?php esc_html_e( 'Becoming a patient', 'harborview' ); ?></a></li>
		<li><a class="hv-link" href="<?php echo esc_url( home_url( '/#faq' ) ); ?>"><?php esc_html_e( 'Common questions', 'harborview' ); ?></a></li>
	</ul>
	<p class="hv-404__crisis"><?php esc_html_e( 'If you need help right now, call or text 988.', 'harborview' ); ?></p>
</section>
<?php
get_footer();
