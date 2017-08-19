<?php


    echo __FILE__. ' testing ...  <br/>';


    $sex = " [ είμαι η εσωτερική μεταβλητή με όνομα sex  και θα ενσωματωθω στο html ] ";
    $FORM_KEY = " [  και εγω ... <mark> ok </mark> ]  ";

    echo ' Auto Load html <br/>';
    echo ' Controller: ' .$_SESSION[ 'PATHS' ]['FRAMEWORK']['CONTROLLER'] .' <br/>  End of PHP file !<br/>';

$base = __FILE__;
Controller::showPHPCode($base);
$pre  = explode(".",$base);
Controller::showPHPCode($pre[0] .".css");
Controller::showPHPCode($pre[0] .".js");
Controller::showPHPCode($pre[0] .".html");
echo '<br/> <br/> <h2> Output of page call = '.Controller::$_page['run'].'</h2> <hr/>';
