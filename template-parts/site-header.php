<?php
/**
 * Crisis notice, header, navigation and mobile drawer.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

$harborview_menu = array(
	'theme_location' => 'primary',
	'container'      => false,
	'menu_class'     => 'hv-menu',
	'depth'          => 1,
	'fallback_cb'    => 'harborview_fallback_menu',
);
$harborview_phone = harborview_setting( 'phone' );
?>
<div class="hv-crisis" role="note">
	<div class="hv-wrap hv-crisis__inner">
		<p>
			<strong><?php esc_html_e( 'In crisis?', 'harborview' ); ?></strong>
			<?php
			printf(
				/* translators: 1: 988 link, 2: 911 */
				esc_html__( 'Call or text %1$s any time, or call %2$s in an emergency.', 'harborview' ),
				'<a href="tel:988">988</a>',
				'<a href="tel:911">911</a>'
			);
			?>
			<span class="hv-crisis__note"><?php esc_html_e( 'This website and our portal are not monitored for urgent needs.', 'harborview' ); ?></span>
		</p>
	</div>
</div>

<header class="hv-header" data-hv-header>
	<div class="hv-wrap hv-header__inner">
		<a class="hv-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php echo harborview_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
			<span class="hv-brand__text">
				<span class="hv-brand__name"><?php bloginfo( 'name' ); ?></span>
				<span class="hv-brand__sub"><?php esc_html_e( 'Behavioral & Primary Wellness', 'harborview' ); ?></span>
			</span>
		</a>

		<nav class="hv-nav" aria-label="<?php esc_attr_e( 'Primary', 'harborview' ); ?>">
			<?php wp_nav_menu( $harborview_menu ); ?>
		</nav>

		<div class="hv-header__actions">
			<a class="hv-header__phone" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $harborview_phone ) ); ?>"><?php echo esc_html( $harborview_phone ); ?></a>
			<?php echo harborview_portal_link( __( 'Patient portal', 'harborview' ), 'hv-button hv-button--solid hv-button--sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<button class="hv-nav-toggle" type="button" aria-expanded="false" aria-controls="hv-drawer" data-hv-nav-toggle>
			<span class="hv-nav-toggle__bars" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'harborview' ); ?></span>
		</button>
	</div>

	<div class="hv-drawer" id="hv-drawer" data-hv-drawer hidden>
		<nav aria-label="<?php esc_attr_e( 'Mobile', 'harborview' ); ?>">
			<?php wp_nav_menu( array_merge( $harborview_menu, array( 'menu_class' => 'hv-drawer__menu' ) ) ); ?>
		</nav>
		<div class="hv-drawer__actions">
			<?php echo harborview_portal_link( __( 'Patient portal', 'harborview' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<a class="hv-button hv-button--quiet" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $harborview_phone ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s phone */ __( 'Call %s', 'harborview' ), $harborview_phone ) ); ?></a>
		</div>
	</div>
</header>
