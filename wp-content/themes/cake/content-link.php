<?php
/**
 * @version		1.0
 * @package		Cake
 * @author		Codeopus <support.codeopus.net>
 * Websites		http://codeopus.net
 */
$readmoretext = get_theme_mod('cake_readmore_text','Read More');
$showpostdate = get_theme_mod('cake_date_meta', 'true');
$link = get_post_meta($post->ID, 'cake_link_embed' ,true);
?>

<div id="post-<?php the_ID(); ?>" <?php esc_attr(sanitize_html_class(post_class('article-post'))); ?>>

	<?php 
	if($showpostdate=='true'){
		do_action('cake_post_meta_date');
	}?>
	
	<?php if($link){ ?>
	<div class="link-post">
	<a href="<?php echo esc_url($link);?>"><?php echo esc_html($link);?></a>
	</div>
	<?php } ?>
	
	<header class="entry-header">
	
		<h2 class="entry-title"><a href="<?php esc_url(the_permalink()); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cake' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php do_action('cake_post_meta'); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search()) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( '<span class="more-text">'.esc_html($readmoretext).'</span>' ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cake' ),
				'after'  => '</div>',
			));
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>
	
	<?php if( is_single() && has_tag() ):// Only display meta for Single ?>
	<footer class="entry-meta">
		<?php do_action('cake_post_meta_footer'); ?>
	</footer><!-- .entry-meta -->
	<?php endif; ?>
		
</div><!-- #post-## -->
