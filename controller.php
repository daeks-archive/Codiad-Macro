<?php

    /*
    *  Copyright (c) Codiad & daeks, distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */
    

    require_once('../../common.php');
    require_once('class.macro.php');

    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////

    if(isset($_GET['action']) && $_GET['action']!='authenticate'){ checkSession(); }

    $macro = new Macro();
    
    //////////////////////////////////////////////////////////////////
    // Load Contextmenu Macros
    //////////////////////////////////////////////////////////////////

    if(isset($_GET['action']) && $_GET['action']=='init'){
        echo json_encode($macro->Load());
    }
    
    //////////////////////////////////////////////////////////////////
    // Save Contextmenu Macros
    //////////////////////////////////////////////////////////////////

    if(isset($_GET['action']) && $_GET['action']=='save'){
        $macro->Save();
    }
    
?>
