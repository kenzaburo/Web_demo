<?php

/**
* @author Jasman 03-05-2010
* @copyright 2010-Ihsana IT Solution. All Rights Reserved.
* @email jasman@ihsana.com
*/

    $fbconfig['appid']  = "121365011227445";
    $fbconfig['api']  = "712d8e91b53f716ecb1a4d3d03ebc501";
    $fbconfig['secret']  = "7f2537ffe06c2b153db9dc6edc766877";

    try{
        include_once "./facebook.php";
    }
    catch(Exception $o){
        echo '<pre>';
        print_r($o);
        echo '</pre>';
    }
    // Create our Application instance.
    $facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));
    Facebook::$CURL_OPTS[CURLOPT_CAINFO] = './ca-bundle.crt';
    $session = $facebook->getSession();

    $fbme = null;
    // Session based graph API call.
    if ($session) {
      try {
        $uid = $facebook->getUser();
        $fbme = $facebook->api('/me');
      } catch (FacebookApiException $e) {
          d($e);
      }
    }

    function d($d){
        echo '<pre>';
        print_r($d);
        echo '</pre>';
    }
?>