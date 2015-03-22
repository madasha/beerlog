<?php
namespace Beerlog\Controllers;

class Admin
{
	private $_pluginBaseDir = null;

	public function __construct()
	{
		$this->_pluginBaseDir = plugin_dir_path( dirname( __FILE__ ) );
	}

	public function renderBeersList()
	{
		include $this->_pluginBaseDir . 'templates/admin/beers_list.php';
	}

	public function renderBreweries()
	{
		include $this->_pluginBaseDir . 'templates/admin/breweries.php';
	}
}