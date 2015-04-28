<?php


class BeerlogBackendTest extends WP_UnitTestCase
{
	public function test_renderBeerPostMeta()
	{
		$controller = new \Beerlog\Controllers\Admin;
		$beerId = $this->factory->post->create( array( 'post_type' => 'beerlog_beer' ) );
		$beerPost = get_post( $beerId );

		$expValues = array(
			'abv' => rand( 2, 15 ),
			'ibu' => rand( 8, 60 ),
		);

		// Set some meta
		foreach ( $expValues as $metaName => $metaValue )
		{
			update_post_meta( $beerId, "_beerlog_meta_{$metaName}", $metaValue );
		}

		ob_start();
		$controller->renderBeerPropertiesEdit( $beerPost );
   		$metaHtml = ob_get_contents();
   		ob_end_clean();

		// Assert all meta is present and field values correspond to stored meta data
   		foreach ( $expValues as $metaName => $metaValue )
   		{
   			$this->assertTrue( (bool) preg_match( "/id=\"beerlog_meta_{$metaName}\"[^\/]+value=\"" . preg_quote( $metaValue, '/' ) . "\"[^\/]*\/>/imU", $metaHtml ) );
   		}
	}

	public function test_saveBeerPostMeta()
	{

	}
}