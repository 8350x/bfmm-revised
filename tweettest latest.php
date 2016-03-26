<?php
header('Content-Type: text/plain; charset=utf-8');
    require_once('twitteroauth/autoload.php');
    require_once('twitteroauth/src/TwitterOAuth.php');
    require_once(dirname(__FILE__) . '/config.php');
    use Abraham\TwitterOAuth\TwitterOAuth;

    try {
    
    // TwistOAuthのインスタンスを生成
    $to = new TwitterOAuth(consumer_key, consumer_secret, access_token, access_token_secret);
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