<?php


    /**
     *    Do not use Echo , only return
     *
     *
     *
     * @return string
     *
     *
     *
     *
     */


    function call_function_call()
    {
        $t = 10;
        $info = " <br> <br/> <hr> <small> Αρχείο ".__FILE__." <br/> Dir ".__DIR__." <br/> κλήση  ". $_SERVER['HTTP_REFERER']. ' </small>';

        $html = " Απο function , δεν κάνω echo , μόνο return ";
        return "$html <br/> Return values $t from Function with same name with the file  " . __FUNCTION__. $info . "<br/> <br/> <hr>" .Controller::showPHPCode(__FILE__, FALSE);

    }


