<?php
namespace Beerlog\Models;

class Brewery implements \Beerlog\Interfaces\ModelClass
{
	private static $_tableName = 'breweries';

	private static $_createSql = <<<EOSQL
CREATE TABLE ___TABLENAME___ (
  brewery_id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  owner_brewery_id mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  description text,
  address_country_code char(2),
  address_state char(2),
  address_street tinytext,
  address_city tinytext,
  address_zip varchar(20),
  added timestamp NOT NULL,
  PRIMARY KEY  brewery_id (brewery_id),
  KEY owner_brewery_id (owner_brewery_id)
)
EOSQL;

	public static function getCreateTableSql( $prefix )
	{
		return str_replace( '___TABLENAME___', $prefix . self::$_tableName, self::$_createSql );
	}

	public static function getTableName()
	{
		return self::$_tableName;
	}
}

