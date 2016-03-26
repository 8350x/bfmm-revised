<?php

    require_once(dirname(__FILE__)."/autoload.php");

    // developer info
   $consumerKey = "MDIqYbuXFNOYDZl7zF25gn5t0";
$consumerSecret = "4eLkTzAkbWwMDlSta624gaeSdBr00rMtQ5aeFPuSCTIHPInz91";
$accessToken = "712633619899031552-bCHgR98V6L2fpC9jiGc9CB0oWIr7izf";
$accessTokenSecret = "krVgNdb44dWH8bTMfftHqi6B5imYLOa3nUgxU8NDFX2mE";

    $oAuth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

    // oAuth認証を利用し、twitterに投稿する
    $TWITTER_STATUS_UPDATE_URL = "http://api.twitter.com/1.1/statuses/update.json";
    $method  = 'POST';
    $message = 'テスト';

    $response = $oAuth->post('statuses/update', array('status' => $message));

    // 結果出力
    var_dump($response);

?>
