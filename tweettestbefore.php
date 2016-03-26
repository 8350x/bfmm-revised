<?php
header('Content-Type: text/plain; charset=utf-8');

// ライブラリを読み込む
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
 
// アプリケーション登録時に控えておいた項目をそれぞれ入力
$consumerKey = "MDIqYbuXFNOYDZl7zF25gn5t0";
$consumerSecret = "4eLkTzAkbWwMDlSta624gaeSdBr00rMtQ5aeFPuSCTIHPInz91";
$accessToken = "712633619899031552-bCHgR98V6L2fpC9jiGc9CB0oWIr7izf";
$accessTokenSecret = "krVgNdb44dWH8bTMfftHqi6B5imYLOa3nUgxU8NDFX2mE";

try {
    
    // TwistOAuthのインスタンスを生成
    $to = new TwistOAuth($ck, $cs, $at, $as);
    
    // 検索パラメータ
    $params = array(
        'lang'  => 'ja',            // 言語
        'q'     => '#bfmm', // 検索ワード
        'count' => '10',            // 取得する件数
    );
    
    // ツイートを取得
    // 失敗したときは自動でcatchに飛ぶのでtwitteroauthのようにいちいち isset(...) とかチェックする必要はない
    $statuses = $to->get('search/tweets', $params)->statuses;
    
    // 1件ごと回す
    foreach ($statuses as $status) {
        
        // ユーザー名
        $name = $status->user->name;
        
        // 本文
        // HTMLエスケープされてるのでhtmlspecialchars_decodeを通してから使うのがスジ
        // これはTwitterAPI共通の仕様
        $text = htmlspecialchars_decode($status->text, ENT_NOQUOTES);
        
        // ユーザ名にhogeもしくはツイートにpiyoを含むものは除外
        // preg_matchを使ってもいいけどstrposで十分
        // 大文字小文字を区別しないときはstriposを使う
        if (stripos($name, 'hoge') !== false || stripos($text, 'piyo') !== false) {
            continue;
        }
        
        try {
            
            // リツイートを実行
            // 失敗したときは自動でcatchに飛ぶ
            // 1件失敗して全て中断してもいい場合はここにcatchブロックを設ける必要はないが、
            // 1件失敗しても次のツイートのリツイートに移りたい場合は必要となる
            $to->post("statuses/retweet/{$status->id_str}");
            echo "1件リツイートしました\n";
            
        } catch (TwistException $e) {
            
            echo "リツイート失敗: {$e->getMessage()}\n";
            
        }
        
    }
    
} catch (TwistException $e) {
    
    echo "検索失敗: {$e->getMessage()}\n";
    
}
