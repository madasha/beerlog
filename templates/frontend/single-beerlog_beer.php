<?php
 /*
  * Template Name: Beerlog beer single view
  */

// TODO: Beautify, render good meta (beer graph), location

get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'beerlog_beer', );
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">

                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>

                <!-- Display Title and Author Name -->
                <strong>Title: </strong><?php the_title(); ?><br />
                <strong>Director: </strong>
                <?php echo esc_html( get_post_meta( get_the_ID(), 'movie_director', true ) ); ?>
                <br />

                <!-- Display yellow stars based on rating -->
                <strong>Rating: </strong>
                <?php
                $nb_stars = intval( get_post_meta( get_the_ID(), 'movie_rating', true ) );
                for ( $star_counter = 1; $star_counter <= 5; $star_counter++ ) {
                    if ( $star_counter <= $nb_stars ) {
                        echo '<img src="' . plugins_url( 'Movie-Reviews/images/icon.png' ) . '" />';
                    } else {
                        echo '<img src="' . plugins_url( 'Movie-Reviews/images/grey.png' ). '" />';
                    }
                }
                ?>
            </header>

            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>

    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>