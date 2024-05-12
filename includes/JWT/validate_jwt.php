<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'bootstrap.php';

//use Carbon\Carbon;

// get the local secret key
$secret = "54321";

if (!isset($_REQUEST["v"])) {
    exit('Please provide a key to verify');
}

$jwt = $_REQUEST["v"];

// split the token
$tokenParts = explode('.', $jwt);
$header = base64_decode($tokenParts[0]);
$payload = base64_decode($tokenParts[1]);
$signatureProvided = $tokenParts[2];

echo json_decode($payload)->exp . "<br>";
echo strtotime(date('Y-m-d H:i:s')) . "<br>";



$tokenExpired = json_decode($payload)->exp - strtotime(date('Y-m-d H:i:s'));
echo $tokenExpired;


// check the expiration time
//$expiration = DateTime::createFromFormat(json_decode($payload)->exp);
// echo $expiration . "<br>";
// $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 60);
// echo $tokenExpired . "<br>";
// build a signature based on the header and payload using the secret
$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
$base64UrlSignature = base64UrlEncode($signature);

// verify it matches the signature provided in the token
$signatureValid = ($base64UrlSignature === $signatureProvided);

echo "Header:\n" . $header . "\n";
echo "Payload:\n" . $payload . "\n";

if ($tokenExpired < 0) {
    echo "Token has expired.\n";
} else {
    echo "Token has not expired yet.\n";
}

if ($signatureValid) {
    echo "The signature is valid.\n";
} else {
    echo "The signature is NOT valid\n";
}
