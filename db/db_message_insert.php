<?php

    if($_POST['bid']) {
        $bid = $_POST['bid']; //掲示板ID(headerで帰る用)
    }

    $ip_addr = $_SERVER["REMOTE_ADDR"]; //訪問者IPアドレス 丁重に扱うように

    $err = '';
    // まずバリデーションチェックしようか
    if($_POST['input_name'] == '' || preg_match("/^[ 　\n\r]*$/", $_POST['input_name'])){
        $err = '*名前を入力してください';
    
    } elseif(strlen($_POST['input_name']) > 32) {
        $err = '*名前は32文字以内にしてください';
    
    } elseif($_POST['input_text'] == '' || preg_match("/^[ 　\n\r]*$/", $_POST['input_text'])) {
        $err = '*テキストを入力してください';
    }


    if($err){
        header('Location: ../messages.php?bid='.$bid.'&err='.$err.'#forms_message_insert');
        exit;
    }


    // 特殊文字エスケープ
    require_once('db_item_htmlspecialchars.php');

    // NULLがあるとエラーになるからここから変数定義
    $bid = h($bid); //掲示板ID
    $name = h($_POST['input_name']); //投稿者名
    $text = h($_POST['input_text']); //テキスト

    setcookie('c_name', $name, time()+3600, "/");

    // DB接続
    require_once('db_connect.php');

    // ステートメント設定
    $stmt = $pdo->prepare('INSERT INTO Messages(BoardID, PosterName, PosterIP, MessageText, MessageTimestamp) VALUES(:board_id, :poster_name, :poster_ip, :message_text, NOW());');

    // インジェクション対策
    $stmt->bindValue(':board_id', $bid);
    $stmt->bindValue(':poster_name', $name);
    $stmt->bindValue(':poster_ip', $ip_addr);
    $stmt->bindValue(':message_text', $text);

    $stmt->execute();

    //DB切断
    $stmt = null;
    $pdo = null;

    header('Location: ../messages.php?bid='.$bid.'#forms_message_insert');