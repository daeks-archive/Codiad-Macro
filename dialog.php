<?php

    /*
    *  Copyright (c) Codiad & daeks (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See 
    *  [root]/license.txt for more. This information must remain intact.
    */

    require_once('../../common.php');
    
    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////
    
    checkSession();

    switch($_GET['action']){
            
        //////////////////////////////////////////////////////////////////////
        // Config
        //////////////////////////////////////////////////////////////////////
        
        case 'config':
        
            if(!checkAccess()){ 
            ?>
            <label><?php i18n("Restricted"); ?></label>
            <pre><?php i18n("You can not modify macros"); ?></pre>
            <button onclick="codiad.modal.unload();return false;"><?php i18n("Close"); ?></button>
            <?php } else {
                require_once('class.macro.php');
                $macro = new Macro();
                $macrolist = $macro->Load();
            ?>
            <form>
            <label><?php i18n("Macro Editor"); ?></label>
            <table width="850px">
              <tr>
                  <th width="200px"><?php i18n("Name"); ?></td>
                  <th width="650px" colspan="2"><?php i18n("Command"); ?></td>
              </tr>
            </table>
            <div class="macro-wrapper">
            <table id="macrolist" width="850px">
            <?php
              foreach($macrolist as $id=>$macro) {
                echo '<tr id="l'.$id.'"><td width="200px"><input id="rowid" type="hidden" value="'.$id.'"><input class="macro-command" id="n'.$id.'" type="text" value="'.$macro[0].'"></td><td width="600px"><input class="macro-command" id="c'.$id.'" type="text" value="'.$macro[1].'"></td><td width="50px"><button class="btn-left" onclick="codiad.macro.remove(\''.$id.'\');return false;">X</button></td></tr>';        
              }           
            ?>
            </table>
            </div>
            <input type="hidden" id="i" value="<?php echo sizeof($macrolist); ?>">
            <pre>Placeholders are: %FILE%, %FOLDER%</pre>
            <button class="btn-left" onclick="codiad.macro.add();return false;"><?php i18n("Add"); ?></button><button class="btn-mid" onclick="codiad.macro.save();return false;"><?php i18n("Save"); ?></button><button class="btn-right" onclick="codiad.modal.unload();return false;"><?php i18n("Close"); ?></button>
            </form>
            <?php }
            break;
            
        //////////////////////////////////////////////////////////////////////
        // Execute
        //////////////////////////////////////////////////////////////////////
        
        case 'execute':
            if(!checkAccess()){ 
            ?>
            <label><?php i18n("Restricted"); ?></label>
            <pre><?php i18n("You are not allowed to do that"); ?></pre>
            <button onclick="codiad.modal.unload();return false;"><?php i18n("Close"); ?></button>
            <?php } else {
                require_once('class.macro.php');
                $macro = new Macro();
                $macrolist = $macro->Load();
                $command = $macrolist[$_GET['id']][1];
                
                if(!Common::isAbsPath($_GET['path'])) {
                  $_GET['path'] = WORKSPACE.'/'.$_GET['path'];
                }
                if(is_file($_GET['path'])) {
                  $command = str_replace('%FILE%',$_GET['path'],$command);
                } else {
                  $command = str_replace('%FOLDER%',$_GET['path'],$command);
                }
            ?>
            <form>
            <label><?php i18n("Execute Command"); ?></label>
            <pre>Command: <?php echo $command; ?></pre>
            <?php
              echo '<pre>'.shell_exec($command).'</pre>';
            ?>
            <button class="btn" onclick="codiad.modal.unload();return false;"><?php i18n("Close"); ?></button>
            </form>
            <?php }
            break;
                       
    }
    
?>
