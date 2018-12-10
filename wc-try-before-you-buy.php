<?php
/**
 * Plugin Name:     WC Try Before You Buy
 * Plugin URI:      https://github.com/tahireu/wc_try_before_you_buy
 * Description:     Fill your customer's cart with products you'll send them by mail, and allow them to choose whether they will keep or return the product, and to leave you feedback about eventual return reason.
 * Version:         1.0.1
 * Author:          Tahireu
 * Author URI:      https://github.com/tahireu/
 * License:         GPLv2 or later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
 */


/*
 * Prevent intruders from sneaking around
 * */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


/*
 * Variables
 * */
const TBYB_TEXT_DOMAIN = "wc-try-before-you-buy";


/*
 * Load TBYB_Activator class before WooCommerce check
 * */
require plugin_dir_path( __FILE__ ) . 'includes/class-tbyb-activator.php';



/*
 * Check if WooCommerce is installed and active
 * */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {


    /*
     * Current plugin version - https://semver.org
     * This should be updated as new versions are released
     * */
    if( !defined( 'TBYB_VERSION' ) ) {
        define( 'TBYB_VERSION', '1.0.1' );
    }



    /*
     * Create database table on plugin activation
     * */
    function tbyb_create_table(){
        TBYB_activator::tbyb_create_table();
    }

    register_activation_hook( __FILE__, 'tbyb_create_table' );




    /*
     * Do the work
     * */
    require plugin_dir_path( __FILE__ ) . 'functions.php';

    if ( is_admin() ) {
        require plugin_dir_path(__FILE__) . 'admin/class-tbyb-admin.php';
        TBYB_admin::tbyb_on_load();
    }

    require plugin_dir_path(__FILE__) . 'public/class-tbyb-public.php';
    TBYB_public::tbyb_on_load();


} else {

    /*
     * Abort and display info message
     * */
    TBYB_activator::tbyb_abort();

}