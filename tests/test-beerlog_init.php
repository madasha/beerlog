<?php

class BeerlogInitTest extends WP_UnitTestCase
{
	public function test_beerPostTypeInit()
	{
		// Assert beer post type set
		$postTypes = get_post_types( array( 'public' => true ), 'object' );
		$this->assertTrue( isset( $postTypes['beerlog_beer'] ) );

		$beerPostType = $postTypes['beerlog_beer'];

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
}