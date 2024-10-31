<?php
/****************************************************************
*=== Plugin Info ===
*Contributors: droopyk
*Tags: woocommerce, sales, tracking, assignment, distribution, testing, segmentation, price
*Requires at least: 4.0.1
*Tested up to: 5.0.3
*Stable tag: 1.2
*Requires PHP: 5.6
*License: GPLv3 or later License
*URI: http://www.gnu.org/licenses/gpl-3.0.html
*WC requires at least: 2.2
*WC tested up to: 3.5.3
*Plugin Name: SegmentLab Extension 
*Plugin URI: https://www.segmentlab.com/main/plugin.php
*Description: This plugin extends existing WooCommerce Functionality and allows you to set price experiments. Different prices and pricing techniques let you understand which pricing model brings the most revenue and sales from your audience.
*Version: 1.2
*Author: SegmentLab
*Author URI: https://www.SegmentLab.com/
*Developer: SegmentLab
*Developer URI: https://www.SegmentLab.com/
*Text Domain: segment-lab-extension
*Domain Path: /languages
*===============================================
* Copyright: © 2017-2019 SegmentLab.
* Donate link: https://www.segmentlab.com/main/contactus.php
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*=======================================================/
/*******************************************************/

//FUNCTIONS
function sgmnt_lab_noti_extension_warning() {
	echo '<div class="message error"><p>';
	printf(__('SegmentLab Extension is enabled but not effective. It requires <a href="%s">WooCommerce</a> in order to work.', 'segment-lab-extension'), 'http://www.woothemes.com/woocommerce/'); 
	echo '</p></div>';
}//WARNING MESSAGE, WP NOT ACTIVATED
function sgmnt_lab_get_client_ip () {
	$ipaddress = '';	
		
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	  $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	else if ($_SERVER['HTTP_CLIENT_IP']) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else if ($_SERVER['HTTP_X_FORWARDED']) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}
	else if ($_SERVER['HTTP_FORWARDED_FOR']) {
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}
	else if ($_SERVER['HTTP_FORWARDED']) {
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	}
	else if ($_SERVER['REMOTE_ADDR']) {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	else {
		$ipaddress = 'UNKNOWN';
	}

	if ($ipaddress == NULL || $ipaddress == "" || $ipaddress == " ") {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}

	return $ipaddress;
}//IP_DETECTION
function sgmnt_lab_check_var ($variable, $setup) {
	if ($setup == 1) {
		if (isset($variable) && $variable !== "" && $variable !== " " && $variable !== NULL && $variable !== false) {
			$response = $variable;			
		}
		else {
			$response = 0;
		}
	}
	else if ($setup == 2) {
		if (isset($variable) && $variable !== "" && $variable !== " " && $variable !== NULL && $variable !== false) {
			if ($variable == 1) {
				$response = "checked";
			}
			else {
				$response = "";
			}
		}
		else {
			$response = "";
		}
	}	
	else {
		if (isset($variable) && $variable !== "" && $variable !== " " && $variable !== NULL && $variable !== false) {
			$response = true;			
		}
		else {
			$response = false;
		}
	}	
	return $response;		
}//CHECK_VARIABLE
function sgmnt_lab_assign_rand_value($num) {
    // accepts 1 - 36
    switch($num) {
        case "1"  : $rand_value = "a"; break;
        case "2"  : $rand_value = "b"; break;
        case "3"  : $rand_value = "c"; break;
        case "4"  : $rand_value = "d"; break;
        case "5"  : $rand_value = "e"; break;
        case "6"  : $rand_value = "f"; break;
        case "7"  : $rand_value = "g"; break;
        case "8"  : $rand_value = "h"; break;
        case "9"  : $rand_value = "i"; break;
        case "10" : $rand_value = "j"; break;
        case "11" : $rand_value = "k"; break;
        case "12" : $rand_value = "l"; break;
        case "13" : $rand_value = "m"; break;
        case "14" : $rand_value = "n"; break;
        case "15" : $rand_value = "o"; break;
        case "16" : $rand_value = "p"; break;
        case "17" : $rand_value = "q"; break;
        case "18" : $rand_value = "r"; break;
        case "19" : $rand_value = "s"; break;
        case "20" : $rand_value = "t"; break;
        case "21" : $rand_value = "u"; break;
        case "22" : $rand_value = "v"; break;
        case "23" : $rand_value = "w"; break;
        case "24" : $rand_value = "x"; break;
        case "25" : $rand_value = "y"; break;
        case "26" : $rand_value = "z"; break;
        case "27" : $rand_value = "0"; break;
        case "28" : $rand_value = "1"; break;
        case "29" : $rand_value = "2"; break;
        case "30" : $rand_value = "3"; break;
        case "31" : $rand_value = "4"; break;
        case "32" : $rand_value = "5"; break;
        case "33" : $rand_value = "6"; break;
        case "34" : $rand_value = "7"; break;
        case "35" : $rand_value = "8"; break;
        case "36" : $rand_value = "9"; break;
    }
    return $rand_value;
}//RANDOM_VALUES_FOR_GENERATION
function sgmnt_lab_get_rand_alphanumeric($length) {
    if ($length>0) {
        $rand_id="";
        for ($i=1; $i<=$length; $i++) {
            mt_srand((double)microtime() * 1000000);
            $num = mt_rand(1,36);
            $rand_id .= sgmnt_lab_assign_rand_value($num);
        }
    }
    return $rand_id;
}//RANDOM_ALPHANUMERIC
function smgnt_lab_data_manager ($url, $blocking, $args) {
	$request = wp_remote_post( $url, array ( 'method' => 'POST',
		'timeout' => 5,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => $blocking,
		'headers' => array() ,
		'body' => $args,
		'cookies' => array()
	));
	if (is_wp_error( $request )) {
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body( $request );
	
	$data = json_decode($body);
	$data = json_decode(json_encode($data), true);

	if (!empty($data)) {
		return $data;
	}
	else {
		return false;
	}
}
function sgmnt_lab_remembered_stats ($price_id, $ending_id, $unique_id) {
	global $lab_directory_road;
	
	$args = array (
		'option' => 		"checkmemory",
		'hostname' => 		$_SERVER['HTTP_HOST'],
		'priceid' => 		$price_id,
		'endingid' => 		$ending_id,
		'uniqueid' => 		$unique_id,
	);
	
	$receiveData = 			smgnt_lab_data_manager ($lab_directory_road, true, $args);

	$results['price'] = 	$receiveData["price"];
	$results['ending'] = 	$receiveData["ending"];
	return $results;
	
}//REMEMBERED_STATS_VALS

//LINKS
$lab_master_domain 		=	"https://www.segmentlab.com";
$lab_support_leads 		= 	$lab_master_domain."/i/leads.php?c=1&r=1";
$lab_support_js		 	= 	$lab_master_domain."/user/js/snippets.mm_1_beta.js";
$lab_directory_road		=	$lab_master_domain."/plugins/wordpress/segmentlab/relay.php";

//CHECKS (if WooCommerce is active)
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$woocommerce_status = 1;
	$wc_ads_commerce = new segment_lab_extension();//START PLUGIN
}
else {
	$woocommerce_status = 2;	
	add_action('admin_notices', 'sgmnt_lab_noti_extension_warning');//NOTICE WARNING
	//return false;     
}

//CLASSES
class segment_lab_extension {	
	function __construct($woocommerce_status) {
		global $woocommerce_status;
		wp_enqueue_script('labScript', plugin_dir_url(__FILE__) . '/js/labScript.js');//INCLUDE LAB SCRIPT
		add_action( 'init', array( &$this, 'init' ), 9999999 );//ACTION INT		
					
		if ($woocommerce_status == 1) {
			add_action( 'init', 'sgmnt_lab_first_time_login_position' );//ACTION FIRST TIME LOGIN
			//add_filter( 'woocommerce_get_price', array( &$this, 'segment_lab_return_price' ), 999, 2 );//GET & SHOW PRICES
			//woocommerce_product_get_price
			function sgmnt_lab_first_time_login_position () {
				global $lab_support_leads;
				if (is_user_logged_in()) {	
					global $current_user;
					$user_id = 				$current_user->id;
					$price_testing_val = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_testing'), 1);
					$reg_testing_val = 		sgmnt_lab_check_var(get_option('sgmntLab_cfg_reg'), 1);
					$db_data_connection = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_connection'), 1);
					$master_settings_control = 		get_option('sgmntLab_cfg_pause');
					if ($master_settings_control == 0) {
						//ADMIN TESTING PERMIT CHECK
						if (is_admin() || is_admin_bar_showing()) { //chekina tik konkreciai ar admin meniu
							$testing_admin_status = sgmnt_lab_check_var(get_option('sgmntLab_cfg_admin_test'), 1);
						}
						else {
							if (isset($_COOKIE["lab_admin_testing"])) {
								$testing_admin_status = $_COOKIE["lab_admin_testing"];
							} 
							else {
								$testing_admin_status = 0;
							}
						}				
						if ($reg_testing_val == 1 && $price_testing_val == 1 && $db_data_connection == 1) {
							$registration_status_counter = get_user_meta($user_id, 'sgmntLab_user_registration_status', 'TRUE');
							if ($registration_status_counter >= 1) {
								update_user_meta( $user_id, 'sgmntLab_user_registration_status', $registration_status_counter+1 );
							}
							if ($registration_status_counter >= 2) {
								echo "<iframe style='display:none;' src='".$lab_support_leads."'></iframe>";
								delete_user_meta($user_id, 'sgmntLab_user_registration_status');
							}
						}					
					}
				}
			}//DETECTS FIRST LOGIN => REGISTRATION LEADS;			
		}
	}//CONSTRUCT
	function init () {
		global $lab_directory_road;
		global $woocommerce_status;
		global $lab_support_js;
		@session_start();
		$reg_library_stat = 		sgmnt_lab_check_var(get_option('sgmntLab_cfg_library'), 		1);
		
		if ($woocommerce_status == 0 && $reg_library_stat) {
			echo '<script type="text/javascript" src="'.$lab_support_js.'"></script>';
		}
		
		if (is_user_logged_in() && is_admin()) {
			//ir maciau kazkur funkcija yra kad jei ne tik prisijunges adminas, bet kad butu admin meniu, kad tik veiktu, tai sita dar dadet ten kur visokie seetingu tabai ir tt
			add_action( 'admin_menu', 										'sgmnt_lab_settings_tab_menu_page');			//SEGMENTLAB SETTINGS MENIU
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'sgmnt_lab_plugin_settings_tab' ); 				//ACTION LINKS	
			add_action( 'admin_notices', 									'sgmnt_lab_plugin_activate');					//PLUGIN_ACTIVATION
			register_deactivation_hook(__FILE__, 							'sgmnt_lab_plugin_deactivate');					//PLUGIN_DEACTIVATION

			wp_register_style('segmentLabStyle', plugins_url('/css/segmentLabStyles.css',__FILE__ ));
    		wp_enqueue_style('segmentLabStyle');
		
			if (sgmnt_lab_check_var(get_option('sgmntLab_cfg_connection'), 1) == 1 && $woocommerce_status == 1) {
				add_action( 'woocommerce_product_options_pricing', 		'sgmnt_lab_variation_testing_menu');				//ADD TESTING MENU
				add_action( 'woocommerce_process_product_meta_simple', 	'sgmnt_lab_variation_testing_castle_save');			//META TESTING, RECHECK (??)
				add_action( 'woocommerce_product_quick_edit_end', 		'sgmnt_lab_quick_testing_variables', 100, 1);		//ADD QUICK TESTING MENU
				add_action( 'manage_product_posts_custom_column', 		'sgmnt_lab_quick_testing_menu', 99, 2);				//HIDDEN_DATA_VARIABLES
				add_action( 'woocommerce_product_quick_edit_save',		'sgmnt_lab_quick_testing_castle_save', 10, 1);		//QUICK TESTING MENU SAVE
				add_action( 'admin_menu', 								'sgmnt_lab_quick_menu_results_button');				//SEGMENTLAB SETTINGS MENIU
				add_action( 'woocommerce_order_status_completed', 		'sgmnt_lab_update_order_status_completed', 1);
			}
			
			if (!isset($_COOKIE["lab_admin_testing"])) {
				$testing_admin_status = $_COOKIE["lab_admin_testing"];
				$set_val = sgmnt_lab_check_var(get_option('sgmntLab_cfg_admin_test'), 1);
				if ($set_val == 0) {
					setcookie("lab_admin_testing", 0, time() + (86400 * 30), "/");					
				}
				else if ($set_val == 1) {
					setcookie("lab_admin_testing", 1, time() + (86400 * 30), "/");					
				}			
			}
		}//ADMIN SETTINGS AND CUSTOMIZATION
	
		//CLIENT_SETTINGS		
		$master_settings_control = get_option('sgmntLab_cfg_pause');
		if (sgmnt_lab_check_var(get_option('sgmntLab_cfg_connection'), 1) == 1 && $master_settings_control == 0 && $woocommerce_status == 1) {
			add_action( 'user_register', 								'sgmnt_lab_new_user_registration', 10, 2 );
			add_action( 'woocommerce_thankyou', 						'sgmnt_lab_update_order_status_ordered', 1);
			
			$settings_price_correction = 	get_option('sgmntLab_cfg_correction');
			$settings_price_testing = 		get_option('sgmntLab_cfg_testing');
			if ($settings_price_correction == 1 ||  $settings_price_testing == 1) {
				add_filter( 'woocommerce_get_price_html', array( &$this, 	'segment_lab_return_wholesale_price' ), 1, 2 ); 
				add_filter( 'woocommerce_sale_price_html', array( &$this, 	'segment_lab_return_wholesale_price' ), 1, 2 );
				add_filter( 'woocommerce_get_price', array( &$this, 'segment_lab_return_price' ), 999, 2 );//GET & SHOW PRICES
			}
			
			//cia darom, kad arba veikia arba neveikia.... master isjungima!
		}
		
		//FUNCTIONS
		function sgmnt_lab_update_order_status_ordered ($order_id) {
			global $lab_directory_road;			
			$price_testing_val = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_testing'), 	1);
			$sell_testing_val = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_sell'), 		1);
			$db_data_connection = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_connection'), 	1);
			
			/*echo "price_testing_val".$price_testing_val."<br>";
			echo "sell_testing_val".$sell_testing_val."<br>";
			echo "db_data_connection".$db_data_connection."<br>";*/
			
			//break;
			
			//segmentLab settings connection
			if ($sell_testing_val == 1 && $price_testing_val == 1 && $db_data_connection == 1) {
				$price_ending_idas = 	get_option('sgmntLab_cfg_price_endings_idas');
				$send_order_id = 		$order_id;
				$user_ip = 				sgmnt_lab_get_client_ip();// kazko neveikia. rechek, pataisiau rodos
				$order = 				new WC_Order( $order_id );
				$items = 				$order->get_items();
				$products_ids_list = 	"";
				foreach ( $items as $item ) {
					$product_name = 		$item['name'];
					$product_id = 			$item['product_id'];
					$product_variation_id = $item['variation_id'];
					$product_quantity = 	$item['qty'];				
					$_product = 			wc_get_product( $product_id );
					$tyt = 					$_product->get_price();
					
					if (sgmnt_lab_check_var($product_id)) {
						$send_id = $product_id;
					}
					else {
						$send_id = "noFound;";
					}
					$products_ids_list .= "id:".$send_id.";price:".$tyt.";qty:".$product_quantity.";-";
				}								
				
			/*echo "data_order_id".$send_order_id."<br>";
			echo "data_domain".$_SERVER['HTTP_HOST']."<br>";
			echo "data_cookie".$_COOKIE["user_data"]."<br>";
			echo "data_user_ip".$user_ip."<br>";
			echo "unique_id".$_COOKIE["unique_lab_id"]."<br>";
			echo "data_product_ids_list".$products_ids_list."<br>";
			echo "endingidas".$price_ending_idas."<br>";
			echo "data_operation_status"."0011"."<br>";
				*/
				$args = array (
					'option' => 				"leadsmanagement",
					'data_order_id' => 			$send_order_id,
					'data_domain' => 			$_SERVER['HTTP_HOST'],
					'data_cookie' => 			$_COOKIE["user_data"],
					'data_user_ip' => 			$user_ip,
					'unique_id' => 				$_COOKIE["unique_lab_id"],
					'data_product_ids_list' => 	$products_ids_list,
					'endingidas' => 			$price_ending_idas,
					'data_operation_status' => 	"0011"
				);
				$receiveData = 	smgnt_lab_data_manager ($lab_directory_road, true, $args);
			}
		}//USER_MAKES_ORDER		
		function sgmnt_lab_update_order_status_completed ($order_id) {
			global $lab_directory_road;
			$price_testing_val = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_testing'), 	1);
			$sell_testing_val = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_sell'), 		1);
			$db_data_connection = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_connection'), 	1);											

			//segmentLab settings connection
			if ($sell_testing_val == 1 && $price_testing_val == 1 && $db_data_connection == 1) {
				$args = array (
					'option' => 				"leadsmanagement",
					'data_order_id' => 			$order_id,
					'data_domain' => 			$_SERVER['HTTP_HOST'],
					'data_operation_status' => 	"0111"
				);

				$receiveData = 	smgnt_lab_data_manager ($lab_directory_road, true, $args);
				//$response = 	$receiveData["status"];
			}
		}//USER_ORDER_COMPLETED	
		function sgmnt_lab_new_user_registration ($user_login_name_send_id) {
			update_user_meta( $user_login_name_send_id, 'sgmntLab_user_registration_status', 1 );
		}//NEW_USER_REGISTRATION
		function sgmnt_lab_settings_data () {
			global $current_user;
			$user_login_name_send_id = 	$current_user->id;
			$uniqueCastleKey = 			get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_castle', 	true );
			$uniqueLabKey = 			get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_lab', 		true );
			$keyStatus = 				get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 	true );	

			if (sgmnt_lab_check_var($uniqueCastleKey) && sgmnt_lab_check_var($uniqueLabKey) && sgmnt_lab_check_var($keyStatus)) {
				$results['castle_key'] = 	$uniqueCastleKey;
				$results['lab_key'] = 		$uniqueLabKey;
				$results['status'] = 		$keyStatus;
				$results['active'] = 		true;
			}
			else {
				$results['castle_key'] = 	$uniqueCastleKey;
				$results['lab_key'] = 		$uniqueLabKey;
				$results['status'] = 		$keyStatus;
				$results['active'] = 		false;					
			}				
			return $results;
		}		
		function sgmnt_lab_check_data () {
			$args = array (
				'option' => 	"checkmanagement",
				'castle_l' => 	$_SERVER['HTTP_HOST'],
				'castle_k' => 	$uniqueCastleKey
			);

			$receiveData = 			smgnt_lab_data_manager($lab_directory_road, true, $args);					
			$price_correction = 	sanitize_text_field($receiveData["price_correction"]);
			$price_status = 		sanitize_text_field($receiveData["status"]);

			if ($correction_testing_val !== $price_correction) {				
				if ($price_correction == 0 || $price_correction == 1) {
					if (sgmnt_lab_check_var($price_correction) && ($price_correction == "0" || $price_correction == "1")) {
						update_option('sgmntLab_cfg_correction', $price_correction);
					}
					if ($price_correction == 1) {
						$correction_testing_stat = "checked";
					}
					else {
						$correction_testing_stat = "";
					}
				}	
			}					
		}//DAR NEPANAUDOTAS
		function sgmnt_lab_activation_check ($zone_id) {
			global $current_user;	
			global $lab_directory_road;

			$user_login_name_send_id = 	$current_user->id;
			$uniqueCastleKey = 			get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_castle', true );

			$args = array (
				'option' => 			"pluginstatus",
				'castle_l' => 			$_SERVER['HTTP_HOST'],
				'castle_k' => 			$uniqueCastleKey,
				'id' => 				$zone_id,
				'plugin_s' => 			"deactivated"
			);

			$receiveData = 				smgnt_lab_data_manager ($lab_directory_road, true, $args);
			$plugin_service_status = 	$receiveData["status"];

			if ($plugin_service_status == 1) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				$error_message = "<div style=\"color: #444;
		font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif;font-size: 13px; line-height: 1.4em;\">
				<p><b>Plugin is under maintenance!</b><br>
				We apologize for the inconvenience and appreciate your patience.                 
				We will back soon. Thank you for using SegmentLab services.
				<br /></div>";
				die($error_message);
			}
		}//NEPANAUDOTAS DAR		
		function sgmnt_lab_plugin_settings_tab ( $links ) {
			$mylinks = array('<a href="' . admin_url( 'admin.php?page=segmentlab-settings-page' ) . '">Settings</a>',);
			return array_merge($mylinks, $links);
		}//CREATES SETTINGS BUTTON
		function sgmnt_lab_settings_tab_menu_page (){
			add_menu_page( 
				__( 'SegmentLab Settings', 'textdomain' ),
				'SegmentLab',
				'manage_options',
				'segmentlab-settings-page',
				'sgmnt_lab_settings_callback',
				plugin_dir_url( __FILE__ ).'/images/segmentlab_icon.png' 
			); 
		}//SEGMENTLAB SETTINGS PAGE ADDON		
		function sgmnt_lab_get_price_format ($addedPrice, $format, $template) {										
			$currency_pos = 				get_option('woocommerce_currency_pos');
			if ($format == 1) {
				$number_of_decimals = 		wp_specialchars_decode(stripslashes(get_option('woocommerce_price_num_decimals')), 	ENT_QUOTES);
				$decimal_sep = 				wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), 	ENT_QUOTES);
				$thousand_sep = 			wp_specialchars_decode(stripslashes(get_option('woocommerce_price_thousand_sep')), 	ENT_QUOTES);
				$addedPrice = number_format($addedPrice, $number_of_decimals, $decimal_sep, $thousand_sep);
			}

			if ($template == "default") {
				switch ($currency_pos) {
					case 'left' :
						$addedPrice = get_woocommerce_currency_symbol().$addedPrice;
					break;
					case 'right' :
						$addedPrice = $addedPrice.get_woocommerce_currency_symbol();
					break;
					case 'left_space' :
						$addedPrice = get_woocommerce_currency_symbol().'&nbsp;'.$addedPrice;
					break;
					case 'right_space' :
						$addedPrice = $addedPrice.'&nbsp;'.get_woocommerce_currency_symbol();
					break;
				}			
			}
			else if ($template == "regular") {
				return $addedPrice;
			}
			else if ($template == "sale") {
				return $addedPrice;
			}
			else {
				return $addedPrice;
			}
			
			return $addedPrice;
		}
		function sgmnt_lab_settings_callback () {
			?>
            <style type="text/css">
				div.texthover_help_tip {   
					display:block;
					z-index:99;
				}				
				div.overlay_help_tip {
					display: none;
					border-width:1px;
					border-color:#E77905;
					margin-top:8px;
					padding-left: 10px;
					padding-right: 10px;
					padding-top: 4px;
					padding-bottom: 4px;
					border-radius: 5px;
					position:absolute;
					min-width:100px; 
					max-width:400px;  
					/*min-height:50px;*/    
					background-color:rgba(0, 0, 0, 0.8);
					z-index: 999;
				}
				div.texthover_help_tip:hover .overlay_help_tip {
					display: block;
				}
				input.settingsButton:hover { 
					color: #ec5500;
					font-weight: 900;
				}
			</style>            
            <?
			global $current_user;
			global $thepostid;
			global $woocommerce;
			global $lab_directory_road;
			global $lab_master_domain;
			global $woocommerce_status;
			
			$user_login_name_send_id = 		$current_user->id;
			$user_email_send = 				$current_user->user_email;
			$data_time_explode = 			explode(" ", $current_user->user_registered);
			$sgmnt_settings_border_color = 	"#ffba00";
			$galunes_is_wp_db = 			get_option('sgmntLab_cfg_price_endings');

			
			//CHECK OR PLUGIN LINKED WITH ACC ACCOUNT			
			$db_data_connection = sgmnt_lab_check_var(get_option('sgmntLab_cfg_connection'), 1);
			if ($db_data_connection == 1) {
				$sgmnt_settings_border_color = "#e6e6e6";
			}
			
			$top_logo_display = "<div id=\"segmentlab_settings_tab_id\" style=\"margin-top:20px; background-color:#FFF; margin-left:2px; padding: 11px 15px; border-left: 4px solid $sgmnt_settings_border_color; max-width: 530px; -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);line-height: 19px;\">";
			$top_logo_display .= "<img src=\"".plugin_dir_url( __FILE__ )."images/logotestbeta.png\" alt=\"Mountain View\" style=\"height:30px;\">
			<span style=\"font-size:20px;position:absolute;margin-top:8px; margin-left:-8px;\"><b>settings</b></span>";
			$top_logo_display .=  "<br><br>";
			
			$help_tip_icon = "<img src=\"".plugin_dir_url( __FILE__ )."images/help_icon.png\" alt=\"Mountain View\" style=\"height:20px; position:absolute; margin-top: -15px;margin-left: 5px;\">";
			
			$header_text = "<div style=\"width:500px; text-align: justify; text-justify: inter-word; \">Find out which prices bring the most engagement and sales. See the most important data and easily make decisions to improve your conversions.</div>";
			

			$error_wrong_notice = '
			<div style="width:500px; margin-top:15px;">
				<div style="color:red"><b>Something went wrong!</b></div> 
				<div>
					<span style="margin-top:15px;">
					Try to Re-Install the plugin, or please
					<a style="text-decoration: none;" target="_blank" href="'.$lab_directory_road.'?option=contact" class="linkform">
					<span style=""><b>Contact Us</b></span></a> at any time.</span>
				</div>
				<div style="margin-top:10px;">
					<span>Sincerely, <br>
					Segment<span style="color:#db5f1f;"><b>Lab</b></span> team.</span>
				</div>
			</div>
			<script>document.getElementById("segmentlab_settings_tab_id").style.borderLeftColor = "red";</script>';		
			?>
			<style class="cp-pen-styles">
				ul,
				li {
				  list-style: none;
				  margin: 0;
				  padding: 0;
				}
				.tg-list {
				  text-align: center;
				  display: flex;
				  align-items: center;
				}
				.tg-list-item {
				  /*margin: 0 2em;*/
				}
				h2 {
				  color: #777;
				}
				h4 {
				  color: #999;
				}
				.tgl {
				  display: none !important;
				}
				.tgl, .tgl:after, .tgl:before, .tgl *, .tgl *:after, .tgl *:before, .tgl + .tgl-btn {
				  box-sizing: border-box;
				}
				.tgl::-moz-selection, .tgl:after::-moz-selection, .tgl:before::-moz-selection, .tgl *::-moz-selection, .tgl *:after::-moz-selection, .tgl *:before::-moz-selection, .tgl + .tgl-btn::-moz-selection {
				  background: none;
				}
				.tgl::selection, .tgl:after::selection, .tgl:before::selection, .tgl *::selection, .tgl *:after::selection, .tgl *:before::selection, .tgl + .tgl-btn::selection {
				  background: none;
				}
				.tgl + .tgl-btn {
				  outline: 0;
				  display: block;
				  width: 4em;
				  height: 2em;
				  /*position: relative;*/
				  cursor: pointer;
				  -webkit-user-select: none;
					 -moz-user-select: none;
					  -ms-user-select: none;
						  user-select: none;
				}
				.tgl + .tgl-btn:after, .tgl + .tgl-btn:before {
				  position: relative;
				  display: block;
				  content: "";
				  width: 50%;
				  height: 100%;
				}
				.tgl + .tgl-btn:after {
				  left: 0;
				}
				.tgl + .tgl-btn:before {
				  display: none;
				}
				.tgl:checked + .tgl-btn:after {
				  left: 50%;
				}
				.tgl-light + .tgl-btn {
				  background: #f0f0f0;
				  border-radius: 2em;
				  padding: 2px;
				  transition: all .4s ease;
				}
				.tgl-light + .tgl-btn:after {
				  border-radius: 50%;
				  background: #fff;
				  transition: all .2s ease;
				}
				.tgl-light:checked + .tgl-btn {
					/*background: #9FD6AE;*/
					/*background: #db5f1f;*/
					background: #5fd880;
				}
				.sgmnt_dots {
					padding-bottom: 2px;
					background-image: linear-gradient(to right, #cac6c6 33%, rgba(255,255,255,0) 0%);
					background-position: bottom;
					background-size: 3px 1px;
					background-repeat: repeat-x;
					cursor: help !important;
				}
				::-webkit-input-placeholder {
				   color: rgba(218,221,221,1);
				}
				:-moz-placeholder { /* Firefox 18- */
				   color: rgba(218,221,221,1);  
				}
				::-moz-placeholder {  /* Firefox 19+ */
				   color: rgba(218,221,221,1);  
				}
				:-ms-input-placeholder {  
				   color: rgba(218,221,221,1);  
				}					
			</style>
			<script>
				function sgmntlab_endings_box_openClose (settings) {
					if (settings == 1) {
						document.getElementById("showTablePriceEndings").name = 			"stop";
						document.getElementById("sgmnt_box_name_id").style.width = 			"120px";
						setTimeout(function(){
							document.getElementById("sgmnt_box_name3_id").style.height = 	"auto";
							document.getElementById("sgmnt_box_name3_id").style.width = 	"auto";
						}, 800);	
						document.getElementById("sgmnt_box_name2_id").style.opacity = 		"1";
						document.getElementById("sgmnt_down_arrow_id").style.display = 		"none";
						document.getElementById("sgmnt_up_arrow_id").style.display = 		"inline-block";
					}
					else {
						document.getElementById("showTablePriceEndings").name = 			"go";
						document.getElementById("sgmnt_box_name_id").style.width = 			"0px";
						document.getElementById("sgmnt_box_name2_id").style.opacity = 		"0";
						document.getElementById("sgmnt_box_name3_id").style.height = 		"0px";
						document.getElementById("sgmnt_box_name3_id").style.width = 		"0px";
						document.getElementById("sgmnt_down_arrow_id").style.display = 		"inline-block";
						document.getElementById("sgmnt_up_arrow_id").style.display = 		"none";
					}
				}							
				jQuery( document ).ready(function() {
					<?
					if ($correction_testing_stat == "checked" && $endings_table_show == "checked") {
						echo "sgmntlab_endings_box_openClose (1);";						
					}
					?>								

					jQuery('input[type=checkbox]#checkbox_example5').click(function(e) {
						var switch_position = document.getElementById('checkbox_example5');
						if (switch_position.checked) {
							document.getElementById("masster_switch_off_id").style.fontWeight = 	"900";
							document.getElementById("menu_general_testing_id").style.color = 		"#f0f0f0";
							document.getElementById("label_checkbox_example0").style.background = 	"#f0f0f0";
							document.getElementById("label_checkbox_example1").style.background = 	"#f0f0f0";
							document.getElementById("label_checkbox_example2").style.background = 	"#f0f0f0";
							document.getElementById("label_checkbox_example3").style.background = 	"#f0f0f0";
							document.getElementById("label_checkbox_example4").style.background = 	"#f0f0f0";

							sgmntlab_endings_box_openClose (0);

							setTimeout(function(){
								document.getElementById("all_ending_box_id").style.display = 	"none";
								document.getElementById("saveChangesId").style.display = 			"none";
								document.getElementById("menu_general_testing_id").style.display = 	"none";
							}, 800);
						}
						else {
							document.getElementById("menu_general_testing_id").style.color = 		"";
							document.getElementById("masster_switch_off_id").style.fontWeight = 	"";
							document.getElementById("label_checkbox_example0").style.background = 	"";
							document.getElementById("label_checkbox_example1").style.background = 	"";
							document.getElementById("label_checkbox_example2").style.background = 	"";
							document.getElementById("label_checkbox_example3").style.background = 	"";
							document.getElementById("label_checkbox_example4").style.background = 	"";

							setTimeout(function() {
								document.getElementById("saveChangesId").style.display = 			"";
								document.getElementById("menu_general_testing_id").style.display = 	"block";
								document.getElementById("all_ending_box_id").style.display = 		"block";
								setTimeout(function() {
									sgmntlab_endings_box_openClose (1);
								}, 400);
							}, 800);
						}
					});
					function segment_lab_save_settings_call (optionId,nameTag) {								
						var ajaxurl = 		location.pathname + location.search;                    
						var sendSubmit = 	true;
						var sendData = 		"?a=a";
						if (sendSubmit) {
							if (nameTag == "extensionendingstable") {
								var option = document.getElementById("showTablePriceEndings").name;
								if (option == "go") {
									option = 1;
								}
								else {
									option = 0;
								}
							}
							else {
								var option = document.getElementById(optionId).checked;
								if (option) {option = 1;} else {option = 0;}
							}
							sendData += "&save_segmentlab_data="+"settings"+"&set="+nameTag+"&val="+option;										
							jQuery.ajax({
							   type: "POST",
							   url: ajaxurl,
							   data: sendData,
							   success: function(result) { //we got the response
								   //P.S.supratau sitas grazina reiksme iskart iraso,, ir poto perkauna ir jau uzkrauna ja is atminties.. o ne tiesiogiai
									//document.getElementById("debug_testas_99").innerHTML = result;
								   //jQuery('.vehicle-value-box').html(msg+",00€");
								   /*setTimeout(function() { 
										//location.reload();
									}, 300);*/								   
								 },
								 error: function(jqxhr, status, exception) {
									 alert('Exception:', exception);
								 }
						   });
						}
					}
					jQuery('#checkbox_example5').click(function() {
						segment_lab_save_settings_call ("checkbox_example5","extensionpause");								
					});
					jQuery('#checkbox_example4').click(function() {
						segment_lab_save_settings_call ("checkbox_example4","extensionadmin");								
					});
					jQuery('#checkbox_example3').click(function() {
						segment_lab_save_settings_call ("checkbox_example3","extensionsell");								
					});
					jQuery('#checkbox_example2').click(function() {
						segment_lab_save_settings_call ("checkbox_example2","extensionregistration");								
					});
					jQuery('#checkbox_example1').click(function() {
						segment_lab_save_settings_call ("checkbox_example1","extensionpricetest");								
					});
					jQuery('#checkbox_example9').click(function() {
						segment_lab_save_settings_call ("checkbox_example9", "extensionlibrary");								
					});
					jQuery('#checkbox_example_correction').click(function() {
						var option = document.getElementById("checkbox_example_correction").checked;
						if (option) {
							sgmntlab_endings_box_openClose (1);
						}
						else {
							sgmntlab_endings_box_openClose (0);
						}
						segment_lab_save_settings_call ("checkbox_example_correction","extensionendings");								
					});								
					jQuery('input[type=button]#saveChangesId').click(function(e) {
						e.preventDefault();
						document.getElementById("saveChangesId").style.display = "none;"				
						jQuery('input[type=submit]#testing_submitas').click();
					});
					jQuery('#segmentlab_price_ending_button').click(function() {
						segment_lab_save_settings_call ("showTablePriceEndings","extensionendingstable");								
					});
					jQuery('#segmentlab_price_ending_button').click(function() {
						var table_status = document.getElementById("showTablePriceEndings").name;
						if (table_status == "go") {
							document.getElementById("showTablePriceEndings").name = 			"stop";
							document.getElementById("sgmnt_box_name_id").style.width = 			"120px";
							setTimeout(function(){
								document.getElementById("sgmnt_box_name3_id").style.height = 	"auto";
								document.getElementById("sgmnt_box_name3_id").style.width = 	"auto";
							}, 800);	
							document.getElementById("sgmnt_box_name2_id").style.opacity = 		"1";
							document.getElementById("sgmnt_down_arrow_id").style.display = 		"none";
							document.getElementById("sgmnt_up_arrow_id").style.display = 		"inline-block";
						}
						else {
							document.getElementById("showTablePriceEndings").name = 			"go";
							document.getElementById("sgmnt_box_name_id").style.width = 			"0px";
							document.getElementById("sgmnt_box_name2_id").style.opacity = 		"0";
							document.getElementById("sgmnt_box_name3_id").style.height = 		"0px";
							document.getElementById("sgmnt_box_name3_id").style.width = 		"0px";
							document.getElementById("sgmnt_down_arrow_id").style.display = 		"inline-block";
							document.getElementById("sgmnt_up_arrow_id").style.display = 		"none";
						}
					})
				});
				jQuery( function ( $ ) {					
					$(document).ready(function() {	
						jQuery('#sgmnt_lab_api_key_id').click(function(e) {
							var element = document.getElementById("sgmnt_lab_api_key_id").innerHTML;
							var $temp = $("<input>");
							$("body").append($temp);
							$temp.val(element).select();
							document.execCommand("copy");
							$temp.remove();

							document.getElementById("sgmnt_lab_copied_id").style.display = "inline";
							document.getElementById("sgmnt_lab_copy_id").style.display = "none";
						});
						jQuery('#sgmnt_lab_api_key_id')
						.mouseover(function(e) {
							document.getElementById("sgmnt_lab_copy_id").style.display = "inline";
						})
						.mouseout(function(e) {
							document.getElementById("sgmnt_lab_copy_id").style.display = "none";
						})
						.mouseout(function(e) {
							document.getElementById("sgmnt_lab_copied_id").style.display = "none";
						})
					});		
				});		
			</script>
			<?
			
			
			
			
			if ($woocommerce_status == 1) {
				if ($db_data_connection == 1 || $db_data_connection == "1") {
					$sgmnt_settings_border_color = "#db5f1f";
					if (get_option('sgmntLab_cfg_plugin_noti') != '1' && !is_plugin_active('/segment-lab-extension/segment-lab-extension.php')) {
						update_option('sgmntLab_cfg_plugin_noti', '1');
						$buttonas = sgmnt_lab_link_to_results ("setButton","masterButton","","",2);
						echo "<div style='margin-left: 2px !important;' class='updated'><p><b>Congratulations</b>, You've <b>successfully activated</b> a SegmentLab plugin. Now, you can go to your products page and edit the original price (“Testing Prices” field), also you can track your <b>".$buttonas."</b> and track all common data.</p></div>";
					}
					echo $top_logo_display;							 
					echo $header_text;
					echo '<script>document.getElementById("segmentlab_settings_tab_id").style.borderLeft = ""; </script>';
				
					$segmentLabData = sgmnt_lab_settings_data();
					if ($segmentLabData["active"]) {
						$uniqueCastleKey = 			$segmentLabData["castle_key"];
						$uniqueLabKey = 			$segmentLabData["lab_key"];

						$decimal_sep = 				wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);

						$correction_testing_stat = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_correction'), 		2);
						$price_testing_stat = 		sgmnt_lab_check_var(get_option('sgmntLab_cfg_testing'), 		2);
						$reg_testing_stat = 		sgmnt_lab_check_var(get_option('sgmntLab_cfg_reg'), 			2);
						$sell_testing_stat = 		sgmnt_lab_check_var(get_option('sgmntLab_cfg_sell'), 			2);
						$sell_testing_admin_stat = 	sgmnt_lab_check_var(get_option('sgmntLab_cfg_admin_test'), 		2);
						$master_pause = 			sgmnt_lab_check_var(get_option('sgmntLab_cfg_pause'), 			2);
						$endings_table_show = 		sgmnt_lab_check_var(get_option('sgmntLab_cfg_endingstable'), 	2);

						if ($master_pause == "checked") {
							$master_table_show = 	"display:none;";
							$master_save_show = 	"display:none;";
							$master_endings_show = 	"display:none;";
						}

						$help_description = "The API Key is the Piece of Information that is used to Encrypt and Connect your WooCommerce and Plugin accounts. The API key allows you to monitor your plugin's usage in SegmentLab Platform.";
						echo '
						<div style="margin-top:15px;margin-bottom:15px; padding-bottom:5px;">
							<div class="texthover_help_tip" >
								<div class="sgmnt_dots" style="float:left; padding-bottom:0px; cursor: help;">API Key:</div>
								<div style="width:390px; margin-top:25px;" class="overlay_help_tip">
									<span style="color:white;text-align:justify;">'.$help_description.'</span>
								</div>
							</div>
							<div style="float:left; width:auto;">
								<div>&nbsp;<b><span id="sgmnt_lab_api_key_id" style="cursor:copy;">'.$uniqueCastleKey.'</span> <span id="sgmnt_lab_copy_id" style="display:none;font-size: 9px; padding: 3px; padding-left: 5px; padding-right: 5px; border-radius: 10px; background-color: #f0f0f0;">Click To Copy</span> <span id="sgmnt_lab_copied_id" style="display:none;font-size: 9px; padding: 3px; padding-left: 5px; padding-right: 5px; border-radius: 10px; background-color: #f0f0f0;">Copied</span></b></div>
							</div>
						</div>';

						function go_aherty_price_ending_change ($decimal_sep) {
							$plus_mark_reg = "\+";
							$plus_mark = "+";
							$minus_mark = "-";	

							if ($decimal_sep == '.') {
								$decimal_block = ',';
								$decimal_regex = '\.';

							}
							else if ($decimal_sep == ',') {
								$decimal_block = '.';
								$decimal_regex = ',';
							}
							else {
								//if not , and not ., use default = .
								$decimal_sep = ".";
								$decimal_regex = '\.';
								$decimal_block = ",";
							}			
							echo '
								$("#myTable input[name=varna]").on("keyup change",function() {
									document.getElementById("saveChangesId").style.display="";
									var price_value = $(this).val();
									var price_regex = new RegExp( "[^0-9-+'.$decimal_regex.']", "gi" );
									var price_value_cleaned = price_value.replace(price_regex, "");								
									price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.'\+/g, "'.$plus_mark.'"); //;;->;						
									price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.'+/g, "'.$minus_mark.'"); //..->.
									price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.$plus_mark_reg.'+/g, "'.$plus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.$minus_mark.'+/g, "'.$minus_mark.'");

									price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.$decimal_regex.'+/g, "'.$plus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$plus_mark_reg.'+/g, "'.$plus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.$decimal_regex.'+/g, "'.$minus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$minus_mark.'+/g, "'.$minus_mark.'");

									var count_dots = countas(price_value_cleaned, "'.$decimal_sep.'");
									if (count_dots >= 2) {
										price_value_cleaned = price_value_cleaned.split("'.$decimal_sep.'", 2);
										price_value_cleaned = price_value_cleaned[0]+"'.$decimal_sep.'"+price_value_cleaned[1];
									}
									if (count_dots > 0) {
										var firstChar = price_value_cleaned.substring(0,1);
										if (firstChar == "'.$prices_sep.'" || firstChar == "'.$decimal_sep.'") {
											price_value_cleaned = price_value_cleaned.substring(1);
										}
									}

									var lastChar = price_value_cleaned.substr(price_value_cleaned.length - 1);
									if ((lastChar == "'.$plus_mark.'" || lastChar == "'.$minus_mark.'") && price_value_cleaned.length >= 2) {
										price_value_cleaned = price_value_cleaned.substring(0, price_value_cleaned.length - 1);
									}

									if (price_value !== price_value_cleaned) {					  								
										$(this).val(price_value_cleaned);
									}
								});
							';
						}
						function go_aherty_price_ending_blur ($decimal_sep) {
							$plus_mark_reg = "\+";
							$plus_mark = "+";
							$minus_mark = "-";
							if ($decimal_sep == '.') {
								$decimal_block = ',';
								$decimal_regex = '\.';
							}
							else if ($decimal_sep == ',') {
								$decimal_block = '.';
								$decimal_regex = ',';
							}
							else {
								//if not , and not ., use default = .
								$decimal_sep = ".";
								$decimal_regex = '\.';
								$decimal_block = ",";
							}		
							echo '
								$("#myTable input[name=varna]").on("blur",function() {	
									var price_value = $(this).val();
									var price_regex = new RegExp( "[^0-9-+'.$decimal_regex.']", "gi" );
									var price_value_cleaned = price_value.replace(price_regex, "");								
									price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.'\+/g, "'.$plus_mark.'"); //;;->;						
									price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.'+/g, "'.$minus_mark.'"); //..->.
									price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.$plus_mark_reg.'+/g, "'.$plus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.$minus_mark.'+/g, "'.$minus_mark.'");

									price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.$decimal_regex.'+/g, "'.$plus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$plus_mark_reg.'+/g, "'.$plus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.$decimal_regex.'+/g, "'.$minus_mark.'");
									price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$minus_mark.'+/g, "'.$minus_mark.'");

									var count_dots = countas(price_value_cleaned, "'.$decimal_sep.'");
									if (count_dots >= 2) {
										price_value_cleaned = price_value_cleaned.split("'.$decimal_sep.'", 2);
										price_value_cleaned = price_value_cleaned[0]+"'.$decimal_sep.'"+price_value_cleaned[1];
									}
									if (count_dots > 0) {
										var firstChar = price_value_cleaned.substring(0,1);
										if (firstChar == "'.$prices_sep.'" || firstChar == "'.$decimal_sep.'") {
											price_value_cleaned = price_value_cleaned.substring(1);
										}
									}

									var lastChar = price_value_cleaned.substr(price_value_cleaned.length - 1);
										if ((lastChar == "'.$plus_mark.'" || lastChar == "'.$minus_mark.'") && price_value_cleaned.length > 1) {
										price_value_cleaned = price_value_cleaned.substring(0, price_value_cleaned.length - 1);
									}

									if (price_value_cleaned.length < 2) {
										if (price_value_cleaned == "'.$plus_mark.'" || price_value_cleaned == "'.$minus_mark.'") {
											price_value_cleaned = "";
										}
									}

									var count_separ = countas(price_value_cleaned, "'.$decimal_sep.'");
									var cleared_nulls = "";
									if (count_separ > 0) {
										cleared_nulls = price_value_cleaned.replace("'.$decimal_regex.'", ".");
										cleared_nulls = Number(cleared_nulls);
										cleared_nulls = cleared_nulls.toString();
										cleared_nulls = cleared_nulls.replace(".","'.$decimal_regex.'");
										price_value_cleaned = cleared_nulls;
									}
									else {										
										if (price_value_cleaned !== "" && price_value_cleaned !== " " && price_value_cleaned !== null) {
											cleared_nulls = Number(price_value_cleaned);
											cleared_nulls = cleared_nulls.toString();
											price_value_cleaned = cleared_nulls;
										}	
									}			

									var firstChar = price_value_cleaned.substring(0,1);
									if (firstChar !== "'.$plus_mark.'" && firstChar !== "'.$minus_mark.'" && firstChar !== "") {
										price_value_cleaned = "'.$plus_mark.'"+String(price_value_cleaned);
									}	

									if (price_value !== price_value_cleaned) {					  								
										$(this).val(price_value_cleaned);
									}
								});
							';
						}
						function go_aherty_price_ending_enter ($decimal_sep) {
							$plus_mark_reg = "\+";
							$plus_mark = "+";
							$minus_mark = "-";
							if ($decimal_sep == '.') {
								$decimal_block = ',';
								$decimal_regex = '\.';
							}
							else if ($decimal_sep == ',') {
								$decimal_block = '.';
								$decimal_regex = ',';
							}
							else {
								//if not , and not ., use default = .
								$decimal_sep = ".";
								$decimal_regex = '\.';
								$decimal_block = ",";
							}				
							echo '
								$("#myTable input[name=varna]").keypress(function(e) {	
									if (e.which == 13) {										
										var price_value = $(this).val();
										var price_regex = new RegExp( "[^0-9-+'.$decimal_regex.']", "gi" );
										var price_value_cleaned = price_value.replace(price_regex, "");								
										price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.'\+/g, "'.$plus_mark.'"); //;;->;						
										price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.'+/g, "'.$minus_mark.'"); //..->.
										price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.$plus_mark_reg.'+/g, "'.$plus_mark.'");
										price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.$minus_mark.'+/g, "'.$minus_mark.'");

										price_value_cleaned = price_value_cleaned.replace(/'.$plus_mark_reg.$decimal_regex.'+/g, "'.$plus_mark.'");
										price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$plus_mark_reg.'+/g, "'.$plus_mark.'");
										price_value_cleaned = price_value_cleaned.replace(/'.$minus_mark.$decimal_regex.'+/g, "'.$minus_mark.'");
										price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$minus_mark.'+/g, "'.$minus_mark.'");

										var count_dots = countas(price_value_cleaned, "'.$decimal_sep.'");
										if (count_dots >= 2) {
											price_value_cleaned = price_value_cleaned.split("'.$decimal_sep.'", 2);
											price_value_cleaned = price_value_cleaned[0]+"'.$decimal_sep.'"+price_value_cleaned[1];
										}
										if (count_dots > 0) {
											var firstChar = price_value_cleaned.substring(0,1);
											if (firstChar == "'.$prices_sep.'" || firstChar == "'.$decimal_sep.'") {
												price_value_cleaned = price_value_cleaned.substring(1);
											}
										}

										var lastChar = price_value_cleaned.substr(price_value_cleaned.length - 1);
										if ((lastChar == "'.$plus_mark.'" || lastChar == "'.$minus_mark.'") && price_value_cleaned.length > 1) {
											price_value_cleaned = price_value_cleaned.substring(0, price_value_cleaned.length - 1);
										}

										if (price_value_cleaned.length < 2) {
											if (price_value_cleaned == "'.$plus_mark.'" || price_value_cleaned == "'.$minus_mark.'") {
												price_value_cleaned = "";
											}
										}							

										var count_separ = countas(price_value_cleaned, "'.$decimal_sep.'");
										var cleared_nulls = "";
										if (count_separ > 0) {
											cleared_nulls = price_value_cleaned.replace("'.$decimal_regex.'", ".");
											cleared_nulls = Number(cleared_nulls);
											cleared_nulls = cleared_nulls.toString();
											cleared_nulls = cleared_nulls.replace(".","'.$decimal_regex.'");
											price_value_cleaned = cleared_nulls;
										}
										else {										
											if (price_value_cleaned !== "" && price_value_cleaned !== " " && price_value_cleaned !== null) {
												cleared_nulls = Number(price_value_cleaned);
												cleared_nulls = cleared_nulls.toString();
												price_value_cleaned = cleared_nulls;
											}	
										}	

										var firstChar = price_value_cleaned.substring(0,1);
										if (firstChar !== "'.$plus_mark.'" && firstChar !== "'.$minus_mark.'" && firstChar !== "") {
											price_value_cleaned = "'.$plus_mark.'"+String(price_value_cleaned);
										}

										if (price_value !== price_value_cleaned) {					  								
											$(this).val(price_value_cleaned);
										}
									}
								});
							';
						}				
						?>

						<div>
							<div class="wrap" style="display: inline-table; float:left;">
								<script>
									jQuery( function ( $ ) {					
										$(document).ready(function() {	
											<?
												go_aherty_price_ending_change ($decimal_sep);
												go_aherty_price_ending_blur ($decimal_sep);
												go_aherty_price_ending_enter ($decimal_sep);
											?>
										});
									});
								</script>
								<form id="save_data_id" method="post" action="options.php" enctype="multipart/form-data" style="margin-top:20px;">
									<? $descriptionas = "Enter prices for further SegmentLab testing.<br>Example:50 (Max: 10, Sale price < Regular Price)."; ?>
									<div style="clear: both;"></div>
									<div id="menu_general_testing_id" style="<? echo $master_table_show; ?>">
										<div style="margin-top:20px;">
											<div style="width:210px; float: left;">
												<div style="margin-top:3px; float:left;" class="texthover_help_tip">
													<label class="sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;" for="ending_value_1">Price Ending Testing</label>
													<div  style="" class="overlay_help_tip">
														<span style="color:white; text-align:justify;">
															This test can help to determine which groups of consumers are more likely to respond with Rounded as opposed to Non-Rounded Pricing. Price change have individual factors that may influence consumer evaluation of buying, test up to 10 price endings. (Example: Product price: <? echo sgmnt_lab_get_price_format (40, 1, "default"); ?>, added -0.1 and 0.39 for testing, so the test variations will be the default price <? echo sgmnt_lab_get_price_format (40, 1, "default"); ?> and testing variations <? echo sgmnt_lab_get_price_format (30.99, 1, "default"); ?>, <? echo sgmnt_lab_get_price_format (40.39, 1, "default"); ?>.)
														</span>
													</div>                        
												</div>
											</div>
											<div style="float:left; ">								 
												<li class="tg-list-item">
													<input class="tgl tgl-light" id="checkbox_example_correction" name="test1" type="checkbox" <? echo $correction_testing_stat; ?>/>
													<label id="label_checkbox_example0"  class="tgl-btn" for="checkbox_example_correction"></label>
												</li>
											</div>
											<div style="clear: both;"></div>
										</div>
										<br>
										<script type="text/javascript">	
											jQuery( function ( $ ) {
												testas ();
												function testas () {
													var eilute = "<? echo $galunes_is_wp_db; ?>";																
													var eilute_cut = 	eilute.split(";");
													var eilute_ilgis = 	eilute_cut.length-1;
													if (eilute == "" || eilute == " " || eilute == null){
														eilute_ilgis = 0;
													}

													if (eilute_ilgis > 1) {
														$('#ending_value_1').remove();
													}
													for (i = 1; i < eilute_ilgis; i++) {
														$('#checkbox_example0_counter0').remove();
														var variable_for_price_cut = eilute_cut[i];
														var variable_for_price_cut_first_char = eilute_cut[i].charAt(0);
														if (variable_for_price_cut_first_char !== "+" && variable_for_price_cut_first_char !== "-") {
															variable_for_price_cut = "+"+variable_for_price_cut;										
														}	
														if (variable_for_price_cut.length > 1) {
															var lentele = '<td><span style="color: #8c8c8c; font-size:15px; float:left; width:20px; margin-top:3px; padding-left:5px; padding-right: 15px;">'+(i+1)+'.</span><input id="ending_value_'+(i)+'" name="varna" type="text" style="width:85px; border-style:solid; border-width:1px; border-radius:3px; color:#5b5b6b; font-weight: 100; margin:0px;" placeholder="+/- 0.00" value="'+variable_for_price_cut+'"></td>';
															$('#myTable').append('<tr class="child">'+lentele+'</tr>');
															if (eilute_ilgis > 11) {
																break;
															}
														}
													}
												}
												$("#button_add_row").click(function() {											
													var counter = 		1;
													var counter2 = 		0;
													var add_new_row = 	1;
													$("#myTable input[name=varna]").each(function() {
														row = $(this).closest("tr");									
														var sitas = $(this).val();
														counter2 = counter2 + 1;
														if (sitas) {
															counter = counter + 1;
														}
														else {
															document.getElementById("ending_value_"+counter2).style.borderColor = "red";
															add_new_row = 0;
														}
													});

													$("#button_add_row").blur(function() {
														var counter3 = 	0;
														$("#myTable input[name=varna]").each(function() {
															counter3 = counter3 + 1;
															row = $(this).closest("tr");	
															var sitas = $(this).val();
															if (!sitas) {
																document.getElementById("ending_value_"+counter3).style.borderColor = "";
															}
														});
													});

													if (add_new_row == 1 && counter <= 9) {
														var lentele = '<td><span style="color: #8c8c8c; font-size:15px; float:left; width:20px; margin-top:3px; padding-left:5px; padding-right: 15px;">'+(counter+1)+'.</span><input id="ending_value_'+(counter)+'" name="varna" type="text" style="width:85px; color:#5b5b6b; font-weight: 100;  border-style:solid; border-width:1px; border-radius:3px; margin:0px;" placeholder="+/- 0.00"> </input></td>';
														$('#myTable').append('<tr class="child">'+lentele+'</tr>');
													}
													else {
														if (counter >= 9) {
															document.getElementById("permitas2567893457").classList = "texthover_help_tip";
														}
													}											

													<?
													go_aherty_price_ending_change ($decimal_sep);
													go_aherty_price_ending_blur ($decimal_sep);
													go_aherty_price_ending_enter ($decimal_sep);
													?>
												});
											});
										</script>
										<div style="clear: both;"></div>
										<div style="">
											<div style="width:210px; float: left;">
												<div style="float:left; margin-top:3px;" class="texthover_help_tip">
													<label class="sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;" >Price Option Testing</label>
													<div style="" class="overlay_help_tip">
														<span style="color:white; text-align: justify;">
															SegmentLab built-in functions lets to add up to 10 prices to run different price and pricing techniques experiments, so you can understand which pricing model brings the most revenue and engagement from your audience. 
														</span>
													</div>                        
												</div>
											</div>
											<div style="float:left;">
												<li class="tg-list-item">
													<input class="tgl tgl-light" id="checkbox_example1" name="test1" type="checkbox" <? echo $price_testing_stat; ?>/>
													<label id="label_checkbox_example1" class="tgl-btn" for="checkbox_example1"></label>
												</li>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div style="margin-top:20px;">
											<div style="width:210px; float: left;">
												<div style="float:left; margin-top:3px;" class="texthover_help_tip">
													<label class="sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;" >Tracking - Registration</label>
													<div style="" class="overlay_help_tip">
														<span style="color:white; text-align: justify;">
															 SegmenyLab automatically creates registration tracking point for new users. You can allways turn off this future to set the lead location by yourself.
														</span>
													</div>                        
												</div>
											</div>
											<div style="float: left;">   								
												<li class="tg-list-item">
													<input class="tgl tgl-light" id="checkbox_example2" name="test2" type="checkbox" <? echo $reg_testing_stat; ?>/>
													<label id="label_checkbox_example2" class="tgl-btn" for="checkbox_example2"></label>
												</li>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div style="margin-top:20px;">
											<div style="width:210px; float: left;">
												<div style="float:left; margin-top:3px;" class="texthover_help_tip">
													<label class="sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;" >Tracking - Sell</label>
													<div style="" class="overlay_help_tip">
														<span style="color:white; text-align: justify;">
															 SegmenyLab automatically creates sale tracking point for your sales. You can allways turn off this future to set the lead location by yourself.
														</span>
													</div>                        
												</div>
											</div>
											<div style="float: left;">   
												<li class="tg-list-item">
													<input class="tgl tgl-light" id="checkbox_example3" name="test3" type="checkbox" <? echo $sell_testing_stat; ?>/>
													<label id="label_checkbox_example3" class="tgl-btn" for="checkbox_example3"></label>
												</li>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div style="margin-top:20px;">
											<div style="width:210px; float: left;">
												<div style="float:left; margin-top:3px;" class="texthover_help_tip">
													<label class="sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;">Tracking - Admin (Exclude)</label>
													<div style="" class="overlay_help_tip">
														<span style="color:white;">
															SegmentLab gives an the ability to exclude admin users from being tracked. (Recommended to turn on for not affecting the statistics.)
														</span>
													</div>                        
												</div>
											</div>
											<div style="float:left;">   								
												<li class="tg-list-item">
													<input class="tgl tgl-light" id="checkbox_example4" name="test4" type="checkbox" <? echo $sell_testing_admin_stat; ?>/>
													<label id="label_checkbox_example4" class="tgl-btn" for="checkbox_example4"></label>
												</li>
											</div>
											<div style="clear: both;"></div>
										</div>
									</div>
									<div style="margin-top:20px;">
										<div style="width:210px; float: left;">
											<div style="float:left; margin-top:3px;" class="texthover_help_tip">
												<label class="weightGrow sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;" id="masster_switch_off_id" >SegmentLab Extension Pause</label>
												<div style="" class="overlay_help_tip">
													<span style="color:white;">
														Toggle the switch to ON and all Tracking and Testing experiments will be paused. The prices and all settings will get back to default immediately.
													</span>
												</div>                        
											</div>
										</div>
										<div style="float:left;">   								
											<li class="tg-list-item">
												<input class="tgl tgl-light" id="checkbox_example5" name="test4" type="checkbox" <? echo $master_pause; ?>/>
												<label class="tgl-btn" for="checkbox_example5"></label>
											</li>
										</div>
										<div style="clear: both;"></div>
									</div>
									<p class="submit" style="display: none;">							  
										<input name="submit" id="testing_submitas" type="submit" class="button-primary" style="display:none;"  value="Save Changes"/>
									</p>
								</form>
								<div style="margin-top:30px;">
									<? echo sgmnt_lab_link_to_results ("setButton","masterButton","","",1); ?>							
								</div>
							</div>
							<div style="float: left;">
								<div id="all_ending_box_id" style="margin-top:25px;margin-left:30px;float:left; <? echo $master_endings_show; ?>">
									<div>
										<div style="float: left;">
											<div class="" style="border-radius: 15px;margin-top: 4px;min-height: 26px;text-align: center;min-width: 51px;border: none;border-style: none;background-color: #f0f0f0;box-shadow: none;">
												<div>
													<div id="segmentlab_price_ending_button" style="text-align: center;width: 51px; cursor: pointer; float:left;">
														<img id="sgmnt_down_arrow_id" src="<? echo plugin_dir_url( __FILE__ ).'images/arrow_down.png' ?>" alt="Arrow Down" style="height:26px;">
														<img id="sgmnt_up_arrow_id" src="<? echo plugin_dir_url( __FILE__ ).'images/arrow_up.png' ?>" alt="Arrow Up" style="height:26px; display: none;">
													</div>
													<div id="sgmnt_box_name_id" style="width:0px;  -webkit-transition: width 2s, height 4s; transition: width 1s, height 4s; overflow: hidden; text-align: center;float:left; padding-top:3px; padding-bottom: 3px; color: #8c8c8c; ">
														<span id="sgmnt_box_name2_id" style="opacity: 0; -webkit-transition: opacity 2s ease-in-out; -moz-transition: opacity 2s ease-in-out; -ms-transition: opacity 2s ease-in-out; -o-transition: opacity 2s ease-in-out; transition: opacity 2s ease-in-out; padding-right: 25px; font-weight: 600;">Endings</span>
													</div>
												</div>
												<div style="clear: both;"></div>
												<div id="sgmnt_box_name3_id" style="height: 0px; width:0px; -webkit-transition: width 2s, height 4s; overflow: hidden; ">
													<div style="padding-left: 10px;padding-right: 10px;">
														<hr style="margin-top:0px;">
														<div id="segmentlab_endings_table_id" name="go" style="float: left; margin-left:5px; padding-bottom: 10px;">
															<table id="myTable0"  style="border-collapse: collapse; -moz-border-radius:6px;" >
																<tbody>
																	 <tr>
																		<td>
																			<span id="checkbox_example0_counter00" style="color: #8c8c8c;float:left; width:20px; margin-top:3px;  font-size:15px; padding-left:5px; padding-right: 15px;">1.</span><input id="checkbox_example00" name="varna" type="text" style="width:85px; border-style:solid; border-width:1px; border-radius:3px; color:#5b5b6b; font-weight: 100;  margin:0px;" value="0" placeholder="+/- 0.00" disabled></input>
																		</td> 
																	</tr>
																</tbody>
															</table>
															<table id="myTable"  style="border-collapse: collapse; -moz-border-radius:6px;" >
																<tbody>
																	 <tr>
																		<td>
																			<span id="checkbox_example0_counter0" style="color: #8c8c8c;float:left; width:20px; margin-top:3px;  font-size:15px; padding-left:5px; padding-right: 15px;">2.</span><input id="ending_value_1" name="varna" type="text" style="width:85px; border-style:solid; border-width:1px; border-radius:3px; color:#5b5b6b; font-weight: 100; margin:0px;" placeholder="+/- 0.00"> </input>
																		</td> 
																	</tr>
																</tbody>
																<tr>
																	<td>
																		<div id="permitas2567893457" style="float:left; margin-left:40px;" class="">
																			<input type="button" id="button_add_row" class="button-secondary" onClick="javascript:void(0);" style="font-size: 17px;font-weight: bold; width: 85px; outline: none;" value="+">
																			<div class="overlay_help_tip">
																				<span style="color:white;">
																					Only 10 testing values (Automatically includes 0 for default price).
																				</span>
																			</div>
																		</div>
																	</td>
																 </tr>
																<tr>
																	<td>
																		<input id="saveChangesId" name="submit" type="button" style="display: none; width: 85px; margin-top:5px; margin-left:40px;" class="button-primary" value="Confirm"/>
																	</td>
																</tr>										
																<input id="showTablePriceEndings" name="go" type="button" style="display: none; margin-left:-168px;" class="button-primary" value="Confirm"/>
															</table>
														</div>															
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div style="clear: both;"></div>
							</div>
							<div style="clear: both;"></div>
						</div>

						<script>
							jQuery('#save_data_id input[type=submit]').click(function(e) {
								e.preventDefault();
								var eile2 = new Array();
								jQuery("#myTable input[name=varna]").each(function(){
									row = jQuery(this).closest("tr");									
									var sitas = jQuery(this).val();
									if (sitas.length >= 2) {
										eile2.push(sitas);										
									}
								});

								//Now use a function to make the array unique
								Array.prototype.unique = function(){
								   var u = {}, a = [];
								   for (var i = 0, l = this.length+1; i < l; ++i) {
									  if (this[i] in u)
										 continue;
									  a.push(this[i]);
									  u[this[i]] = 1;
								   }
								   return a;
								}
								eile2.unshift(0);
								eile2 = eile2.unique();					
								eile2 = eile2.join(";");
								var eile = eile2;

								var ajaxurl = "<? echo $_SERVER['REQUEST_URI']; ?>";
								var testing_prices = document.getElementById("checkbox_example1").checked;
								var testing_prices_data = 0;
								if (testing_prices == true) {testing_prices_data = 1;}

								var correction_reg = document.getElementById("checkbox_example_correction").checked;
								var correction_reg_data = 0;
								if (correction_reg == true) {correction_reg_data = 1;}					

								var testing_reg = document.getElementById("checkbox_example2").checked;
								var testing_reg_data = 0;
								if (testing_reg == true) {testing_reg_data = 1;}

								var testing_sell = document.getElementById("checkbox_example3").checked;
								var testing_sell_data = 0;
								if (testing_sell == true) {testing_sell_data = 1;}

								var admin_testing = document.getElementById("checkbox_example4").checked;
								var admin_testing_data = 0;
								if (admin_testing == true) {admin_testing_data = 1;}
								jQuery('#saveChangesId').css("background-color","green");
								setTimeout(function(){
									jQuery('#saveChangesId').css("background-color","#0085ba");
								}, 300);

								//alert("corr:"+correction_reg_data);
								var sendData = "";
								sendData += "&save_segmentlab_data=" + 		"save";
								sendData += "&testing_correction_data=" + 	correction_reg_data;
								sendData += "&testing_prices_data=" + 		testing_prices_data;
								sendData += "&admin_testing_data=" + 		admin_testing_data;
								sendData += "&ending_price=" + 				encodeURIComponent(eile);
								sendData += "&testing_reg_data=" + 			testing_reg_data;
								sendData += "&testing_sell_data=" + 		testing_sell_data;
								jQuery.ajax({
								   type: "POST",
								   url: ajaxurl,
								   data: sendData,  
								   success: function(msg){
									  //jQuery('#testasDebug2').html(msg);
								   }
							   });
							})
						</script>
						<div id="testasDebug2"></div>
						<?			
					}
					else {
						echo $top_logo_display;
						echo "Something went wrong!<br>Please try to reinstall the plugin.<br>For further troubleshooting contact us.<br><br>";
					}
				} 
				else if ($db_data_connection == 0 || $db_data_connection == "0") {
					echo $top_logo_display;		
					echo $header_text;

					$segmentLabData = 		sgmnt_lab_settings_data();
					$uniqueCastleKey = 		$segmentLabData["castle_key"];
					$uniqueLabKey = 		$segmentLabData["lab_key"];
					$menu_option_text = 	get_option('sgmntLab_cfg_settings_text');
					$menu_option_settings = get_option('sgmntLab_cfg_settings_menu');
					?>
					<style>
						.ajax_loader {
							margin-top:30px;
							margin-bottom:30px;
							height:30px;
							width:30px;
							-webkit-animation: rotation .7s infinite linear;
							-moz-animation: rotation .7s infinite linear;
							-o-animation: rotation .7s infinite linear;
							animation: rotation .7s infinite linear;
							border-left:8px solid #cd671c;
							border-right:8px solid rgba(0,0,0,.20);
							border-bottom:8px solid rgba(0,0,0,.20);
							border-top:8px solid rgba(0,0,0,.20);
							border-radius:100%;
						}
						@keyframes rotation {
							from {transform: rotate(0deg);}
							to {transform: rotate(359deg);}
						}
						@-webkit-keyframes rotation {
							from {-webkit-transform: rotate(0deg);}
							to {-webkit-transform: rotate(359deg);}
						}
						@-moz-keyframes rotation {
							from {-moz-transform: rotate(0deg);}
							to {-moz-transform: rotate(359deg);}
						}
						@-o-keyframes rotation {
							from {-o-transform: rotate(0deg);}
							to {-o-transform: rotate(359deg);}
						}
					</style>
					<?
					echo '
					<div id="segmentlab_time_loader_id" style="display:none; width:100%; text-align:-webkit-center;">
						<div style="" class="ajax_loader"></div>
					</div>';
					
					if ($menu_option_settings == 0 || !isset($menu_option_settings)) {
						//STARTAS, Connect Plugin
						$connectPluginButton = '
						<div id="segmentlab_connect_plugin_id" style="margin-bottom:20px;">
							<div class="submit" style="">
								<input id="check_user_availability_id" name="submit" type="submit" class="button-primary" value="Connect Plugin"/>
							</div>
							<div id="segmentlab_connect_plugin_help_tip_id" class="texthover_help_tip" style="margin-top: -32px; margin-left: 120px;" >
								'.$help_tip_icon.'
								<div style="" class="overlay_help_tip">
									<span style="color:white; text-align: justify;">
										System will check your domain address: <b>'.$_SERVER["HTTP_HOST"].'</b> for previous registrations and help to activate your account.
									</span>
								</div>
							</div>
						</div>';
						echo $connectPluginButton;						
					}
					else if ($menu_option_settings == 1 && ($menu_option_text == 0 || $menu_option_text == 1)) {
						//RELINK COUNTER
						$stat_rel_att = 5;
						$relink_setting = get_option('sgmntLab_cfg_relink');
						if (sgmnt_lab_check_var($relink_setting)) {
							$relink_setting_val = $relink_setting;
							$remainingas = $stat_rel_att - $relink_setting_val;
						}
						else {
							$relink_setting_val = 0;
							$remainingas = $stat_rel_att - $relink_setting_val;							
						}

						if ($relink_setting_val > 0) {
							$today = date('Y-m-d H:i:s');
							$relink_date_stop = get_option('sgmntLab_cfg_relink_date');
							if (($remainingas == 0) && ($today >= $relink_date_stop)) {
								$bad_Val = 			'';
								$remainingas = 		5;
								update_option('sgmntLab_cfg_relink', '0');	
							}
							else {
								$bad_Val = '
								<div style="margin-top:15px;">
									<div style="width:135px; float:left; color:red;">
										<label for="text_add_3">Re-Link Failed!</label>
									</div>
									<div style="width:335px;">   
										<b>'.$remainingas.' attempts remaining.</b>
									</div>
								</div><br>';
								$sgmnt_settings_border_color = "red";
							}							
						}
						else {
							$bad_Val = '';							
						}
						
						if ($remainingas > 0) {
							$uniqueRegEmail = get_option('sgmntLab_cfg_settings_reg_mail');
							if (sgmnt_lab_check_var($uniqueRegEmail)) {
								$menu_option_settings = get_option('sgmntLab_cfg_settings_menu');
								if ($menu_option_settings == 2) {
									$reg_mail = "";
								}
								else {
									$reg_mail = $uniqueRegEmail;
								}
							}
							else {
								$reg_mail = "";
							}			

							$show_relink_form = 0;
							if ($menu_option_text == 0 || !isset($menu_option_text)) {
								//when REGISTERING and domain already exists
								$text_option = '<div id="relink_third" style="display:block; width:500px; text-align: justify; text-justify: inter-word;">Welcome back, system detected that you already have an account which is associated with this domain: <b>'.$_SERVER["HTTP_HOST"].'</b>. <div style="margin-top:5px;">Now you need to Synchronize & Re-Link your plugin with existing account. Please type your Email Address and User Key which is provided at <a style="text-decoration: none; margin-left:0px;" href="'.$lab_master_domain.'" target="_blank"><b>SegmentLab.com</b></a> (<i>My Account Settings -> Keys -> User</i>).</div></div>';
								$borderColor = 		"";
								$show_relink_form = 1;
							}
							else if ($menu_option_text == 1) {
								//when REGISTERING and email already exists
								$text_option = '<div id="relink_second" style="display:block; width:500px; text-align: justify; text-justify: inter-word; ">Welcome back, system detected that you already have an account which is associated with this email: <b>'.$reg_mail.'</b>. <div style="margin-top:5px;">Now you need to Synchronize & Re-Link your plugin with existing account. Please type your Email Address and User Key which is provided at <a style="text-decoration: none; margin-left:0px;" href="'.$lab_master_domain.'" target="_blank"><b>SegmentLab.com</b></a> (<i>My Account Settings -> Keys -> User</i>).</div></div>';
								$borderColor = "border-color:red";
								$show_relink_form = 1;
							}
							else {
								echo $error_wrong_notice;			
								$borderColor = "";
							}
							
							$selection_form_1 = '
							<form id="existing_user_id" method="post" style="display:block; margin-top: 10px; max-width:500px;" action="options.php" enctype="multipart/form-data">
								<div style="margin-top:15px; margin-bottom:15px; font-size:15px;"><b>Re-Link Existing Account</b></div>
								'.$text_option.'							
								<div style="margin-top:20px;">
									<div>
										<div style="width:135px; float:left;">
											<label for="existing_email_id">Email Address:</label>
										</div>
										<div>
											<input type="text" placeholder="example@example.com" id="existing_email_id" name="text1" value="'.$reg_mail.'" style="width:335px; '.$borderColor.'" />
										</div>
									</div>
									<div style="margin-top:10px;">
										<div style="width:135px; float:left;">
											<label for="existing_key_id">User Key:</label>
										</div>
										<div>   
											<input type="text" placeholder="" id="existing_key_id" name="text1" value="" style="width:335px;"   /> <br>
										</div>
									</div>
									 '.$bad_Val.'
									<p class="submit">
										<input name="submit" type="submit" class="button-primary" value="Re-Link Account"/>
									</p>
								</div>
							</form>';
							if ($show_relink_form == 1) {
								echo $selection_form_1;					
							}
						}
						else {
							echo '<div>
							<div style="width:500px; margin-top:15px;">
								<div style="color:red"><b>FAILED TO RE-LINK!</b></div> 
								<div style="margin-top:10px;">Too many Re-Link attempts, try again at the next day.</div>
								<div>
									<span style="margin-top:15px;">
									If something went wrong, please
									<a style="text-decoration: none;" target="_blank" href="'.$lab_directory_road.'?option=contact" class="linkform">
									<span style=""><b>Contact Us</b></span></a> at any time.</span>
								</div>
								<div style="margin-top:10px;">
									<span>Sincerely, <br>
									Segment<span style="color:#db5f1f;"><b>Lab</b></span> team.</span>
								</div>
							</div>
							';
							echo '<script>document.getElementById("segmentlab_settings_tab_id").style.borderLeftColor = "red";</script>';
						}					
					}
					else if ($menu_option_settings == 2) {
						//REGISTER
						$selection_form_2 = '
						<form id="new_user_id" method="post" style="display:block; margin-top: 25px; max-width:500px;" action="'.$lab_directory_road.'" target="_blank" enctype="multipart/form-data">
							<div style="margin-bottom:8px; font-size:15px;"><b>Registration</b></div>
							<div style="width:500px; text-align: justify; text-justify: inter-word; ">We are happy you decided to try our services, please register to get started and improve your sales.</div>
							<div style="margin-top:20px;">
								<div>
									<div style="width:135px; padding-top:5px; float:left;">
										<label for="new_email_id">Email Address:</label>
									</div>
									<div>
										<input type="text" placeholder="example@example.com" id="new_email_id" name="reg_email" value="" style="width:335px;"  />
										<input type="text" id="" name="option" 			value="signup" 						style="display:none;"  />
										<input type="text" id="" name="start" 			value="signup" 						style="display:none;"  />
										<input type="text" id="" name="castle_l" 		value="'.$_SERVER["HTTP_HOST"].'"  	style="display:none;"  />
										<input type="text" id="" name="castle_k" 		value="'.$uniqueCastleKey.'" 		style="display:none;"  />
										<input type="text" id="" name="login_email" 	value="'.$user_email_send.'" 		style="display:none;"  />
										<input type="text" id="" name="data_platform" 	value="'.$data_time_explode[0].'" 	style="display:none;"  />
										<input type="text" id="" name="time_platform" 	value="'.$data_time_explode[1].'" 	style="display:none;"  />	
									</div>												
								</div>
								<p class="submit">
									<input name="submit" type="submit" class="button-primary" value="Register Account"/>
								</p>
							</div>
						</form>';
						echo $selection_form_2;					
					}
					else if ($menu_option_settings == 3) {
						$selection_form_3 = '<form id="auth_user_id" method="post" style="display:block; margin-top: 25px; max-width:500px;" action="'.$lab_directory_road.'" target="_blank" enctype="multipart/form-data">
							<div style="margin-bottom:8px; font-size:15px;"><b>Almost done!</b></div>
							<div style="width:500px; text-align: justify; text-justify: inter-word; ">We’ll redirect you to SegmentLab.com to complete your registration. After you finish the registration, click the button bellow.</div>
							<div style="margin-top:20px;">
								<div>
									<div>									
									</div>												
								</div>
								<p class="submit">
									<input name="submit" type="submit" class="button-primary" value="Check Status"/>
								</p>
							</div>
						</form>';
						echo $selection_form_3;					
					}
					else if ($menu_option_settings == 4) {
						$uniqueRegEmail = get_option('sgmntLab_cfg_settings_reg_mail');
						$selection_form_3 = '<form id="auth_user_id" method="post" style="display:block; margin-top: 25px; max-width:500px;" action="'.$lab_directory_road.'" target="_blank" enctype="multipart/form-data">
							<div style="margin-bottom:8px; font-size:15px;"><b>Please verify your email address!</b></div>
							<div style="width:500px; text-align: justify; text-justify: inter-word; ">Information was sent, please check your email: <b>'.$uniqueRegEmail.'</b>. It contains instructions for veryfing your account. For security purposes, that message will expire in <b>2 hour</b>. Be sure to check your junk mail folders or spam filters if you do not see a message appear within a couple minutes.</div>
							<div style="margin-top:20px;">
								<div>
									<div>									
									</div>												
								</div>
								<div>
									<p class="submit">
										<input name="submit" type="submit" class="button-primary" value="Check Status"/>
									</p>
								<div>
							</div>
						</form>';
						echo $selection_form_3;
					}
					else {
						echo $error_wrong_notice;
					}

					?>
					<script>
						jQuery('#check_user_availability_id').click(function(e) {
							e.preventDefault();
							var ajaxurl = 		location.pathname + location.search;                    
							var sendSubmit = 	true;
							var sendData = 		"?a=a";

							if (sendSubmit) {
								document.getElementById("segmentlab_connect_plugin_help_tip_id").style.display = "none";
								document.getElementById("segmentlab_connect_plugin_id").style.display = "none";
								document.getElementById("segmentlab_time_loader_id").style.display = "block";

								jQuery('#check_user_availability_id').css("background-color","green");
								setTimeout(function(){
									jQuery('#check_user_availability_id').css("background-color","#0085ba");
								}, 300);
								sendData += "&save_segmentlab_data=" + 	"check";
								jQuery.ajax({
								   type: "POST",
								   url: ajaxurl,
								   data: sendData,
								   success: function(result) { //we got the response
									   //supratau sitas grazina reiksme iskart iraso,, ir poto perkauna ir jau uzkrauna ja is atminties.. o ne tiesiogiai
										//document.getElementById("testasDebug").innerHTML = result;
									   //jQuery('.vehicle-value-box').html(msg+",00€");
									   setTimeout(function() { 
											location.reload();
										}, 300);								   
									 },
									 error: function(jqxhr, status, exception) {
										 alert('Exception:', exception);
									 }
							   });
							}
						});
						jQuery('#existing_user_id input[type=submit]').click(function(e) {
							e.preventDefault();
							var ajaxurl = 		location.pathname + location.search;
							var email = 		document.getElementById("existing_email_id");
							var key = 			document.getElementById("existing_key_id");                        
							var sendSubmit = 	true;
							var sendData = 		"";
							if (validateEmail(email.value))  {
								email.style.borderColor = "";							
							}
							else {
								email.style.borderColor = 	"red";
								sendSubmit = 				false;
							}

							if (validateLength(key.value, 5))  {
								key.style.borderColor = "";							
							}
							else {
								key.style.borderColor = 	"red";
								sendSubmit = 				false;
							}
							if (sendSubmit) {
								document.getElementById("segmentlab_time_loader_id").style.display = "block";
								document.getElementById("existing_user_id").style.display = "none";

								jQuery('#existing_user_id input[type=submit]').css("background-color","green");
								setTimeout(function() {
									jQuery('#existing_user_id input[type=submit]').css("background-color","#0085ba");
								}, 300);

								sendData += "&save_segmentlab_data=" + 	"relink";
								sendData += "&user_email_address=" + 	email.value;
								sendData += "&user_secret_key=" + 		key.value;

								jQuery.ajax({
								   type: "POST",
								   url: ajaxurl,
								   data: sendData,
								   success: function(result) { //we got the response
										//supratau sitas grazina reiksme iskart iraso,, ir poto perkauna ir jau uzkrauna ja is atminties.. o ne tiesiogiai
										//document.getElementById("testasDebug").innerHTML = result;
										//jQuery('.vehicle-value-box').html(msg+",00€");

										setTimeout(function(){ 
											location.reload();
										}, 800);
									 },
									 error: function(jqxhr, status, exception) {
										 alert('Exception:', exception);
									 }
							   });
							}
						});
						jQuery('#new_user_id input[type=submit]').click(function(e) {
							var ajaxurl = 		location.pathname + location.search;
							var email = 		document.getElementById("new_email_id");
							var sendSubmit = 	true;
							var sendData = 		"";
							if (validateEmail(email.value))  {
								email.style.borderColor = "";							
							}
							else {
								email.style.borderColor = 	"red";
								sendSubmit = 				false;
								e.preventDefault();
							}
							if (sendSubmit) {
								document.getElementById("segmentlab_time_loader_id").style.display = 	"block";
								document.getElementById("new_user_id").style.display = 					"none";							
								sendData += "&save_segmentlab_data=" + 	"create";
								sendData += "&user_email_address=" + 	email.value;
								jQuery.ajax({
								   type: "POST",
								   url: ajaxurl,
								   data: sendData,
								   success: function(result) { //we got the response
										setTimeout(function(){ 
											location.reload();
										}, 200);
										//document.getElementById("testasDebug").innerHTML = result;
									 },
									 error: function(jqxhr, status, exception) {
										 alert('Exception:', exception);
									 }
							   });
							}
						});
						jQuery('#auth_user_id input[type=submit]').click(function(e) {
							e.preventDefault();
							var ajaxurl = 		location.pathname + location.search;
							var sendSubmit = true;
							var sendData = ";"
							if (sendSubmit) {
								document.getElementById("segmentlab_time_loader_id").style.display = "block";
								document.getElementById("auth_user_id").style.display = "none";
								sendData += "&save_segmentlab_data=" + 	"auth";
								jQuery.ajax({
								   type: "POST",
								   url: ajaxurl,
								   data: sendData,
								   success: function(result) { //we got the response
									   setTimeout(function(){ 
											location.reload();
										}, 500);
									   //document.getElementById("testasDebug").innerHTML = result;
									 },
									 error: function(jqxhr, status, exception) {
										 alert('Exception:', exception);
									 }
							   });
							}
						});
					</script>
					<div id="testasDebug"></div>
					<?
					$statut 				= 	sanitize_text_field($_REQUEST["save_segmentlab_data"]);
					$user_email_address 	= 	sanitize_text_field($_REQUEST["user_email_address"]);
					$user_secret_key 		= 	sanitize_text_field($_REQUEST["user_secret_key"]);			

					//SAVE_DATA_BY_ACTION
					if ($statut == "relink") {
						$args = array (
							'option' => 	"relink",
							'castle_l' => 	$_SERVER["HTTP_HOST"],
							'castle_e' => 	$user_email_address,
							'castle_k' => 	$uniqueCastleKey,
							'user_key' => 	$user_secret_key,
							'sub' => 		"rel"
						);

						$receiveData =  smgnt_lab_data_manager ($lab_directory_road, true, $args);					
						$resp = 		$receiveData["response"];

						if ($resp == "good") {
							$relink_status = 			sanitize_text_field($receiveData["status"]);
							$relink_lab_key = 			sanitize_text_field($receiveData["lab_key"]);
							$relink_management_status = sanitize_text_field($receiveData["management_status"]);
							$cashier = 					sanitize_text_field($receiveData["cashier"]);
							$correction = 				sanitize_text_field($receiveData["correction"]);
							$relink_management_array = 	$receiveData["management"];//RAW_SANITIZE_LATER				

							if ($cashier == 1) {
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_cashier_level', 1 );
							}
							else {
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_cashier_level', 0 );
							}

							if ($relink_status == "added_domain") {
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_lab', 			$relink_lab_key);
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 		'connected');
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_testing_ids', 	'');

								update_option( 'sgmntLab_cfg_plugin_status', 	'active' );
								update_option( 'sgmntLab_cfg_sell', 			'1' );
								update_option( 'sgmntLab_cfg_reg', 				'1' );
								update_option( 'sgmntLab_cfg_testing', 			'1' );
								update_option( 'sgmntLab_cfg_connection', 		'1' );
								update_option( 'sgmntLab_cfg_admin_test', 		'1' );
								update_option( 'sgmntLab_cfg_correction', 		'0' );
								update_option( 'sgmntLab_cfg_pause', 			'0' );
							}
							else if ($relink_status == "good") {							
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_lab', 			$relink_lab_key);
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 		'connected');
								update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_testing_ids', 	'');						

								update_option( 'sgmntLab_cfg_plugin_status', 	'active' );
								update_option( 'sgmntLab_cfg_sell', 			'1' );
								update_option( 'sgmntLab_cfg_reg', 				'1' );
								update_option( 'sgmntLab_cfg_testing', 			'1' );
								update_option( 'sgmntLab_cfg_connection', 		'1' );
								update_option( 'sgmntLab_cfg_admin_test', 		'1' );
								update_option( 'sgmntLab_cfg_pause', 			'0' );

								if (sgmnt_lab_check_var($correction) && ($correction == "0" || $correction == "1")) {
									update_option('sgmntLab_cfg_correction', $correction);
								}

								if ($relink_management_status == "good") {
									$idsTestingList = "";
									$relink_management_array_count = count($relink_management_array);
									if (count($relink_management_array) >= 1) {
										foreach ($relink_management_array as $relink_management_single) {
											$relink_management_idq = 		$relink_management_single["idq"];
											$relink_management_product = 	$relink_management_single["product"];
											$relink_management_management = $relink_management_single["testing"];

											$clean_product_id = str_replace("aedrew32","",$relink_management_product);
											$relink_management_management_first = explode(",", $relink_management_management);
											$relink_management_management_first = $relink_management_management_first[0];
											$sale_price_go = 	get_post_meta( $clean_product_id, '_sale_price', true );
											$regular_price_go = get_post_meta( $clean_product_id, '_regular_price', true );

											if ($clean_product_id == "SALE_PRICE_ENDING") {
												$galuneles = $relink_management_management;
												$changedData = 	str_replace(',', ';', $galuneles);
												if (sgmnt_lab_check_var($galuneles)) {
													update_option('sgmntLab_cfg_price_endings', sanitize_text_field($changedData));										
												}										
											}
											else {
												$idsTestingList .= $clean_product_id.";";										
											}

											if (sgmnt_lab_check_var($sale_price_go)) {
												$actionas = 1;
											}
											else if (sgmnt_lab_check_var($regular_price_go)) { 
												$actionas = 1;
											}
											else {
												$actionas = 0;										
											}
											if ($actionas == 1 && ($relink_management_management_first == $sale_price_go || $relink_management_management_first == $regular_price_go)) {
												$relink_management_management_cleaned = trim($relink_management_management, ",");
												update_post_meta($clean_product_id, 'sgmntLab_testing_prices_'.$clean_product_id.'_idas', 	sanitize_text_field($relink_management_idq));
												update_post_meta($clean_product_id, 'sgmntLab_testing_prices_'.$clean_product_id.'_code', 	sanitize_text_field($relink_management_management_first));
												update_post_meta($clean_product_id, 'sgmntLab_testing_prices_'.$clean_product_id.'_a', 		sanitize_text_field($relink_management_management_cleaned));
											}
										}
										update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_testing_ids', sanitize_text_field($idsTestingList)); //testing List update;
									}
								}
							}
							else if ($relink_status == "verify") {
								update_option('sgmntLab_cfg_settings_menu', '4');	//ner tokio varianto reliai, nes nematys key neprisijunges..						
							}
							else {
								echo "Something went wrong, email address or API key is wrong!"; //padaryt kazkoki dar notice, kad veiktu!
							}
							update_option('sgmntLab_cfg_relink', '0');	
							delete_option('sgmntLab_cfg_relink_date');
						}
						else {
							$relink_setting = get_option('sgmntLab_cfg_relink');
							if (sgmnt_lab_check_var($relink_setting)) {
								update_option('sgmntLab_cfg_relink', $relink_setting + 1);
								if (($relink_setting + 1) == 5) {
									$today = 		date('Y-m-d H:i:s');
									$stop_date = 	date('Y-m-d H:i:s', strtotime($today . ' +1 day'));
									update_option('sgmntLab_cfg_relink_date', $stop_date);								
								}
							}
							else {
								update_option('sgmntLab_cfg_relink', 1); //0+1 the first
							}
						}
					}
					else if ($statut == "create") {
						update_option('sgmntLab_cfg_settings_reg_mail', $user_email_address);									
						$args = array (
							'option' => 				"tmpacc",
							'account_category' => 		"plugin",
							'domain' => 				$_SERVER['HTTP_HOST'],
							'data_platform' => 			$data_time_explode[0],
							'time_platform' => 			$data_time_explode[1],
							'regemail' => 				$user_email_address,
							'castle_k' => 				$uniqueCastleKey,
							'lab_k' => 					$uniqueLabKey
						);

						$receiveData = 		smgnt_lab_data_manager ($lab_directory_road, true, $args);					
						$receiveStatus = 	sanitize_text_field($receiveData["lab_status"]);
						$unique_lab_code = 	sanitize_text_field($receiveData["lab_key"]);

						if ($receiveStatus == 2) {
							//completed registration
							update_option('sgmntLab_cfg_settings_menu', '3');						
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_lab', 			$unique_lab_code	);
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 		'notconnected'		);
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_testing_ids', ''						);
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_cashier_level', 	1 						);
						}
						else if ($receiveStatus == 1) {
							//domain already registered by other email		
							update_option('sgmntLab_cfg_settings_menu', '1');
							update_option('sgmntLab_cfg_settings_text', '0');
						}
						else if ($receiveStatus == 3) {
							//this email already registered, need to add domain
							update_option('sgmntLab_cfg_settings_menu', '1');
							update_option('sgmntLab_cfg_settings_text', '1');
						}
						else if ($receiveStatus == 4) {
							//temnporary user and domain found, go auth again
							update_option('sgmntLab_cfg_settings_menu', 	'3');
							update_option('sgmntLab_cfg_settings_text', 	'0');

							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_lab', 			$unique_lab_code	);
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 		'notconnected'		);
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_testing_ids', 	''					);
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_cashier_level', 		1 					);
						}
						else if ($receiveStatus == 5) {
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 'connected');
							update_option('sgmntLab_cfg_connection', 	'1');
							update_option('sgmntLab_cfg_plugin_status', 'active');

							update_option( 'sgmntLab_cfg_sell', 			'1' );
							update_option( 'sgmntLab_cfg_reg', 				'1' );
							update_option( 'sgmntLab_cfg_testing', 			'1' );
							update_option( 'sgmntLab_cfg_correction', 		'0' );
							update_option( 'sgmntLab_cfg_admin_test', 		'1' );
							update_option( 'sgmntLab_cfg_pause', 			'0' );

							update_option('sgmntLab_cfg_settings_menu', '1');
							update_option('sgmntLab_cfg_settings_text', '0');
						}
						else if ($receiveStatus == 6) {
							update_option('sgmntLab_cfg_settings_menu', '4');
						}
						else {
							//something went wrong
							update_option('sgmntLab_cfg_settings_menu', '7');
						}
					}
					else if ($statut == "auth") {
						$args = array (
							'option' => 			"connect",
							'account_category' => 	"plugin",
							'castle_l' => 			$_SERVER['HTTP_HOST'],
							'castle_k' => 			$uniqueCastleKey,
							'lab_k' => 				$uniqueLabKey
						);

						$receiveData = 		smgnt_lab_data_manager($lab_directory_road, true, $args);					
						$receiveStatus = 	sanitize_text_field($receiveData["status"]);

						if ($receiveStatus == 1) {	
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 'connected');
							update_option('sgmntLab_cfg_connection', 		'1');
							update_option('sgmntLab_cfg_plugin_status', 	'active');
							update_option( 'sgmntLab_cfg_sell', 			'1' );
							update_option( 'sgmntLab_cfg_reg', 				'1' );
							update_option( 'sgmntLab_cfg_testing', 			'1' );
							update_option( 'sgmntLab_cfg_correction', 		'0' );
							update_option( 'sgmntLab_cfg_admin_test', 		'1' );
							update_option( 'sgmntLab_cfg_pause', 			'0' );
							update_option('sgmntLab_cfg_settings_menu', '1');
							update_option('sgmntLab_cfg_settings_text', '0');
						}
						else if ($receiveStatus == 2) {
							if (get_option('sgmntLab_cfg_settings_menu') == 4) {
								update_option('sgmntLab_cfg_settings_menu', '0');
								update_option('sgmntLab_cfg_settings_text', '0');
							}
							else {
								update_option('sgmntLab_cfg_settings_menu', '4');
							}
						}
						else {
							update_option('sgmntLab_cfg_settings_menu', '0');
							update_option('sgmntLab_cfg_settings_text', '0');
						}					
					}
					else if ($statut == "check") {
						$args = array (
							'option' => 			"check",
							'castle_l' => 			$_SERVER['HTTP_HOST'],
							'castle_k' => 			$uniqueCastleKey,
							'lab_k' => 				$uniqueLabKey
						);

						$receiveData = 		smgnt_lab_data_manager($lab_directory_road, true, $args);					
						$receiveStatus = 	sanitize_text_field($receiveData["status"]);

						if ($receiveStatus == 0) {
							//nerado domeno, ijungia registracija.
							update_option('sgmntLab_cfg_settings_menu', '2');
						}
						else if ($receiveStatus == 1) {
							//rado domena, ijungia langa, kad reik Re-Link
							update_option('sgmntLab_cfg_settings_menu', '1');
						}
						else if ($receiveStatus == 2) {
							//rado, kad jau uzsiregistraves, ir, kad atitinka visi KEY
							update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_status', 'connected');
							update_option('sgmntLab_cfg_connection', 	'1');
							update_option('sgmntLab_cfg_plugin_status', 'active');
							update_option( 'sgmntLab_cfg_sell', 		'1' );
							update_option( 'sgmntLab_cfg_reg', 			'1' );
							update_option( 'sgmntLab_cfg_testing', 		'1' );
							update_option( 'sgmntLab_cfg_correction', 	'0' );
							update_option( 'sgmntLab_cfg_admin_test', 	'1' );
							update_option( 'sgmntLab_cfg_pause', 		'0' );
							update_option('sgmntLab_cfg_settings_menu', 1	);
							update_option('sgmntLab_cfg_settings_text', 0	);
						}
						else if ($receiveStatus == 3) {
							//rado domena, ijungia langa, kad reik Re-Link
							update_option('sgmntLab_cfg_settings_menu', '4');
						}
						else {
							//something went wrong, ant connect to the server. Please try again later or contact us! ir duoti linka i forma!
							update_option('sgmntLab_cfg_settings_menu', '7');
						}
					}
				}
				else {
					echo $top_logo_display;
					echo $header_text;
					echo $error_wrong_notice;
				}
			}
			else {
				echo $top_logo_display;
				//echo '<div>SegmentLab Extension is enabled but not effective. It requires <a href="%s">WooCommerce</a> in order to work.</div>';
				echo '<div style="margin-bottom:30px;">';
					printf(__('We appreciate you choosing us, and we\'ll do our best to continue to give you the kind of service you deserve. However, SegmentLab Extension is enabled but not effective. It requires <a href="%s">WooCommerce</a> in order to work.', 'segment-lab-extension'), 'http://www.woothemes.com/woocommerce/');
				echo '</div>';
				$reg_library_stat = sgmnt_lab_check_var(get_option('sgmntLab_cfg_library'), 		2);				
				?>
				<div style="margin-top:20px; margin-bottom:15px;">
					<div style="width:210px; float: left;">
						<div style="float:left; margin-top:3px;" class="texthover_help_tip">
							<label class="sgmnt_dots" style="-webkit-transition: background-color 150ms linear, color 1s linear; -moz-transition: background-color 150ms linear, color 1s linear; -o-transition: background-color 150ms linear, color 1s linear; -ms-transition: background-color 150ms linear, color 1s linear; transition: background-color 150ms linear, color 1s linear; cursor:help !important;" >Tracking - Library</label>
							<div style="" class="overlay_help_tip">
								<span style="color:white; text-align: justify;">
									 SegmenyLab Library is required for Setup and test unlimited variations of your website and Track exactly how each variation performed . Please register at <b>SegmentLab.com</b> to get started and improve your sales. 
								</span>
							</div>                        
						</div>
					</div>
					<div style="float: left;">   								
						<li class="tg-list-item">
							<input class="tgl tgl-light" id="checkbox_example9" name="test2" type="checkbox" <? echo $reg_library_stat; ?>/>
							<label id="label_checkbox_example9" class="tgl-btn" for="checkbox_example9"></label>
						</li>
					</div>
					<div style="clear: both;"></div>
				</div>
				<?
			}
								
			$statut = 				sanitize_text_field($_REQUEST["save_segmentlab_data"]);
			$testing_prices = 		sanitize_text_field($_REQUEST["testing_prices_data"]);
			$testing_correction = 	sanitize_text_field($_REQUEST["testing_correction_data"]);
			$testing_reg = 			sanitize_text_field($_REQUEST["testing_reg_data"]);
			$testing_sell = 		sanitize_text_field($_REQUEST["testing_sell_data"]);
			$price_ending = 		sanitize_text_field($_REQUEST["ending_price"]);
			$admin_testing = 		sanitize_text_field($_REQUEST["admin_testing_data"]);

			if ($statut == "save") {
				if (sgmnt_lab_check_var($price_ending)) {						
					update_option('sgmntLab_cfg_price_endings', $price_ending);
					$args = array (
						'option' => 			"ending",
						'castle_key' => 		$uniqueCastleKey,
						'status_pause' => 		$testing_correction,
						'snippet_code_name' => 	urlencode($product_title)
					);

					$product_testing_prices = str_replace( $decimal_sep,'.', $price_ending );
					$product_testing_prices = str_replace( ';',',', $product_testing_prices );
					$explode_testing_prices = explode(",", $product_testing_prices); // pakeista decimal is , i decimal_sep, 2016.oct.07
					$explode_testing_prices = array_filter($explode_testing_prices);
					array_unshift($explode_testing_prices,0);

					$explode_testing_prices_lenght = count($explode_testing_prices);
					$iii = 1;
					for ($ii=0; $ii < $explode_testing_prices_lenght; $ii++) {				
						$po_viena = $explode_testing_prices[$ii];
						$args["snippet_code_title_".$iii] = $po_viena;
						$args["snippet_code_code_".$iii] = $po_viena;						
						$iii = $iii + 1;
						if ($ii>10) {break;}
					}
					if ($explode_testing_prices_lenght > 1) {
						$receiveData = 	smgnt_lab_data_manager ($lab_directory_road, true, $args);					
						$zone_id = 		sanitize_text_field($receiveData["zone_id"]);
						$zone_status = 	sanitize_text_field($receiveData["status"]);
						update_option('sgmntLab_cfg_price_endings_idas', $zone_id);
					}
					////////////////////////////////////////////////////////////////////
					/////////////////////////////////////////////////////////
					//////////////////////////////////////////////////
					//////////////////////
				}
				else {/*error!*/}
			}
			else if ($statut == "settings") {
				$set_option = 	sanitize_text_field($_REQUEST["set"]);
				$set_val = 		sanitize_text_field($_REQUEST["val"]);
				if ($set_option == "extensionpause") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_pause', 		'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_pause', 		'1' );						
					}
				}	
				else if ($set_option == "extensionadmin") {
					if ($set_val == 0) {
						setcookie("lab_admin_testing", 0, time() + (86400 * 30), "/");					
						update_option( 'sgmntLab_cfg_admin_test', 		'0' );						
					}
					else if ($set_val == 1) {
						setcookie("lab_admin_testing", 1, time() + (86400 * 30), "/");					
						update_option( 'sgmntLab_cfg_admin_test', 		'1' );						
					}
				}	
				else if ($set_option == "extensionsell") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_sell', 		'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_sell', 		'1' );						
					}
				}	
				else if ($set_option == "extensionregistration") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_reg', 		'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_reg', 		'1' );						
					}
				}	
				else if ($set_option == "extensionpricetest") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_testing', 		'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_testing', 		'1' );						
					}
				}
				else if ($set_option == "extensionlibrary") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_library', 		'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_library', 		'1' );						
					}
				}
				else if ($set_option == "extensionendings") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_correction', 		'0' );
						update_option( 'sgmntLab_cfg_endingstable', 	'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_correction', 		'1' );
						update_option( 'sgmntLab_cfg_endingstable', 	'1' );						
					}

					//curlas
					$args = array (
						'option' => 			"stopmanagement",
						'castle_l' => 			$_SERVER['HTTP_HOST'],
						'castle_k' => 			$uniqueCastleKey,
						'correction_status' => 	$set_val
					);

					$receiveData = 	smgnt_lab_data_manager ($lab_directory_road, true, $args);
				}
				else if ($set_option == "extensionendingstable") {
					if ($set_val == 0) {
						update_option( 'sgmntLab_cfg_endingstable', 		'0' );						
					}
					else if ($set_val == 1) {
						update_option( 'sgmntLab_cfg_endingstable', 		'1' );						
					}
				}						
			}			
			
			$settings_footer_display = "<br><span style=\"font-size: 12px;\">For more information reach as at<a style=\"text-decoration: none; margin-left:5px;\" href='".$lab_master_domain."' target='_blank'><b>SegmentLab.com</b></a></span><br></div>";
			echo $settings_footer_display;			
		} 							//SEGMENTLAB SETTINGS PAGE		
		function sgmnt_lab_plugin_activate () {
			global $current_user;
			global $lab_directory_road;
			$user_login_name_send_id = 		$current_user->id;
			if (get_option('sgmntLab_cfg_plugin_status') != 'active' && !is_plugin_active('/segment-lab-extension/segment-lab-extension.php')) {
				update_option('sgmntLab_cfg_plugin_status', 'active');
				
				echo "<div class='update-nag'><p><b>Congratulations</b>, You are almost ready to use SegmentLab Extension plugin. Now you need to connect your plugin to SegmentLab platform by going to the <b><a href=". admin_url( 'admin.php?page=segmentlab-settings-page' ).">SegmentLab->Settings</a></b> and clicking on <b>“Connect Plugin”</b> button.</p></div>";
				
				$generatedUnique1 = 		sgmnt_lab_get_rand_alphanumeric(22);
				$generatedUnique2 = 		sgmnt_lab_get_rand_alphanumeric(5);
				$generatedUnique = 			$generatedUnique1.$generatedUnique2;					
				update_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_castle', $generatedUnique);
				
				$user_login_name_send_id = 		$current_user->id;
				$uniqueCastleKey = 	get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_castle', true );

				//curlas
				$args = array (
					'option' => 			"pluginmanagement",
					'castle_l' => 			$_SERVER['HTTP_HOST'],
					'castle_k' => 			$uniqueCastleKey,
					'plugin_s' => 			"activated"
				);

				$receiveData = 	smgnt_lab_data_manager ($lab_directory_road, true, $args);		
				update_option('sgmntLab_cfg_settings_menu', '0');
				update_option('sgmntLab_cfg_settings_text', '0');
			}
		}
		function sgmnt_lab_plugin_deactivate () {
			global $current_user;						
			global $lab_directory_road;
			$user_login_name_send_id = 	$current_user->id;
			$uniqueCastleKey = 			get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_castle', true );

			//curlas
			$args = array (
				'option' => 			"pluginmanagement",
				'castle_l' => 			$_SERVER['HTTP_HOST'],
				'castle_k' => 			$uniqueCastleKey,
				'plugin_s' => 			"deactivated"
			);
			
			$receiveData = 	smgnt_lab_data_manager ($lab_directory_road, true, $args);					
			//$atsakymas = 	$receiveData["status"];
		}
		function sgmnt_lab_link_to_results ($setting, $buttonName, $fieldName, $fieldValue, $buttonType) {
			global $lab_directory_road;
			if ($setting == "loadData") {
				$segmentLabData = sgmnt_lab_settings_data();
				if ($segmentLabData["active"]) {
					$uniqueCastleKey = 			$segmentLabData["castle_key"];
					$uniqueLabKey = 			$segmentLabData["lab_key"];					
					$uniqueRegEmail = 			get_option('sgmntLab_cfg_settings_reg_mail');
					$results_form_link = '<form id="segmentlab_results_'.$buttonName.'_id" method="post" style="display:block; margin-top: 10px; max-width:500px;" action="'.$lab_directory_road.'" target="_blank" enctype="multipart/form-data">
						<div style="display:none;">
							<div>
								<div>
									<input type="text" name="option" 		value="result" 						style="display:none;"  />
									<input type="text" name="start" 		value="results" 					style="display:none;"  />
									<input type="text" name="castle_l" 		value="'.$_SERVER["HTTP_HOST"].'"  	style="display:none;"  />
									<input type="text" name="castle_k" 		value="'.$uniqueCastleKey.'" 		style="display:none;"  />
									<input type="text" name="lab_k" 		value="'.$uniqueLabKey.'" 			style="display:none;"  />
									<input type="text" name="product_id" 	value="" 							style="display:none;" class="segmentlab_product_id"/>
									<input type="text" name="email" 		value="'.$uniqueRegEmail.'" 	style="display:none;"  />	
								</div>
							</div>
							<input id="segmentlab_results_'.$buttonName.'_button_id" name="submit" type="submit" class="button-secondary" value="Results"/>
						</div>
					</form>';
					return $results_form_link;
				}
				else {
					return "";
				}							
			}
			else if ($setting == "setButton") {
				if (isset($buttonName)) {					
					if ($buttonType == 1) {
						$resultsButton = '
						<input id="segmentlab_'.$buttonName.'_edite_button_show_id" name="submit" type="button" class="button-secondary" value="Results"/>
						<script>
							jQuery("input[type=button]#segmentlab_'.$buttonName.'_edite_button_show_id").click(function(e) {
								e.preventDefault();
								jQuery("#segmentlab_'.$buttonName.'_edite_button_show_id").css("background-color","green");
								setTimeout(function(){
									jQuery("#segmentlab_'.$buttonName.'_edite_button_show_id").css("background-color","");
								}, 300);
								jQuery("input[type=submit]#segmentlab_results_'.$buttonName.'_button_id").click();
							})
						</script>';
					}
					else if ($buttonType == 2) {
						$resultsButton = '
						<a id="segmentlab_'.$buttonName.'_edite_button_show_id" name="submit" style="cursor:pointer;" value="Results"/>Results</a><script>jQuery("a#segmentlab_'.$buttonName.'_edite_button_show_id").click(function(e) {jQuery("input[type=submit]#segmentlab_results_'.$buttonName.'_button_id").click();})</script>';						
					}
					else {
						$resultsButton = '';
					}					
					return $resultsButton;
				}
				else {
					return "";
				}
			}
			else if ($setting == "setData") {
				$loadScript = "
				<script>
					document.getElementById('segmentlab_results_".$buttonName."_id').getElementsByClassName('".$fieldName."')[0].value = '".$fieldValue."' ;
				</script>";
				return $loadScript;
			}
			else {
				return false;
			}
		}
		function sgmnt_lab_quick_menu_results_button () {
			echo sgmnt_lab_link_to_results ("loadData","masterButton");
		}
		function sgmnt_lab_variation_testing_menu () {
			global $thepostid;
			global $woocommerce;
			$segmentLabData = sgmnt_lab_settings_data();
			if ($segmentLabData["active"]) {
				$decimal_sep = 				wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
				$prices_sep = ";"; //skyrimas
				echo sgmnt_lab_link_to_results ("setData","masterButton", "segmentlab_product_id", $thepostid);
				$site = sgmnt_lab_link_to_results ("setButton","masterButton","","",2);	
				
				$just_text = 'You can make changes, see stats and more right here - <u><i>'.$site.'</i></u>';
				$textas = '<p>Enter prices for further testing. Example: 45'.$prices_sep.'47'.$prices_sep.'50 <br>(Max: 10, Sale price < Regular Price)'.$site;					

				$meta_id_1 = get_mid_by_key( $thepostid, 'sgmntLab_testing_prices_'.$thepostid.'_a' );
				$meta_id_2 = get_mid_by_key( $thepostid, 'sgmntLab_testing_prices_'.$thepostid.'_code' );
				$meta_id_3 = get_mid_by_key( $thepostid, 'sgmntLab_testing_prices_'.$thepostid.'_idas' );
				
				sgmnt_lab_input_price_check("_regular_price", "segmentlab_testing_prices_".$thepostid."_id");
				sgmnt_lab_input_price_check("_sale_price", "segmentlab_testing_prices_".$thepostid."_id");
				sgmnt_lab_input_testing_price_check ("segmentlab_testing_prices_".$thepostid."_id");
				sgmnt_lab_help_tip_message();

				echo '
				<script>
					var new_id_for_remove_1 = "#meta-"+"'.$meta_id_1.'";
					var new_id_for_remove_2 = "#meta-"+"'.$meta_id_2.'";
					var new_id_for_remove_3 = "#meta-"+"'.$meta_id_3.'";

					jQuery( function ( $ ) {
						$(new_id_for_remove_1).remove();
						$(new_id_for_remove_2).remove();
						$(new_id_for_remove_3).remove();
					});
				</script>';

				$descriptionas = "Enter prices for further SegmentLab testing.<br>Example: 45".$prices_sep."47".$prices_sep."50 (Max: 10, Sale price < Regular Price)";

				$kainos_is_db = 	get_post_meta( $thepostid, 'sgmntLab_testing_prices_'.$thepostid.'_a', true );
				$sutvarkyta_kaina = str_replace( ',',';', $kainos_is_db );
					
				$sutvarkyta_kaina = str_replace( '.',$decimal_sep, $sutvarkyta_kaina );
				$sale_price_go = 	get_post_meta( $thepostid, '_sale_price', true );
				$regular_price_go = get_post_meta( $thepostid, '_regular_price', true );
			
				if (sgmnt_lab_check_var($sale_price_go)) {
					$kaina = $sale_price_go;
				}
				else if (sgmnt_lab_check_var($regular_price_go)) { 
					$kaina = $regular_price_go;
				}
				else {
					$kaina = 0;										
				}
				if (!isset($sutvarkyta_kaina) || $sutvarkyta_kaina == "" || $sutvarkyta_kaina == " " || $sutvarkyta_kaina == NULL) {			
					$sutvarkyta_kaina = $kaina;
				}

				woocommerce_wp_text_input ( 			
					array( 
						'id'          => 'segmentlab_testing_prices_'.$thepostid.'_id', 
						'label'       => __( 'Testing Prices (' . get_woocommerce_currency_symbol() . ')', 'woocommerce' ), 
						'desc_tip'    => 'true',
						'description' => __( $descriptionas, 'woocommerce' ),
						'placeholder' => 'xx,xx,xx',
						'value'       => $sutvarkyta_kaina,
						'custom_attributes' => array('step' 	=> 'any', 'min'	=> '0') 
					)
				);
				echo "<p style='margin-bottom: 7px;'>$just_text</p>";
			}
			else {
				//not show the testing menu
			}
		}//sgmnt_lab_variation_testing_menu
		function sgmnt_lab_variation_testing_castle_save ($post_id, $post = '') {
			global $current_user;
			$user_login_name_send_id = 	$current_user->id;
			$product_information = 		get_product( $post_id );
			$product_title = 			$product_information->post->post_title;
			
			$post_segmentlab_testing_prices_by_id = sanitize_text_field($_POST[ 'segmentlab_testing_prices_'.$post_id.'_id' ]);
			if (sgmnt_lab_check_var($post_segmentlab_testing_prices_by_id)) {
				$pries_irasant_i_db = 				$post_segmentlab_testing_prices_by_id;
				$decimal_sep = 						wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
				$pries_irasant_i_db_sutvarkyta = 	str_replace( $decimal_sep,'.', $pries_irasant_i_db );
				$pries_irasant_i_db_sutvarkyta = 	str_replace( ';',',', $pries_irasant_i_db_sutvarkyta );
				
				update_post_meta($post_id, 'sgmntLab_testing_prices_'.$post_id.'_a', stripslashes($pries_irasant_i_db_sutvarkyta));
				sgmnt_lab_variation_testing_lab_save ($post_id, $post_segmentlab_testing_prices_by_id, $product_title); //creating ACC

				$get_testing_products = get_user_meta( $user_login_name_send_id, 'sgmntLab_user_key_testing_ids', true);
				if (sgmnt_lab_check_var($get_testing_products)) {
					$get_testing_products = 		$get_testing_products.$post_id.";";
					$explode_products_array = 		explode(";", $get_testing_products);				
					$leave_unique = 				array_unique($explode_products_array);
					$create_new_products_list = 	implode(";", $leave_unique);
					update_user_meta($user_login_name_send_id, 'sgmntLab_user_key_testing_ids', $create_new_products_list);
				}
				else {
					update_user_meta($user_login_name_send_id, 'sgmntLab_user_key_testing_ids', $post_id.";");
				}
			}
		}//CALL SAVE-PRICES,SNIPPETS			
		function sgmnt_lab_variation_testing_lab_save ($product_id, $product_testing_prices, $product_title) {
			global $current_user;
			global $woocommerce;			
			global $lab_directory_road;
			
			$segmentLabData = sgmnt_lab_settings_data();
			if ($segmentLabData["active"]) {
				$uniqueCastleKey = 			$segmentLabData["castle_key"];
				
				$args = array (
					'option' => 			"setup",
					'domain' => 			$_SERVER['HTTP_HOST'],
					'prdid' => 				$product_id,
					'snippet_code_name' => 	$product_title,
					'castle_key' => 		$uniqueCastleKey
				);

				$decimal_sep = 						wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
				$product_testing_prices = 			str_replace( $decimal_sep,'.', $product_testing_prices );
				$product_testing_prices = 			str_replace( ';',',', $product_testing_prices );
				$explode_testing_prices = 			explode(",", $product_testing_prices); // pakeista decimal is , i decimal_sep, 2016.oct.07
				$explode_testing_prices_lenght = 	count($explode_testing_prices);
				
				$iii = 1;
				$counteris_stop = 0;
				for ($ii=0; $ii < $explode_testing_prices_lenght; $ii++) {				
					$po_viena = $explode_testing_prices[$ii];
					$args["snippet_code_title_".$iii] = $po_viena;
					$args["snippet_code_code_".$iii] = $po_viena;					
					$iii = $iii + 1;
					$counteris_stop = $counteris_stop + 1;
					if ($ii>10) {break;}
				}

				if ($counteris_stop > 1) {
					$receiveData = 		smgnt_lab_data_manager ($lab_directory_road, true, $args);					
					$zone_id = 			sanitize_text_field($receiveData["zone_id"]);
					$zone_status = 		sanitize_text_field($receiveData["status"]);
					$cashier = 			sanitize_text_field($receiveData["cashier"]);

					if ($cashier == 1 || $cashier == "1") {
						//points good
						update_user_meta( $user_login_name_send_id, 'sgmntLab_user_cashier_level', 1 );
					}
					else {
						//points bad
						update_user_meta( $user_login_name_send_id, 'sgmntLab_user_cashier_level', 0 );
					}

					$sale_price_go = 		get_post_meta( $product_id, '_sale_price', true );
					$regular_price_go = 	get_post_meta( $product_id, '_regular_price', true );
					if (isset($sale_price_go) && $sale_price_go !== "" && $sale_price_go !== NULL) {
						$kaina = $sale_price_go;
					}
					else if (isset($regular_price_go) && $regular_price_go !== "" && $regular_price_go !== NULL) { 
						$kaina = $regular_price_go;
					}
					else {
						$kaina = $explode_testing_prices[0];										
					}
					$atsakymas = preg_replace('/\s+/', '', $atsakymas);
					$sale_price = $kaina;

					if ($zone_status !== "EXIT" && $zone_status !== "EXIST") {
						$create_testing_code = $sale_price;
						update_post_meta( $product_id, 'sgmntLab_testing_prices_'.$product_id.'_code', $create_testing_code );
						update_post_meta( $product_id, 'sgmntLab_testing_prices_'.$product_id.'_idas', $zone_id );	
					}
					else {
						$get_idas = get_post_meta($product_id, 'sgmntLab_testing_prices_'.$product_id.'_idas', true);
						if (!isset($get_idas) || $get_idas == "" || $get_idas == " " || $get_idas == NULL) {
							update_post_meta( $product_id, 'sgmntLab_testing_prices_'.$product_id.'_idas', $zone_id );	
						}
					}
				}
			}
			else {
				//not save
			}
		} 	//UPDADE SNIPPET		
		function sgmnt_lab_quick_testing_menu($column,$post_id){
			global $woocommerce;
			global $current_user;
			global $lab_directory_road;
			
			echo sgmnt_lab_link_to_results ("setData","masterButton", "segmentlab_product_id", $post_id);
			$textas = 			sgmnt_lab_link_to_results ("setButton","masterButton","","",2);			
			$decimal_sep = 		wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
			$kainos_is_db = 	get_post_meta( $post_id, 'sgmntLab_testing_prices_'.$post_id.'_a', true );
			
			$sutvarkyta_kaina = str_replace( ',',';', $kainos_is_db );
			$sutvarkyta_kaina = str_replace( '.',$decimal_sep, $sutvarkyta_kaina );
		
			switch ( $column ) {
				case 'name' :
					?>
					<div class="hidden testing_prices_inline" id="testing_prices_inline_<?php echo $post_id; ?>">
						<div id="segmentlab_testing_prices_id"><?php echo $sutvarkyta_kaina; ?></div>
					</div>
					
					<div class="hidden segmentlab_url_inline" id="segmentlab_url_inline_<?php echo $post_id; ?>">
						<div id="segmentlab_segmentlab_url_id"><?php echo $textas; ?></div>
					</div>
					<?php
					break;
				default :
					break;
			}
		}//HIDDEN_DATA_VARIABLES_FUNCTION
		function sgmnt_lab_quick_testing_variables ($product) {
			global $woocommerce;
			global $current_user;
			?>
			<style>
				.left {
					overflow: hidden;
					width: inherit;
					float: left;            
					/*border: 2px dashed #f0f*/
				}
				.right {
					float: right;
					width: 80px;
					/*min-height: 50px;*/
					margin-left: 5px;
					/*border: 2px dashed #00f*/
				}
			</style>             
            <script>
				jQuery( function ( $ ) {
					$('#the-list').on('click', '.editinline', function(){
						// Extract metadata and put it as the value for the custom field form
						inlineEditPost.revert();
						var post_id = $(this).closest('tr').attr('id');
						post_id = post_id.replace("post-", "");
						var $cfd_inline_data = $('#testing_prices_inline_' + post_id),
							$url_inline_data = $('#segmentlab_url_inline_' + post_id),
							$wc_inline_data = $('#woocommerce_inline_' + post_id );
						$('input[name="segmentlab_testing_prices_id"]', '.inline-edit-row').val($cfd_inline_data.find("#segmentlab_testing_prices_id").text());	
						$('div[name="_segmentlab_url_id"]', '.inline-edit-row').html($url_inline_data.find("#_segmentlab_url_id").html());
					});
					
					var testing_prices = "testas";
					var segmentlab_url = "url";
					var testing_content_fields = '';
					testing_content_fields += '<label>';
					testing_content_fields += '<span class="title">Testing</span>';
					testing_content_fields += '<span class="input-text-wrap">';
					testing_content_fields += '<div style="width:100%; display:flex;"><div class="left"><input type="text" style="" name="segmentlab_testing_prices_id" id="testing_prices_input_id" class="text" placeholder="Testing Prices" value="'+testing_prices+'"></div>';
					testing_content_fields += '<div name="_segmentlab_url_id" class="right"></div>';
					testing_content_fields += '</div></span>';
					testing_content_fields += '</label>';
					testing_content_fields += '<br class="clear">';
					$( ".price_fields" ).append( testing_content_fields );
				});
			</script>				
			<?

            sgmnt_lab_help_tip_message();			
			sgmnt_lab_input_price_check("_regular_price", "segmentlab_testing_prices_".$thepostid."_id");
			sgmnt_lab_input_price_check("_sale_price", "segmentlab_testing_prices_".$thepostid."_id");
			sgmnt_lab_input_testing_price_check ("testing_prices_input_id");			
		}//QUICK EDITE SETTINGS				
		function sgmnt_lab_quick_testing_castle_save ($product) {
			$post_id = $product->id;
			$customFieldDemo = trim(sanitize_text_field($_REQUEST['segmentlab_testing_prices_id'] ));
			if (sgmnt_lab_check_var($customFieldDemo)) {
				$decimal_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
				$pries_irasant_i_db_sutvarkyta = str_replace( $decimal_sep,'.', $customFieldDemo );
				$pries_irasant_i_db_sutvarkyta = str_replace( ';',',', $pries_irasant_i_db_sutvarkyta );
				update_post_meta( $post_id, 'sgmntLab_testing_prices_'.$post_id.'_a', wc_clean( $pries_irasant_i_db_sutvarkyta ) );// kas yra wc_clean?
				$product_title = 			$product_information->post->post_title;
				sgmnt_lab_variation_testing_lab_save ($post_id, $pries_irasant_i_db_sutvarkyta, $product_title); //creating ACC
			}
		}//QUICK EDITE SAVE	
		function get_mid_by_key( $post_id, $meta_key ) {
			global $wpdb;
			$mid = $wpdb->get_var( $wpdb->prepare("SELECT meta_id FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post_id, $meta_key) );
			if( $mid != '' )
			return (int) $mid;
			return false;
		}
		function sgmnt_lab_help_tip_message () {
			$decimal_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
			$prices_sep = ";";
			$prices_sep_word = "semilonon";
			if ($decimal_sep == '.') {
				$decimal_block = ',';
				$decimal_regex = '\.';
			}
			else if ($decimal_sep == ',') {
				$decimal_block = '.';
				$decimal_regex = ',';
			}
			else {
				//if not , and not ., use default = .
				$decimal_sep = ".";
				$decimal_regex = '\.';
				$decimal_block = ",";
			}

			echo '
			<script>
				jQuery( function ( $ ) {					
					$(document).ready(function() {
						$( document.body ).on( "wc_add_error_tipe54_regular_sale", function( e, element, error_type ) {
							var textas_notice = "Please enter in monetary decimal ('.$decimal_sep.') format without thousand separators and currency symbols";
							var offset = element.position();
							if ( element.parent().find( ".wc_error_tip" ).size() === 0 ) {
								element.after( "<div class=\"wc_error_tip " + error_type + "\">"+textas_notice+"</div>" );
								element.parent().find( ".wc_error_tip" )
									.css( "left", offset.left + element.width() - ( element.width() / 2 ) - ( $( ".wc_error_tip" ).width() / 2 ) )
									.css( "top", offset.top + element.height() )
									.fadeIn( "300" );
							}
						});


						$( document.body ).on( "wc_add_error_tipe54", function( e, element, error_type ) {
							var textas_notice = "Please enter your selected prices and separate them with '.$prices_sep_word.' ('.$prices_sep.').";
							var offset = element.position();
							if ( element.parent().find( ".wc_error_tip" ).size() === 0 ) {
								element.after( "<div class=\"wc_error_tip " + error_type + "\">"+textas_notice+"</div>" );
								element.parent().find( ".wc_error_tip" )
									.css( "left", offset.left + element.width() - ( element.width() / 2 ) - ( $( ".wc_error_tip" ).width() / 2 ) )
									.css( "top", offset.top + element.height() )
									.fadeIn( "300" );
							}
						});
						$( document.body ).on( "wc_add_error_tipe64", function( e, element, error_type ) {
							var offset = element.position();
							if ( element.parent().find( ".wc_error_tip" ).size() === 0 ) {
								element.after( "<div class=\"wc_error_tip " + error_type + "\">Maximum 10 selected prices separated with '.$prices_sep_word.' ('.$prices_sep.').</div>" );
								element.parent().find( ".wc_error_tip" )
									.css( "left", offset.left + element.width() - ( element.width() / 2 ) - ( $( ".wc_error_tip" ).width() / 2 ) )
									.css( "top", offset.top + element.height() )
									.fadeIn( "300" );
							}
						});
						$( document.body ).on( "wc_add_error_tipe54_regular_sale_rem", function( e, element, error_type ) {
							$( ".wc_error_tip" ).fadeOut( "300", function() { $( this ).remove(); } );
						});
						$( document.body ).on( "wc_remove_error_tipe54", function( e, element, error_type ) {
							$( ".wc_error_tip" ).fadeOut( "300", function() { $( this ).remove(); } );
						});
						$( document.body ).on( "blur", function() {
							$( ".wc_error_tip" ).fadeOut( "300", function() { $( this ).remove(); } );
						});						
					});
				});
			</script>';
		}//HELP TIP MESSAGES
		function sgmnt_lab_input_price_check ($id_to_shoot, $id_test_val) {
			$decimal_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
			$prices_sep = ";";
			$prices_sep_word = "semilonon";
			if ($decimal_sep == '.') {
				$decimal_block = ',';
				$decimal_regex = '\.';
			}
			else if ($decimal_sep == ',') {
				$decimal_block = '.';
				$decimal_regex = ',';
			}
			else {
				$decimal_sep = ".";
				$decimal_regex = '\.';
				$decimal_block = ",";
			}			
			
			echo '
			<script>
				jQuery( function ( $ ) {					
					$(document).ready(function() {			
						$("#'.$id_to_shoot.'").bind("blur keyup",function(e) { 
							if ((e.type === "keyup" || e.type === "change") && 1==1) {
								var price_value = $(this).val();
								var price_regex = new RegExp( "[^0-9'.$decimal_sep.']", 								"gi" );
								var price_value_cleaned = price_value.replace(price_regex, 								"");								
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.'+/g, 					"'.$prices_sep.'"); //;;->;						
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.'+/g, 				"'.$decimal_sep.'"); //..->.
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$prices_sep.'+/g, 	"'.$prices_sep.'"); //.;->;
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.$decimal_regex.'+/g, "'.$prices_sep.'"); //;.->;
								price_value_cleaned = price_value_cleaned.replace(/^\s+|\s+$/g,""); //remove the spaces

								var count_dots = countas(price_value_cleaned, "'.$decimal_sep.'");
								if (count_dots > 0) {
									var firstChar = price_value_cleaned.substring(0,1);
									if (firstChar == "'.$decimal_sep.'") {
										price_value_cleaned = price_value_cleaned.substring(1);
									}
									var count_dots = countas(price_value_cleaned, "'.$decimal_sep.'");
									if (count_dots >= 2) {
										price_value_cleaned = price_value_cleaned.split("'.$decimal_sep.'", 2);
										price_value_cleaned = price_value_cleaned[0]+"'.$decimal_sep.'"+price_value_cleaned[1];							
									}
								}
								if (price_value !== price_value_cleaned) {					  								
									$(this).val(price_value_cleaned);
								}


								var test_value = 		$("#'.$id_test_val.'").val();
								var test_value_orig = 	test_value;

								var regular_value = 	$("input[name=_regular_price]").val();	
								var sale_value = 		$("input[name=_sale_price]").val();									
								var set_price = 		0;
								var change = 			0;
								if ("'.$id_to_shoot.'" == "_regular_price") {
									if (sale_value == "" || sale_value == " " || sale_value == null || sale_value === false) {
										if (price_value_cleaned == "" || price_value_cleaned == " " || price_value_cleaned == null || price_value_cleaned === false) {
											set_price = 0;
										}
										else {
											set_price = price_value_cleaned;
										}
										change = 1;
									}
									else {
										set_price = sale_value;
										change = 1;
									}
								}

								if ("'.$id_to_shoot.'" == "_sale_price") {
									if (price_value_cleaned == "" || price_value_cleaned == " " || price_value_cleaned == null || price_value_cleaned === false) {
										set_price = regular_value;
										change = 1;
									}
									else {
										if ((Number(price_value_cleaned) >= Number(regular_value))) {
											set_price = regular_value;
											change = 1;										
										}
										else {
											set_price = sale_value;
											change = 1;
										}									
									}
								}

								if (change == 1) {
									var check_commas = countas(test_value, "'.$prices_sep.'");
									if (check_commas >= 1) {
										var pirma_reiksme_array = 	test_value.split("'.$prices_sep.'");
										var pirma_reiksme = 		pirma_reiksme_array[0];

										if (pirma_reiksme !== set_price) {
											if (set_price !== "" && set_price !== " " && set_price !== null && set_price !== false) {
												pirma_reiksme_array["0"] = 	set_price;
												test_value = 				pirma_reiksme_array.join("'.$prices_sep.'");
											}
											else {
												pirma_reiksme_array["0"] = 	0;
												test_value = 				pirma_reiksme_array.join("'.$prices_sep.'");
											}
										}
									}
									else {
										if (set_price !== "" && set_price !== " " && set_price !== null && set_price !== false) {
											test_value = set_price;
										}
										else {
											test_value = 0;
										}
									}		

									if (test_value !== test_value_orig) {
										$("#'.$id_test_val.'").val(test_value);																
									}									
								}							
							}
							if (e.type === "blur" || e.keyCode === 13)  {
								var price_value = $(this).val();
								var price_regex = new RegExp( "[^0-9'.$decimal_sep.']", 								"gi");
								var price_value_cleaned = price_value.replace(price_regex, 								"");		
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.'+/g, 					"'.$prices_sep.'"); //;;->;	
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.'+/g, 				"'.$decimal_regex.'"); //..->.
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$prices_sep.'+/g, 	"'.$prices_sep.'"); //.;->;
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.$decimal_regex.'+/g, 	"'.$prices_sep.'"); //;.->;
								price_value_cleaned = price_value_cleaned.replace(/^\s+|\s+$/g, 						""); //remove the spaces

								var lastChar = price_value_cleaned.substr(price_value_cleaned.length - 1);
								if (lastChar == "'.$prices_sep.'" || lastChar == "'.$decimal_sep.'") {
									price_value_cleaned = price_value_cleaned.substring(0, price_value_cleaned.length - 1);
								}
								var firstChar = price_value_cleaned.substring(0,1);
								if (firstChar == "'.$prices_sep.'" || firstChar == "'.$decimal_sep.'") {
									price_value_cleaned = price_value_cleaned.substring(1);
								}

								var count_separ = countas(price_value_cleaned, "'.$decimal_sep.'");
								var cleared_nulls = "";
								if (count_separ > 0) {
									cleared_nulls = 		price_value_cleaned.replace("'.$decimal_regex.'", ".");
									cleared_nulls = 		Number(cleared_nulls);
									cleared_nulls = 		cleared_nulls.toString();
									cleared_nulls = 		cleared_nulls.replace(".","'.$decimal_regex.'");
									price_value_cleaned = 	cleared_nulls;
								}
								else {										
									if (price_value_cleaned !== "" && price_value_cleaned !== " " && price_value_cleaned !== null) {
										cleared_nulls = 		Number(price_value_cleaned);
										cleared_nulls = 		cleared_nulls.toString();
										price_value_cleaned = 	cleared_nulls;
									}	
								}
								
								if (price_value !== price_value_cleaned) {					  								
									$(this).val(price_value_cleaned);
								}
							
								
								var regular_value = 	$("input[name=_regular_price]").val();	
								var sale_value = 		$("input[name=_sale_price]").val();									
								var set_price = 		0;
								var change = 			0;
								
								
								if ("'.$id_to_shoot.'" == "_regular_price") {
									if (sale_value == "" || sale_value == " " || sale_value == null || sale_value === false) {
										if (price_value_cleaned == "" || price_value_cleaned == " " || price_value_cleaned == null || price_value_cleaned === false) {
											set_price = 0;
										}
										else {
											set_price = price_value_cleaned;
										}
									}
									else {
										if (price_value_cleaned == "" || price_value_cleaned == " " || price_value_cleaned == null || price_value_cleaned === false) {
											set_price = 0;
											$("input[name=_sale_price]").val("");	
										}
										else {
											if (Number(price_value_cleaned) <= Number(sale_value)) {
												set_price = price_value_cleaned;	
												$("input[name=_sale_price]").val("");
											}
											else {
												set_price = sale_value;	
											}																				
										}
									}
								}

								if ("'.$id_to_shoot.'" == "_sale_price") {
									if (price_value_cleaned == "" || price_value_cleaned == " " || price_value_cleaned == null || price_value_cleaned === false) {
										if (regular_value == "" || regular_value == " " || regular_value == null || regular_value == false) {
											set_price = 0;										
										}
										else {
											set_price = regular_value;										
										}
										change = 1;
									}
									else {
										if ((Number(price_value_cleaned) >= Number(regular_value))) {
											set_price = regular_value;
											change = 1;										
										} else {
											set_price = sale_value;
											change = 1;
										}									
									}
								}
								$(this).val(price_value_cleaned);
								
								var test_value = 		$("#'.$id_test_val.'").val();
								var test_value_orig = 	test_value;
								
								var check_commas = countas(test_value, "'.$prices_sep.'");
								if (check_commas >= 1) {
									var pirma_reiksme_array = 	test_value.split("'.$prices_sep.'");
									var pirma_reiksme = 		pirma_reiksme_array[0];

									if (pirma_reiksme !== price_value_cleaned) {
										pirma_reiksme_array["0"] = set_price;
										test_value = pirma_reiksme_array.join("'.$prices_sep.'");
									}
								}
								else {
									test_value = set_price;
								}
								$("#'.$id_test_val.'").val(test_value);
								
								var regular_value = $("input[name=_regular_price]").val();
								var sale_value = 	$("input[name=_sale_price]").val();	
								var testing_value = $("#'.$id_test_val.'").val();
								//check price
								var set_price = 	"";
								var set_new = 		0;
								var check_reg = 	0;
								if (sale_value == "" || sale_value == " " || sale_value == null || sale_value == false) {
									if (regular_value == "" || regular_value == " " || regular_value == null || regular_value == false) {
										set_price = 0;
										set_new = 1;
									}
									else {
										set_price = regular_value;
									}
								}
								else {
									check_reg = 1;
									if (Number(sale_value) >= Number(regular_value)) {
										set_price = 0;
									}
									else {
										set_price = sale_value;
									}
								}
								
								var price_value_cleaned = 	testing_value;
								var count_separators = 		countas(price_value_cleaned, "'.$prices_sep.'");

								if (count_separators > 0 && 1 === 1) {
									var price_value_array = 	price_value_cleaned.split("'.$prices_sep.'");
									var price_value_array_tmp = new Array();
									for (var i = 0; i < price_value_array.length; i++) {
										var price_single_value = price_value_array[i];
										if (price_single_value !== "" && price_single_value !== " " && price_single_value !== null &&  price_single_value !== false) {
											if (check_reg == 1) {
												if (Number(price_single_value) < Number(regular_value)) {
													//dedam
													if (i == 0) {
														if (set_new == 1) {
															price_value_array_tmp.push(price_single_value);
														}
														else {
															price_value_array_tmp.unshift(set_price);
														}
													}
													else {
														price_value_array_tmp.push(price_single_value);
													}
												}
												else {
													//nededam
												}
											}
											else {
												//dedam
												if (i == 0) {
													if (set_new == 1) {
														price_value_array_tmp.push(price_single_value);
													}
													else {
														price_value_array_tmp.unshift(set_price);
													}
												}
												else {
													price_value_array_tmp.push(price_single_value);
												}
											}		
										}
									}
									price_value_cleaned = price_value_array_tmp.join("'.$prices_sep.'");									
									var price_value_array = price_value_cleaned.split("'.$prices_sep.'");
									
									price_value_array = 	price_value_array.filter(function(e){ return e.length});
									//Now use a function to make the array unique
									Array.prototype.unique = function(){
									   var u = {}, a = [];
									   for (var i = 0, l = this.length+1; i < l; ++i) {
										  if (this[i] in u)
											 continue;
										  a.push(this[i]);
										  u[this[i]] = 1;
									   }
									   return a;
									}

									price_value_array = 	price_value_array.unique();
									price_value_cleaned = 	price_value_array.join("'.$prices_sep.'");
									
									var lastChar = price_value_cleaned.substr(price_value_cleaned.length - 1);
									if (lastChar == "'.$prices_sep.'" || lastChar == "'.$decimal_sep.'") {
										price_value_cleaned = price_value_cleaned.substring(0, price_value_cleaned.length - 1);
									}
									
									var commas = 		price_value_cleaned.split("'.$prices_sep.'");
									var commas_length = commas.length;
									var split_from = 	10;
									if (commas_length > split_from){
										var price_value_cleaned = 	price_value_cleaned.split("'.$prices_sep.'", split_from);
										price_value_cleaned = 		price_value_cleaned.join("'.$prices_sep.'");										
									}
									
									
									
									
									
									$("#'.$id_test_val.'").val(price_value_cleaned);
								}
								
							}								
						});  						
					});
				});
			</script>';
		}
		function sgmnt_lab_input_testing_price_check ($id_to_shoot, $testingField) {
			$decimal_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
			$prices_sep = ";";
			$prices_sep_word = "semilonon";
			if ($decimal_sep == '.') {
				$decimal_block = ',';
				$decimal_regex = '\.';
			}
			else if ($decimal_sep == ',') {
				$decimal_block = '.';
				$decimal_regex = ',';
			}
			else {
				$decimal_sep = ".";
				$decimal_regex = '\.';
				$decimal_block = ",";
			}
			
			echo '<script>
				jQuery( function ( $ ) {					
					$(document).ready(function() {			
						$("#'.$id_to_shoot.'").bind("blur keyup",function(e) {
							var regular_value = $("input[name=_regular_price]").val();
							var sale_value = 	$("input[name=_sale_price]").val();	
							var testing_value = $("#'.$testingField.'").val();
							//check price
							var set_price = 	"";
							var set_new = 		0;
							var check_reg = 	0;
							if (sale_value == "" || sale_value == " " || sale_value == null || sale_value == false) {
								if (regular_value == "" || regular_value == " " || regular_value == null || regular_value == false) {
									set_price = 0;
									set_new = 	1;
								}
								else {
									set_price = regular_value;
								}
							}
							else {
								check_reg = 1;
								if (Number(sale_value) >= Number(regular_value)) {
									set_price = 0;
								}
								else {
									set_price = sale_value;
								}
							}
							
							if ((e.type === "keyup" || e.type === "change") && 1==1) {
								var price_value = $(this).val();
								var price_regex = new RegExp( "[^0-9'.$prices_sep.$decimal_sep.']", "gi");
								var price_value_cleaned = price_value.replace(price_regex, "");		
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.'+/g, "'.$prices_sep.'"); //;;->;	
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.'+/g, "'.$decimal_regex.'"); //..->.
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.$decimal_regex.'+/g, "'.$prices_sep.'"); //;.->;
								price_value_cleaned = price_value_cleaned.replace(/^\s+|\s+$/g,""); //remove the spaces

								if (price_value !== price_value_cleaned) {
									//$(this).val(price_value_cleaned);
									$(document.body).triggerHandler("wc_add_error_tipe54_regular_sale", [$(this), "erroras"]);
								}
								else {
									$(document.body).triggerHandler("wc_add_error_tipe54_regular_sale_rem", [$(this), "erroras"]);
								}
								
								var count_separators = countas(price_value_cleaned, "'.$prices_sep.'");
								if (count_separators > 0) {
									var price_value_array = 	price_value_cleaned.split("'.$prices_sep.'");
									var price_value_array_tmp = new Array();
									var cleared_nulls = 		"";
									for (var i = 0; i < price_value_array.length; i++) {
										var price_single_value = price_value_array[i];
										if (price_single_value !== "" && price_single_value !== " " && price_single_value !== null && price_single_value !== false) {
											var count_dec_separators = countas(price_single_value, "'.$decimal_sep.'");
											if (count_dec_separators > 0) {
												if (count_dec_separators >= 2) {
													price_single_value = price_single_value.split("'.$decimal_sep.'",2);
													price_single_value = price_single_value[0]+"'.$decimal_sep.'"+price_single_value[1];
												}
												
												var firstChar = price_single_value.substring(0,1);
												var lastChar = price_single_value.substr(price_single_value.length - 1);
												if (firstChar == "'.$decimal_sep.'" || lastChar == "'.$decimal_sep.'") {
												}
												else {
													cleared_nulls = 		Number(price_single_value);
													cleared_nulls = 		cleared_nulls.toString();
													price_single_value = 	cleared_nulls;
												}
											}
											else {
												cleared_nulls = Number(price_single_value);
												cleared_nulls = cleared_nulls.toString();
												price_single_value = cleared_nulls;
											}
										}
										price_value_array_tmp.push(price_single_value);
									}
									price_value_cleaned = price_value_array_tmp.join("'.$prices_sep.'");
									
									var value = 		price_value_cleaned;
									var commas = 		value.split("'.$prices_sep.'");
									var commas_length = commas.length;

									var split_from = 10;
									if (commas_length > split_from){
										var new_value_f_l = 	value.split("'.$prices_sep.'",split_from);
										price_value_cleaned = 	new_value_f_l.join("'.$prices_sep.'");
										$(document.body).triggerHandler("wc_add_error_tipe64", [$(this), "erroras"]);
									}
								}
								else {
									var count_dec_separators = countas(price_value_cleaned, "'.$decimal_sep.'");
									if (count_dec_separators >= 2) {
										mistake_found = 		1;
										price_value_cleaned = 	price_value_cleaned.split("'.$decimal_sep.'",2);
										price_value_cleaned = 	price_value_cleaned[0]+"'.$decimal_sep.'"+price_value_cleaned[1];
									}
								}
								
								
								
								
								
								
								
								
								$(this).val(price_value_cleaned);
							}
							if ((e.type === "blur" || e.keyCode === 13) && 1==1)  {
								var price_value = 			$(this).val();
								var price_regex = 			new RegExp( "[^0-9'.$prices_sep.$decimal_sep.']", 			"gi");
								var price_value_cleaned = 	price_value.replace(price_regex, 							"");		
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.'+/g, 					"'.$prices_sep.'"); //;;->;	
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.'+/g, 				"'.$decimal_regex.'"); //..->.
								price_value_cleaned = price_value_cleaned.replace(/'.$decimal_regex.$prices_sep.'+/g, 	"'.$prices_sep.'"); //.;->;
								price_value_cleaned = price_value_cleaned.replace(/'.$prices_sep.$decimal_regex.'+/g, 	"'.$prices_sep.'"); //;.->;
								price_value_cleaned = price_value_cleaned.replace(/^\s+|\s+$/g, 						""); //remove the spaces

								var count_separators = countas(price_value_cleaned, "'.$prices_sep.'");
								if (count_separators > 0 && 1 === 1) {
									var price_value_array = 	price_value_cleaned.split("'.$prices_sep.'");
									var price_value_array_tmp = new Array();
									var cleared_nulls = 		"";
									
									for (var i = 0; i < price_value_array.length; i++) {
										var price_single_value = price_value_array[i];
										if (price_single_value !== "" && price_single_value !== " " && price_single_value !== null &&  price_single_value !== false) {
											var count_dec_separators = countas(price_single_value, "'.$decimal_sep.'");
											if (count_dec_separators >= 2) {
												price_single_value = price_single_value.split("'.$decimal_sep.'",2);
												price_single_value = price_single_value[0]+"'.$decimal_sep.'"+price_single_value[1];
											}
											
											var count_dec_separators = countas(price_single_value, "'.$decimal_sep.'");
											if (count_dec_separators > 0) {
												cleared_nulls = 		price_single_value.replace("'.$decimal_regex.'", ".");
												cleared_nulls = 		Number(cleared_nulls);
												cleared_nulls = 		cleared_nulls.toString();
												cleared_nulls = 		cleared_nulls.replace(".","'.$decimal_regex.'");
												price_single_value = 	cleared_nulls;
											}
											else {
												cleared_nulls = 		Number(price_single_value);
												cleared_nulls = 		cleared_nulls.toString();
												price_single_value = 	cleared_nulls;
											}
											
											if (check_reg == 1) {
												if (Number(price_single_value) < Number(regular_value)) {
													//dedam
													if (i == 0) {
														if (set_new == 1) {
															$("input[name=_regular_price]").val(price_single_value);
															price_value_array_tmp.push(price_single_value);
														}
														else {
															price_value_array_tmp.unshift(set_price);
														}
													}
													else {
														price_value_array_tmp.push(price_single_value);
													}
												}
												else {
													//nededam
												
												}
											}
											else {
												//dedam
												if (i == 0) {
													if (set_new == 1) {
														$("input[name=_regular_price]").val(price_single_value);
														price_value_array_tmp.push(price_single_value);
													}
													else {
														price_value_array_tmp.unshift(set_price);
													}
												}
												else {
													price_value_array_tmp.push(price_single_value);
												}
											}											
										}
									}
									
									price_value_cleaned = 	price_value_array_tmp.join("'.$prices_sep.'");
									var price_value_array = price_value_cleaned.split("'.$prices_sep.'");
									price_value_array = 	price_value_array.filter(function(e){ return e.length});
									//Now use a function to make the array unique
									Array.prototype.unique = function(){
									   var u = {}, a = [];
									   for (var i = 0, l = this.length+1; i < l; ++i) {
										  if (this[i] in u)
											 continue;
										  a.push(this[i]);
										  u[this[i]] = 1;
									   }
									   return a;
									}

									price_value_array = 	price_value_array.unique();
									price_value_cleaned = 	price_value_array.join("'.$prices_sep.'");
									
									var lastChar = price_value_cleaned.substr(price_value_cleaned.length - 1);
									if (lastChar == "'.$prices_sep.'" || lastChar == "'.$decimal_sep.'") {
										price_value_cleaned = price_value_cleaned.substring(0, price_value_cleaned.length - 1);
									}
									
									var commas = 		price_value_cleaned.split("'.$prices_sep.'");
									var commas_length = commas.length;
									var split_from = 	10;
									if (commas_length > split_from){
										var price_value_cleaned = 	price_value_cleaned.split("'.$prices_sep.'", split_from);
										price_value_cleaned = 		price_value_cleaned.join("'.$prices_sep.'");										
									}
								}
								else {
									var count_dec_separators = countas(price_value_cleaned, "'.$decimal_sep.'");
									if (count_dec_separators >= 2) {
										price_value_cleaned = price_value_cleaned.split("'.$decimal_sep.'",2);
										price_value_cleaned = price_value_cleaned[0]+"'.$decimal_sep.'"+price_value_cleaned[1];
									}
									var count_dec_separators = countas(price_value_cleaned, "'.$decimal_sep.'");
									if (count_dec_separators > 0) {
										cleared_nulls = 		price_value_cleaned.replace("'.$decimal_regex.'", ".");
										cleared_nulls = 		Number(cleared_nulls);
										cleared_nulls = 		cleared_nulls.toString();
										cleared_nulls = 		cleared_nulls.replace(".","'.$decimal_regex.'");
										price_value_cleaned = 	cleared_nulls;
									}
									else {
										cleared_nulls = 		Number(price_value_cleaned);
										cleared_nulls = 		cleared_nulls.toString();
										price_value_cleaned = 	cleared_nulls;
									}		
								
									if (set_new == 1) {
										$("input[name=_regular_price]").val(price_value_cleaned);
									}
									else {
										price_value_cleaned = set_price;									
									}
								}								
								
								
								$(this).val(price_value_cleaned);
							}
						});
					});
				});
			</script>';			
		}
	}//INT
	
	function segment_lab_return_price ($price, $_product) {
		global $product;
		global $woocommerce;
		
		//SETTINGS
		$settings_price_correction = 	get_option('sgmntLab_cfg_correction');
		$settings_price_testing = 		get_option('sgmntLab_cfg_testing');
		$kaina_sale = 					$_product->sale_price;					
		
		if (is_admin() || is_admin_bar_showing()) { //chekina tik konkreciai ar admin meniu
			$testing_admin_status = sgmnt_lab_check_var(get_option('sgmntLab_cfg_admin_test'), 1);
		}
		else {
			if (isset($_COOKIE["lab_admin_testing"])) {
				$testing_admin_status = $_COOKIE["lab_admin_testing"];
			}
			else {
				$testing_admin_status = 0;
			}
		}
		//add_filter('woocommerce_get_price','change_price_regular_member', 10, 2);

		$master_settings_control = 		get_option('sgmntLab_cfg_pause');
		if ($master_settings_control == 0 && ($settings_price_correction == 1 || $settings_price_testing == 1)) {
			//VARIABLES
			$number_of_decimals = 		wp_specialchars_decode(stripslashes(get_option('woocommerce_price_num_decimals')), 	ENT_QUOTES);
			$decimal_sep = 				wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), 	ENT_QUOTES);
			$thousand_sep = 			wp_specialchars_decode(stripslashes(get_option('woocommerce_price_thousand_sep')), 	ENT_QUOTES);
			$currency_pos = 			get_option('woocommerce_currency_pos');

			$testing_product_id = 		$_product->id;
			$kaina_regular = 			$_product->regular_price;

			$price_testing_vals = 		get_post_meta($testing_product_id, 	'sgmntLab_testing_prices_'.$testing_product_id.'_a', 	true);
			$get_testing_code_idas = 	get_post_meta($testing_product_id, 	'sgmntLab_testing_prices_'.$testing_product_id.'_idas', true);
			$get_testing_code = 		get_post_meta($testing_product_id, 	'sgmntLab_testing_prices_'.$testing_product_id.'_code', true);			

			$galunes_is_wp_db = 		get_option('sgmntLab_cfg_price_endings');
			$price_ending_id = 			get_option('sgmntLab_cfg_price_endings_idas');
			$testing_admin_check = 		get_option('sgmntLab_cfg_admin_test');

			$price_from_cookie = 		$_COOKIE["snippets_show_code_info"];
			$admin_testing_cookie = 	$_COOKIE["lab_admin_testing"];
			$ad_block = 				$_COOKIE["adblckstatus"]; 
			$user_unique_id = 			$_COOKIE["unique_lab_id"]; 

			$pieces = 					explode(",", $price_testing_vals);
			$pieces_lenght = 			count($pieces);

			$pieces_ending = 			explode(";", $galunes_is_wp_db);
			$pieces_ending_lenght = 	count($pieces_ending);

			if (sgmnt_lab_check_var($price_from_cookie)) {$galunes_cookie_egzist = 1;	} else {$galunes_cookie_egzist = 0;	}
			if (sgmnt_lab_check_var($galunes_is_wp_db)) {$galunes_egzist = 1;			} else {$galunes_egzist = 0;		}
			
			//REMEMBER_CHECK_FOR_FURTHER_INVESTIGATION
			$lab_sutapo_price_remember = 	0;
			$lab_sutapo_ending_remember = 	0;
			if ($galunes_cookie_egzist == 0) {
				$go_check_memory = 	0;
				$go_check_ending = 	0;
				$go_check_price = 	0;
				if (sgmnt_lab_check_var($price_ending_id) && sgmnt_lab_check_var($galunes_is_wp_db)) {
					$ending_id_send = 	$price_ending_id;
					$go_check_memory = 	1;
					$go_check_ending = 	1;
				}
				else {
					$ending_id_send = "stop";
				}

				if (sgmnt_lab_check_var($get_testing_code_idas) && sgmnt_lab_check_var($price_testing_vals)) {
					$price_id_send = $get_testing_code_idas;
					$go_check_memory = 1;
					$go_check_price = 1;			
				}
				else {
					$price_id_send = "stop";				
				}

				if ($go_check_memory == 1) {
					$lab_mem_stats_result = sgmnt_lab_remembered_stats ($price_id_send, $ending_id_send, $user_unique_id);
					if ($go_check_ending == 1) {
						$rem_ending_result = $lab_mem_stats_result['ending']; // ar nelygu noFound
						$lab_sutapo_ending_remember = 0;
						if ($pieces_ending_lenght >= 1) {
							if (sgmnt_lab_check_var($rem_ending_result) && $rem_ending_result !== "noFound") {
								for ($ii=0; $ii < $pieces_ending_lenght-1; $ii++) {
									$values_by_one = $pieces_ending[$ii];								
									if ($values_by_one == $rem_ending_result) {
										$lab_sutapo_ending_remember = 1;		
									}
								}	
							}
						}
					}

					if ($go_check_price == 1) {
						$rem_price_result = $lab_mem_stats_result['price'];					
						$lab_sutapo_price_remember = 0;
						if ($pieces_lenght >= 1) {
							if (sgmnt_lab_check_var($rem_price_result) && $rem_price_result !== "noFound") {
								for ($ii=0; $ii < $pieces_lenght; $ii++) {
									$values_by_one = $pieces[$ii];
									if ($values_by_one == $rem_price_result) {
										$lab_sutapo_price_remember = 1;		
									}
								}	
							}
						}
					}
				}			
			}		
			//ENDING MODIFICATION
			if (sgmnt_lab_check_var($price_ending_id) && $galunes_egzist == 1 && $galunes_cookie_egzist == 1) {
				//echo "<br>ciaa<br>";
				$price_ending_id =  $price_ending_id;
				$endingas_tst_basic = "ZONEX134|".$price_ending_id."|X091X|";
				$endingas_tst_galas = "|X332X193X";
				$endingas_tst_priekis = "|X99X0X23|";

				$kaina_is_cookio = explode($endingas_tst_basic, $price_from_cookie);
				$kaina_is_cookio = explode($endingas_tst_galas, $kaina_is_cookio[1]);
				$kaina_is_cookio = explode($endingas_tst_priekis, $kaina_is_cookio[0]);
				$ending_val = $kaina_is_cookio[1];	

				$galunes_is_wp_db = rtrim($galunes_is_wp_db,"; ");
				$price_endings_array = explode(";", $galunes_is_wp_db);
				$price_endings_array_lenght = count($price_endings_array);
				$sutapusi_galune = "";
				$faund_val = 0;
				for ($is=0; $is < $price_endings_array_lenght; $is++) {
					$galune_single = $price_endings_array[$is];
					$ending_val = str_replace(' ', '+', $ending_val);
					if ($galune_single == $ending_val) {
						$faund_val = 1;
						$sutapusi_galune = $galune_single;
						break;
					}
				}
				if ($faund_val == 0) {
					if ($lab_sutapo_ending_remember == 1) {
						$faund_val = 0;
						for ($is=0; $is < $price_endings_array_lenght; $is++) {
							$galune_single = $price_endings_array[$is];
							$ending_val = $rem_ending_result;
							if ($galune_single == $ending_val) {
								$faund_val = 1;
								$sutapusi_galune = $galune_single;
								break;
							}
						}
						if ($faund_val == 0) {
							$sutapusi_galune = 0;
						}					
					} 
					else {
						$sutapusi_galune = 0;					
					}				
				}
			}
			else {
				//echo "here<br>";
				if ($galunes_egzist == 1 && $galunes_cookie_egzist == 1) {
					if ($lab_sutapo_ending_remember == 1) {
						$faund_val = 0;
						for ($is=0; $is < $price_endings_array_lenght; $is++) {
							$galune_single = $price_endings_array[$is];
							$ending_val = $rem_ending_result;
							if ($galune_single == $ending_val) {
								$faund_val = 1;
								$sutapusi_galune = $galune_single;
								break;
							}
						}
						if ($faund_val == 0) {
							$sutapusi_galune = 0;
						}
					}
				}
				else {
					$ending_val =  0;
					$sutapusi_galune = 0;
				}
			}		

			if (is_admin() || is_admin_bar_showing()) { //chekina tik konkreciai ar admin meniu
				if (sgmnt_lab_check_var($testing_admin_check)) {
					$testing_admin_status = $testing_admin_check;
				}
				else {
					$testing_admin_status = 0; //dar padaryt gal checka visur su reiksmem, kur zinom.... if 1 || if 0 or .... kad jei kokia kitokia ateina tai neapsigautu sistema ir parodytu 0 pvz.....
				}
			}
			else {
				if (sgmnt_lab_check_var($admin_testing_cookie)) {
					$testing_admin_status = $admin_testing_cookie;
				} 
				else {
					$testing_admin_status = 0;
				}
			}

			$admin_status_check = $testing_admin_status; //0-off_1-on;ar admin paneleje ar pagrindiniame lauke
			if ($admin_status_check == 1) {$admin_status = 1;}
			else {
				if (!is_admin()) {
					$admin_status = 0;//ne adminas
				}
				else {
					$admin_status = 1;//adminas
				}		
			}

			if (sgmnt_lab_check_var($kaina_sale)) {$kaina_atsargine = $kaina_sale;}
			else if (sgmnt_lab_check_var($kaina_regular)) {$kaina_atsargine = $kaina_regular;}
			else {$kaina_atsargine = "error";}
			//echo "atsargine:".$kaina_atsargine."<br>";
			if (sgmnt_lab_check_var($price_testing_vals)) {
				//echo "varo<br>";
				//echo "vals:".$price_testing_vals."<br>";
				$pieces = explode(",", $price_testing_vals);
				$pieces_lenght = count($pieces);

				//echo "sita:".$pieces[0]."<br>";
				if ($kaina_atsargine !== "error" && $kaina_atsargine == $pieces[0]) {
					//$kaina_atsargine = $pieces[0]; // nereikalinga	
				}
				else {
					//$kaina_atsargine = "error";
				}
				//echo "atsargine2:".$kaina_atsargine."<br>";
				//echo "get_testing_code:".$get_testing_code."<br>";
				//echo "get_testing_code_idas:".$get_testing_code_idas."<br>";
				
				if (sgmnt_lab_check_var($get_testing_code) && sgmnt_lab_check_var($get_testing_code_idas)) {
					$sistema = 0;
					if (sgmnt_lab_check_var($ad_block)) {
						if ($ad_block == "off") {
							$sistema = 1;
						}
						else {
							$sistema = 0;//rodo default sale price
						}
					}
					else {
						$sistema = 0;//rodo default sale price
					}				
					if (sgmnt_lab_check_var($price_from_cookie)) {
						$karpymui_tst_basic = "ZONEX134|".$get_testing_code_idas."|X091X|";
						$karpymui_tst_galas = "|X332X193X";
						$karpymui_tst_priekis = "|X99X0X23|";

						$kaina_is_cookio = explode($karpymui_tst_basic, $price_from_cookie);
						$kaina_is_cookio = explode($karpymui_tst_galas, $kaina_is_cookio[1]);
						$kaina_is_cookio = explode($karpymui_tst_priekis, $kaina_is_cookio[0]);
						$kaina_is_cookio = $kaina_is_cookio[1];

						$script_price = 0; //ar atitiko kaina, skripto su db		
						if ($pieces_lenght >= 1) {
							for ($i=0; $i < $pieces_lenght; $i++) {
								$values_by_one = $pieces[$i];
								if ($values_by_one == $kaina_is_cookio) {
									$script_price = 1;					
								}
							}									
							if ($admin_status == 0) {
								if ($sistema == 1) {								
									if ($script_price == 1) {
										$dedam = $kaina_is_cookio;
									}
									else {
										$sutapo_remember = 0;
										if ($lab_sutapo_price_remember == 1) {
											if ($pieces_lenght >= 1) {
												if (sgmnt_lab_check_var($rem_price_result)) {
													for ($ii=0; $ii < $pieces_lenght; $ii++) {
														$values_by_one = $pieces[$ii];
														if ($values_by_one == $rem_price_result) {
															$sutapo_remember = 1;						
														}
													}	
												}
											}
										}										 
										if ($sutapo_remember == 1) {
											//patikrinti ar su endingu ok, jei ok rodom su, dar check sutapusi galune ar ok ar ne... ti tada ieskom
											$dedam = $rem_price_result;
										}
										else {
											$dedam = $kaina_atsargine;
										}
									}
									$modified = $dedam + ($sutapusi_galune);
									//$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
									$price = $modified;
								}
								else {
									if ($script_price == 1) {
										$dedam = $kaina_is_cookio;
									 }
									 else {
										$sutapo_remember = 0;
										 if ($lab_sutapo_price_remember == 1) {
											if ($pieces_lenght >= 1) {
												if (sgmnt_lab_check_var($rem_price_result)) {
													for ($ii=0; $ii < $pieces_lenght; $ii++) {
														$values_by_one = $pieces[$ii];
														if ($values_by_one == $rem_price_result) {
															$sutapo_remember = 1;						
														}
													}	
												}
											}
										 }		

										if ($sutapo_remember == 1) {
											$dedam = $rem_price_result;
										}
										else {
											$dedam = $kaina_atsargine;
										}									
									 }
									 $modified = $dedam + ($sutapusi_galune);
									 //$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
									 //$get_testing_code = $modified;
									 //$get_testing_code = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
									 //$price = $get_testing_code;
									 $price = $modified;
								}				
							}
							else {
								if ($script_price == 1) {
									$dedam = $kaina_is_cookio;
								}
								else {
									$sutapo_remember = 0;
									 if ($lab_sutapo_price_remember == 1) {
										if ($pieces_lenght >= 1) {
											if (sgmnt_lab_check_var($rem_price_result)) {
												for ($ii=0; $ii < $pieces_lenght; $ii++) {
													$values_by_one = $pieces[$ii];
													if ($values_by_one == $rem_price_result) {
														$sutapo_remember = 1;						
													}
												}	
											}
										}
									 }		

									if ($sutapo_remember == 1) {
										$dedam = $rem_price_result;
									}
									else {
										$dedam = $kaina_atsargine;
									}
								}

								$modified = $dedam + ($sutapusi_galune);
								//$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
								//$price = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
								$price = $modified;
							}
						}
						else {
							$modified = $kaina_atsargine + ($sutapusi_galune);
							//$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
							//$price = $kaina_atsargine;	
							$price = $modified;				
						}
					}
					else {
						if ($admin_status == 0) {
							if ($sistema == 1) {
							   $sutapo_remember = 0;
							   if ($lab_sutapo_price_remember == 1) {
								  if ($pieces_lenght >= 1) {
									  if (sgmnt_lab_check_var($rem_price_result)) {
										  for ($ii=0; $ii < $pieces_lenght; $ii++) {
											  $values_by_one = $pieces[$ii];
											  if ($values_by_one == $rem_price_result) {
												  $sutapo_remember = 1;						
											  }
										  }	
									  }
								  }
							   }						
							   if ($sutapo_remember == 1) {
									$dedam = $rem_price_result;
							   }
							   else {
									$dedam = $kaina_atsargine;
							   }
							   $modified = $dedam + ($sutapusi_galune);
							   //$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
							   //$get_testing_code_show = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
							   $get_testing_code_show = $modified;

							   $price_ending_id = get_option('sgmntLab_cfg_price_endings_idas');
								if (sgmnt_lab_check_var($price_ending_id)) {
									$price_ending_id =  $price_ending_id;
								}
								else {
									$price_ending_id =  "noFound";
								}

							   //$get_testing_code_show = '<span class="customizable_cd_'.$get_testing_code_idas.'" name="0" data-lab-section="price" data-lab-ending="'.$price_ending_id.'" data-lab-category="plugin"  data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:inline;">'.$get_testing_code_show.'</span>';
							   $price = $get_testing_code_show;
						   }
							else {
								//echo "cia??<br>";

							   $sutapo_remember = 0;
							   if ($lab_sutapo_price_remember == 1) {
								  if ($pieces_lenght >= 1) {
									  if (sgmnt_lab_check_var($rem_price_result)) {
										  for ($ii=0; $ii < $pieces_lenght; $ii++) {
											  $values_by_one = $pieces[$ii];
											  if ($values_by_one == $rem_price_result) {
												  $sutapo_remember = 1;						
											  }
										  }	
									  }
								  }
							   }


							   if ($sutapo_remember == 1) {
									$dedam = $rem_price_result;
							   }
							   else {
									$dedam = $kaina_atsargine;
							   }
							   //echo "dedam:".$dedam."<br>";				

							   $modified = $dedam + ($sutapusi_galune);
							  // $modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);

							   //$get_testing_code_show = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
							   $get_testing_code_show = $modified;

							   //echo "kaina:".$get_testing_code."<br>";



								//$get_testing_code_show = '<span class="customizable_cd_'.$get_testing_code_idas.'" name="0" data-lab-section="price" data-lab-deff="'.$kaina_atsargine.'" data-lab-ending="'.$price_ending_id.'" data-lab-category="plugin" data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:none;">'.$get_testing_code_show.'</span>';
								$price = $get_testing_code_show;
						   }
						}
						else {
						   $sutapo_remember = 0;
						   if ($lab_sutapo_price_remember == 1) {
							  if ($pieces_lenght >= 1) {
								  if (sgmnt_lab_check_var($rem_price_result)) {
									  for ($ii=0; $ii < $pieces_lenght; $ii++) {
										  $values_by_one = $pieces[$ii];
										  if ($values_by_one == $rem_price_result) {
											  $sutapo_remember = 1;						
										  }
									  }	
								  }
							  }
						   }

						   if ($sutapo_remember == 1) {
								$dedam = $rem_price_result;
						   }
						   else {
								$dedam = $kaina_atsargine;
						   }
						   $modified = $dedam + ($sutapusi_galune);
						   //$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);

						   //$get_testing_code_show = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
						   //$price = $get_testing_code_show;
						   $price = $modified;
						}
					}
				}
				else {				
					$nauja_kainele = $kaina_atsargine + ($sutapusi_galune);
					$kaina_atsargine_original = $kaina_atsargine;
					if ($nauja_kainele > 0 ) {
						$kaina_atsargine = $nauja_kainele;					
					}
					else {
						$kaina_atsargine = $kaina_atsargine;					
					}

					$formatted_price = number_format($kaina_atsargine, $number_of_decimals, $decimal_sep, $thousand_sep);
					$price = $formatted_price;
					$debuger = $price;
					if (sgmnt_lab_check_var($price_ending_id)) {
						
						$price = $formatted_price;
					}
					else {
						$price = $formatted_price;					
					}
					//echo "cia<br>";
				}		
				return $price;
				//echo "kaina:".$price."<br>";
				//return 991;
		
			}
			else {
				$nauja_kainele = $kaina_atsargine + ($sutapusi_galune);
				$kaina_atsargine_original = $kaina_atsargine;
				if ($nauja_kainele > 0 ) {
					$kaina_atsargine = $nauja_kainele;					
				}
				else {
					$kaina_atsargine = $kaina_atsargine;					
				}			
				//var_dump("kaina_atsargine2:".$kaina_atsargine);
				//$formatted_price = number_format($kaina_atsargine, $number_of_decimals, $decimal_sep, $thousand_sep);
				$formatted_price = $kaina_atsargine;
				$price = $formatted_price;

				if (sgmnt_lab_check_var($price_ending_id)) {
					$deffault_cat = $testing_product_id."_deff";					
					$price = $formatted_price;
				}
				else {
					$price = $formatted_price;					
				}
			 
				return $price;
				//return 992;
			}
		}
		else {
			return $kaina_sale;
			//return 993;
		}
	}//RETURN_PRICE
	function segment_lab_return_wholesale_price ($price, $_product) {
		global $product;
		global $woocommerce;
		global $lab_support_js;
		//VARIABLES
		$testing_product_id = 			$_product->id;
		$kaina_sale = 					$_product->sale_price;					
		$kaina_regular = 				$_product->regular_price;
		$currency_pos = 				get_option('woocommerce_currency_pos');
		$settings_price_correction = 	get_option('sgmntLab_cfg_correction');
		$settings_price_testing = 		get_option('sgmntLab_cfg_testing');
		
		$number_of_decimals = 			wp_specialchars_decode(stripslashes(get_option('woocommerce_price_num_decimals')), 	ENT_QUOTES);
		$decimal_sep = 					wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), 	ENT_QUOTES);
		$thousand_sep = 				wp_specialchars_decode(stripslashes(get_option('woocommerce_price_thousand_sep')), 	ENT_QUOTES);
		
		$master_settings_control = 		get_option('sgmntLab_cfg_pause');
		if ($master_settings_control == 0 && ($settings_price_correction == 1 || $settings_price_testing == 1)) {
			$price_testing_vals = 		get_post_meta($testing_product_id, 	'sgmntLab_testing_prices_'.$testing_product_id.'_a', 	true);
			$get_testing_code_idas = 	get_post_meta($testing_product_id, 	'sgmntLab_testing_prices_'.$testing_product_id.'_idas', true);
			$get_testing_code = 		get_post_meta( $testing_product_id, 'sgmntLab_testing_prices_'.$testing_product_id.'_code', true);

			$galunes_is_wp_db = 		get_option('sgmntLab_cfg_price_endings');
			$price_ending_id = 			get_option('sgmntLab_cfg_price_endings_idas');
			$testing_admin_check = 		get_option('sgmntLab_cfg_admin_test');

			$price_from_cookie = 		$_COOKIE["snippets_show_code_info"];
			$admin_testing_cookie = 	$_COOKIE["lab_admin_testing"];
			$ad_block = 				$_COOKIE["adblckstatus"]; 
			$user_unique_id = 			$_COOKIE["unique_lab_id"]; 

			$pieces = 					explode(",", $price_testing_vals);
			$pieces_lenght = 			count($pieces);

			$pieces_ending = 			explode(";", $galunes_is_wp_db);
			$pieces_ending_lenght = 	count($pieces_ending);

			if (sgmnt_lab_check_var($price_from_cookie)) {$galunes_cookie_egzist = 1;	} else {	$galunes_cookie_egzist = 0;	}
			if (sgmnt_lab_check_var($galunes_is_wp_db)) {$galunes_egzist = 1;			} else {	$galunes_egzist = 0;		}

			//REMEMBER_CHECK_FOR_FURTHER_INVESTIGATION
			$lab_sutapo_price_remember = 	0;
			$lab_sutapo_ending_remember = 	0;
			if ($galunes_cookie_egzist == 0) {
				$go_check_memory = 	0;
				$go_check_ending = 	0;
				$go_check_price = 	0;
				if (sgmnt_lab_check_var($price_ending_id) && sgmnt_lab_check_var($galunes_is_wp_db)) {
					$ending_id_send = 	$price_ending_id;
					$go_check_memory = 	1;
					$go_check_ending = 	1;
				}
				else {
					$ending_id_send = "stop";
				}

				if (sgmnt_lab_check_var($get_testing_code_idas) && sgmnt_lab_check_var($price_testing_vals)) {
					$price_id_send = 	$get_testing_code_idas;
					$go_check_memory = 	1;
					$go_check_price = 	1;			
				}
				else {
					$price_id_send = "stop";				
				}

				if ($go_check_memory == 1) {
					$lab_mem_stats_result = sgmnt_lab_remembered_stats ($price_id_send, $ending_id_send, $user_unique_id);
					if ($go_check_ending == 1) {
						$rem_ending_result = sanitize_text_field($lab_mem_stats_result['ending']); // ar nelygu noFound
						$lab_sutapo_ending_remember = 0;
						if ($pieces_ending_lenght >= 1) {
							if (sgmnt_lab_check_var($rem_ending_result) && $rem_ending_result !== "noFound") {
								for ($ii=0; $ii < $pieces_ending_lenght-1; $ii++) {
									$values_by_one = sanitize_text_field($pieces_ending[$ii]);								
									if ($values_by_one == $rem_ending_result) {
										$lab_sutapo_ending_remember = 1;		
									}
								}	
							}
						}
					}
					if ($go_check_price == 1) {
						$rem_price_result = sanitize_text_field($lab_mem_stats_result['price']);					
						$lab_sutapo_price_remember = 0;
						if ($pieces_lenght >= 1) {
							if (sgmnt_lab_check_var($rem_price_result) && $rem_price_result !== "noFound") {
								for ($ii=0; $ii < $pieces_lenght; $ii++) {
									$values_by_one = $pieces[$ii];
									if ($values_by_one == $rem_price_result) {
										$lab_sutapo_price_remember = 1;		
									}
								}	
							}
						}
					}
				}			
			}
			//ENDING MODIFICATION
			if (sgmnt_lab_check_var($price_ending_id) && $galunes_egzist == 1 && $galunes_cookie_egzist == 1) {
				//echo "endingas!<br>";
				$price_ending_id =  			$price_ending_id;
				$endingas_tst_basic = 			"ZONEX134|".$price_ending_id."|X091X|";
				$endingas_tst_galas = 			"|X332X193X";
				$endingas_tst_priekis = 		"|X99X0X23|";

				$kaina_is_cookio = 				explode($endingas_tst_basic, $price_from_cookie);
				$kaina_is_cookio = 				explode($endingas_tst_galas, $kaina_is_cookio[1]);
				$kaina_is_cookio = 				explode($endingas_tst_priekis, $kaina_is_cookio[0]);
				$ending_val = 					$kaina_is_cookio[1];	

				$galunes_is_wp_db = 			rtrim($galunes_is_wp_db,"; ");
				$price_endings_array = 			explode(";", $galunes_is_wp_db);
				$price_endings_array_lenght = 	count($price_endings_array);
				$sutapusi_galune = 				"";
				$faund_val = 					0;
				
				for ($is=0; $is < $price_endings_array_lenght; $is++) {
					$galune_single = 	$price_endings_array[$is];
					$ending_val = 		str_replace(' ', '+', $ending_val);
					if ($galune_single == $ending_val) {
						$faund_val = 1;
						$sutapusi_galune = $galune_single;
						break;
					}
				}
				if ($faund_val == 0) {
					if ($lab_sutapo_ending_remember == 1) {
						$faund_val = 0;
						for ($is=0; $is < $price_endings_array_lenght; $is++) {
							$galune_single = $price_endings_array[$is];
							$ending_val = $rem_ending_result;
							if ($galune_single == $ending_val) {
								$faund_val = 1;
								$sutapusi_galune = $galune_single;
								break;
							}
						}
						if ($faund_val == 0) {
							$sutapusi_galune = 0;
						}					
					} 
					else {
						$sutapusi_galune = 0;					
					}				
				}
			}
			else {
				if ($galunes_egzist == 1 && $galunes_cookie_egzist == 1){
					if ($lab_sutapo_ending_remember == 1) {
						$faund_val = 0;
						for ($is=0; $is < $price_endings_array_lenght; $is++) {
							$galune_single = $price_endings_array[$is];
							$ending_val = $rem_ending_result;
							if ($galune_single == $ending_val) {
								$faund_val = 1;
								$sutapusi_galune = $galune_single;
								break;
							}
						}
						if ($faund_val == 0) {
							$sutapusi_galune = 0;
						}
					}
				}
				else {
					$ending_val =  0;
					$sutapusi_galune = 0;
				}
			}				

			if (is_admin() || is_admin_bar_showing()) { //chekina tik konkreciai ar admin meniu
				if (sgmnt_lab_check_var($testing_admin_check)) {
					$testing_admin_status = $testing_admin_check;
				}
				else {
					$testing_admin_status = 0; //dar padaryt gal checka visur su reiksmem, kur zinom.... if 1 || if 0 or .... kad jei kokia kitokia ateina tai neapsigautu sistema ir parodytu 0 pvz.....
				}
			}
			else {
				if (sgmnt_lab_check_var($admin_testing_cookie)) {
					$testing_admin_status = $admin_testing_cookie;
				} 
				else {
					$testing_admin_status = 0;
				}
			}

			$admin_status_check = $testing_admin_status; //0-off_1-on;ar admin paneleje ar pagrindiniame lauke
			if ($admin_status_check == 1) {$admin_status = 1;}
			else {
				if (!is_admin()) {// sitas ka daro admin????? tuscia reiksmee, pareina?!
					$admin_status = 0;//ne adminas
				}
				else {
					$admin_status = 1;//adminas
				}		
			}		

			if ($admin_status == 0) {
				echo '<script type="text/javascript" src="'.$lab_support_js.'"></script>';
			}
			if (sgmnt_lab_check_var($kaina_sale)) {$kaina_atsargine = $kaina_sale;}
			else if (sgmnt_lab_check_var($kaina_regular)) {$kaina_atsargine = $kaina_regular;}
			else {$kaina_atsargine = "error";}
			/////////

			if (sgmnt_lab_check_var($price_testing_vals)) {
				$pieces = explode(",", $price_testing_vals);
				$pieces_lenght = count($pieces);
				if ($kaina_atsargine !== "error" && $kaina_atsargine == $pieces[0]) {
					//$kaina_atsargine = $pieces[0];		nereikia, kam??
				}
				else {
					//$kaina_atsargine = "error";
				}

				if (sgmnt_lab_check_var($get_testing_code) && sgmnt_lab_check_var($get_testing_code_idas)) {
					$sistema = 0;
					if (sgmnt_lab_check_var($ad_block)) {
						if ($ad_block == "off") {
							$sistema = 1;
						}
						else {
							$sistema = 0;//rodo default sale price
						}
					}
					else {
						$sistema = 0;//rodo default sale price
					}				
					if (sgmnt_lab_check_var($price_from_cookie)) {
						$karpymui_tst_basic = 	"ZONEX134|".$get_testing_code_idas."|X091X|";
						$karpymui_tst_galas = 	"|X332X193X";
						$karpymui_tst_priekis = "|X99X0X23|";

						$kaina_is_cookio = explode($karpymui_tst_basic, $price_from_cookie);
						$kaina_is_cookio = explode($karpymui_tst_galas, $kaina_is_cookio[1]);
						$kaina_is_cookio = explode($karpymui_tst_priekis, $kaina_is_cookio[0]);
						$kaina_is_cookio = $kaina_is_cookio[1];

						$script_price = 0; //price check	
						if ($pieces_lenght >= 1) {
							for ($i=0; $i < $pieces_lenght; $i++) {
								$values_by_one = $pieces[$i];
								if ($values_by_one == $kaina_is_cookio) {
									$script_price = 1;					
								}
							}									

							if ($admin_status == 0) {
								if ($sistema == 1) {								
									if ($script_price == 1) {
										$dedam = $kaina_is_cookio;
									}
									else {
										$sutapo_remember = 0;
										if ($lab_sutapo_price_remember == 1) {
											if ($pieces_lenght >= 1) {
												if (sgmnt_lab_check_var($rem_price_result)) {
													for ($ii=0; $ii < $pieces_lenght; $ii++) {
														$values_by_one = $pieces[$ii];
														if ($values_by_one == $rem_price_result) {
															$sutapo_remember = 1;						
														}
													}	
												}
											}
										}										 

										if ($sutapo_remember == 1) {
											//patikrinti ar su endingu ok, jei ok rodom su, dar check sutapusi galune ar ok ar ne... ti tada ieskom
											$dedam = $rem_price_result;
										}
										else {
											$dedam = $kaina_atsargine;
										}
									}

									$modified = 	$dedam + ($sutapusi_galune);
									$modified = 	number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
									//$get_testing_code_to_show = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
									$get_testing_code_to_show = '<span class="customizable_cd_'.$get_testing_code_idas.'" name="0" data-lab-section="price" data-lab-deff="'.$dedam.'" data-lab-endcode="'.$sutapusi_galune.'" data-lab-ending="'.$price_ending_id.'" data-lab-category="plugin" data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:inline;">'.$modified.'</span>';
									$price = $get_testing_code_to_show;
								}
								else {
									if ($script_price == 1) {
										$dedam = $kaina_is_cookio;
									}
									else {
										$sutapo_remember = 0;
										if ($lab_sutapo_price_remember == 1) {
											if ($pieces_lenght >= 1) {
												if (sgmnt_lab_check_var($rem_price_result)) {
													for ($ii=0; $ii < $pieces_lenght; $ii++) {
														$values_by_one = $pieces[$ii];
														if ($values_by_one == $rem_price_result) {
															$sutapo_remember = 1;						
														}
													}	
												}
											}
										}		

										if ($sutapo_remember == 1) {
											$dedam = $rem_price_result;
										}
										else {
											$dedam = $kaina_atsargine;
										}									
									}
									$modified = $dedam + ($sutapusi_galune);
									$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
									//$get_testing_code = $modified;
									//$get_testing_code = number_format($dedam, $number_of_decimals, $decimal_sep, $thousand_sep);
									//$price = $get_testing_code;
									$price = $modified;
								}				
							}
							else {
								if ($script_price == 1) {
									$dedam = $kaina_is_cookio;
								}
								else {
									$sutapo_remember = 0;
									if ($lab_sutapo_price_remember == 1) {
										if ($pieces_lenght >= 1) {
											if (sgmnt_lab_check_var($rem_price_result)) {
												for ($ii=0; $ii < $pieces_lenght; $ii++) {
													$values_by_one = $pieces[$ii];
													if ($values_by_one == $rem_price_result) {
														$sutapo_remember = 1;						
													}
												}	
											}
										}
									}		

									if ($sutapo_remember == 1) {
										$dedam = $rem_price_result;
									}
									else {
										$dedam = $kaina_atsargine;
									}
								}

								$modified = $dedam + ($sutapusi_galune);
								$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
								$price = $modified;
							}
						}
						else {
							$modified = $kaina_atsargine + ($sutapusi_galune);
							$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);
							$price = $modified;				
						}
					}
					else {
						if ($admin_status == 0) {
							if ($sistema == 1) {
							   	$sutapo_remember = 0;
							   	if ($lab_sutapo_price_remember == 1) {
								  if ($pieces_lenght >= 1) {
									  if (sgmnt_lab_check_var($rem_price_result)) {
										  for ($ii=0; $ii < $pieces_lenght; $ii++) {
											  $values_by_one = $pieces[$ii];
											  if ($values_by_one == $rem_price_result) {
												  $sutapo_remember = 1;						
											  }
										  }	
									  }
								  }
							   	}						
							   	if ($sutapo_remember == 1) {
									$dedam = $rem_price_result;
							   	}
							   	else {
									$dedam = $kaina_atsargine;
							   	}
							   	$modified = $dedam + ($sutapusi_galune);
							   	$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);

							   	$get_testing_code_show = $modified;

							   	$price_ending_id = get_option('sgmntLab_cfg_price_endings_idas');
								if (sgmnt_lab_check_var($price_ending_id)) {
									$price_ending_id =  $price_ending_id;
								}
								else {
									$price_ending_id =  "noFound";
								}

							   $get_testing_code_show = '<span class="customizable_cd_'.$get_testing_code_idas.'" name="0" data-lab-section="price" data-lab-deff="'.$dedam.'" data-lab-endcode="'.$sutapusi_galune.'" data-lab-ending="'.$price_ending_id.'"  data-lab-category="plugin"  data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:inline;">'.$get_testing_code_show.'</span>';
							   $price = $get_testing_code_show;
							}
							else {
								$sutapo_remember = 0;
								if ($lab_sutapo_price_remember == 1) {
									if ($pieces_lenght >= 1) {
									  if (sgmnt_lab_check_var($rem_price_result)) {
										  for ($ii=0; $ii < $pieces_lenght; $ii++) {
											  $values_by_one = $pieces[$ii];
											  if ($values_by_one == $rem_price_result) {
												  $sutapo_remember = 1;						
											  }
										  }	
									  }
									}
								}
								if ($sutapo_remember == 1) {
									$dedam = $rem_price_result;
								}
								else {
									$dedam = $kaina_atsargine;
								}

								$modified = $dedam + ($sutapusi_galune);
								$modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);

								$get_testing_code_show = $modified;

								$price_ending_id = get_option('sgmntLab_cfg_price_endings_idas');
								if (sgmnt_lab_check_var($price_ending_id)) {
									$price_ending_id =  $price_ending_id;
								}
								else {
									$price_ending_id =  "noFound";
								}

								$get_testing_code_show = '<span class="customizable_cd_'.$get_testing_code_idas.'" name="0" data-lab-section="price" data-lab-deff="'.$kaina_atsargine.'" data-lab-ending="'.$price_ending_id.'" data-lab-endcode="'.$sutapusi_galune.'"  data-lab-category="plugin" data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:none;">'.$get_testing_code_show.'</span>';
								$price = $get_testing_code_show;
						   }
						}
						else {
						   $sutapo_remember = 0;
						   if ($lab_sutapo_price_remember == 1) {
							  if ($pieces_lenght >= 1) {
								  if (sgmnt_lab_check_var($rem_price_result)) {
									  for ($ii=0; $ii < $pieces_lenght; $ii++) {
										  $values_by_one = $pieces[$ii];
										  if ($values_by_one == $rem_price_result) {
											  $sutapo_remember = 1;						
										  }
									  }	
								  }
							  }
						   }

						   if ($sutapo_remember == 1) {
								$dedam = $rem_price_result;
						   }
						   else {
								$dedam = $kaina_atsargine;
						   }
						   $modified = $dedam + ($sutapusi_galune);
						   $modified = number_format($modified, $number_of_decimals, $decimal_sep, $thousand_sep);

						   $price = $modified;
						}
					}
				}
				else {				
					$nauja_kainele = $kaina_atsargine + ($sutapusi_galune);
					$kaina_atsargine_original = $kaina_atsargine;
					if ($nauja_kainele > 0 ) {
						$kaina_atsargine = $nauja_kainele;					
					}
					else {
						$kaina_atsargine = $kaina_atsargine;					
					}

					$formatted_price = number_format($kaina_atsargine, $number_of_decimals, $decimal_sep, $thousand_sep);
					$price = $formatted_price;

					if (sgmnt_lab_check_var($price_ending_id)) {
						$deffault_cat = $testing_product_id."_deff";					
						$formatted_price = '<span class="customizable_cd_'.$deffault_cat.'" name="0" data-lab-section="deff" data-lab-deff="'.$kaina_atsargine_original.'" data-lab-ending="'.$price_ending_id.'"  data-lab-endcode="'.$sutapusi_galune.'" data-lab-category="plugin"  data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:inline;">'.$formatted_price.'</span>';
						$price = $formatted_price;
					}
					else {
						$price = $formatted_price;					
					}
				}
				
				
				$pradine_be_akcijos = "";
				if (sgmnt_lab_check_var($kaina_regular)) {
					$kaina_regular = number_format($kaina_regular, $number_of_decimals, $decimal_sep, $thousand_sep);
					if (sgmnt_lab_check_var($kaina_sale)) {
						switch ($currency_pos) {
							case 'left' :
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.$kaina_regular.'</span></del>';
							break;
							case 'right' :
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount">'.$kaina_regular.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span></del>';
							break;
							case 'left_space' :							
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>&nbsp;'.$kaina_regular.'</span></del>';
							break;
							case 'right_space' :
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount">'.$kaina_regular.'&nbsp;<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span></del>';
							break;
						}
					}
				}

				if ($pradine_be_akcijos == "" || $pradine_be_akcijos == " " || $pradine_be_akcijos == NULL) {
					$ins_start = "";
					$ins_end = "";
				}
				else {
					$ins_start = "<ins>";
					$ins_end = "</ins>";
				}
				
				$price_by_pos = "";
				switch ( $currency_pos ) {
					case 'left' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.$price.'</span>'.$ins_end;
					break;
					case 'right' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount">'.$price.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>'.$ins_end;
					break;
					case 'left_space' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>&nbsp;'.$price.'</span>'.$ins_end;
					break;
					case 'right_space' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount">'.$price.'&nbsp;<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>'.$ins_end;
					break;
				}

				$price = '<span class="price">' .$pradine_be_akcijos.' '.$price_by_pos.' </span>';
				return $price;
			}
			else {
				$nauja_kainele = $kaina_atsargine + ($sutapusi_galune);
				$kaina_atsargine_original = $kaina_atsargine;
				if ($nauja_kainele > 0 ) {
					$kaina_atsargine = $nauja_kainele;					
				}
				else {
					$kaina_atsargine = $kaina_atsargine;					
				}			

				$formatted_price = number_format($kaina_atsargine, $number_of_decimals, $decimal_sep, $thousand_sep);
				$price = $formatted_price;

				if (sgmnt_lab_check_var($price_ending_id)) {
					$deffault_cat = $testing_product_id."_deff";					
					$formatted_price = '<span class="customizable_cd_'.$deffault_cat.'" name="0" data-lab-section="deff" data-lab-deff="'.$kaina_atsargine_original.'" data-lab-endcode="'.$sutapusi_galune.'" data-lab-ending="'.$price_ending_id.'" data-lab-category="plugin"  data-lab-platform="wordpress" data-lab-decimals ="'.$number_of_decimals.'" data-lab-decimalsep ="'.$decimal_sep.'" data-lab-thousandsep ="'.$thousand_sep.'" style="display:inline;">'.$formatted_price.'</span>';
					$price = $formatted_price;
				}
				else {
					$price = $formatted_price;					
				}

				
				$pradine_be_akcijos = "";
				if (sgmnt_lab_check_var($kaina_regular)) {
					$kaina_regular = number_format($kaina_regular, $number_of_decimals, $decimal_sep, $thousand_sep);
					if (sgmnt_lab_check_var($kaina_sale)) {
						switch ($currency_pos) {
							case 'left' :
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.$kaina_regular.'</span></del>';
							break;
							case 'right' :
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount">'.$kaina_regular.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span></del>';
							break;
							case 'left_space' :							
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>&nbsp;'.$kaina_regular.'</span></del>';
							break;
							case 'right_space' :
								$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount">'.$kaina_regular.'&nbsp;<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span></del>';
							break;
						}
					}
				}
				
				if ($pradine_be_akcijos == "" || $pradine_be_akcijos == " " || $pradine_be_akcijos == NULL) {
					$ins_start = "";
					$ins_end = "";
				}
				else {
					$ins_start = "<ins>";
					$ins_end = "</ins>";
				}
				
				$price_by_pos = "";
				switch ( $currency_pos ) {
					case 'left' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.$price.'</span>'.$ins_end;
					break;
					case 'right' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount">'.$price.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>'.$ins_end;
					break;
					case 'left_space' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>&nbsp;'.$price.'</span>'.$ins_end;
					break;
					case 'right_space' :
						$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount">'.$price.'&nbsp;<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>'.$ins_end;
					break;
				}

				$price = '<span class="price">' .$pradine_be_akcijos.' '.$price_by_pos.' </span>';				
				return $price;
			}
		}
		else {			
			if (sgmnt_lab_check_var($kaina_sale)) {$kaina_atsargine = $kaina_sale;}
			else if (sgmnt_lab_check_var($kaina_regular)) {$kaina_atsargine = $kaina_regular;}
			else {$kaina_atsargine = "error";}
			
			
			$formatted_sale = 		number_format($kaina_atsargine, $number_of_decimals, $decimal_sep, $thousand_sep);
			
			$pradine_be_akcijos = 	"";
			if (sgmnt_lab_check_var($kaina_regular)) {
				$kaina_regular = number_format($kaina_regular, $number_of_decimals, $decimal_sep, $thousand_sep);
				if (sgmnt_lab_check_var($kaina_sale)) {
					switch ($currency_pos) {
						case 'left' :
							$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.$kaina_regular.'</span></del>';
						break;
						case 'right' :
							$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount">'.$kaina_regular.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span></del>';
						break;
						case 'left_space' :							
							$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>&nbsp;'.$kaina_regular.'</span></del>';
						break;
						case 'right_space' :
							$pradine_be_akcijos = '<del><span class="woocommerce-Price-amount amount">'.$kaina_regular.'&nbsp;<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span></del>';
						break;
					}
				}
			}
			
			if ($pradine_be_akcijos == "" || $pradine_be_akcijos == " " || $pradine_be_akcijos == NULL) {
				$ins_start = "";
				$ins_end = "";
			}
			else {
				$ins_start = "<ins>";
				$ins_end = "</ins>";
			}
			
			$price_by_pos = "";
			switch ( $currency_pos ) {
				case 'left' :
					$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.$formatted_sale.'</span>'.$ins_end;
				break;
				case 'right' :
					$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount">'.$formatted_sale.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>'.$ins_end;
				break;
				case 'left_space' :
					$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>&nbsp;'.$formatted_sale.'</span>'.$ins_end;
				break;
				case 'right_space' :
					$price_by_pos = $ins_start.'<span class="woocommerce-Price-amount amount">'.$formatted_sale.'&nbsp;<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>'.$ins_end;
				break;
			}
			
			$price = '<span class="price">' .$pradine_be_akcijos.' '.$price_by_pos.' </span>';
			return $price;
		}
	}//RETURN_WHOLESALE_PRICE	
}//GENERAL CLASS
			
	

?>