<?php

namespace WpPool\Store\Order;

/**
 * Ajax handler class
 */
use WpPool\Store\Order\Traits\Get_Value;
class Ajax
{

    use Get_Value;
    /**
     * Class constructor
     */
    function __construct()
    {

        add_action('wp_ajax_wppool-store-order-get-api', [$this, 'get_api_from_hub']);
        add_action('wp_ajax_nopriv_wppool-store-order-get-api', [$this, 'get_api_from_hub']);
    }

   

    /**
     * get api
     *
     * @return void
     */
    public function get_api_from_hub() {

        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'wppool-so-admin-nonce')) {
            wp_send_json_error([
                'message' => __('Nonce verification failed!', 'wppool-store-order')
            ]);
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error([
                'message' => __('No permission!', 'wppool-store-order')
            ]);
        }
        $url = $this->endpoint.'/wp-json/api/v1/getapi';
        $response = wp_remote_post(
            $url,
            array(
                'method'      => 'POST',
                'timeout'     => 45,
                'headers'     => array(),
                'body'        => array(
                    'domainname' => $_POST['domainname'],
                    'buttonaction' => $_POST['buttonaction'],
                ),

            )
        );
        // $response = wp_remote_get($url);
        if (is_array($response) && !is_wp_error($response)) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content
            $body = json_decode($body);
            if (isset($body->remove)) {
                delete_option("wppool_apikey_saved");
                delete_option("wppool_apikey_saved_time");
            } else {
                $getapikey = $body->api_key;
                $installed = get_option('wppool_apikey_saved');
                if (!$installed) {
                    update_option('wppool_apikey_saved_time', time());
                }
                update_option('wppool_apikey_saved', $getapikey);
            }
            echo "ok";
        }else{
            echo "error";
        }
        wp_die();
    }
}
