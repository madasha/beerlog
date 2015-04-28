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

	public function test_stylesJsonIsOk()
	{
		$stylesFile = BEERLOG_BASEDIR . '/assets/beer-styles.json';
		$this->assertTrue( is_readable( $stylesFile ) );

		$json = trim( file_get_contents( $stylesFile ) );
		$this->assertFalse( empty( $json ) );

		$styles = json_decode( $json );
		$this->assertNotEquals( null, $styles );
	}

	public function ttest_insertStylesTerms()
	{
		// TODO: Use faker to fake names of beer styles! I'd be cool!
		$testTax = 'beerlog_testtax';
		register_taxonomy( $testTax, 'post' );

		$testTerms = array();
		$testTerms[0] = new stdClass();
		$testTerms[0]->name = 'Parent Style 1';
		$testTerms[0]->slug = 'parent_1';
		$testTerms[0]->children = array();

		$testTerms[0]->children[0] = new stdClass();
		$testTerms[0]->children[0]->name = "Child Style 1.1";
		$testTerms[0]->children[0]->slug = "child_1.1";

		$testTerms[0]->children[1] = new stdClass();
		$testTerms[0]->children[1]->name = "Child Stye 1.2";
		$testTerms[0]->children[1]->slug = "child_1.2";

		$testTerms[1] = new stdClass();
		$testTerms[1]->name = 'Parent Style 2';
		$testTerms[1]->slug = 'parent_2';
		$testTerms[1]->children = array();

		$testTerms[1]->children[1] = new stdClass();
		$testTerms[1]->children[1]->name = "Child Style 2.1";
		$testTerms[1]->children[1]->slug = "child_2.1";

		\Beerlog\Utils\Init::insertStylesTerms( $testTerms );
		$terms = get_terms( $testTax, array( 'hierarchical' => true ) );
		var_dump( $terms );
		// exit;

	}

	// TODO: Assert beer style terms registered, delete option first ??
	public function test_initBeerStyles()
	{
		// Assert beer styles are loaded
	}
}