<?php
namespace Beerlog\Models;

class Beer
{
	private $_post;
	private $_styles;

	public function __construct( $post )
	{
		$this->setPost( $post );
	}

	public function getPost()
	{
		return $this->_post;
	}

	public function setPost( $post )
	{
		$this->_post = $post;
	}

	public function getTitle()
	{
		if ( !function_exists('get_the_title') )
		{
			// throw new Exception( "WP not initialized - function 'get_the_title' doea not exist!" );
			return $this->_post->post_title;
		}

		return get_the_title( $this->_post->ID );
	}

	public function getMeta( $metaName, $single = true, $escHtml = true )
	{
		if ( !function_exists('get_post_meta') )
			throw new Exception( "WP not initialized - function 'get_post_meta' doea not exist!" );

		$meta = get_post_meta( $this->_post->ID, $metaName, $single );

		return $escHtml ? esc_html( $meta ) : $meta;
	}

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

