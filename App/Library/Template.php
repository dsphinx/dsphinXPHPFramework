<?php
    /**
     * difF PHP Framework :: html_template.php
     *
     * @version: 2.1
     * @date   : 6/10/2012
     * @author Melisides Constantinos (dsphinx@gmail.com)
     * @Description:  load HTML tempalte
     *
     *  usage:            &template_theme=XXXXX
     *
     * Licensed under MIT licence:
     *   http://www.opensource.org/licenses/mit-license.php
     **/


    class Template extends Html
    {


        public $values = array();
        protected $_path;
        public $_theme = "default"; // template theme στο templates/
        protected $_template = "template.html";
        protected $_template_run = "template.php";

        // Template prefix
        protected $_prefix_admin = "admin.";
        protected $_prefix_users = "user.";




        /**
         * @param null $template_theme_load
         * @param null $templete_html
         */
        public function __construct($template_theme_load = NULL, $templete_html = NULL)
        {
            //  Get preference from Cookies Strategy from theme
            $this->_theme    = isset($template_theme_load) ? $template_theme_load : AppCookieStrategy::$_preferences['templateTheme'];
      //      $this->_theme    = isset($template_theme_load) ? $template_theme_load : $this->_theme;
            $this->_template = isset($templete_html) ? $templete_html : $this->_template;


            /**
             *
             *   Registered Users
             */
            if (isset($_SESSION['Auth']['User_Session_Id']) && !empty($_SESSION['Auth']['User_Session_Id'])) {

                $prefix = $_SESSION['Auth']['Level'] > 1 ? $this->_prefix_users : $this->_prefix_admin;

                $this->_template     = $prefix . $this->_template;
                $this->_template_run = $prefix . $this->_template_run;
                // echo $this->_template;
            }


            $this->_theme        = $_SESSION['PATHS']['TEMPLATE_DIR'] . $this->_theme;
            $this->_template     = $this->_theme . DIRECTORY_SEPARATOR . $this->_template;
            $this->_template_run = $this->_theme . DIRECTORY_SEPARATOR . $this->_template_run;

            if (!is_dir($this->_theme)) {
                echo ' Template Directory not Found = '.$this->_theme ;
                die();
            }

        }

        static public function merge($templates, $separator = "\n")
        {
            /**
             * Loops through the array concatenating the outputs from each template, separating with $separator.
             * If a type different from Template is found we provide an error message.
             */
            $output = "";

            foreach ($templates as $template) {
                $content = (get_class($template) !== "Template")
                    ? "Error, incorrect type - expected Template."
                    : $template->output();
                $output .= $content . $separator;
            }

            return $output;
        }

        public function _autorun_template()
        {
            $ret = FALSE;

            if (file_exists($this->_template_run)) { // return $Template_Values;
                $this->values = include($this->_template_run);
                $ret          = TRUE;
            }

            return $ret;
        }

        // Sets a value for replacing a specific tag.
        //
        // @param string $key the name of the tag to replace
        // @param string $value the value to replace

        function bind($tag_name, $data)
        {
            if (is_array($data)) {
                foreach ($data as $name => $val) {
                    $this->set($name, $val);
                }
            } else {
                $this->set($tag_name, $data);

            }
        }


        // Outputs the content of the template, replacing the keys for its respective values.
        //
        // @return string

        public function set($key, $value)
        {
            $this->values[$key] = $value;
        }


        // Merges the content from an array of templates and separates it with $separator.
        //
        // @param array $templates an array of Template objects to merge
        // @param string $separator the string that is used between each Template object
        // @return string

        public function output()
        {
            if (!file_exists($this->_template))
                trigger_error(' Template File not found ' . $this->_template);

            $output = file_get_contents($this->_template);

            foreach ($this->values as $key => $value) {
                $tagToReplace = "[@$key]";
                $output       = str_replace($tagToReplace, $value, $output);
            }

            return $output;
        }
    }

    require_once ('Template_files.php');
