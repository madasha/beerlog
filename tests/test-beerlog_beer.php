<?php
class BeerlogModelsBeerTest extends WP_UnitTestCase
{
	private function _getNewBeerPost()
	{
		$beerId = $this->factory->post->create( array( 'post_type' => 'beerlog_beer' ) );
		$this->assertTrue( is_int( $beerId ) && $beerId > 0 );
		$beer = get_post( $beerId );
		$this->assertTrue( $beer instanceof WP_Post );
		$this->assertEquals( 'beerlog_beer', $beer->post_type );

		return new \Beerlog\Models\Beer( $beer );
	}

	public function test_beerPostCreate()
	{
		$beerPost = $this->_getNewBeerPost();
	}

	public function test_beerGetStyles()
	{
		// First insert the post and assign styles
		$beerPost = $this->_getNewBeerPost();
		$post = $beerPost->getPost();
		$termIds = array(
			 $this->factory->term->create( array( 'taxonomy' => 'beerlog_style', 'name' => 'Term 1', 'slug' => 'slug1' ) ),
			 $this->factory->term->create( array( 'taxonomy' => 'beerlog_style', 'name' => 'Term 2', 'slug' => 'slug2' ) ),
		);

		wp_set_post_terms( $post->ID, $termIds, 'beerlog_style' );

		$expectedTerms = array(
			get_term( $termIds[0], 'beerlog_style' ),
			get_term( $termIds[1], 'beerlog_style' ),
		);

		$styles = $beerPost->getStyles();
		$this->assertEquals( count( $expectedTerms ), count( $styles ) );

		foreach ( $styles as $key => $style )
		{
			$this->assertEquals( $expectedTerms[ $key ]->term_id, 	$style->term_id );
			$this->assertEquals( $expectedTerms[ $key ]->name, 		$style->name );
			$this->assertEquals( $expectedTerms[ $key ]->slug, 		$style->slug );
		}

	}
}