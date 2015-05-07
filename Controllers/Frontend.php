<?php
namespace Beerlog\Controllers;

use Beerlog\Models\Beer;

class Frontend
{
	public function getBeerViewTemplate( $templatePath )
	{
		if ( get_post_type() == 'beerlog_beer' )
		{
			$filename = is_single() ? 'single-beerlog_beer.php' : 'archive-beerlog_beer.php';

			// checks if the file exists in the theme first,
    		// otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array( $filename ) ) )
        		$templatePath = $theme_file;
    		else
        		$templatePath = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/frontend/' . $filename;
		}

		return $templatePath;
	}

	public static function renderBeerStyles( Beer $beerlogBeer )
	{
		$beerStyles = $beerlogBeer->getStyles();
		foreach ( $beerStyles as $styleTerm )
		{
            ?>
            <span style="border: 1px solid #a1a1a1; padding: 3px; background: #dddddd; border-radius: 4px; margin-right: 4px">
                <?php echo $styleTerm->link ? $styleTerm->link : esc_html( $styleTerm->name ); ?>
            </span>
            <?php
		}
	}
}

