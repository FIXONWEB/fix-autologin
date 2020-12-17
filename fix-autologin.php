<?php
/**
 * Plugin Name:     FIX AUTOLOGIN
 * Plugin URI:      https://agencia.fixonweb.com.br/plugin/fix-autologin
 * Description:     Permite autologin de um certo usuário através de uma key exclusiva cadastrado previamente
 * Author:          FIXONWEB
 * Author URI:      https://agencia.fixonweb.com.br
 * Text Domain:     fix-autologin
 * Domain Path:     /languages
 * Version:         0.1.3
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


add_action( 'show_user_profile', 'fix1608230887_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'fix1608230887_show_extra_profile_fields' );

function fix1608230887_show_extra_profile_fields( $user ) {
	$fix1608230887_aul = get_the_author_meta( 'fix1608230887_aul', $user->ID );
	?>
	<h3>Login Magico</h3>

	<table class="form-table">
		<tr>
			<th><label for="fix1608230887_aul">Key</label></th>
			<td>
				<input type="text" id="fix1608230887_aul" name="fix1608230887_aul" value="<?php echo esc_attr( $fix1608230887_aul ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>
	<?php
	// echo $_SERVER['REQUEST_URI'];
}

add_action( 'user_profile_update_errors', 'fix1608230887_user_profile_update_errors', 10, 3 );
function fix1608230887_user_profile_update_errors( $errors, $update, $user ) {
	// if ( ! $update ) {
	// 	return;
	// }

	// if ( empty( $_POST['fix1608230887_aul'] ) ) {
	// 	$errors->add( 'fix1608230887_aul_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'crf' ) );
	// }

	// if ( ! empty( $_POST['fix1608230887_aul'] )  ) {
	// 	$errors->add( 'fix1608230887_aul_error', __( '<strong>ERROR</strong>: You must be born after 1900.', 'crf' ) );
	// }
}


add_action( 'personal_options_update', 'fix1608230887_update_profile_fields' );
add_action( 'edit_user_profile_update', 'fix1608230887_update_profile_fields' );

function fix1608230887_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! empty( $_POST['fix1608230887_aul'] )  ) {
		update_user_meta( $user_id, 'fix1608230887_aul',  $_POST['fix1608230887_aul']  );
	}
}



//--request
add_action( 'parse_request', 'fix1608230887_parse_request');
function fix1608230887_parse_request( &$wp ) {

	// if($wp->request == ''){
		$vai = 1;
		$fix1608230887_aul = isset($_GET['aul']) ? $_GET['aul'] : '';

		if($fix1608230887_aul) {
			if(current_user_can('administrator')) $vai = 0;

			if($vai) {
				if($fix1608230887_aul=='logout'){
					wp_logout();
					$url = $_SERVER['REQUEST_URI'];
					$url = preg_replace('/aul/', 'out', $url);
					wp_redirect( $url );
					exit;
				}
				

				$tmp_tt = get_users(array(
					'meta_key' => 'fix1608230887_aul', 
					'meta_value' => $fix1608230887_aul)
				);

				// print_r($tmp_tt);

				if($tmp_tt[0]->ID){
					wp_set_current_user( $tmp_tt[0]->ID );
	    			wp_set_auth_cookie( $tmp_tt[0]->ID );
					// if(current_user_can('fix-associado')) {
					// 	wp_redirect( home_url( '/fix-associados/associado/' ) );
					// 	exit;
					// }
					// if(current_user_can('fix-administrativo')) {
					// 	wp_redirect( home_url( '/fix-associados/listagem/' ) );
					// 	exit;
					// }
					$url = $_SERVER['REQUEST_URI'];
					$url = preg_replace('/aul/', 'login', $url);
					wp_redirect( $url );
					exit;
	    			
				// } else {
				// 	wp_logout();
				// 	$url = $_SERVER['REQUEST_URI'];
				// 	$url = preg_replace('/aul/', 'out', $url);
				// 	wp_redirect( $url );
				// 	exit;
				}
    		}
		}
	// }
	// if($wp->request == 'logout'){
	// 	wp_logout();
	// 	wp_redirect( home_url() );
	// 	exit;
	// }

}


add_shortcode("fix1608230887_list_user", "fix1608230887_list_user");
function fix1608230887_list_user($atts, $content = null){



	$args = array(
		'blog_id'      => $GLOBALS['blog_id'],
		'role'         => '',
		'role__in'     => array(),
		'role__not_in' => array(),
		'meta_key'     => '',
		'meta_value'   => '',
		'meta_compare' => '',
		'meta_query'   => array(),
		'date_query'   => array(),        
		'include'      => array(),
		'exclude'      => array(),
		'orderby'      => 'login',
		'order'        => 'ASC',
		'offset'       => '',
		'search'       => '',
		'number'       => '',
		'count_total'  => false,
		'fields'       => 'all',
		'who'          => '',
	 ); 
	$users = get_users( $args ); 
	// echo '<pre>';
	// print_r($users);
	// echo '</pre>';

	//$all_meta_for_user = get_user_meta( 9 );

	ob_start();
	foreach ($users as $user) {
		$metas = get_user_meta( $user->ID );
		// echo '<pre>';
		// print_r($user);
		// echo '</pre>';

		// echo '<pre>';
		// print_r($metas);
		// echo '</pre>';

		// echo '<div>----</div>';
		foreach ($metas as $key => $meta) {
			if($key == 'fix1608230887_aul'){
				echo '<div><a href="?aul='.$meta[0].'">'.$user->ID.' - '.$user->data->user_login.'</a></div>';
				// echo '<div>'.$key.': </div>';
				// echo '<pre>';
				// print_r($meta);
				// echo '</pre>';
				// echo '<div> -- </div>';
			}
		}


	}
	return ob_get_clean();
}