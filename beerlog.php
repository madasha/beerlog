<?php
/**
 * Plugin Name: Beerlog
 * Plugin URI: https://github.com/madasha/beerlog
 * Description: Beer blogging - add, rate, comment on beers, breweries, brewpubs, bars, etc.
 * Version: 0.0.1
 * Author: Mihail Irintchev
 * Author URI: http://github.com/madasha
 * License: GPL2
 */

/*  Copyright 2015  Mihail Irintchev  (email : madasha@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
namespace Beerlog;

defined( 'ABSPATH' ) or die( 'U\'r playing with powers you do not fully understand!' );

// TODO: Register autoloader and forget about this!
require_once 'interfaces/ModelClass.php';

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
				$className = __NAMESPACE__ . '\\' . $matches['class'];

				if ( in_array( __NAMESPACE__ . '\\ModelClass', class_implements( $className ) ) )
				{
					$tableName = $className::$tableName;
					if ( !self::_db_table_exist( $tableName ) )
					{
						$createSql = $className::getCreateTableSql( $wpdb->prefix . self::BEERLOG_DB_PREFIX );
						dbDelta( $createSql );
					}
				}
			}
		}
	}
}


// Activation setup
register_activation_hook( __FILE__, array( 'Beerlog\Installer', 'install' ) );

