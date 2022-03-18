<!-- db_item_htmlspecialchars.php -->
<?php
    function h( $str ) {
        return htmlspecialchars( $str, ENT_QUOTES, 'UTF-8' );
    }