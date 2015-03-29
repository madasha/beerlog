<?php
namespace Beerlog\Utils;

class Init
{
	private static $_metaNonceAction 	= 'beerlog_edit_beer';
	private static $_metaNonceField 	= 'beerlog_meta_nonce';

	public static function initAll()
	{
		self::initBeerPostType();
		self::initBreweryPostType();
		self::initBeerEditMeta();

		add_action( 'save_post', array( __CLASS__, 'savePost' ) );
	}

	public function addBeerMenus()
	{
		$adminController = new \Beerlog\Controllers\Admin();
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
		add_action('add_meta_boxes', function() {
			add_meta_box( 'beer-properties', 'Beer Properties', function() {
					global $post;
					$beerEntity = get_post_custom( $post->ID );
					$controller = new \Beerlog\Controllers\Admin;

					// We'll use this nonce field later on when saving.
					wp_nonce_field( self::$_metaNonceAction, self::$_metaNonceField );

					echo $controller->renderBeerPropertiesEdit( $beerEntity );
				},
				'beerlog_beer', 'normal', 'core'
			);

			// Add more meta?
		});
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public static function savePost( $postId )
	{
		if ( !self::assertEditPossible( $postId ) )
			return $postId;

		if ( isset( $_POST['beerlog_meta'] ) && is_array( $_POST['beerlog_meta'] ) )
		{
			foreach ( $_POST['beerlog_meta'] as $fieldName => $value )
			{
				update_post_meta( $postId, "_beerlog_meta_{$fieldName}", sanitize_text_field( $value ) );
			}
		}
	}

	public static function assertEditPossible( $postId )
	{
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return false;

		// Verify that the nonce is pesent and valid.
		if ( !isset( $_POST[ self::$_metaNonceField ] )
			|| !wp_verify_nonce( $_POST[ self::$_metaNonceField ], self::$_metaNonceAction ) )
			return false;

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] )
		{
			if ( !current_user_can( 'edit_page', $postId ) )
				return false;
		}
		else
		{
			if ( !current_user_can( 'edit_post', $postId ) )
				return false;
		}

		return true;
	}
}