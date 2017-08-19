<?php

    /**
     *
     *   Normal PHP  , echo printf
     *
     *
     */

    $info = " <br> <br/> <hr> <small> Αρχείο ".__FILE__." <br/> Dir ".__DIR__." <br/> κλήση  ". $_SERVER['HTTP_REFERER']. ' </small>';

    echo " Return values from  <h3> OUTPUT  , echo , print </h3> etc .. nice  ";
    echo " δεν κλαώ το MVC απλος PHP κώδικας   ".$info;

 Controller::showPHPCode(__FILE__);