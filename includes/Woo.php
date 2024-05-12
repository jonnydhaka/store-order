<?php

namespace WpPool\Store\Order;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/**
 * API Class
 */
class Woo
{

    /**
     * Initialize the class
     */
    function __construct()
    {
        //file_put_contents(__DIR__ . '/my_log_1.txt', "testwoo");
        add_action('woocommerce_thankyou', [$this, 'register_woo'], 10, 1);
        add_action('woocommerce_order_status_changed',[$this, 'register_status_change'] , 10, 3);
       add_action( 'woocommerce_order_note_added', [$this, 'register_note_added'], 10, 2 );
        
        //add_action('woocommerce_new_order', [$this, 'register_woo'], 10, 1);

        //add_action('woocommerce_checkout_order_created', [$this, 'register_woo'], 10, 1);

       //add_action('init', [$this, 'register_woo'], 10, 1);
    }

    /**
     * Register the API
     *
     * @return void
     */
    public function register_woo($order_id)
    {
        $getapikey = get_option('wppool_apikey_saved');
        if ($getapikey) {
            $addressbook = new Woo\Woo();
            $addressbook->create_woo_order($order_id);
           
        }else{
            return false;
        }
    }


    public function register_status_change($order_id, $old_status, $new_status)
    {
        $getapikey = get_option('wppool_apikey_saved');
        if ($getapikey) {
            $addressbook = new Woo\Woo();
            $addressbook->create_status_change($order_id,$old_status, $new_status,$getapikey);
        }else{
            return false;
        }
    }


    public function register_note_added($comment_id, $order)
    {
        $getapikey = get_option('wppool_apikey_saved');
        if ($getapikey) {
            $addressbook = new Woo\Woo();
            $addressbook->create_note_added($comment_id, $order,$getapikey);
        }else{
            return false;
        }
    }

    
}
