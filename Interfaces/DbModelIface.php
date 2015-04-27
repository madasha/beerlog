<?php
namespace Beerlog\Interfaces;

interface DbModelIface
{
	public static function getCreateTableSql( $prefix );

	public static function getTableName();
}