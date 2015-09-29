<?php
 /*
  * Template Name: Beerlog beer single view
  */

// TODO: Beautify, render good meta (beer graph), location
defined( 'ABSPATH' ) or die( 'U\'r playing with powers you do not fully understand!' );

get_header(); ?>

<link rel="stylesheet" href="<?php echo BEERLOG_DIR_URL;?>assets/css/radar-chart.css">

<script type="text/javascript" src="<?php echo BEERLOG_DIR_URL;?>assets/js/d3.v3.js"></script>
<script type="text/javascript" src="<?php echo BEERLOG_DIR_URL;?>assets/js/radar-chart.js"></script>

<div id="primary">
    <div id="content" role="main">
    <?php while ( have_posts() ):?>
        <?php
            the_post();
            $beerPost = new \Beerlog\Models\Beer( $post );

            $apiKey         = get_option( 'beerlog_gmaps_key' );
            $beerStyles     = $beerPost->getStyles();
            $hasPropsChart  = $beerPost->getMeta( '_beerlog_meta_prop_chart' );
            $hasLocations   = $beerPost->getMeta( '_beerlog_meta_locations' );
            // $hasPropsChart  = get_post_meta( $post->ID, '_beerlog_meta_prop_chart', true );
            // $beerStyles     = wp_get_post_terms( $post->ID, 'beerlog_style',
            //     array( 'orderby' => 'parent', 'order' => 'ASC' )
            // );
        ?>
        <article id="post-<?php echo $post->ID; ?>">
            <header class="entry-header" style="padding-top: 20px">

                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>

                <!-- Display Title -->
                <strong><?php the_title(); ?></strong><br />
            </header>

            <!-- Display beer post contents -->
            <div class="entry-content">
                <p><?php the_content(); ?></p>

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="20%"><?php _e('Alcohol (ABV): ', 'beerlog'); ?></th>
                        <td width="30%"><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_abv', true ) ); ?> %</td>
                        <th width="20%"><?php _e('IBU: ', 'beerlog'); ?></th>
                        <td><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_ibu', true ) ); ?></td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0">
                    <?php if ( is_array( $beerStyles ) ): ?>
                    <tr>
                        <th width="35%"><?php _e( count( $beerStyles ) > 1 ? 'Styles: ' : 'Style: ', 'beerlog'); ?></th>
                        <td>
                            <?php \Beerlog\Controllers\Frontend::renderBeerStyles( $beerPost ); ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th width="35%"><?php _e('Malts: ', 'beerlog'); ?></th>
                        <td><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_malts', true ) ); ?></td>
                    </tr>
                    <tr>
                        <th width="35%"><?php _e('Hops: ', 'beerlog'); ?></th>
                        <td><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_hops', true ) ); ?></td>
                    </tr>
                    <tr>
                        <th width="35%"><?php _e('Additions/Spices: ', 'beerlog'); ?></th>
                        <td><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_adds', true ) ); ?></td>
                    </tr>
                    <tr>
                        <th width="35%"><?php _e('Yeast: ', 'beerlog'); ?></th>
                        <td><?php echo esc_html( get_post_meta( $post->ID, '_beerlog_meta_yeast', true ) ); ?></td>
                    </tr>
                </table>

                <?php if ( $hasPropsChart ): ?>

                    <h4><?php _e('Beer properties chart: ', 'beerlog'); ?></h4>
                    <div id="chart" style="text-align: center"></div>

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

                    RadarChart.defaultConfig.w = 500;
                    RadarChart.defaultConfig.h = 500;
                    RadarChart.draw("#chart", beerData);
                    </script>

                <?php endif; ?>


                <?php if ( $hasLocations ): ?>

                    <script type="text/javascript">

                        function initMap( myLatLng ) {
                          // Create a map object and specify the DOM element for display.
                          var map = new google.maps.Map(document.getElementById('map'), {
                            center: myLatLng,
                            scrollwheel: false,
                            zoom: 4
                          });

                          // Create a marker and set its position.
                          var marker = new google.maps.Marker({
                            map: map,
                            position: myLatLng,
                            title: 'There you are!!'
                          });
                        }

                        jQuery(document).ready(function init() 
                        {
                            // TODO: Replace this with a loop of the last N locations, pass coordinates to initMap
                            setTimeout( 'initMap( {lat: 42.122, lng: 70} );', 3000 );
                        });

                    </script>

                    <h4><?php _e('This beer can be found here: ', 'beerlog'); ?></h4>

                    <div id="map" style="width: 80%; height: 400px">
    
                    </div>

                    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey;?>&signed_in=true&callback=initMap">
                    </script>

                <?php endif;?>

            </div>
        </article>

    <?php endwhile;?>
    </div>
</div>
<?php get_footer(); ?>