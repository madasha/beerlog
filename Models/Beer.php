<?php
namespace Beerlog\Models;

class Beer extends AbstractPost
{
	protected static $_postTypeLabels = array(
		'name'               => 'Beers',
		'singular_name'      => 'Beer',
		'add_new'            => 'Add New Beer',
		'add_new_item'       => 'Add New Beer',
		'edit_item'          => 'Edit Beer',
		'new_item'           => 'New Beer',
		'all_items'          => 'Beer List',
		'view_item'          => 'View Beer',
		'search_items'       => 'Search for beers',
		'not_found'          => 'No beers found',
		'not_found_in_trash' => 'No beers found in the Trash',
		'menu_name'          => 'Beers'
	);

	protected static $_postTypeProperties 	= array(
		'description'   		=> 'Holds beer-related data',
		'public'        		=> true,
		'publicly_queryable'	=> true,
		'show_ui' 				=> true,
		// 'query_var' 			=> true,
		'query_var' 			=> 'beerlog_beer',
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'taxonomies'			=> array('beerlog_style', 'beerlog_brewery'),
		'has_archive' 			=> true,
	);

	private $_styles;

	public function getStyles( $preloadLinks = true )
	{
		if ( $this->_styles === null )
		{
			$beerStyles = wp_get_post_terms( $this->_post->ID, 'beerlog_style',
	            array( 'orderby' => 'parent', 'order' => 'ASC' )
	        );

			$this->_styles = $beerStyles ? $beerStyles : array();

			if ( $preloadLinks )
	        	$this->_preloadStylesLinks();
		}

		return $this->_styles;
	}

	private function _preloadStylesLinks()
	{
		foreach ( $this->_styles as $styleTerm )
		{
			$styleTerm->url 	= false;
            $styleTerm->link 	= false;

			$termLink = get_term_link( $styleTerm );

            if ( !is_wp_error( $termLink ) )
            {
            	$styleTerm->url 	= esc_url( $termLink );
            	$styleTerm->link 	= "<a href=\"{$styleTerm->url}\">" . esc_html( $styleTerm->name ) . "</a>";
            }
		}
	}
}

