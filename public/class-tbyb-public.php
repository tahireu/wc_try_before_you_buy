<?php
/**
 * Prevent intruders from sneaking around
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

session_start();


/**
 * WC Try Before You Buy Public class
 */

class TBYB_public
{

    const FORM_ID = "tbyb-return-reason-form";



    public static function tbyb_on_load()
    {
        add_action('plugins_loaded', array(__CLASS__, 'init'));
    }



    public static function init()
    {
        /* Load scripts */
        add_action('wp_enqueue_scripts', array(__CLASS__, 'tbyb_load_scripts'));

        /* Render return reasons form outside the main form */
        add_filter('wp_footer', array(__CLASS__, 'tbyb_render_return_reasons_form'));

        /* Display return reasons select buttons if RETURN is selected */
        add_filter('woocommerce_cart_item_remove_link', array(__CLASS__, 'tbyb_display_return_reasons_select_box'));

        /* AJAX catch, process and return form POST data */
        add_action('wp_ajax_return_reason', array(__CLASS__, 'tbyb_select_return_reason'));

        /* Add data processed in previous step to cart item meta data */
        add_filter('woocommerce_get_cart_item_from_session', array(__CLASS__, 'tbyb_update_cart_items_data'), 10, 3);

        /* Display updated cart items meta data on cart and checkout page */
        add_filter('woocommerce_get_item_data', array(__CLASS__, 'tbyb_display_updated_cart_items_data'), 10, 2);

        /* Set price to be 0 for returned items */
        add_filter('woocommerce_before_calculate_totals', array(__CLASS__, 'tbyb_add_custom_price'));

        /* Set returned items price to 0 in mini cart */
        add_filter('woocommerce_cart_item_price', array(__CLASS__, 'tbyb_add_custom_price_mini_cart'), 10, 3 );

        /* Display updated cart items meta data in orders, emails, admin orders... */
        add_action('woocommerce_add_order_item_meta', array(__CLASS__, 'tbyb_save_in_order_item_meta'), 10, 3);

        /* Add to cart on user login */
        add_action('wp', array(__CLASS__, 'tbyb_add_to_cart'));

        /* Delete from prepared items table on order submit */
        add_action('save_post_shop_order', array(__CLASS__, 'tbyb_delete_from_prepared_items_table'));

        /* Override Woocommerce Quantity Field template */
        add_filter('woocommerce_locate_template', array(__CLASS__, 'tbyb_override_quantity_field_template'), 10, 3 );

        /* Redirect users */
        add_filter('template_redirect', array(__CLASS__, 'tbyb_redirect_users'));

    }




    /*
     * Load class scripts
     * */
    public static function tbyb_load_scripts()
    {
        /* CSS */
        wp_enqueue_style('tbyb-public-css', plugins_url('/css/tbyb-public.css', __FILE__));

        /* JS */
        wp_enqueue_script('tbyb-public-js', plugins_url('/js/tbyb-public.js', __FILE__));

        /* AJAX */
        wp_localize_script('tbyb-public-js', 'tbyb_public_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }



    /**
     * Render form in footer, outside of WP Cart main form, to prevent form nesting
     * This form's fields are located in "Cart Item Remove" section, and they are connected with this form with "form" property - https://www.w3schools.com/tags/att_form.asp
     * */
    public static function tbyb_render_return_reasons_form()
    {

        if (is_page('cart') || is_cart()) {
            echo "
        <input  type='hidden' name='action' value='return_reason' form=" . self::FORM_ID . " />
        <form type='hidden' method='POST' id=" . self::FORM_ID . "></form>
        <button type='submit' name='submit' class='tbyb-submit-return-reason' form=" . self::FORM_ID . ">" . __('Submit reason', TBYB_TEXT_DOMAIN) . "</button>
        ";
        }
    }



    /*
     * Return reasons buttons HTML and behavior
     * */
    public static function tbyb_display_return_reasons_select_box($cart_item_key)
    {
        /* Create unique input "name" for each cart item out of item "key" */
        $full_string = $cart_item_key;
        $parsed = tbyb_get_string_between($full_string, 'remove_item=', '_wpnonce=');
        $parsed = substr($parsed, 0, -6);

        $visibility_class = $Keep = $Unselected = $Style = $Price = $Fit = $Quality = '';


        foreach (WC()->cart->get_cart() as $key => $cart_item) {
            if ($key == $parsed) {
                if (isset ($cart_item['return_data']['value'])) {
                    ${$cart_item['return_data']['value']} = 'checked';

                    if ($cart_item['return_data']['value'] == 'Style' ||
                        $cart_item['return_data']['value'] == 'Price' ||
                        $cart_item['return_data']['value'] == 'Fit' ||
                        $cart_item['return_data']['value'] == 'Quality' ||
                        $cart_item['return_data']['value'] == 'Unselected'
                    ) {
                        $visibility_class = 'visible';
                        $Unselected = 'checked';
                    }

                } else {
                    $Keep = 'checked';
                }
            }
        }


        echo "
        <ul class='tbyb-return-options-holder $visibility_class'>
        
            <li class='tbyb-return-options tbyb-main-option'>
                <ul>
                    <li>
                        <input type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-1' " . $Keep . " value='Keep' form=" . self::FORM_ID . " >
                        <label for='reason-" . $parsed . "-1' class='" . $Keep . "'>" . __('Keep', TBYB_TEXT_DOMAIN) . "</label>
                    </li>
                    <li>
                        <input type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-2' " . $Unselected . " value='Unselected' form=" . self::FORM_ID . " >
                        <label for='reason-" . $parsed . "-2' class='" . $Unselected . "'>" . __('Return', TBYB_TEXT_DOMAIN) . "</label>
                    </li>
                    <!--<li>
                        <input  type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-3' value='Exchange' form=" . self::FORM_ID . " disabled >
                        <label for='reason-" . $parsed . "-3'>" . __('Exchange', TBYB_TEXT_DOMAIN) . "</label>
                    </li>-->
                </ul>
            </li>
            
            <li class='tbyb-return-options tbyb-return-reasons'>
            <span class='tbyb-why-didnt-you'>" . __('Why Didn\'t You Like It?', TBYB_TEXT_DOMAIN) . "</span>
                <ul>
                    <li>
                        <input type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-4' " . $Style . "  value='Style' form=" . self::FORM_ID . " >
                        <label for='reason-" . $parsed . "-4' class='$Style'>" . __('Style', TBYB_TEXT_DOMAIN) . "</label>
                    </li>
                    <li>
                        <input type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-5' " . $Price . " value='Price' form=" . self::FORM_ID . " >
                        <label for='reason-" . $parsed . "-5' class='" . $Price . "'>" . __('Price', TBYB_TEXT_DOMAIN) . "</label>
                    </li>
                    <li>
                        <input type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-6' " . $Fit . " value='Fit' form=" . self::FORM_ID . " >
                        <label for='reason-" . $parsed . "-6' class='" . $Fit . "'>" . __('Fit', TBYB_TEXT_DOMAIN) . "</label>
                    </li>
                    <li>
                        <input type='radio' name='" . $parsed . "' id='reason-" . $parsed . "-7' " . $Quality . " value='Quality' form=" . self::FORM_ID . " >
                        <label for='reason-" . $parsed . "-7' class='" . $Quality . "'>" . __('Quality', TBYB_TEXT_DOMAIN) . "</label>
                    </li>
                </ul>
            </li>
        </ul>
        ";
    }



    /*
     * Catch return reason sent by AJAX and save it to php session variable
     * */
    public static function tbyb_select_return_reason()
    {

        $i = 0;

        $return_reasons = array();

        $cart_items = WC()->cart->get_cart();


        foreach ($cart_items as $cart_item_key => $cart_item) {

            array_push($return_reasons, sanitize_text_field(tbyb_prepare($_POST[$cart_item_key])));

            foreach ($return_reasons as $reason_key => $return_reason) {
                if ($i == $reason_key) {
                    if (!$return_reason == '') {
                        $item_id = $cart_item['key'];
                        $_SESSION += [$item_id => $return_reason];
                    }
                }
            }

            $i++;

        }

        wp_die();
    }



    /*
     * Update cart items data
     * */
    public static function tbyb_update_cart_items_data($item, $cart_item, $key)
    {
        if (isset($_SESSION[$key])) {
            $item['return_data']['label'] = "<span class='tbyb-returned'>" . __('RETURNED', TBYB_TEXT_DOMAIN) . "</span>" . __(' - Reason', TBYB_TEXT_DOMAIN) . "";
            $item['return_data']['value'] = $_SESSION[$key];
            unset($_SESSION[$key]);
        }
        return $item;
    }



    /*
     * Display items custom fields label and value in cart and checkout pages
     * */
    public static function tbyb_display_updated_cart_items_data($cart_data, $cart_item)
    {
        $custom_items = array();

        if (!empty($cart_data)) {
            $custom_items = $cart_data;
        }

        if (isset($cart_item['return_data']) && ($cart_item['return_data']['value'] !== 'Keep')) {

            $custom_items[] = array(
                'name' => $cart_item['return_data']['label'],
                'value' => $cart_item['return_data']['value'],
            );
        }

        return $custom_items;
    }



    /*
     * Set returned items price to 0 on cart page
     * */
    public static function tbyb_add_custom_price($cart_obj)
    {

        /* This is necessary for WC 3.0+ */
        if (is_admin() && !defined('DOING_AJAX'))
            return;

        /* Avoiding hook repetition (when using price calculations for example) */
        if (did_action('woocommerce_before_calculate_totals') >= 2)
            return;

        /* Loop through cart items */
        foreach ($cart_obj->get_cart() as $cart_item) {

            if (isset($cart_item['return_data']['value'])
                && $cart_item['return_data']['value'] !== NULL
                && $cart_item['return_data']['value'] !== 'Keep') {
                $cart_item['data']->set_price(0);
            }
        }
    }



    /*
     * Set returned items price to 0 in mini cart
     * */
    public static function tbyb_add_custom_price_mini_cart( $price, $cart_item, $cart_item_key ) {
        $returned_item_price = $cart_item['data']->get_price();

        if (isset($cart_item['return_data']['value'])
            && $cart_item['return_data']['value'] !== NULL
            && $cart_item['return_data']['value'] !== 'Keep') {
            $returned_item_price = 0;
        }

        return get_woocommerce_currency_symbol() . $returned_item_price;
    }



    /*
     * Save item custom fields label and value as order item meta data
     * */
    public static function tbyb_save_in_order_item_meta($item_id, $values, $cart_item_key)
    {
        if (isset($values['return_data']) && ($values['return_data']['value'] !== 'Keep')) {
            wc_add_order_item_meta($item_id, $values['return_data']['label'], $values['return_data']['value']);
        }

        session_destroy();
    }



    /*
     * Add items to customer's cart on customer's login
     */
    public static function tbyb_add_to_cart()
    {
        if ( ! is_admin() ) {

            global $wpdb;
            global $woocommerce;

            $current_user = wp_get_current_user();
            if (!$current_user->exists()) {
                return;
            }

            $query = "SELECT * FROM {$wpdb->prefix}tbyb_prepared_items WHERE user_id = %s AND imported_to_cart = %s";
            $query = $wpdb->prepare($query, $current_user->id, 0);
            $results = $wpdb->get_results($query);


            if (isset($results)) {
                foreach ($results as $result) {
                    $woocommerce->cart->add_to_cart(intval($result->product_id), intval($result->quantity), intval($result->variation_id));
                    $query = "UPDATE {$wpdb->prefix}tbyb_prepared_items SET imported_to_cart = %s WHERE product_id = %s AND user_id = %s";
                    $query = $wpdb->prepare($query, 1, intval($result->product_id), $current_user->id);
                    $wpdb->get_results($query);
                }
            }
        }
    }



    /*
     * Delete from prepared items table on order submit
     * */
    public static function tbyb_delete_from_prepared_items_table()
    {

        global $wpdb;

        $current_user = wp_get_current_user();
        if ( ! $current_user->exists() ) {
            return;
        }

        $query = "DELETE FROM {$wpdb->prefix}tbyb_prepared_items WHERE imported_to_cart = %s AND user_id = %s";
        $query = $wpdb->prepare($query, 1, intval($current_user->id));
        $wpdb->get_results($query);
    }



    /*
     * Override Woocommerce Quantity Input template part on Cart page
     * */
    public static function tbyb_override_quantity_field_template($template, $template_name, $template_path)
    {

        $plugin_path  = untrailingslashit( plugin_dir_path( __DIR__ ) )  . '/templates/woocommerce/';

        if ((is_page('cart') || is_cart()) && $template_name == 'global/quantity-input.php') {
            $template = $plugin_path . $template_name;
        }

        return $template;

    }



    /*
     * Redirect unauthenticated users to login page when they try to access WooCommerce pages
     * and redirect authenticated users to Cart page (prevent them from visiting shop and other WooCommerce pages, except Cart page)
     * */
    public static function tbyb_redirect_users()
    {


        if ( ! is_user_logged_in() ) {

            if (( is_woocommerce() || is_cart() || is_checkout() ) ) {

                auth_redirect();

                exit;
            }

        } else if ( is_woocommerce() && ! is_cart()) {

            $cart_url = wc_get_cart_url();

            wp_redirect($cart_url);

            exit;

        }
    }

}