<?php
use Beerlog\Models\Beer;
use Beerlog\Controllers\Frontend;

class BeerlogFrontendTest extends WP_UnitTestCase
{
	public function test_renderBeerStyles()
	{
		$beer = $this	->getMockBuilder('\\Beerlog\\Models\\Beer')
		 				->disableOriginalConstructor()
                		->getMock();

        $testStyles = array();
        $testStyles[] = (object) array(
        	'name' => 'my style 1', 'link' => 'sink'
        );
        $testStyles[] = (object) array(
        	'name' => 'other style 1', 'link' => 'other sink'
        );

        $beer	->method('getStyles')
             	->willReturn( $testStyles );

		ob_start();
		Frontend::renderBeerStyles( $beer );
   		$metaHtml = ob_get_contents();
   		ob_end_clean();

   		foreach ( $testStyles as $style ) {
   			$escValue = preg_quote( $style->link, '/' );
   			$this->assertTrue( (bool) preg_match(
   				"/<span\s+[^>]+>\s*{$escValue}\s*<\/span>/imU", $metaHtml
   			));
   		}
	}

}