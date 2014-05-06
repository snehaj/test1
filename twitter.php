<?php
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
'oauth_access_token' => "117669203-xGbpZjedQ2x3O4unXAOMmz4myHJ4iMlA3IrMpD1A",
'oauth_access_token_secret' => "SSgaYWtZoBgGrBlbNcLIMwsE5aJer8gD7g6SC6IH1Ulw7",
'consumer_key' => "hYE4JdaG3Zba6HespcnFg",
'consumer_secret' => "YOUR_CONSUMER_SECRET"
);

$ta_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?screen_name=replace_me;
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
$follow_count=$twitter->setGetfield($getfield)
->buildOauth($ta_url, $requestMethod)
->performRequest();
$data = json_decode($follow_count, true);
$followers_count=$data[0]['user']['followers_count'];
echo $followers_count;