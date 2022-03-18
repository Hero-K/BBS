<?php
    // PDO設定 *******************************************

    define('DSN', 'mysql:host=localhost;dbname=bbs;charset=utf8mb4');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');

    /* 例外が発生しうる場所をtryブロックで括る */

    // DB接続: PDO(PHP Data Object)クラスを使う
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // PDOの動作変更
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // error発生時、サイレント→例外発生へ変更

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // プリペアードステートメントのエミュレートをoffに
    // こっちのがｾｷｭﾘﾃｨ的に良いというおまじない

    