<?php
/**
 * Plugin Name:     FIX AUTOLOGIN
 * Plugin URI:      https://agencia.fixonweb.com.br/plugin/fix-autologin
 * Description:     Permite autologin de um certo usuário através de uma key exclusiva cadastrado previamente
 * Author:          FIXONWEB
 * Author URI:      https://agencia.fixonweb.com.br
 * Text Domain:     fix-autologin
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         Fix-Autologin
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
require 'plugin-update-checker.php';
$fix1608230887_url_update 	= 'https://github.com/fixonweb/fix-autologin';
$fix1608230887_slug 		= 'fix-autologin/fix-autologin';
$fix1608230887_check 		= Puc_v4_Factory::buildUpdateChecker($fix1608230887_url_update,__FILE__,$fix1608230887_slug);

register_activation_hook( __FILE__, 'fix1608230887_activation_hook' );
function fix1608230887_activation_hook() {
    add_role( 'fix-administrativo', 'fix-administrativo', array( 'read' => true, 'level_0' => true ) );
}

add_action('wp_enqueue_scripts', "fix1608230887_enqueue_scripts");
function fix1608230887_enqueue_scripts(){
    wp_enqueue_script( 'jquery-validate-min', plugin_dir_url( __FILE__ ) . '/js/jquery.validate.min.js', array( 'jquery' )  );
    wp_enqueue_style('fix1608230887_style', plugin_dir_url(__FILE__) . '/css/fix1608230887_style.css', array(), '0.1.0', 'all');
}
