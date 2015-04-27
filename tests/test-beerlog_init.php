<?php

class BeerlogInitTest extends WP_UnitTestCase
{
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

}