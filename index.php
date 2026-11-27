<?php
/**
 * Fallback template (practice news).
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="hv-page hv-wrap">
	<header class="hv-page__head">
		<p class="hv-eyebrow"><?php esc_html_e( 'Practice news', 'harborview' ); ?></p>
		<h1 class="hv-page__title"><?php esc_html_e( 'Updates from Harborview', 'harborview' ); ?></h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<ul class="hv-news" role="list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<li <?php post_class( 'hv-news__item' ); ?>>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No updates yet.', 'harborview' ); ?></p>
	<?php endif; ?>
</section>
<?php
get_footer();
