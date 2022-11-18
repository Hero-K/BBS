<?php

    $err = '';
    // まずバリデーションチェックしようか
    if($_POST['input_board_name'] == '' || preg_match("/^[ 　\n\r]*$/", $_POST['input_board_name'])){
        $err = '*掲示板タイトルを入力してください';

    } elseif(strlen($_POST['input_board_name']) > 32) {
        $err = '*掲示板タイトルは64文字以内にしてください';
    
    } elseif($_POST['input_name'] == '' || preg_match("/^[ 　\n\r]*$/", $_POST['input_name'])){
        $err = '*名前を入力してください';
    
    } elseif(strlen($_POST['input_name']) > 32) {
        $err = '*名前は32文字以内にしてください';
    
    } elseif($_POST['input_text'] == '' || preg_match("/^[ 　\n\r]*$/", $_POST['input_text'])) {
        $err = '*テキストを入力してください';
    }

    if($err){
        header("Location: ../index.php?err=$err#forms_board_insert");
        exit;
    }

    // 特殊文字エスケープ
    require_once('db_item_htmlspecialchars.php');

    // NULLがあるとエラーになるからここから変数定義
    $b_name = $_POST['input_board_name'];
    $b_name = h($b_name); //掲示板ID

    // DB接続
    require_once('db_connect.php');

    // ステートメント設定
    $stmt = $pdo->prepare('INSERT INTO Boards(BoardName, BoardTimestamp) VALUES(:board_name, NOW());');
    $stmt2 = $pdo->prepare('SELECT MAX(BoardID) AS Mx FROM Boards;');

    // インジェクション対策
    $stmt->bindValue(':board_name', $b_name);

    $stmt->execute();
    $stmt2->execute();

    // 新しい掲示板IDを指定
    $max = $stmt2->fetch();
    $bid = $max['Mx'];
    $mid = 1;

    require_once('db_message_insert.php');