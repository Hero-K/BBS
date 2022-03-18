<?php

    // GET取得
    $bid = $_GET['bid'];
    $mid = $_GET['mid'];


    $ip_addr = $_SERVER["REMOTE_ADDR"]; // 訪問者のIPも取得

    // DB接続
    require_once('db_connect.php');

    $stmt = $pdo->prepare('SELECT MessageID, PosterIP, Deleted FROM Messages WHERE MessageID =:id'); // ステートメント設定
    $stmt->bindValue(':id', $mid); // インジェクション対策
    $stmt->execute(); // 実行

    // データ取得
    $message = $stmt->fetch();

    // 取得したデータをそれぞれの変数に格納
    $mid = $message['MessageID'];
    $poster_ip = $message['PosterIP'];

    // echo $mid;

    $alert =''; //結果を格納する
    if($poster_ip == $ip_addr && !isset($message['Deleted'])) {

        // 成功処理(削除)
        $stmt2 = $pdo->prepare('UPDATE Messages SET Deleted=1 WHERE MessageID= :id');
        $stmt2->bindValue(':id', $mid);
        $stmt2->execute();

    } else {
        
        echo $mid.$poster_ip.$deleted;

        // 失敗処理(勝手に削除しようとしている or 削除済み)
        $alert = 'alert("ERROR: 削除するための権限が無いか、もしくは既に削除されています。");';
        
    }

    //DB切断
    $stmt = null;
    $pdo = null;
    
    
    // PHPとJSが競合してしまうのでJSに統一
    $start_of_script = '<script type="text/javascript">';
    $header = 'location.href="../board.php?bid='.$bid.'";'; // 帰る為のロケーション設定
    $end_of_script = '</script>';

    echo $start_of_script. $alert. $header. $end_of_script; // 削除された場所へリダイレクト
