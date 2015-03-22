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
		if ( !self::_db_table_exist( 'beers' ) )
			self::_create_db_tables();

		// TODO: Options get set here
	}

	/**
	 * Asserts if a DB table which this plugin uses already exists.
	 *
	 * @return boolean
	 */
	private static function _db_table_exist( $table )
	{
		global $wpdb;
		$tableName = $wpdb->prefix . self::BEERLOG_DB_PREFIX . $table;

		return $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $tableName;
	}

	private static function _create_db_tables()
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$modelsDir = dir( __DIR__ . '/models' );

		while ( false !== ( $entry = $modelsDir->read() ) )
		{
			if ( preg_match( '/^(?P<class>[\w_]+)\.php$/', $entry, $matches ) )
			{
				// TODO: Register autoloader and forget about this!
				require_once $modelsDir->path . '/' . $entry;

				$className = 'Beerlog\\' . $matches['class'];

				if ( in_array( 'Beerlog\\Interfaces\\ModelClass', class_implements( $className ) ) )
				{
					$tableName = $className::gettableName();
					if ( !self::_db_table_exist( $tableName ) )
					{
						$createSql = $className::getCreateTableSql( $wpdb->prefix . self::BEERLOG_DB_PREFIX )
							. $wpdb->get_charset_collate();

						dbDelta( $createSql );
					}
				}
			}
		}
	}
}