<?php
    /**
     *    Testing Framework Plugins -  Feb 2014
     *
     */

    Init::_load('Unix');

    $_tmp  = Unixfile::get_files_from_dir(__DIR__);
    $files = array();
    $cx    = 0;
    foreach ($_tmp as $f) {
        $t                  = explode('.', $f);
        $name               = $t[0];
        $files[$cx]['name'] = '<a target="_blank" href="?page=' . $name . '">' . $name . '</a>';
        $cx++;
    }


    $_tmp = Unixfile::get_dirs_from_dir(__DIR__);
    foreach ($_tmp as $f) {
        $t    = explode('.', $f);
        $name = $t[0];
        if ($name != "") {
            $files[$cx]['name'] = '<a target="_blank" href="?page=' . $name . '"><mark> Directory </mark> >> ' . $name . '</a>';
            $cx++;
        }

    }


    $_tmp_header = array(
        'page Run' => 0,
    );


    array_multisort($files);

    Html_Table::show($_tmp_header, $files, "400px", "Framework CALL files Testing ---  ?page=xx");


?>

<pre>

URI : http://xxxxx/index.php?page=main
Θα ψάξει να βρει το αρχείο main ή κατάλογο main, σε
{ Contents/ , Private/ } [στις τιμές του πίνακα $_SESSION[PATHS][FILES]] και θα το βρεί στο Contents/main.php
οπου κια το εκτελέσει, επιστρέφοντας το αποτέλεσμα της εκτέλεσης στο working div main_page_contents [template html]. Η σειρά με την οποία γίνεται η αναζήτηση εων αρχείων είναι με προτεραιότητα απο php, html, htm.
Κάθε πέρασμα με $_GET[‘page’] μπορεί να εκτελεστεί απο το framework είτε με το ίδιο όνομα όπως αναφέρθηκε παραπάνω με τις καταλήζεις .php, .html καθώς μπορεί να εκτελέσει και την συνονόματες functions με τα αρχεία ή τις συνονόματες κλάσεις με τον ίδιο constructor (php4 )
Π.χ
URI : http://xxxxx/index.php?page= call_function_call File:: call_function_call.php
￼
 function call_function_call()
{
	$t = 10;
	$info = " <br> <br/> <hr> <small> Αρχείο ".__FILE__." <br/> Dir
".__DIR__." <br/> κλήση ". $_SERVER['HTTP_REFERER']. ' </small>';
	$html = " Απο function , δεν κάνω echo , μόνο return ";
	return "$html <br/> Return values $t from Function with same name with
the file " . __FUNCTION__. $info;
	￼￼}





	URI : http://xxxxx/index.php?page= call_normal_php_class File:: call_normal_php.php
{
 ￼￼class
call_normal_php_class
￼function call_normal_php_class(){
	￼$info = " <br> <br/> <hr> <small> Αρχείο ".__FILE__." <br/> Dir
￼".__DIR__." <br/> κλήση ". $_SERVER['HTTP_REFERER']. ' </small>';
	￼return " 1 Return values from Class ".__CLASS__.
￼" called method is ".__METHOD__.
￼" with function name ".__FUNCTION__. $info;
￼}
￼￼￼}
και αναδρομικά σε φάκελο




	URI : http://xxxxx/index.php?page=call_files File:: call_files / call_files .php
 ￼echo __FILE__. ' testing ... <br/>';
￼$sex = " [ είμαι η εσωτερική μεταβλητή με όνομα sex και θα ενσωματωθω
￼στο html ] ";
￼$FORM_KEY = " [ και εγω ... <mark> ok </mark> ] ";
￼echo ' Auto Load html <br/>';
￼echo ' Controller: ' .$_SESSION[ 'PATHS'
￼]['FRAMEWORK']['CONTROLLER'] .' <br/> End of PHP file !<br/>';
￼File:: call_files/call_files.html
<div>
￼￼ok =[@FORM_KEY]= meta po from
<p> </p> </div>
<br/>
=[@sex]= meta ekdoso
Πολυ σημαντικό
καλείς το php και φορτώνεται αυτόματα το .html όπως το .net asp aspx file
</pre>
