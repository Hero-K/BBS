<!-- index.php -->

<?php

    // 送信エラー取得(あれば)
    $err = '';
    if(isset($_GET['err'])){
    $err = $_GET['err'];
    }

    
    // DB接続
    require_once('db/db_connect.php');
    

    // ステートメント設定 ********************************
    $stmt = $pdo->prepare('SELECT B.BoardID, B.BoardName, B.BoardTimestamp, COUNT(M.MessageID) AS Amount, B.Deleted FROM Boards B INNER JOIN Messages M ON B.BoardID = M.BoardID GROUP BY M.BoardID HAVING B.Deleted IS NULL;');

    $stmt->execute();

    $boards = $stmt->fetchAll();

    //DB切断
    $stmt = null;
    $pdo = null;

    // 表示確認
    // foreach ($boards as $val){
    //     print_r($val);
    // }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/style_common.css">
    <link rel="stylesheet" href="./css/item_toggle.css">
    <link rel="stylesheet" href="./css/style_index.css">

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

        <!-- 掲示板作成フォーム
         ----------------------------------------------------------->
         <form id="forms_board_insert" class="forms" action="./db/db_board_insert.php" method="post">
            <h2 class="promote">早速、あなたの掲示板を建ててみましょう！</h2>

            <p class="form_err"><?php echo $err ?></p>
            <input type="hidden" name="ip" value="000.000.000.000">
            <dl class="form_item_board_name">
                <dt>
                    <label for="form_board_name">掲示板タイトル</label>
                </dt>
                <dd>
                    <input id="form_board_name" type="name" name="input_board_name" placeholder="掲示板ネーム">
                </dd>
            </dl>

            <dl class="form_item_name">
                <dt>
                    <label for="form_name">名前</label>
                </dt>
                <dd>
                    <input id="form_name" type="name" name="input_name" placeholder="名無し" value="名無し">
                </dd>
            </dl>

            <dl class="form_item_text">
                <dt>
                    <label for="form_text">テキスト</label>
                </dt>
                <dd>
                    <textarea id="form_text" type="text" name="input_text" placeholder="はじめのひとこと"></textarea>
                </dd>
            </dl>
        
            <input id="submit" type="submit" value="送信する"></input>
        </form>

        <div class="catalog">
            <ul class="threads">

                <?php foreach ($boards as $board){ ?>
                    <li class="thread_item">
                    <a class="thread_link" href="messages.php?bid=<?php echo $board['BoardID'] ?>">
                        <small class="thread_timestamp"><?php echo $board['BoardTimestamp'] ?></small>
                        <h3 class="thread_name"><?php echo $board['BoardName'] ?></h3>
                        <p class="thread_amount"><?php echo $board['Amount'] ?></p>
                    </a>

                    <!-- <div class="toggle">
                        <a class="toggle_btn">＝</a>
                    </div>

                    <div class="toggle_menu">
                            <ul>
                                <li><a href="delete_board.php?bid=<?php echo $board['BoardID'] ?>">削除</a></li>
                            </ul>
                    </div> -->
                </li>
                <?php } ?>
                
            </ul>
        </div>
    </div>

    <footer>
        <small class="copyright">Copyright test 2021.</small>
    </footer>
</div>
</body>
</html>