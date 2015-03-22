<?php
namespace Beerlog\Interfaces;

interface ModelClass
{
	public static function getCreateTableSql( $prefix );

	public static function getTableName();
}