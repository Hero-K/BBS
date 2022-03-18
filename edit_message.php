<!-- Board.php -->
<?php

    // GET取得
    $bid = $_GET['bid'];
    $mid = $_GET['mid'];
    $no = $_GET['tid'];

    $ip_addr = $_SERVER["REMOTE_ADDR"]; //IPアドレス取得

    // 送信エラー取得(あれば)
    $err = '';
    if(isset($_GET['err'])){
    $err = $_GET['err'];
    }

    // DB接続
    require_once('./db/db_connect.php');

    // ステートメント設定
    $stmt = $pdo->prepare('SELECT BoardName FROM Boards WHERE BoardID = :id');
    $stmt2 = $pdo->prepare('SELECT * FROM Messages WHERE MessageID =:id');

    // インジェクション対策
    $stmt->bindValue(':id', $bid);
    $stmt2->bindValue(':id', $mid);

    // 実行
    $stmt->execute();
    $stmt2->execute();

    // データ取得
    $board_name = $stmt->fetch();
    $message = $stmt2->fetch();

    //DB切断
    $stmt = null;
    $pdo = null;


    // 本人確認

    if($ip_addr !== $message['PosterIP'] || isset($message['Deleted'])) {
        
        // 失敗処理(勝手に削除しようとしている or 削除済み)
        $result = '<script type="text/javascript">'
        .'alert("ERROR: 削除するための権限が無いか、もしくは既に削除されています。");'
        .'location.href="../board.php?bid='.$bid
        .'";</script>';
        echo $result;
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/style_common.css">

    <link rel="stylesheet" href="./css/style_edit_message.css">

    <title>Document</title>
</head>
<body>
<div class="container">
    <header>
        <h1><a href="index.php">test</a></h1>
        <nav></nav>
    </header>
    

    <!-- メインコンテンツ --
    -------------------------------------------------------->
    <div class="main_content">
    <hr>

    <div class="thread_description">
        <h2 class="thread_name"><?php echo $board_name['BoardName'] ?></h2>
        <p class="thread_timestamp">2021/12/31(あ) 23:59:59</p>
    </div>

    <!-- コンテンツ 掲示板 
        ---------------------------------------------------->
        <div class="board">

            <ul class="messages">
                <li class="message_item">
                    <div class="message_poster">
                        <h5 id="#<?php echo $no ?>" class="poster_id">No.<?php echo $no ?></h5>
                        <h3 class="poster_name<?php if($ip_addr == $message['PosterIP']) {echo ' self';}  ?>"><?php echo $message['PosterName'] ?></h3>
                        <p class="poster_timestamp"><?php echo $message['MessageTimestamp'] ?></p>
                        <p class="poster_timestamp_edit"><?php if($message['EditTimestamp']){ echo '編集済: '. $message['EditTimestamp']; }?></p>
                    </div>

                    <?php if(!isset($message['Deleted'])){ ?>
                        <div class="message_text">
                            <p><?php echo $message['MessageText'] ?></p>
                        </div>
                    <?php } ?>
                </li>
            </ul>
        </div>

        <!-- メッセージ 編集フォーム 
        ---------------------------------------------------->
        <form id="forms_message_edit" class="forms" action="./db/db_message_update.php" method="post">
            <h3>メッセージを編集</h3>
            
            <!-- エラー表示 -->
            <p class="form_err"><?php echo $err ?></p>

            <input type="hidden" name="bid" value="<?php echo $bid ?>">
            <input type="hidden" name="mid" value="<?php echo $mid ?>">
            <input type="hidden" name="tid" value="<?php echo $no ?>">

            <dl class="form_item_name">
                <dt>
                    <label for="form_name">名前</label>
                </dt>
                <dd>
                    <input id="form_name" type="name" name="input_name" placeholder="名無し" value="<?php echo $message['PosterName']; ?>">
                </dd>
            </dl>

            <dl class="form_item_text">
                <dt>
                    <label for="form_text">テキスト</label>
                </dt>
                <dd>
                    <textarea id="form_text" type="text" name="input_text" placeholder="hogehoge"><?php echo $message['MessageText'];?></textarea>
                </dd>
            </dl>

            <input id="submit" type="submit" value="変更する"></input>
        </form>
    </div>
    

    <footer>
        <small class="copyright">Copyright test 2021.</small>
    </footer>
</div>
</body>
</html>