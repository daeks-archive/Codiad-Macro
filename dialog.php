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
                  <th width="100px"><?php i18n("Applies"); ?></td>
                  <th width="450px"><?php i18n("Shell Command"); ?></td>
                  <th width="100px" colspan="2"><?php i18n("Daemon"); ?></td>
              </tr>
            </table>
            <div class="macro-wrapper">
            <table id="macrolist" width="850px">
            <?php
              foreach($macrolist as $id=>$macro) {
                echo '<tr id="l'.$id.'"><td width="200px"><input id="rowid" type="hidden" value="'.$id.'"><input class="macro-command" id="n'.$id.'" type="text" value="'.$macro['n'].'"></td><td width="100px"><input class="macro-command" id="i'.$id.'" type="hidden" value="'.$macro['i'].'"><input class="macro-command" id="t'.$id.'" type="hidden" value="'.$macro['t'].'"><select id="a'.$id.'" type="text">';
                if($macro['a'] == 'root-only') {
                  echo '<option value="root-only" selected>Root</option>';
                } else {
                  echo '<option value="root-only">Root</option>';
                }
                if($macro['a'] == 'file-only') {
                  echo '<option value="file-only" selected>File</option>';
                } else {
                  echo '<option value="file-only">File</option>';
                }
                if($macro['a'] == 'directory-only') {
                  echo '<option value="directory-only" selected>Folder</option>';
                } else {
                  echo '<option value="directory-only">Folder</option>';
                }
                if($macro['a'] == 'both') {
                  echo '<option value="both" selected>All</option>';
                } else {
                  echo '<option value="both">All</option>';
                }
                echo '</select></td><td width="450px"><input class="macro-command" id="c'.$id.'" type="text" value="'.htmlentities($macro['c']).'"></td><td width="100px"><select id="d'.$id.'" type="text">';
                if($macro['d'] == 'false') {
                  echo '<option value="false" selected>No</option>';
                } else {
                  echo '<option value="false">No</option>';
                }
                if($macro['d'] == 'true') {
                  echo '<option value="true" selected>Yes</option>';
                } else {
                  echo '<option value="true">Yes</option>';
                }
                echo '</select></td><td width="50px"><button class="btn-left" onclick="codiad.macro.remove(\''.$id.'\');return false;">X</button></td></tr>';        
              }           
            ?>
            </table>
            </div>
            <input type="hidden" id="macrocount" value="<?php echo sizeof($macrolist); ?>">
            <pre>Placeholders are: %FILE%, %FOLDER%</pre>
            <button class="btn-left" onclick="codiad.macro.add('context-menu');return false;"><?php i18n("Add New Macro"); ?></button><button style="color: blue;" class="btn-mid" onclick="codiad.macro.save();return false;"><?php i18n("Save & Reload"); ?></button><button class="btn-right" onclick="codiad.modal.unload();return false;"><?php i18n("Close"); ?></button>
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
                $command = $macrolist[$_GET['id']]['c'];
                
                if(!Common::isAbsPath($_GET['path'])) {
                  $_GET['path'] = WORKSPACE.'/'.$_GET['path'];
                }
                if(is_file($_GET['path'])) {
                  $command = str_replace('%FILE%',$_GET['path'],$command);
                  $command = str_replace('%FOLDER%',dirname($_GET['path']),$command);
                } else {
                  $command = str_replace('%FOLDER%',$_GET['path'],$command);
                }
            ?>
            <form>
            <label><?php i18n("Execute Command"); ?></label>
            <pre>Command: <?php echo $command; ?></pre>
            <?php
              echo '<pre>'.shell_exec(escapeshellcmd($command)).'</pre>';
            ?>
            <button class="btn" onclick="codiad.modal.unload();return false;"><?php i18n("Close"); ?></button>
            </form>
            <?php }
            break;
                       
    }
    
?>
