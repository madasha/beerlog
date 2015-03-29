<?php
namespace Beerlog\Utils;

class Init
{
	private static $_controllers = array();

	public static function initAll()
	{
		self::initBeerPostType();
		self::initBreweryPostType();
		self::initBeerEditMeta();
		self::initBeerSaveMeta();
	}

	private static function _getController( $controllerName )
	{
		$className = '\\Beerlog\\Controllers\\' . $controllerName;
		if ( !class_exists( $className, true ) )
			throw new Exception( "Controller class $className does not exist!" );

		if ( !isset( self::$_controllers[ $controllerName ] ) )
			self::$_controllers[ $controllerName ] = new $className;

		return self::$_controllers[ $controllerName ];
	}

	public function addBeerMenus()
	{
		$adminController = self::_getController( 'Admin' );
		add_menu_page( 'Beer list', 'Biers', 'manage_options',
			'edit.php?post_type=beerlog_beer', '',
			BEERLOG_DIR_URL . 'assets/img/icons/beer.png', 8
		);
		// add_submenu_page( 'edit.php?post_type=beerlog_beer', 'Breweries', 'Breweries',
			// 'manage_options', 'edit.php?post_type=beerlog_brewery'
		// );
	}

	public static function initBeerPostType()
	{
		$labels = array(
			'name'               => __( 'Beers', 'beerlog' ),
			'singular_name'      => __( 'Beer', 'beerlog' ),
			'add_new'            => __( 'Add New Beer', 'beerlog' ),
			'add_new_item'       => __( 'Add New Beer', 'beerlog' ),
			'edit_item'          => __( 'Edit Beer', 'beerlog' ),
			'new_item'           => __( 'New Beer', 'beerlog' ),
			'all_items'          => __( 'Beer List', 'beerlog' ),
			'view_item'          => __( 'View Beer', 'beerlog'),
			'search_items'       => __( 'Search for beers', 'beerlog' ),
			'not_found'          => __( 'No beers found', 'beerlog' ),
			'not_found_in_trash' => __( 'No beers found in the Trash', 'beerlog' ),
			'menu_name'          => __( 'Beers', 'beerlog' )
		);

		$args = array(
			'labels'        	=> $labels,
			'description'   	=> 'Holds beer-related data',
			'public'        	=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'taxonomies'		=> array('beerlog_style', 'beerlog_brewery'),
			'has-archive' 		=> true,
			'menu_position' 	=> 8,
			'rewrite' 			=> array( 'slug' => 'beers', 'with_front' => false, 'feeds' => true, 'pages' => true),
			'supports'      	=> array( 'title', 'editor', 'thumbnail', 'revisions', 'comments'),
		);

		register_post_type( 'beerlog_beer', $args );
	}

	public static function initBreweryPostType()
	{
		$labels = array(
			'name'               => __( 'Breweries', 'beerlog' ),
			'singular_name'      => __( 'Brewery', 'beerlog' ),
			'add_new'            => __( 'Add New Brewery', 'beerlog' ),
			'add_new_item'       => __( 'Add New Brewery', 'beerlog' ),
			'edit_item'          => __( 'Edit Brewery', 'beerlog' ),
			'new_item'           => __( 'New Brewery', 'beerlog' ),
			'all_items'          => __( 'Breweries', 'beerlog' ),
			'view_item'          => __( 'View Brewery', 'beerlog'),
			'search_items'       => __( 'Search for breweries', 'beerlog' ),
			'not_found'          => __( 'No breweries found', 'beerlog' ),
			'not_found_in_trash' => __( 'No breweries found in the Trash', 'beerlog' ),
			'menu_name'          => __( 'Breweries', 'beerlog' )
		);

		$args = array(
			'labels'        	=> $labels,
			'description'   	=> 'Holds breweries-related data',
			'public'        	=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'taxonomies'		=> array('beerlog_style', 'beerlog_brewery'),
			'has-archive' 		=> true,
			'show_in_menu'  	=> 'edit.php?post_type=beerlog_beer',
			'rewrite' 			=> array( 'slug' => 'breweries', 'with_front' => false, 'feeds' => true, 'pages' => true),
			'supports'      	=> array( 'title', 'editor', 'thumbnail', 'revisions', 'comments'),
		);

		register_post_type( 'beerlog_brewery', $args );
	}

	public static function initBeerEditMeta()
	{
		$adminController = self::_getController( 'Admin' );
		add_action('add_meta_boxes', function() use ( $adminController ) {
			add_meta_box( 'beer-properties', 'Beer Properties',
				array( $adminController, 'addBeerPropertiesMeta' ),
				'beerlog_beer', 'normal', 'core'
			);

			// Add more meta?
		});
	}

	public static function initBeerSaveMeta()
	{
		$adminController = self::_getController( 'Admin' );
		add_action( 'save_post', array( $adminController, 'savePostMeta' ) );
	}
}