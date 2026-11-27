<?php
/**
 * Default page template (privacy notices, policies, about pages).
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'hv-page hv-wrap' ); ?>>
		<header class="hv-page__head">
			<p class="hv-eyebrow"><?php bloginfo( 'name' ); ?></p>
			<h1 class="hv-page__title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="hv-lead-sm"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</header>
		<div class="hv-prose hv-page__content"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;

get_footer();
