<?php
/**
 * The template for displaying Beerlog beer Archive pages
 *
 * @package Beerlog
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<table cellspacing="0" cellpadding="0" style="width: 80%; text-align: center">
				<tr>
					<th width="120px"></th>
					<th><?php _e('Beer', 'beerlog'); ?></th>
					<th width="20%"><?php _e('Style(s)', 'beerlog'); ?></th>
					<th width="20%"><?php _e('Alcohol', 'beerlog'); ?></th>
				</tr>

				<?php /* The loop */ ?>
				<?php while ( have_posts() ) :
					the_post();
					$beerPost 	= new \Beerlog\Models\Beer( $post );
					$beerStyles = $beerPost->getStyles();
				?>

				<tr>
					<td style="text-align: left; vertical-align: top">
						<div>
                    		<a href="<?php echo get_permalink( $post->id ); ?>"><?php the_post_thumbnail( array( 100, 100 ) ); ?></a>
                		</div>
                	</td>
					<td style="text-align: left; padding-left: 10px">
						<strong><a href="<?php echo get_permalink( $post->id ); ?>"><?php the_title(); ?></a></strong><br/>
						<p><?php the_excerpt(); ?></p>
					</td>
					<td style="text-align: left; vertical-align: top">
					<?php foreach ( $beerStyles as $styleTerm ): ?>
						<span style="margin-right: 4px">
                            <?php echo $styleTerm->link ? $styleTerm->link : esc_html( $styleTerm->name ); ?>
                        </span>
					<?php endforeach; ?>
                	</td>
					<td style="text-align: left; vertical-align: top">
						<?php _e('Alc: ', 'beerlog'); ?><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_abv', true ) ); ?> %
                	</td>
				</tr>

				<?php endwhile; ?>
			</table>

			<?php twentythirteen_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>