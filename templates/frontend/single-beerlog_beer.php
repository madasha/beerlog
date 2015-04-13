<?php
 /*
  * Template Name: Beerlog beer single view
  */

// TODO: Beautify, render good meta (beer graph), location

get_header(); ?>

<link rel="stylesheet" href="<?php echo BEERLOG_DIR_URL;?>assets/css/radar-chart.css">

<script type="text/javascript" src="<?php echo BEERLOG_DIR_URL;?>assets/js/d3.v3.js"></script>
<script type="text/javascript" src="<?php echo BEERLOG_DIR_URL;?>assets/js/radar-chart.js"></script>

<div id="primary">
    <div id="content" role="main">
        <?php
            // var_dump( $post );
            $beerPost = new \Beerlog\Models\Beer( $post );
            // var_dump( $beerPost );
            // $hasPropsChart  = $beerPost->getMeta( '_beerlog_meta_prop_chart' );
            $hasPropsChart  = get_post_meta( $post->ID, '_beerlog_meta_prop_chart', true );
        ?>
        <article id="post-<?php echo $post->ID; ?>">
            <header class="entry-header">

                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>

                <!-- Display Title and Author Name -->
                <?php the_title(); ?><br />
            </header>

            <!-- Display movie review contents -->
            <div class="entry-content">
                <?php the_content(); ?>

                <strong><?php _e('Alcohol (ABV): ', 'beerlog'); ?></strong>
                <?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_abv', true ) ); ?> %
                <br />

                <strong><?php _e('IBU: ', 'beerlog'); ?></strong>
                <?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_ibu', true ) ); ?>
                <br />

                <?php if ( true || $hasPropsChart ): ?>

                    <strong><?php _e('Beer properties chart: ', 'beerlog'); ?></strong>
                    <div id="chart"></div>

                    <!-- Can use this chart to compare beers! -->

                    <?php if ( true || 'pro' == $hasPropsChart ): ?>
                    <script type="text/javascript">
                    var beerData = [
                        [
                            {axis: "<?php _e('Fruty', 'beerlog'); ?>"       , value: 2},
                            {axis: "<?php _e('Alcoholic', 'beerlog'); ?>"   , value: 4},
                            {axis: "<?php _e('Citrus', 'beerlog'); ?>"      , value: 3},
                            {axis: "<?php _e('Hoppy', 'beerlog'); ?>"       , value: 2},
                            {axis: "<?php _e('Floral', 'beerlog'); ?>"      , value: 3},
                            {axis: "<?php _e('Spicy', 'beerlog'); ?>"       , value: 5},
                            {axis: "<?php _e('Malty', 'beerlog'); ?>"       , value: 4},
                            {axis: "<?php _e('Toffee', 'beerlog'); ?>"      , value: 2},
                            {axis: "<?php _e('Burnt', 'beerlog'); ?>"       , value: 4},
                            {axis: "<?php _e('Sulphury', 'beerlog'); ?>"    , value: 1},
                            {axis: "<?php _e('Sweet', 'beerlog'); ?>"       , value: 5},
                            {axis: "<?php _e('Sour', 'beerlog'); ?>"        , value: 3},
                            {axis: "<?php _e('Bitter', 'beerlog'); ?>"      , value: 4},
                            {axis: "<?php _e('Dry', 'beerlog'); ?>"         , value: 2},
                            {axis: "<?php _e('Body', 'beerlog'); ?>"        , value: 1},
                            {axis: "<?php _e('Linger', 'beerlog'); ?>"      , value: 5},
                        ]
                    ];
                    <?php else: ?>
                    var beerData = [
                        [
                            {axis: "<?php _e('Sourness', 'beerlog'); ?>"    , value: 2},
                            {axis: "<?php _e('Bitterness', 'beerlog'); ?>"  , value: 4},
                            {axis: "<?php _e('Sweetness', 'beerlog'); ?>"   , value: 3},
                            {axis: "<?php _e('Saltiness', 'beerlog'); ?>"   , value: 2},
                            {axis: "<?php _e('Yeast', 'beerlog'); ?>"       , value: 3},
                            {axis: "<?php _e('Hop', 'beerlog'); ?>"         , value: 5},
                            {axis: "<?php _e('Malt', 'beerlog'); ?>"        , value: 4}
                        ]
                    ];
                    <?php endif; ?>

                    RadarChart.draw("#chart", beerData);
                    </script>

                <?php endif; ?>
            </div>


        </article>

    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>