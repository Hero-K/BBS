<?php

    // 帰る用の情報取得
    $bid = $_POST['bid'];
    $mid = $_POST['mid'];
    $tid = $_POST['tid']; 


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
        header('Location: ../edit_message.php?bid='.$bid.'&mid='.$mid.'&tid='.$tid.'&err='.$err);
        exit;
    }

    // NULLがあるとエラーになるからここから
    $name = $_POST['input_name']; //投稿者名
    $text = $_POST['input_text']; //テキスト

    // DB接続
    require_once('db_connect.php');

    // ステートメント設定
    $stmt = $pdo->prepare('UPDATE Messages SET MessageText=:message_text , EditTimestamp=NOW() WHERE MessageID=:message_id');

    // インジェクション対策
    $stmt->bindValue(':message_text', $text);
    $stmt->bindValue(':message_id', $mid);

    $stmt->execute();

    //DB切断
    $stmt = null;
    $pdo = null;

    header('Location: ../messages.php?bid='.$bid.'#i'.$tid);