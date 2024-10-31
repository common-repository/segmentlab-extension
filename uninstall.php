<?php
/*
 * SegmentLab Uninstall
 *
 * Uninstalling SegmentLab deletes user roles, pages, tables, and options.
 *
 * @author      SegmentLab
 * @category    Plugin
 * @package     SegmentLab/Uninstaller
 * @version     1.3.1
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

global $current_user;
global $thepostid;
global $woocommerce;
global $wpdb;

//Delete irrelevant data
//delete options
$delete_options_vars = array();
$delete_options_vars[] = "sgmntLab_cfg_testing";
$delete_options_vars[] = "sgmntLab_cfg_reg";
$delete_options_vars[] = "sgmntLab_cfg_connection";
$delete_options_vars[] = "sgmntLab_cfg_sell";
$delete_options_vars[] = "sgmntLab_cfg_correction";
$delete_options_vars[] = "sgmntLab_cfg_admin_test";
$delete_options_vars[] = "sgmntLab_cfg_price_endings_idas";
$delete_options_vars[] = "sgmntLab_cfg_plugin_noti";
$delete_options_vars[] = "sgmntLab_cfg_price_endings";
$delete_options_vars[] = "sgmntLab_cfg_relink";
$delete_options_vars[] = "sgmntLab_cfg_relink_date";
$delete_options_vars[] = "sgmntLab_cfg_settings_reg_mail";
$delete_options_vars[] = "sgmntLab_cfg_settings_menu";
$delete_options_vars[] = "sgmntLab_cfg_settings_text";
$delete_options_vars[] = "sgmntLab_cfg_plugin_status";
$delete_options_vars[] = "sgmntLab_cfg_pause";
$delete_options_vars[] = "sgmntLab_cfg_library";
$delete_options_vars[] = "sgmntLab_cfg_endingstable";


foreach ($delete_options_vars as $delete_options_var) {
	$optionas_vienas = get_option($delete_options_var);
	if ( isset($optionas_vienas) ) {
		delete_option($delete_options_var);
	}
}

$users = 					get_users();
$delete_user_meta_vars = 	array();
$delete_user_meta_vars[] = "sgmntLab_user_key_castle";
$delete_user_meta_vars[] = "sgmntLab_user_cashier_level";
$delete_user_meta_vars[] = "sgmntLab_user_key_lab";
$delete_user_meta_vars[] = "sgmntLab_user_key_status";
$delete_user_meta_vars[] = "sgmntLab_user_key_testing_ids";
$delete_user_meta_vars[] = "sgmntLab_user_registration_status";
foreach ($users as $user) {
	$check_existance = get_user_meta($user->id, 'sgmntLab_user_key_castle');
	if (isset($check_existance)) {
		$data_castle_key = 	get_user_meta($user->id, 'sgmntLab_user_key_castle', true );
		$data_lab_key = 	get_user_meta($user->id, 'sgmntLab_user_key_lab', true );
		$data_castle_link = $_SERVER[HTTP_HOST];
		
		//delete post meta
		$data_products_key = get_user_meta($user->id, 'sgmntLab_user_key_testing_ids', true );
		if (isset($data_products_key) && $data_products_key !== "" && $data_products_key !== " " && $data_products_key !== NULL) {
			$explode_products_array = explode(";", $data_products_key);
			foreach ($explode_products_array as $explode_product) {
				$testing_prices_a = 		get_post_meta($explode_product, 'sgmntLab_testing_prices_'.$explode_product.'_a', true);
				$testing_prices_code = 		get_post_meta($explode_product, 'sgmntLab_testing_prices_'.$explode_product.'_code', true);
				$testing_prices_idas = 		get_post_meta($explode_product, 'sgmntLab_testing_prices_'.$explode_product.'_idas', true);
				if (isset($testing_prices_a)) {
					delete_post_meta($explode_product, 'sgmntLab_testing_prices_'.$explode_product.'_a');
				}
				if (isset($testing_prices_code)) {
					delete_post_meta($explode_product, 'sgmntLab_testing_prices_'.$explode_product.'_code');
				}
				if (isset($testing_prices_idas)) {
					delete_post_meta($explode_product, 'sgmntLab_testing_prices_'.$explode_product.'_idas');
				}				
			}
		}
		//delete user meta
		foreach ( $delete_user_meta_vars as $delete_user_meta_var ) {
			$get_user_id = get_user_meta($user->id, $delete_user_meta_var);
			if (isset($get_user_id)) {
				delete_user_meta($user->id, $delete_user_meta_var);
			}
		}
	}
				
}
