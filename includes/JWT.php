<?php

namespace WpPool\Store\Order;

use WpPool\Store\Order\Traits\Get_Value;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/**
 * API Class
 */
class JWT
{

    use Get_Value;

    /**
     * Initialize the class
     */
    function __construct()
    {
    }
    function generate_jwt($payload, $secret)
    {

        // echo "<pre>", print_r($payload);
        // echo json_encode($payload);
        //get the local secret key


        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        // Create the token payload
        $payload = json_encode([
            'woodata' => $payload,
            'role' => 'admin',
            'exp' =>  strtotime(date('Y-m-d H:i:s', strtotime('+2 minutes '))),
            'domain' =>  $this->get_domain()
        ]);
        $payload = str_replace('?', '+:::+', $payload);

        //echo  strtotime(date('Y-m-d H:i:s', strtotime('now +2 minutes')));
        // Encode Header
        $base64UrlHeader = $this->base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    function validate_jwt($request, $secret)
    {
        $jwt = $request['senddata'];
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode(str_replace(' ', '+', $tokenParts[1])  );
        $signatureProvided = $tokenParts[2];
        str_replace('+:::+', '?', $payload);
       // echo "<br>";
        //print_r($payload);
        //echo json_decode($payload)->exp;
        //echo "<br>";
        $tokenExpired = json_decode($payload)->exp - strtotime(date('Y-m-d H:i:s'));
        // echo "<br>";
        // echo $tokenExpired;
        // echo "<br>";
        // echo $tokenExpired;
        // check the expiration time
        //$expiration = DateTime::createFromFormat(json_decode($payload)->exp);
        // echo $expiration . "<br>";
        // $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 60);
        // echo $tokenExpired . "<br>";
        // build a signature based on the header and payload using the secret
        $base64UrlHeader =  $this->base64UrlEncode($header);
        $base64UrlPayload =  $this->base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature =  $this->base64UrlEncode($signature);
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        // echo "Header:\n" . $header . "\n";
        // echo "Payload:\n" . $payload . "\n";
        if ($tokenExpired < 0) {
            echo "tokenExpired";
            return false;;
        }

        if ($signatureValid) {
            $payload = json_decode($payload, true);
            return $payload;
        } else {
            echo "signatureValid";
            return false;
        }
    }
}
