<?php
namespace Beerlog\Models;

class AbstractPost
{
	protected $_post;

	protected static $_postTypeLabels 		= array();
	protected static $_postTypeProperties 	= array();

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

	public static function getPostTypeLabels()
	{
		return static::$_postTypeLabels;
	}

	public static function getPostTypeProperties()
	{
		return static::$_postTypeProperties;
	}
}