<?php
namespace Beerlog\Models;

class Beer implements \Beerlog\Interfaces\ModelClass
{
	private static $_tableName = 'beers';

	private static $_createSql = <<<EOSQL
CREATE TABLE ___TABLENAME___ (
  id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  brewery_id mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  style_id smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  description text,
  added timestamp NOT NULL,
  PRIMARY KEY  id (id),
  KEY name (name),
  KEY brewery_id (brewery_id),
  KEY style_id (style_id)
)
EOSQL;

	private $_post;
	private $_styles;

	public static function getCreateTableSql( $prefix )
	{
		return str_replace( '___TABLENAME___', $prefix . self::$_tableName, self::$_createSql );
	}

	public static function getTableName()
	{
		return self::$_tableName;
	}

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

