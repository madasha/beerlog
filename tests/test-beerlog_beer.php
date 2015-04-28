<?php
class BeerlogModelsBeerTest extends WP_UnitTestCase
{
	public function test_beerPostCreate()
	{
		$beerId = $this->factory->post->create( array( 'post_type' => 'beerlog_beer' ) );
		$this->assertTrue( is_int( $beerId ) && $beerId > 0 );

		$beer = get_post( $beerId );
		$this->assertTrue( $beer instanceof WP_Post );
		$this->assertEquals( 'beerlog_beer', $beer->post_type );
	}
}