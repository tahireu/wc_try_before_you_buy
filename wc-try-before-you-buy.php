<?php
/**
 * Plugin Name:     WC Try Before You Buy
 * Plugin URI:      https://github.com/tahireu/wc_try_before_you_buy
 * Description:     WooCommerce support for "Try Before You Buy" type of web shops - Prepare cart content for your customers from admin side and allow them to choose whether they will keep or return the product.
 * Version:         1.0.0
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
    if( !defined( 'WC_TRY_BEFORE_YOU_BUY_VERSION' ) ) {
        define( 'WC_TRY_BEFORE_YOU_BUY_VERSION', '1.0.0' );
    }



    /*
     * Create database table on plugin activation
     * */
    function create_table(){
        TBYB_activator::create_table();
    }

    register_activation_hook( __FILE__, 'create_table' );




    /*
     * Do the work
     * */
    require plugin_dir_path( __FILE__ ) . 'functions.php';

    if ( is_admin() ) {
        require plugin_dir_path(__FILE__) . 'admin/class-tbyb-admin.php';
        TBYB_admin::on_load();
    }

    require plugin_dir_path(__FILE__) . 'public/class-tbyb-public.php';
    TBYB_public::on_load();


} else {

    /*
     * Abort and display info message
     * */
    TBYB_activator::abort();

}