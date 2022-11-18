<!-- Board.php -->

<?php
    // DB接続
    require_once('./db/db_connect.php');

    $bid = $_GET['bid']; // GET取得
    
    // 送信エラー取得(あれば)
    $err = '';
    if(isset($_GET['err'])){
    $err = $_GET['err'];
    }

    // クッキー取得(あれば)
    $c_name = '名無し';
    if($_COOKIE['c_name']){
    $c_name = $_COOKIE['c_name'];
    }

    $ip_addr = $_SERVER["REMOTE_ADDR"]; // 訪問者のIPも取得

    // ステートメント設定
    $stmt = $pdo->prepare('SELECT BoardName FROM Boards WHERE BoardID = :id');
    $stmt2 = $pdo->prepare('SELECT MessageID, PosterName, PosterIP, MessageText, MessageTimestamp, EditTimestamp, Deleted FROM Messages WHERE BoardID = :id');

    // インジェクション対策
    $stmt->bindValue(':id', $bid);
    $stmt2->bindValue(':id', $bid);

    // 実行
    $stmt->execute();
    $stmt2->execute();

    // データ取得
    $board_name = $stmt->fetch();
    $messages = $stmt2->fetchAll();

    //DB切断
    $stmt = null;
    $pdo = null;

    
    // 表示確認
    // echo print_r($boardName);
    // foreach ($messages as $val){
    //     print_r($val);
    // }

    // 大文字小文字に注意！！！
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/style_common.css">
    <link rel="stylesheet" href="./css/item_toggle.css">

    <link rel="stylesheet" href="./css/style_board.css">

    <!-- scripts -->
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/index.js"></script>

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
        <!-- <p class="thread_timestamp">2021/12/31(あ) 23:59:59</p> -->
    </div>

        <!-- コンテンツ 掲示板 
        ---------------------------------------------------->
        <div class="board">

            <ul class="messages">

                <?php $no = 1; ?>
                <?php foreach($messages as $message){ ?>

                    <li class="message_item">
                    <div class="message_poster">
                        <h5 id="i<?php echo $no ?>" class="poster_id">No.<?php echo $no ?></h5>
                        <h3 class="poster_name<?php if($ip_addr == $message['PosterIP']) {echo ' self';}  ?>"><?php echo $message['PosterName'] ?></h3>
                        <p class="poster_timestamp"><?php echo $message['MessageTimestamp'] ?></p>
                        <p class="poster_timestamp_edit"><?php if($message['EditTimestamp']){ echo '編集済: '. $message['EditTimestamp']; }?></p>
                    </div>

                    <?php if(!isset($message['Deleted'])){ ?>
                        <div class="message_text">
                            <p><?php echo $message['MessageText'] ?></p>
                        </div>

                        <?php if($ip_addr == $message['PosterIP']) {?>
                            <div class="toggle">
                                <a class="toggle_btn">＝</a>
                                </div>
                                <div class="toggle_menu">
                                <ul>
                                    <li class="edit"><a href="./edit_message.php?bid=<?php echo $bid ?>&mid=<?php echo $message['MessageID'] ?>&tid=<?php echo $no ?>">編集</a></li>
                                    <li class="delete"><a href="./db/db_message_delete.php?bid=<?php echo $bid ?>&mid=<?php echo $message['MessageID'] ?>">削除</a></li>
                                </ul>
                            </div>    
                        <?php } ?>
                    <?php } else { ?>
                        <div class="message_text">
                            <p class="deleted">#このメッセージは削除されました#</p>
                        </div>
                    <?php } ?>
                </li>
                <?php $no++; } ?>
            </ul>
        </div>

        <!-- メッセージ投稿フォーム 
        ---------------------------------------------------->
        <form id="forms_message_insert" class="forms" action="./db/db_message_insert.php" method="post">
            
            <p class="form_err"><?php echo $err ?></p>

            <input type="hidden" name="bid" value="<?php echo $bid ?>">
            <dl class="form_item_name">
                <dt>
                    <label for="form_name">名前</label>
                </dt>
                <dd>
                    <input id="form_name" type="name" name="input_name" placeholder="名無し" value="<?php echo $c_name ?>">
                </dd>
            </dl>

            <dl class="form_item_text">
                <dt>
                    <label for="form_text">テキスト</label>
                </dt>
                <dd>
                    <textarea id="form_text" type="text" name="input_text" placeholder="hogehoge"></textarea>
                </dd>
            </dl>
        
            <input id="submit" type="submit" value="送信する"></input>
        </form>
    </div>
    

    <footer>
        <small class="copyright">Copyright test 2021.</small>
    </footer>
</div>
</body>
</html>