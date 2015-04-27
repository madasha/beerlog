<?php
namespace Beerlog\Utils;

class Installer
{
	const BEERLOG_DB_PREFIX = 'beerlog_';

	/**
	 * Handles plugin activation/installation - saves default options values, creates necessary DB tables, etc.
	 *
	 * @return void
	 */
	public static function install()
	{
		// Check for DB and create tables if necessary (sssentially check if the most rudimentary table: 'beers' is there)
		self::_createDbTables();

		// Any options to set?
		self::_setInitialOptions();
	}

	public static function uninstall()
	{
		self::_dropDbTables();
	}

	private static function _createDbTables()
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$modelClasses = self::getModelClasses();
		foreach ( $modelClasses as $className )
		{
			$tableName = $className::gettableName();
			if ( method_exists( $className, 'getCreateTableSql' ) )
			{
				$createSql = $className::getCreateTableSql( $wpdb->prefix . self::BEERLOG_DB_PREFIX )
					. $wpdb->get_charset_collate();

				$result = dbDelta( $createSql );
				// var_dump( $createSql, $result );
				// exit;
			}
		}
	}

	private static function _dropDbTables()
	{
		global $wpdb;

		$modelClasses = self::getModelClasses();
		foreach ( $modelClasses as $className )
		{
			$tableName = $className::gettableName();
			$wpdb->query( "DROP TABLE `$tableName`" );
		}
	}

	public static function getModelClasses()
	{
		$modelsDir = dir( BEERLOG_BASEDIR . '/Models' );

		$modelClasses = array();
		while ( false !== ( $entry = $modelsDir->read() ) )
		{
			if ( preg_match( '/^(?P<class>[\w_]+)\.php$/', $entry, $matches ) )
			{
				$className = 'Beerlog\\Models\\' . $matches['class'];

				if ( in_array( 'Beerlog\\Interfaces\\ModelClass', class_implements( $className ) ) )
				{
					$modelClasses[] = $className;
				}
			}
		}

		return $modelClasses;
	}

	private static function _setInitialOptions()
	{
		// This forces the beer styles terms generation, adds the option if it does not exist
		update_option( 'beerlog_styles_loaded', 'false' );
	}
}