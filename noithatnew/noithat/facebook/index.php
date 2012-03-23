<?php

define('FACEBOOK_APP_ID', '121365011227445');
define('FACEBOOK_SECRET', '7f2537ffe06c2b153db9dc6edc766877');

function get_facebook_cookie($app_id, $application_secret) {
    $args = array();
    parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
    ksort($args);
    $payload = '';
    foreach ($args as $key => $value) {
        if ($key != 'sig') {
            $payload .= $key . '=' . $value;
        }
    }
    if (md5($payload . $application_secret) != $args['sig']) {
      return null;
    }
    return $args;
}

$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
echo 'The ID of the current user is ' . $cookie['uid'] ;
$friends = json_decode(file_get_contents( 'https://graph.facebook.com/me/friends?access_token='.$session['access_token']) );
var_dump($friends);
?>
<html>
  <body>
    <?php if ($cookie) { ?>
      Your user ID is <?= $cookie['uid'];
      ?>
        <fb:serverFbml>
            <script type="text/fbml">
            <fb:fbml>
                <fb:request-form
                    method='POST'
                    type='join my helloapi group'
                    content='Would you like to join my helloapi group? 
                        <fb:req-choice url="http://apps.facebook.com/helloapi/yes.php" 
                            label="Yes" />'
                        <fb:req-choice url="http://apps.facebook.com/helloapi/no.php" 
                            label="No" />'
                    <fb:multi-friend-selector 
                        actiontext="Invite your friends to join your helloapi group.">
                </fb:request-form>
            </fb:fbml>
            </script>
        </fb:serverFbml>
    <?php } else { ?>
      <fb:login-button>Install Example App</fb:login-button>
    <?php } ?>

    <div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script>
      FB.init({appId: '121365011227445', xfbml: true, cookie: true});
      FB.Event.subscribe('auth.login', function(response) {
        // Reload the application in the logged-in state
        window.top.location = 'http://apps.facebook.com/helloapi/';
      });
    </script>
   

  </body>
</html>
