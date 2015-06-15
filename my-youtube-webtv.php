<?php
/**
* @package My Youtude Webtv plugin
*
* Plugin Name:  My Youtube Webtv
* Plugin URI: http://local.fr
* Description: Ce plugin permet de gérer l'ajout de vidéo directement dans l'interface wordpress.
* Version: 0.1
* Author: Pascal Canadas
* Author URI: http://local.fr
* License: Private
*/
include_once(dirname(__FILE__)."/vendor/autoload.php");
include_once(dirname(__FILE__)."/admin/init.php");
include_once(dirname(__FILE__)."/admin/functions.php");
include_once(dirname(__FILE__)."/lib/function.php");
include_once(dirname(__FILE__)."/public/shortcode.php");
/* création du menu */
add_action('admin_menu', 'add_menu');