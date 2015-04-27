<?php

/**
 * Extending WP_UnitTestCase does not work in this case because of the DB stuff that happens in setUp and tearDown
 */
class BeerlogInstallationTest extends PHPUnit_Framework_TestCase {

	private static $_tablesToCheck = array( 'breweries' );

	public function setUp()
	{
		delete_option('beerlog_styles_loaded'); // Just in case
	}

	public function tearDown()
	{
		global $wpdb;

		// Wipe out created tables
		foreach( self::$_tablesToCheck as $tableName )
		{
			$tableName = $wpdb->prefix . \Beerlog\Utils\Installer::BEERLOG_DB_PREFIX . $tableName;
			$wpdb->query( "DROP TABLE IF EXISTS `$tableName`" );
		}

		delete_option('beerlog_styles_loaded');
	}

	public function test_getModelClasses()
	{
		$expectedModelClasses = array( 'Beerlog\\Models\\Brewery' );
		$this->assertEquals( $expectedModelClasses, \Beerlog\Utils\Installer::getModelClasses() );
	}

	public function test_install()
	{
		global $wpdb;

		// Call the installation procedure
		\Beerlog\Utils\Installer::install();

		// We expect certain options to have been set, assert that
		$this->assertEquals( 'false', get_option('beerlog_styles_loaded') );

		// We expect new tables in DB, so asset that
		foreach( self::$_tablesToCheck as $tableName )
		{
			$tableName = $wpdb->prefix . \Beerlog\Utils\Installer::BEERLOG_DB_PREFIX . $tableName;
			$this->assertEquals( $tableName, $wpdb->get_var("SHOW TABLES LIKE '$tableName'") );
		}
	}
}

