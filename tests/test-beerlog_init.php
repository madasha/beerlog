<?php

class BeerlogInitTest extends WP_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();

		// Set the 'beerlog_styles_loaded' option to false to force loading of styles terms
		update_option( 'beerlog_styles_loaded', 'false' );
	}

	public function test_beerPostTypeInit()
	{
		// Assert beer post type set
		$postTypes = get_post_types( array( 'public' => true ), 'object' );
		$this->assertTrue( isset( $postTypes['beerlog_beer'] ) );

		$beerPostType = $postTypes['beerlog_beer'];

		// Assert some essential basic properties
		$this->assertTrue( $beerPostType->public );
		$this->assertTrue( $beerPostType->publicly_queryable );
		$this->assertTrue( $beerPostType->show_ui );
		$this->assertEquals( 'post', $beerPostType->capability_type );
		$this->assertFalse( $beerPostType->hierarchical );
		$this->assertTrue( in_array( 'beerlog_style', $beerPostType->taxonomies ) );

		// Assert Labels are set properly
		$expectedLabels = \Beerlog\Models\Beer::getPostTypeLabels();
		$actualLabels	= (array) $beerPostType->labels;
		foreach ( $expectedLabels as $label => $value )
			$this->assertEquals( $value, $actualLabels[ $label ] );

		// Assert basic properties are set properly
		$expectedProps = \Beerlog\Models\Beer::getPostTypeProperties();
		foreach ( $expectedProps as $propName => $value )
			$this->assertEquals( $value, $beerPostType->$propName, $propName );
	}

	public function test_beerStyleTaxonomyInit()
	{
		// Assert beer style taxonomy registered
		$beerStyleTax = get_taxonomy( 'beerlog_style' );
		$this->assertTrue( is_object( $beerStyleTax ) );

		// Assert some essential basic properties
		$this->assertTrue( $beerStyleTax->hierarchical );
		$this->assertTrue( $beerStyleTax->public );
		$this->assertTrue( $beerStyleTax->show_ui );
		$this->assertTrue( $beerStyleTax->rewrite['hierarchical'] );
		$this->assertFalse( $beerStyleTax->rewrite['with_front'] );

		// Assert Labels are set properly
		$expectedLabels = \Beerlog\Utils\Init::convertArrayToI18n( \Beerlog\Utils\Init::$styleTaxLabels );
		$actualLabels	= (array) $beerStyleTax->labels;
		foreach ( $expectedLabels as $label => $value )
			$this->assertEquals( $value, $actualLabels[ $label ] );
	}

	public function test_stylesJsonAndTermsOk()
	{
		// Assert beer styles file is present, readable and json-parsable
		$stylesFile = BEERLOG_BASEDIR . '/assets/beer-styles.json';
		$this->assertTrue( is_readable( $stylesFile ) );

		$json = trim( file_get_contents( $stylesFile ) );
		$this->assertFalse( empty( $json ) );

		$styles = json_decode( $json );
		$this->assertNotEquals( null, $styles );

		// Assert beer styles are loaded
		$terms = get_terms( 'beerlog_style', array( 'hide_empty' => 0, 'hierarchical' => true ) );
		$this->assertTrue( is_array( $terms ) );
		$this->assertTrue( !empty( $terms ) );

		// Assert they are the same count
		$stylesFlat = self::_getFlatArrayFromStyles( $styles );
		$this->assertEquals( count( $stylesFlat ), count( $terms ), "Terms count does not match styles file" );

		// TODO: Assert parental hierarchy
	}

	private static function _getFlatArrayFromStyles( $styles )
	{
    	$return = array();

    	foreach ( $styles as $style )
    	{
    		$styleClone 	= clone $style;
    		$styleChildren 	= false;
    		if ( isset( $styleClone->children ) )
    		{
    			$styleChildren = $styleClone->children;
    			unset( $styleClone->children );
    		}

    		$return[] = (array) $styleClone;

    		if ( $styleChildren )
    			$return = array_merge( $return, self::_getFlatArrayFromStyles( $styleChildren ) );
    	}

    	return $return;
	}
}