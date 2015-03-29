<?php
namespace Beerlog\Controllers;

class Admin
{
	private $_pluginBaseDir = null;

	public function __construct()
	{
		$this->_pluginBaseDir = plugin_dir_path( dirname( __FILE__ ) );
	}

	// Not used, custom post data used instead
	public function renderBeersList()
	{
		include $this->_pluginBaseDir . 'templates/admin/beers_list.php';
	}

	// Not used, custom post data used instead
	public function renderBreweries()
	{
		include $this->_pluginBaseDir . 'templates/admin/breweries.php';
	}

	public function renderBeerPropertiesEdit( $beerEntity )
	{
		global $post;

		include $this->_pluginBaseDir . 'templates/admin/beer_properties.php';
	}


}