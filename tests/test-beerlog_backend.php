<?php


class BeerlogBackendTest extends WP_UnitTestCase
{
	public function test_renderBeerPostMeta()
	{
		$beerId = $this->factory->post->create( array( 'post_type' => 'beerlog_beer' ) );
		$this->assertTrue( is_int( $beerId ) && $beerId > 0 );

		$beerPost = get_post( $beerId );
		$this->assertTrue( $beerPost instanceof WP_Post );
		$this->assertEquals( 'beerlog_beer', $beerPost->post_type );

		$expValues = array(
			'abv' => rand( 2, 15 ),
			'ibu' => rand( 8, 60 ),
		);
		// TODO: Add all the other meta props

		// Set some meta
		foreach ( $expValues as $metaName => $metaValue ) {
			update_post_meta( $beerId, "_beerlog_meta_{$metaName}", $metaValue );
		}

		$controller = new \Beerlog\Controllers\Admin;

		ob_start();
		$controller->renderBeerPropertiesEdit( $beerPost );
   		$metaHtml = ob_get_contents();
   		ob_end_clean();

		// Assert all meta is present and field values correspond to stored meta data
   		foreach ( $expValues as $metaName => $metaValue ) {
   			$escValue = preg_quote( $metaValue, '/' );
   			$this->assertTrue( (bool) preg_match( 
   				"/id=\"beerlog_meta_{$metaName}\"[^\/]+value=\"{$escValue}\"[^\/]*\/>/imU", $metaHtml 
   			));
   		}
	}
}