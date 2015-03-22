<?php
namespace Beerlog;

function autoload( $className )
{
	$namespace = preg_quote( __NAMESPACE__, '/' );
	if ( preg_match( "/^{$namespace}/", $className ) )
	{
		$relFilePath = preg_replace( "/^{$namespace}/", '', $className );
		$relFilePath = str_replace( '\\', DIRECTORY_SEPARATOR, $relFilePath );

		require_once( BEERLOG_BASEDIR . "/{$relFilePath}.php");
	}
}

spl_autoload_register( __NAMESPACE__ . '\\autoload' );