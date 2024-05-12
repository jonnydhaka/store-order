<?php

namespace WpPool\Store\Order\API;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Error;
use WpPool\Store\Order\JWT as JWT;

/**
 * Addressbook Class
 */
class Addressbook extends WP_REST_Controller {

    /**
     * Initialize the class
     */
    function __construct() {
        $this->namespace = 'api/v1';
        $this->rest_base = 'orderupdate';
    }

    /**
     * Registers the routes for the objects of the controller.
     *
     * @return void
     */
    public function register_routes() {

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [$this, 'update_order_data'],
                    'permission_callback' => "__return_true",
                ],
            ]
        );

        
    }
    public function update_order_data($request){
        $getapikey = get_option('wppool_apikey_saved');
        if ($getapikey) {
            $jwt = new JWT();
            $senddata = $jwt->validate_jwt($request, $getapikey);
            if ($senddata) {
               
                //print_r($senddata);
                $order = wc_get_order($senddata['woodatahub']['orders_id']);
                $order->update_status($senddata['woodatahub']['status']); 
                $order->add_order_note($senddata['woodatahub']['note'] );
                $return = array("Massage" => "Data Update", "status" => "Success");
                
            } else {
                $return = array("Massage" => "Sig Failed", "status" => "failed");
            }
        }else{
            return false;
        }
        //exit();
        return rest_ensure_response($return);

    }

   
}
