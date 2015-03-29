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

define( 'BEERLOG_BASEDIR', dirname( __FILE__ ) );
define( 'BEERLOG_DIR_URL', plugin_dir_url( __FILE__ ) );

require "autoload.php";

// Activation setup
register_activation_hook( __FILE__, array( 'Beerlog\Utils\Installer', 'install' ) );

// Init stuff
add_action( 'init', array( 'Beerlog\Utils\Init', 'initAll' ) );


