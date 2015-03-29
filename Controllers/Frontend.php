<?php
namespace Beerlog\Controllers;

class Frontend
{
	public function getBeerSingleViewTemplate( $templatePath )
	{
		if ( get_post_type() == 'beerlog_beer' )
		{
			if ( is_single() ) {
	    		// checks if the file exists in the theme first,
	    		// otherwise serve the file from the plugin
				if ( $theme_file = locate_template( array( 'single-beerlog_beer.php' ) ) )
	        		$templatePath = $theme_file;
	    		else
	        		$templatePath = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/frontend/single-beerlog_beer.php';
			}
		}

		return $templatePath;
	}
}

