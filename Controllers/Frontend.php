<?php
namespace Beerlog\Controllers;

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
}

