<?php
namespace Beerlog;

class Beer implements ModelClass
{
	private static $_tableName = 'beers';

	private static $_createSql = <<<EOSQL
CREATE TABLE ___TABLENAME___ (
  id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  brewery_id mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  style_id smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  description text,
  added timestamp NOT NULL,
  PRIMARY KEY  id (id),
  KEY name (name),
  KEY brewery_id (brewery_id),
  KEY style_id (style_id)
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

