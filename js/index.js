$(function(){
    $('.toggle').on('click', function(){
        $(this).next().toggle();
    });

    // メッセージ削除のポップアップ
    $('.delete').on('click', () => {
        if(confirm('メッセージを削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    });

});
