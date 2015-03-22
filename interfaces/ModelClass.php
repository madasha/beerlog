<?php
namespace Beerlog;

interface ModelClass
{
	public static function getCreateTableSql( $prefix );

	public static function getTableName();
}