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

	public static $styleTaxLabels = array(
		'name' 				=> 'Styles',
		'singular_name' 	=> 'Style',
		'search_items' 		=> 'Search Beer Styles',
		'all_items' 		=> 'All Styles',
		'parent_item' 		=> 'Parent Style',
		'parent_item_colon' => 'Parent Style:',
		'edit_item' 		=> 'Edit Style',
		'update_item' 		=> 'Update Style',
		'add_new_item' 		=> 'Add New Style',
		'new_item_name' 	=> 'New Style Name',
		'menu_name' 		=> 'Beer Styles',
	);

	public static $breweryTaxLabels = array(
		'name' 				=> 'Breweries',
		'singular_name' 	=> 'Brewery',
		'search_items' 		=> 'Search Breweries',
		'all_items' 		=> 'All Breweries',
		'parent_item' 		=> 'Parent Brewery',
		'parent_item_colon' => 'Parent Brewery:',
		'edit_item' 		=> 'Edit Brewery',
		'update_item' 		=> 'Update Brewery',
		'add_new_item' 		=> 'Add New Brewery',
		'new_item_name' 	=> 'Brewery Name',
		'menu_name' 		=> 'Breweries',
	);

	private static $_controllers = array();
	private static $_stylesLoaded = false;

	public static function initAll()
	{
		self::initBeerPostType();
		self::initBeerEditMeta();
		self::initBeerSaveMeta();
		self::initBeerStyleTaxonomy();
		self::initBeerStyles();
		self::initBreweryTaxonomy();
		self::initBreweries();
		self::initBeerViewTemplates();
		self::initPluginOptions();
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

	public static function initBeerPostType()
	{
		$args 						= \Beerlog\Models\Beer::getPostTypeProperties();
		$args['labels']        		= self::convertArrayToI18n( \Beerlog\Models\Beer::getPostTypeLabels() );
		$args['menu_position'] 		= 8;
		$args['menu_icon']			= BEERLOG_DIR_URL . 'assets/img/icons/beer.png';
		$args['rewrite'] 			= array( 'slug' => 'beers', 'with_front' => false, 'feeds' => true, 'pages' => true );
		$args['supports']      		= array( 'title', 'editor', 'thumbnail', 'revisions', 'comments' );

		register_post_type( 'beerlog_beer', $args );
	}

	public static function initBeerStyleTaxonomy()
	{
		$labels = self::convertArrayToI18n( self::$styleTaxLabels );
		$args 	= array(
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

	public static function initBreweryTaxonomy()
	{
		$labels = self::convertArrayToI18n( self::$breweryTaxLabels );
		$args 	= array(
			'hierarchical' 	=> true,
			'labels' 		=> $labels,
			'rewrite' => array(
	      		'slug' 			=> 'breweries', // This controls the base slug that will display before each term
	      		'with_front' 	=> false, // Don't display the category base before "/styles/"
	      		'hierarchical' 	=> true // This will allow URL's like "/styles/lager/pilsner/"
	      	),
		);

		register_taxonomy( 'beerlog_brewery', 'beerlog_beer', $args );
		register_taxonomy_for_object_type( 'beerlog_brewery', 'beerlog_beer' );
	}

	public static function convertArrayToI18n( $arr, $textDomain = 'beerlog' )
	{
		$converted = array();
		foreach ( $arr as $key => $value )
		{
			$converted[ $key ]	= __( $value, $textDomain );
		}

		return $converted;
	}

	public static function initBeerStyles()
	{
		if ( get_option('beerlog_styles_loaded') != 'true' )
		{
			$stylesFile = BEERLOG_BASEDIR . '/assets/beer-styles.json';
			if ( is_readable( $stylesFile ) )
			{
				$json 	= trim( file_get_contents( $stylesFile ) );
				$styles = json_decode( $json );

				if ( is_array( $styles ) )
				{
					self::insertTaxTerms( $styles );
					update_option('beerlog_styles_loaded', 'true');
				}
			}
		}
	}

	public static function initBreweries()
	{
		// TODO: Load breweries hierarchy from file like the beer styles
	}

	public static function insertTaxTerms( $taxTerms, $parentId = 0, $taxonomy = 'beerlog_style' )
	{
		foreach( $taxTerms as $taxTerm )
		{
			if ( $term = get_term_by( 'slug', $taxTerm->slug, $taxonomy ) )
			{
				$result = wp_update_term(
					$term->term_id,
					$taxonomy,
					array(
						'name'		=> $taxTerm->name,
						'slug' 		=> $taxTerm->slug,
						'parent'	=> $parentId,
					)
				);
			}
			else
			{
				$result = wp_insert_term(
					$taxTerm->name,
					$taxonomy,
					array(
						'slug' 		=> $taxTerm->slug,
						'parent'	=> $parentId,
					)
				);
			}

			if ( $result && $result['term_id'] && isset( $taxTerm->children ) && count( $taxTerm->children ) )
			{
				self::insertTaxTerms( $taxTerm->children, $result['term_id'] );
			}
		}
	}

	public static function initPluginOptions()
	{
		// TODO: Init the management of 'beerlog_styles_loaded' and 'breweries_loaded' taxonomies
		// Need to be able to rest them to force load form file
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
