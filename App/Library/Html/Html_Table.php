<?php



    class Html_Table extends Html
    {
        static $_tr_height = 25;


        static public function old_show($displayarray, $datarray, $width = "100%", $tablename_ok = "tbl",
                                        $links_id = NULL, $printok = TRUE, $links_id_href = NULL, &$returnrow = "",
                                        $popup = FALSE, $aa_notid = FALSE, $new_max = FALSE)
        {

        }


        static public function show($_header_array, $_records, $_width = NULL, $_tablename = "tbl", $_print_output = TRUE, $_set_link = NULL, $_set_link_field = NULL)
        {

            $tablename = htmlentities($_tablename);
            $width     = (isset($_width)) ? ' style="width:' . $_width . ';" ' : NULL;

            $tr_height = ' style="height:' . self::$_tr_height . 'px;" ';
            $lastrow   = "";


            $html = "<div $width id=\"$tablename\" >"; // class=\"well \"

            $html .= '<table class="table table-hover table-striped table-condensed tablesorter" >';
            if ($_tablename != "tbl")
                $html .= " <caption> <h4> $_tablename </h4> </caption>";

            $html .= '<thead> <tr> ';

            $cx = 0;

            if (isset($_header_array))
                while (list($key, $value) = each($_header_array)) {

                    $size = (is_numeric($value) && $value > 0) ? 'width: ' . $value . 'px' : NULL;
                    $cx++;

                    $html .= '<th class="btn-info" style="' . $size . '; "> ' . $key . '</th> ';
                }

            $html .= '</tr> </thead> <tfoot> </tfoot>';


            $html .= "<tbody>";

            $cx = 1;
            while (list($key, $value) = each($_records)) {
                if (is_array($value)) {
                    $html .= "<tr $tr_height >";
                    $links_id_start = NULL;
                    while (list($key2, $value2) = each($value)) {
                        if ($_set_link) {
                            if ($key2 == "id")
                                $links_id_start = $value2;


                            if ($key2 == $_set_link_field) {
                                $value2 = '<a class=" " href="' . $_set_link . $links_id_start . '"> ' . $value2 . '</a>';
                            }


                        }
                        if ($key2 == "id")
                            $value2 = $cx++;

                        $html .= "<td> $value2 </td>";
                    }
                    $html .= "</tr> \n";
                } else
                    $html .= "<tr $tr_height class=\"rosw\"><td> $value</td></tr>";
            }

            // <tfoot>      <tr>  <td> </td>  </tr>		</tfoot>

            $html .= "</tbody>	</table>";
            $html .= $lastrow;

            $html .= '</div> ';


           // $returnrow = $inputfield;

            if ($_print_output)
                Echoc::output($html);
            else
                return $html;


        }

        static public function dynamic($displayarray, $datarray, $width = "100%", $tablename_ok = "css_dynamic_table", $headers = "", $printok = TRUE, $lastrow = "", $url = NULL, $popup = FALSE, $newidof = FALSE)
        {


            $NEWLINES = "";

            $html = Html_Tables::show($displayarray, $datarray, $width, $tablename_ok, $headers, $printok, $url, $NEWLINES, $popup, FALSE, $newidof);

            $tablename = str_replace(" ", "", $tablename_ok);
            $cx        = count($displayarray) - 1;


            $html .= HForm::css();
            $html .= "<div style=\"width:$width;\" class=\"\"    >"; // clas"table_fancy"
            $html .= HForm::Html_start("Form_$tablename");
            $html .= "<table width=\"$width\"  class=\"mytable\" >"; //  class=\"bgimage\"
            $html .= '<Input id="Update" name="Update" type="hidden" Value="0" /> ';

            $title_of = "Sumbit";
            if (Css::answer("Update") == "1")
                $title_of = "Update";

            $subm = ' <Input id="Form_Submit_" name="Form_Submit_" type="submit" title="" value="' . $title_of . '" class="awesome magenta small" />';
            $html .= "<tr style=\"text-align:right; height:20px; background-color: #e1e8ee;\"><td align=\"left\"> <a   href=\"#\" onClick=\"AddnewItem('NewRec" . $tablename . "');\" class=\"mytoggle small blue awesome\"  \> ( + ) Add Rec</a> </td><td colspan=\"$cx\"> <b class=\" default\"> $subm </b> &nbsp;&nbsp;</td></tr>";
            // id=\"AddnewButn\"
            $html .= $NEWLINES;
            $html .= " </table><div id=\"NEWRECS_copy\"></div></Form></div></div> ";

            if ($printok)
                __diff_html::e($html);
            else
                return $html;

        }

        static public function answers($_display, $tablename_ok = "css_dynamic_table")
        { // Return $_POSTS

            $tablename = str_replace(" ", "", $tablename_ok);

            if (!HForm::Submitted("Form_$tablename")) {
                return FALSE;
            }

            $ret_array  = array();
            $ret_array2 = array();
            $cx         = 0;


            for ($i = 1; $i <= count($_display); $i++) {
                $x = "N_" . $tablename . '_' . $i;
                if ($_POST[$x]) {
                    foreach ($_POST[$x] as $t) {
                        if ($t)
                            $ret_array2[] = $t;
                        //	 else
                        //	 echo " not found $t ".$_POST[$x];
                    }
                }
            }


            while (list($key, $value) = each($_display)) {
                //	if ($key!="id")
                $ret_array[$key] = $ret_array2[$cx];
                $cx++;
            }


            return $ret_array;
        }


        static public function init_tablesorter()
        {
            $html = '<link rel="stylesheet" href="Media/images/tablesorter/style.css" type="text/css" id="" media="print, projection, screen" />
		    <script>   $(".tablesorter").tablesorter();	</script>';

            echo $html;
        }

    }

    /*
                    Examples




        $_display = array(
            "Τηλέφωνο" 			=> 110,
            "Ονοματεπώνυμο"		=> "",
            "Ώρα Κλήσης"		=> 110,
            "Σχόλια"			=> "",
            "Ώρα "		=> 10,

            );

        $_records =array (
            0 => array(  "69723847823","Melisidsi", "16:11", " tyi les " ,1),
            1 => array(  "3","Κωστας", "26:11", " Magireii ",1 ),
            3 => array(  "1","Giotiaki", "12:11", " Magireii " ,2),
            2 => array(  "34234","Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    ", "6:11", " Magireii ",2 )


                );


                    $_display2 = array(
                        "xxx" 			=> 110,
                        "asdas"		=> "",
                        "Ώρα ddd"		=> 110,
                        "Σχόλια"			=> "",
                        "Ώρα "		=> 10,

                        );
                        $_records2 =array (
                            0 => array(  "1","Melisidsi", "16:11", " tyi les " ,1),
                            1 => array(  "3","Κωστας", "26:11", " Magireii ",1 ),
                            3 => array(  "1","Giotiaki", "12:11", " Magireii " ,2),
                            2 => array(  "2","Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    ", "6:11", " Magireii ",2 )


                                );



            $Table_name = "Εισερχόμενα Λογιστηρίου";

        Html_Tables::dynamic($_display,$_records, "1180px", $Table_name);
            echo "<br/> ";

    //		Html_Tables::show($_display2,$_records2, "1180px", "ΤΕΣΤ με ");
            Html_Tables::Show($_display,$_records, "1180px", "Εισερχόμενα Λογιστηρίου");

            Html_Tables::init_tablesorter();

            if ($ret= Html_Tables::answers($_display,$Table_name)) {

                print_r($ret);
            }

    */
?>