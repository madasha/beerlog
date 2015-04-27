<?php
namespace Beerlog\Models;

class Beer extends AbstractPost
{
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

