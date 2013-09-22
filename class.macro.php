<?php

/*
*  Copyright (c) Codiad & daeks, distributed
*  as-is and without warranty under the MIT License. See
*  [root]/license.txt for more. This information must remain intact.
*/

require_once('../../common.php');

class Macro extends Common {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $command = 0;

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // -----------------------------||----------------------------- //

    //////////////////////////////////////////////////////////////////
    // Construct
    //////////////////////////////////////////////////////////////////

    public function __construct(){
        if(!file_exists(DATA."/config/".get_called_class().".php")) {
          mkdir(DATA."/config");
          saveJSON("/config/".get_called_class().".php", array());
        }
    }

    //////////////////////////////////////////////////////////////////
    // Load Contextmenu Macros
    //////////////////////////////////////////////////////////////////

    public function Load() {
        return getJSON("/config/".get_called_class().".php");
    }

    //////////////////////////////////////////////////////////////////
    // Save Contextmenu Macros
    //////////////////////////////////////////////////////////////////

    public function Save() {
      $data = array();
      foreach ($_GET['name'] as $key => $name){
        $tmp['n'] = trim($name);
        $tmp['c'] = trim($_GET["command"][$key]);
        
        array_push($data,$tmp);
			}
			saveJSON("/config/".get_called_class().".php", $data);
			echo formatJSEND("success",null);
    }
    
}
