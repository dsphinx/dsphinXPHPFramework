<?php



class call_normal_php_class {
	function call_normal_php_class(){

        $info = " <br> <br/> <hr> <small> Αρχείο ".__FILE__." <br/> Dir ".__DIR__." <br/> κλήση  ". $_SERVER['HTTP_REFERER']. ' </small>';

        return " 1 Return values from Class ".__CLASS__.
               " called method is ".__METHOD__.
               " with function  name   ".__FUNCTION__. $info.  "<br/> <br/> <hr>" .Controller::showPHPCode(__FILE__, FALSE);

	}
}



