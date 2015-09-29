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
		$beerTax = get_taxonomy( 'beerlog_style' );
		$this->_testTaxonomyInit( $beerTax );

		// Assert Labels are set properly
		$expectedLabels = \Beerlog\Utils\Init::convertArrayToI18n( \Beerlog\Utils\Init::$styleTaxLabels );
		$actualLabels	= (array) $beerTax->labels;
		foreach ( $expectedLabels as $label => $value )
			$this->assertEquals( $value, $actualLabels[ $label ] );
	}

	public function test_breweriesTaxonomyInit()
	{
		// Assert beer style taxonomy registered
		$beerTax = get_taxonomy( 'beerlog_brewery' );
		$this->_testTaxonomyInit( $beerTax );

		// Assert Labels are set properly
		$expectedLabels = \Beerlog\Utils\Init::convertArrayToI18n( \Beerlog\Utils\Init::$breweryTaxLabels );
		$actualLabels	= (array) $beerTax->labels;
		foreach ( $expectedLabels as $label => $value )
			$this->assertEquals( $value, $actualLabels[ $label ] );
	}

	private function _testTaxonomyInit( $beerTax )
	{
		// Assert beer style taxonomy registered
		$this->assertTrue( is_object( $beerTax ) );

		// Assert some essential basic properties
		$this->assertTrue( $beerTax->hierarchical );
		$this->assertTrue( $beerTax->public );
		$this->assertTrue( $beerTax->show_ui );
		$this->assertTrue( $beerTax->rewrite['hierarchical'] );
		$this->assertFalse( $beerTax->rewrite['with_front'] );
	}

	public function test_stylesJsonAndTermsOk()
	{
		// Assert beer styles file is present, readable and json-parsable
		$stylesFile = BEERLOG_BASEDIR . '/assets/beer-styles.json';
		$this->_test_jsonAndTermsOk( $stylesFile, 'beerlog_style' );

		$breweriesFile = BEERLOG_BASEDIR . '/assets/breweries.json';
		$this->_test_jsonAndTermsOk( $breweriesFile, 'beerlog_brewery' );
	}

	private function _test_jsonAndTermsOk( $filename, $taxname )
	{
		$this->assertTrue( is_readable( $filename ) );

		$json = trim( file_get_contents( $filename ) );
		$this->assertFalse( empty( $json ) );

		$entities = json_decode( $json );
		$this->assertNotEquals( null, $entities );

		// Assert beer styles are loaded
		$terms = get_terms( $taxname, array( 'hide_empty' => 0, 'hierarchical' => true ) );
		// var_dump( $terms );
		$this->assertTrue( is_array( $terms ) );
		$this->assertTrue( !empty( $terms ) );

		// Assert they are the same count
		$entitiesFlat = self::_getFlatArrayFromEntites( $entities );
		$this->assertEquals( count( $entitiesFlat ), count( $terms ), "Terms count does not match file" );

		// TODO: Assert parental hierarchy
	}

	private static function _getFlatArrayFromEntites( $styles )
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
    			$return = array_merge( $return, self::_getFlatArrayFromEntites( $styleChildren ) );
    	}

    	return $return;
	}
}