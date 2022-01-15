<?php 

require_once("PayPal-PHP-SDK/autoload.php");

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AZ6u_Hk3UmEB9nUbv1d8m4QsEIhWPLGyNtFgOIGzK799U1ZNifWIV4ptZeUwaYn4-WQZCT4ipdKpdKOv',     // ClientID
        'EB8KqL3upV3l_FUsihtiM9RpvnnKi2uY7XWppfDrLkIo-pvlToT58tWIv4pzz6En-DRRB45JmpYeMyJu'      // ClientSecret
    )
);

?>