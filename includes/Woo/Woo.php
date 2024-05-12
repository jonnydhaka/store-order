<?php

namespace WpPool\Store\Order\Woo;

use WpPool\Store\Order\JWT as JWT;
use WpPool\Store\Order\Traits\Get_Value;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/**
 * Woo Class
 */
class Woo
{
    use Get_Value;
    private $buiddata = [];

    public function remote_post_response($order_data, $getapikey){

        $jwt = new JWT();
        $senddata = $jwt->generate_jwt($order_data, $getapikey);
        
        $url = $this->endpoint.'/wp-json/api/v1/apiupload';
        $response = wp_remote_post(
            $url,
            array(
                'method'      => 'POST',
                'timeout'     => 45,
                'headers'     => array(),
                'body'        => array(
                    'domainname' => $this->get_domain(),
                    'senddata' => $senddata,
                ),
            )
        );
        return $response;
        
    }


    public function create_woo_order($order_id)
    {

        $getapikey = get_option('wppool_apikey_saved');
        if (!$getapikey) {
            return false;
        }
        $order = wc_get_order($order_id);
        $order_data = $order->get_data();
        $ordernotes=wc_get_order_notes(["order_id"=>$order_id]);
        $postid=get_post_meta($order_id,"wppool_order_id_saved_id",true);
        if($postid){
            $order_data["wppool_post_id"]=$postid;
        }
        // //$paymethod = $order->payment_method_title;
        // $orderstat = $order->get_status();
        // echo "<pre>",print_r($ordernotes);
        // echo "<pre>",print_r($order_data);
        $ordernotestring='';
        foreach($ordernotes as $ordernote){
            $ordernotestring.=  $ordernote->content.",";
        }
        $ordernotestring=rtrim($ordernotestring,',');
        $order_data["notes"]=$ordernotestring;
        $response=$this->remote_post_response($order_data, $getapikey);
        add_post_meta( $order_id, 'wppool_order_id_saved_id', json_decode($response['body'])->postid, true );
        // print_r($response);
        // exit();
        // $response = wp_remote_get($url);
        file_put_contents(__DIR__ . '/create_woo_order.txt', print_r($response , true));
        if ( is_wp_error( $response ) ) {
            return $response->get_error_message();
        }else{
            return true;
        }

        // file_put_contents(__DIR__ . '/my_log_3.txt', print_r($order, true));
    }

    public function create_status_change($order_id,$old_status, $new_status,$getapikey){
        $order_data=[];
        $postid=get_post_meta($order_id,"wppool_order_id_saved_id",true);
        if($postid){
            $order_data["wppool_post_id"]=$postid;
            $order_data["status"]=$new_status;
            $response=$this->remote_post_response($order_data, $getapikey);
            file_put_contents(__DIR__ . '/create_status_change.txt', print_r($response , true));
            if ( is_wp_error( $response ) ) {
                return $response->get_error_message();
            }else{
                return true;
            }
        } else {
            return false;
        }
    }

    public function create_note_added($comment_id, $order,$getapikey){
        $order_data=[];
        $order_id=$order->get_id();
        $postid=get_post_meta($order_id,"wppool_order_id_saved_id",true);
        if($postid){
            $comment_data = get_comment( $comment_id );
            $order_data["wppool_post_id"]=$postid;
            $order_data["wppool_notes"]=$comment_data->comment_content;
            $response=$this->remote_post_response($order_data, $getapikey);
            file_put_contents(__DIR__ . '/create_note_added.txt', print_r($order_data , true));
            file_put_contents(__DIR__ . '/create_note_added_1.txt', print_r($comment_data , true));
            if ( is_wp_error( $response ) ) {
                return $response->get_error_message();
            }else{
                return true;
            }
        } else {
            return false;
        }
    }
}
