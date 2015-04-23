<?php
namespace Beerlog\Utils;

class Init
{
	public static $propsSimple = array(
	  	'sourness'    => 1,
		'bitterness'  => 1,
	    'sweetness'   => 1,
	    'saltiness'   => 1,
	    'yeast'       => 1,
	    'hop'         => 1,
	    'malt'        => 1,
	);

	public static $propsPro = array(
	  	'fruty'       => 1,
	    'alcoholic'   => 1,
	    'citrus'      => 1,
	    'hoppy'       => 1,
	    'floral'      => 1,
	    'spicy'       => 1,
	    'malty'       => 1,
	    'toffee'      => 1,
	    'burnt'       => 1,
	    'sulphury'    => 1,
	    'sweet'       => 1,
	    'sour'        => 1,
	    'bitter'      => 1,
	    'dry'         => 1,
	    'body'        => 1,
	    'linger'      => 1,
	);

	private static $_controllers = array();
	private static $_stylesLoaded = false;

	public static function initAll()
	{
		self::initBeerPostType();
		self::initBreweryPostType();
		self::initBeerEditMeta();
		self::initBeerSaveMeta();
		self::initBeerCustomTaxonomies();
		self::initBeerStyles();
		self::initBeerViewTemplates();

		// echo "Styles:\n";
		// var_dump( get_terms( 'beerlog_style' ) );
		// exit;
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

	/**
	 * Not used anymore since initBeerPostType & initBreweryPostType do the job
	 * @deprecated
	 */
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
			'labels'        		=> $labels,
			'description'   		=> 'Holds beer-related data',
			'public'        		=> true,
			'publicly_queryable'	=> true,
			'show_ui' 				=> true,
			'query_var' 			=> true,
			'capability_type' 		=> 'post',
			'hierarchical' 			=> false,
			'taxonomies'			=> array('beerlog_style', 'beerlog_brewery'),
			'has_archive' 			=> true,
			'menu_position' 		=> 8,
			'menu_icon'				=> BEERLOG_DIR_URL . 'assets/img/icons/beer.png',
			// 'rewrite' 			=> array( 'slug' => 'beers', 'with_front' => false, 'feeds' => true, 'pages' => true),
			'rewrite' 				=> array( 'slug' => 'beers' ),
			'supports'      		=> array( 'title', 'editor', 'thumbnail', 'revisions', 'comments'),
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
			'has-archive' 		=> true,
			'show_in_menu'  	=> 'edit.php?post_type=beerlog_beer',
			'rewrite' 			=> array( 'slug' => 'breweries', 'with_front' => false, 'feeds' => true, 'pages' => true),
			'supports'      	=> array( 'title', 'editor', 'thumbnail', 'revisions', 'comments'),
		);

		register_post_type( 'beerlog_brewery', $args );
	}

	public static function initBeerCustomTaxonomies()
	{
		$labels = array(
			'name' 				=> __( 'Styles', 'beerlog' ),
			'singular_name' 	=> __( 'Style', 'beerlog' ),
			'search_items' 		=> __( 'Search Beer Styles', 'beerlog' ),
			'all_items' 		=> __( 'All Styles' ),
			'parent_item' 		=> __( 'Parent Style', 'beerlog' ),
			'parent_item_colon' => __( 'Parent Style:', 'beerlog' ),
			'edit_item' 		=> __( 'Edit Style', 'beerlog' ),
			'update_item' 		=> __( 'Update Style' ),
			'add_new_item' 		=> __( 'Add New Style' ),
			'new_item_name' 	=> __( 'New Style Name' ),
			'menu_name' 		=> __( 'Beer Styles' ),
		);

		$args = array(
			'hierarchical' 	=> true,
			'labels' 		=> $labels,
			'rewrite' => array(
	      		'slug' 			=> 'styles', // This controls the base slug that will display before each term
	      		'with_front' 	=> false, // Don't display the category base before "/styles/"
	      		'hierarchical' 	=> true // This will allow URL's like "/styles/lager/pilsner/"
	      	),
		);

		register_taxonomy( 'beerlog_style', 'beerlog_beer', $args );
		register_taxonomy_for_object_type( 'beerlog_style', 'beerlog_beer' );
	}

	public static function initBeerStyles()
	{
		if ( get_option('beerlog_styles_loaded') != 'true')
		{
			$stylesFile = BEERLOG_BASEDIR . '/assets/beer-styles.json';
			if ( is_readable( $stylesFile ) )
			{
				$json 	= trim( file_get_contents( $stylesFile ) );
				var_dump( $json );
				$styles = json_decode( $json );
				var_dump( $styles );
				if ( is_array( $styles ) )
				{
					self::insertStylesTerms( $styles );
					add_option('beerlog_styles_loaded', 'true');
				}
			}
		}
	}

	public static function insertStylesTerms( $styles, $parentId = 0, $taxonomy = 'beerlog_style' )
	{
		foreach( $styles as $style )
		{
			if ( $term = get_term_by( 'slug', $style->slug, $taxonomy ) )
			{
				wp_delete_term( (int) $term->term_id, $taxonomy );
			}

			$result = wp_insert_term(
				$style->name,
				$taxonomy,
				array(
					'parent'	=> $parentId,
					'slug' 		=> $style->slug,
				)
			);

			if ( $result && isset( $style->children ) && count( $style->children ) )
			{
				self::insertStylesTerms( $style->children, $result['term_id'] );
			}
		}
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

	public static function initBeerViewTemplates()
	{
		$frontendController = self::_getController( 'Frontend' );
		add_filter( 'template_include', array( $frontendController, 'getBeerViewTemplate' ), 1 );
	}
}