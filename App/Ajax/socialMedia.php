<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: dsphinx
     * Date: 1/16/14
     * Time: 12:24 PM
     * To change this template use File | Settings | File Templates.
     *
     * javascript
     *          social(title,desc,url,img);

     *
     */


    require_once ( '../Library/Ajax/Ajax.php' );
    $_return = TRUE;


    if ($results = file_get_contents ("php://input")) {

        $results = json_decode ($results, TRUE);

        $_SESSION [ 'SOCIAL_HEADER' ][ 'TITLE' ]       = Ajax_Call::IN ($results[ 'title' ]);
        $_SESSION [ 'SOCIAL_HEADER' ][ 'DESCRIPTION' ] = Ajax_Call::IN ($results[ 'desc' ]);
        $_SESSION [ 'SOCIAL_HEADER' ][ 'URL' ]         = Ajax_Call::IN ($results[ 'url' ]); // http://
        $_SESSION [ 'SOCIAL_HEADER' ][ 'IMG' ]         = Ajax_Call::IN ($results[ 'img' ]); // http://
       // trigger_error (' mea ien i' . $_SESSION[ 'PATHS' ][ 'SOCIAL_HEADER' ][ 'TITLE' ]);

    } else {
        unset( $_SESSION [ 'SOCIAL_HEADER' ] );
    }


    return $_return;