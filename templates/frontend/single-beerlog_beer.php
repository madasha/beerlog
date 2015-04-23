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

                <!-- Display Title -->
                <strong><?php the_title(); ?></strong><br />
            </header>

            <!-- Display movie review contents -->
            <div class="entry-content">
                <?php the_content(); ?>

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="20%"><?php _e('Alcohol (ABV): ', 'beerlog'); ?></th>
                        <td width="30%"><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_abv', true ) ); ?> %</td>
                        <th width="20%"><?php _e('IBU: ', 'beerlog'); ?></th>
                        <td><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_ibu', true ) ); ?></td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="20%"><?php _e('Malts: ', 'beerlog'); ?></th>
                        <td width="30%"><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_malts', true ) ); ?></td>
                    </tr>
                    <tr>
                        <th width="20%"><?php _e('Hops: ', 'beerlog'); ?></th>
                        <td width="30%"><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_hops', true ) ); ?></td>
                    </tr>
                    <tr>
                        <th width="20%"><?php _e('Additions/Spices: ', 'beerlog'); ?></th>
                        <td width="30%"><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_adds', true ) ); ?></td>
                    </tr>
                    <tr>
                        <th width="20%"><?php _e('Yeast: ', 'beerlog'); ?></th>
                        <td width="30%"><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_yeast', true ) ); ?></td>
                    </tr>
                </table>

                <?php if ( $hasPropsChart ): ?>

                    <strong><?php _e('Beer properties chart: ', 'beerlog'); ?></strong>
                    <div id="chart"></div>

                    <!-- Can use this chart to compare beers! -->
                    <script type="text/javascript">

                    <?php if ( 'pro' == $hasPropsChart ): ?>
                    var beerData = [
                        [
                        <?php foreach ( \Beerlog\Utils\Init::$propsPro as $propName => $porpDefaultValue ):?>
                            {axis: "<?php _e(ucfirst( $propName ), 'beerlog'); ?>", value: <?php echo (int) get_post_meta( $post->ID, "_beerlog_meta_props_{$propName}" , true ) ?> },
                        <?php endforeach; ?>
                        ]
                    ];
                    <?php else: ?>
                    var beerData = [
                        [
                        <?php foreach ( \Beerlog\Utils\Init::$propsSimple as $propName => $porpDefaultValue ):?>
                            {axis: "<?php _e(ucfirst( $propName ), 'beerlog'); ?>", value: <?php echo (int) get_post_meta( $post->ID, "_beerlog_meta_props_{$propName}" , true ) ?> },
                        <?php endforeach; ?>
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